<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Warnings and errors for the team's «Protokoll»: one row per distinct
     * problem, counted, scrubbed of personal data, pruned after 30 days.
     */
    public function up(): void
    {
        Schema::create('log_entries', function (Blueprint $table) {
            $table->id();
            $table->string('level', 12);
            $table->string('fingerprint', 40)->unique();
            $table->text('message');
            $table->string('exception')->nullable();
            $table->string('location')->nullable();
            $table->string('url')->nullable();
            $table->text('trace')->nullable();
            $table->unsignedInteger('count')->default(1);
            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at')->index();
            $table->timestamp('mailed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_entries');
    }
};
