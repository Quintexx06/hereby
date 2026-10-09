<?php

namespace App\Actions\Invitations;

use App\Enums\ContentBlockType;
use App\Models\Wedding;

/**
 * The content blocks one household may see: blocks for everyone plus blocks
 * tied to events it is invited to, in its language (falling back to the
 * wedding's main one), without the empty ones.
 */
class VisibleBlocks
{
    /**
     * @param  list<int>  $eventIds
     * @return list<array{id: int, type: string, title: string|null, body: string|null, items: list<array{question: string, answer: string}>, venue: array{name: string|null, address: string|null, route: string|null}|null}>
     */
    public function handle(Wedding $wedding, array $eventIds, string $locale): array
    {
        $blocks = [];

        foreach ($wedding->contentBlocks as $block) {
            if ($block->event_id !== null && ! in_array($block->event_id, $eventIds, true)) {
                continue;
            }

            $text = $block->textIn($locale, $wedding->default_locale->value);
            $venue = $block->type === ContentBlockType::Venue ? $this->venue($wedding) : null;

            if ($text === null && $venue === null) {
                continue;
            }

            $blocks[] = [
                'id' => $block->id,
                'type' => $block->type->value,
                'title' => $text['title'] ?? null,
                'body' => $text['body'] ?? null,
                'items' => $text['items'] ?? [],
                'venue' => $venue,
            ];
        }

        return $blocks;
    }

    /**
     * A plain link for directions: nothing third-party loads on the page.
     *
     * @return array{name: string|null, address: string|null, route: string|null}|null
     */
    private function venue(Wedding $wedding): ?array
    {
        $town = trim(($wedding->venue_postcode ?? '').' '.($wedding->venue_town ?? ''));
        $address = implode(', ', array_filter([$wedding->venue_address, $town]));

        if (! $wedding->venue_name && $address === '') {
            return null;
        }

        $query = $address !== '' ? $address : (string) $wedding->venue_name;

        return [
            'name' => $wedding->venue_name,
            'address' => $address !== '' ? $address : null,
            'route' => 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($query),
        ];
    }
}
