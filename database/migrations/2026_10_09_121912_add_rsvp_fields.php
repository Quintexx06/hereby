<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The 60-second RSVP (roadmap 1.4): the couple's questions on the wedding,
 * one answer per household for the household-wide questions.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->json('menu_options')->nullable()->after('guest_estimate');
            $table->boolean('children_menu')->default(false)->after('menu_options');
            $table->boolean('offers_shuttle')->default(false)->after('children_menu');
            $table->boolean('offers_stay')->default(false)->after('offers_shuttle');
            $table->boolean('asks_song')->default(true)->after('offers_stay');
        });

        Schema::table('households', function (Blueprint $table) {
            $table->unsignedTinyInteger('shuttle_seats')->nullable()->after('plus_one_allowed');
            $table->boolean('needs_stay')->nullable()->after('shuttle_seats');
            $table->string('song_wish', 160)->nullable()->after('needs_stay');
            $table->timestamp('responded_at')->nullable()->after('opened_at');
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn(['shuttle_seats', 'needs_stay', 'song_wish', 'responded_at']);
        });

        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['menu_options', 'children_menu', 'offers_shuttle', 'offers_stay', 'asks_song']);
        });
    }
};
