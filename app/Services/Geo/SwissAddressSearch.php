<?php

namespace App\Services\Geo;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Exact Swiss addresses from the federal register of building addresses,
 * through the swisstopo geo.admin.ch search (ADR 0010). Called server-side
 * so visitors' IPs never reach a third party; results are cached.
 */
class SwissAddressSearch
{
    private const int LIMIT = 6;

    /**
     * @return list<array{label: string, street: string, postcode: string, town: string, lat: float, lng: float, reference: string}>
     */
    public function search(string $query): array
    {
        $query = Str::squish($query);

        if (Str::length($query) < 3) {
            return [];
        }

        $key = 'address-search:'.md5(Str::lower($query));

        if (($cached = Cache::get($key)) !== null) {
            return $cached;
        }

        $results = $this->fetch($query);

        if ($results !== null) {
            Cache::put($key, $results, now()->addDays(7));
        }

        return $results ?? [];
    }

    /**
     * @return list<array{label: string, street: string, postcode: string, town: string, lat: float, lng: float, reference: string}>|null Null when the service failed.
     */
    private function fetch(string $query): ?array
    {
        try {
            $response = Http::timeout(3)->acceptJson()->get(config('services.geoadmin.search_url'), [
                'searchText' => $query,
                'type' => 'locations',
                'origins' => 'address',
                'sr' => 4326,
                'limit' => self::LIMIT,
            ]);
        } catch (ConnectionException) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        /** @var list<array{attrs?: array<string, mixed>}> $results */
        $results = (array) $response->json('results', []);

        return array_values(array_filter(array_map(
            fn (array $result): ?array => $this->toAddress($result['attrs'] ?? []),
            $results,
        )));
    }

    /**
     * "Seestrasse 1 <b>6354 Vitznau</b>" → street, postcode, town.
     *
     * @param  array<string, mixed>  $attrs
     * @return array{label: string, street: string, postcode: string, town: string, lat: float, lng: float, reference: string}|null
     */
    private function toAddress(array $attrs): ?array
    {
        $label = (string) ($attrs['label'] ?? '');

        if (! preg_match('/^(.*?)\s*<b>\s*(\d{4})\s+(.+?)\s*<\/b>\s*$/u', $label, $parts)
            || ! isset($attrs['lat'], $attrs['lon'])) {
            return null;
        }

        [, $street, $postcode, $town] = $parts;

        return [
            'label' => trim("{$street}, {$postcode} {$town}", ', '),
            'street' => trim($street),
            'postcode' => $postcode,
            'town' => trim($town),
            'lat' => round((float) $attrs['lat'], 6),
            'lng' => round((float) $attrs['lon'], 6),
            'reference' => (string) ($attrs['featureId'] ?? ''),
        ];
    }
}
