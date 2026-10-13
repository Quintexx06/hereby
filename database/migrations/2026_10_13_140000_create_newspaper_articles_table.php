<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «Hochzeitszeitung» (spec 2026-10-13): stories friends send through one
 * link, edited and printed by the witnesses.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newspaper_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('author_name', 80);
            $table->string('title', 120);
            $table->text('body');
            $table->string('photo_path')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::table('weddings', function (Blueprint $table) {
            $table->string('newspaper_token', 40)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropUnique(['newspaper_token']);
            $table->dropColumn('newspaper_token');
        });

        Schema::dropIfExists('newspaper_articles');
    }
};
