<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The couple's own photos for their site's hero (spec 2026-10-13-couple-photos):
 * at most four, the first is the cover. Web copies on the `memories` disk.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('token', 40)->unique();
            $table->string('path');
            $table->unsignedTinyInteger('position')->default(0);
            $table->unsignedSmallInteger('width');
            $table->unsignedSmallInteger('height');
            $table->timestamps();

            $table->index(['wedding_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_photos');
    }
};
