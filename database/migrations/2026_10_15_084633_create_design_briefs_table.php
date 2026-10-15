<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * «Massanfertigung»: the couple's brief for a site the team builds for
     * them, with their colours, mood, links and inspiration photos.
     */
    public function up(): void
    {
        Schema::create('design_briefs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('draft');
            $table->text('wishes')->nullable();
            $table->json('colors')->nullable();
            $table->string('mood', 300)->nullable();
            $table->text('links')->nullable();
            $table->date('ready_by')->nullable();
            $table->text('team_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('brief_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_brief_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brief_photos');
        Schema::dropIfExists('design_briefs');
    }
};
