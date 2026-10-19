<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A personal sentence for the team's pitch mail to a prospect venue.
     * Team-only: never shown to partners, couples or guests.
     */
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('pitch_note', 500)->nullable()->after('stay');
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('pitch_note');
        });
    }
};
