<?php

namespace App\Support;

use App\Models\Household;
use App\Models\Wedding;

/**
 * A household's personal links, on the couple's own domain once the team
 * has connected it (spec 2026-10-13), otherwise on Hereby's.
 */
class PersonalLink
{
    /**
     * @param  Wedding|null  $wedding  Pass it when looping over households, to skip a query each.
     */
    public static function to(Household $household, string $route = 'invitation.show', ?Wedding $wedding = null): string
    {
        $url = route($route, $household);
        $domain = ($wedding ?? $household->wedding)->connectedDomain();

        return $domain !== null ? self::onDomain($url, $domain) : $url;
    }

    /**
     * The same path and query, on another host, always over https.
     */
    public static function onDomain(string $url, string $domain): string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);

        return 'https://'.$domain.$path.($query ? '?'.$query : '');
    }
}
