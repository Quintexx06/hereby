<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «Heimweg» (spec 2026-10-13): the night's last connections, and which
 * reminders already went out.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->json('way_home')->nullable();
            $table->json('way_home_sent')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['way_home', 'way_home_sent']);
        });
    }
};
