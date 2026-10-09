<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The invitation's content blocks (roadmap 1.2): story, venue, dress code
 * and FAQ, with text per language and an optional event they belong to.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('position')->default(0);
            $table->json('content');
            $table->timestamps();

            $table->unique(['wedding_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
