<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «Wir fassen nach» (spec 2026-10-13): a phone number per household for the
 * team, and when the team last reached out.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->timestamp('chased_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn(['phone', 'chased_at']);
        });
    }
};
