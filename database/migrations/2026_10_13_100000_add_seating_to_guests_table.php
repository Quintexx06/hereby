<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * «Wer sitzt mit euch am Tisch» (spec 2026-10-13): a table per guest, one
 * line from the couple about them, and when the couple shows the plan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('table_name', 40)->nullable()->after('is_plus_one');
            $table->string('table_note', 160)->nullable()->after('table_name');
        });

        Schema::table('weddings', function (Blueprint $table) {
            $table->timestamp('seating_published_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('seating_published_at');
        });

        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['table_name', 'table_note']);
        });
    }
};
