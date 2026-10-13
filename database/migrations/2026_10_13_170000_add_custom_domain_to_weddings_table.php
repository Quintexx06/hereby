<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «Eigene Domain» (spec 2026-10-13): the couple's address for their guest
 * pages, and when the team connected it (DNS and TLS by hand).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('custom_domain', 191)->nullable()->unique();
            $table->timestamp('custom_domain_verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropUnique(['custom_domain']);
            $table->dropColumn(['custom_domain', 'custom_domain_verified_at']);
        });
    }
};
