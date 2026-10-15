<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A couple invites their venue, photographer or planner by mail: the link
     * opens a partner account already connected to the wedding.
     */
    public function up(): void
    {
        Schema::create('partner_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('name', 120);
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_invites');
    }
};
