<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «Einlagen» (spec 2026-10-13): speeches, games and songs guests offer for
 * the evening, run by the witnesses through their own private link.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('household_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contact_name', 80);
            $table->string('kind', 20);
            $table->string('title', 120);
            $table->string('notes', 500)->nullable();
            $table->unsignedTinyInteger('minutes');
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::table('weddings', function (Blueprint $table) {
            $table->string('witness_token', 40)->nullable()->unique();
            $table->unsignedSmallInteger('programme_minutes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropUnique(['witness_token']);
            $table->dropColumn(['witness_token', 'programme_minutes']);
        });

        Schema::dropIfExists('programme_items');
    }
};
