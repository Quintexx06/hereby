<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extras a couple bought on top of their plan, one row per payment. The
 * Checkout session id makes the webhook and the return page idempotent.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('addon', 30);
            $table->string('stripe_session')->nullable()->unique();
            $table->string('stripe_payment_intent')->nullable();
            $table->timestamps();

            $table->index(['wedding_id', 'addon']);
        });

        Schema::table('weddings', function (Blueprint $table) {
            $table->date('memories_kept_until')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn('memories_kept_until');
        });

        Schema::dropIfExists('wedding_addons');
    }
};
