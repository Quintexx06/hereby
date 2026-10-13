<?php

namespace Tests;

use App\Models\GuestPhoto;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\Features;

abstract class TestCase extends BaseTestCase
{
    /**
     * Every test gets its own photo disk. Deleting a wedding removes its
     * directory (HasMemories), and test weddings share IDs with a local
     * demo wedding: without this, running the suite wiped real photos.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(GuestPhoto::DISK);
    }

    protected function skipUnlessFortifyHas(string $feature, ?string $message = null): void
    {
        if (! Features::enabled($feature)) {
            $this->markTestSkipped($message ?? "Fortify feature [{$feature}] is not enabled.");
        }
    }
}
