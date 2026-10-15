<?php

namespace App\Actions\Partners;

use App\Actions\Weddings\ApplyProgramme;
use App\Enums\ContentBlockType;
use App\Models\Partner;
use App\Models\Wedding;
use Illuminate\Support\Facades\DB;

/**
 * The couple connects a partner with its code. A venue's profile is
 * entered once: an empty venue on the wedding and missing «Anreise» and
 * «Übernachten» blocks are filled from it; nothing the couple wrote is
 * overwritten.
 */
class ConnectPartner
{
    public function __construct(private ApplyProgramme $applyProgramme) {}

    public function handle(Wedding $wedding, Partner $partner): void
    {
        DB::transaction(function () use ($wedding, $partner): void {
            $wedding->partners()->syncWithoutDetaching([$partner->id => ['created_at' => now()]]);

            if ($partner->isVenue()) {
                $this->fillVenue($wedding, $partner);
            }
        });
    }

    private function fillVenue(Wedding $wedding, Partner $venue): void
    {
        if (! $wedding->venue_name) {
            $wedding->update([
                'venue_name' => $venue->name,
                'venue_address' => $venue->address,
                'venue_postcode' => $venue->postcode,
                'venue_town' => $venue->town,
            ]);
            $this->applyProgramme->syncVenue($wedding);
        }

        $blocks = [ContentBlockType::Travel->value => $venue->travel, ContentBlockType::Stay->value => $venue->stay];

        foreach (array_filter($blocks) as $type => $text) {
            if ($wedding->contentBlocks()->where('type', $type)->exists()) {
                continue;
            }

            $wedding->contentBlocks()->create([
                'type' => $type,
                'position' => (int) $wedding->contentBlocks()->max('position') + 1,
                'content' => [$wedding->default_locale->value => ['title' => null, 'body' => $text, 'items' => []]],
            ]);
        }
    }
}
