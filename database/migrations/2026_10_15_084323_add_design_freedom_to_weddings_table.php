<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * More freedom inside the three templates: the couple's own accent
     * colour and the built-in sections they leave out.
     */
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('accent_color', 7)->nullable()->after('theme');
            $table->json('hidden_sections')->nullable()->after('accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['accent_color', 'hidden_sections']);
        });
    }
};
