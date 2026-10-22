<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Five finished designs instead of the page builder (ADR 0015): each
     * wedding moves to the closest design, and the colour theme is no
     * longer chosen. The builder's columns (theme, accent_color,
     * hidden_sections, look_styles, look_wishes) keep their data for now
     * and are no longer read.
     */
    public function up(): void
    {
        DB::table('weddings')->where('template', 'atelier')->update(['template' => 'salon']);
        DB::table('weddings')->where('template', 'panorama')->update(['template' => 'arve']);

        Schema::table('weddings', function (Blueprint $table) {
            $table->string('template', 20)->default('salon')->change();
            $table->string('theme')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('weddings')->whereIn('template', ['salon', 'raster'])->update(['template' => 'atelier']);
        DB::table('weddings')->whereIn('template', ['arve', 'lido'])->update(['template' => 'panorama']);
        DB::table('weddings')->whereNull('theme')->update(['theme' => 'ivory']);

        Schema::table('weddings', function (Blueprint $table) {
            $table->string('template', 20)->default('atelier')->change();
            $table->string('theme')->nullable(false)->change();
        });
    }
};
