<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The spot on a couple photo that must stay in view when a template crops
 * it (arch, frame, strip), in percent from the top left. Centre by default.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_photos', function (Blueprint $table) {
            $table->unsignedTinyInteger('focus_x')->default(50);
            $table->unsignedTinyInteger('focus_y')->default(50);
        });
    }

    public function down(): void
    {
        Schema::table('wedding_photos', function (Blueprint $table) {
            $table->dropColumn(['focus_x', 'focus_y']);
        });
    }
};
