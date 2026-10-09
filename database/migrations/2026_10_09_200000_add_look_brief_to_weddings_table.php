<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The couple's look brief from the setup: style directions and their own
 * idea in words, for the team to hand-tune the site.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->json('look_styles')->nullable()->after('theme');
            $table->text('look_wishes')->nullable()->after('look_styles');
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['look_styles', 'look_wishes']);
        });
    }
};
