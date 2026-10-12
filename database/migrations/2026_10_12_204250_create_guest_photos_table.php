<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «Fotos von allen» (roadmap 2.2): one upload link per wedding and the
 * photos guests send through it. The files live on the `memories` disk.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('memories_token', 40)->nullable()->unique();
        });

        Schema::create('guest_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->unsignedInteger('size');
            $table->timestamps();

            $table->index(['wedding_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_photos');

        Schema::table('weddings', function (Blueprint $table) {
            $table->dropUnique(['memories_token']);
            $table->dropColumn('memories_token');
        });
    }
};
