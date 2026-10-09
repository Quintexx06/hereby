<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reminders before the deadline (roadmap 1.10): the couple's switch, the
 * last stage each household got, and the household's opt-out.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->boolean('sends_reminders')->default(true)->after('asks_song');
        });

        Schema::table('households', function (Blueprint $table) {
            $table->unsignedTinyInteger('reminder_stage')->nullable()->after('responded_at');
            $table->timestamp('reminders_opted_out_at')->nullable()->after('reminder_stage');
        });
    }

    public function down(): void
    {
        Schema::table('households', function (Blueprint $table) {
            $table->dropColumn(['reminder_stage', 'reminders_opted_out_at']);
        });

        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('sends_reminders');
        });
    }
};
