<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «Gruppenfotos» (spec 2026-10-13): the couple's list of group photos, who
 * belongs in each, and when the witnesses called it on the day.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('title', 80);
            $table->string('place', 80)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamp('called_at')->nullable();
            $table->timestamps();
        });

        Schema::create('group_photo_household', function (Blueprint $table) {
            $table->foreignId('group_photo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('household_id')->constrained()->cascadeOnDelete();
            $table->primary(['group_photo_id', 'household_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_photo_household');
        Schema::dropIfExists('group_photos');
    }
};
