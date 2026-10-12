<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * What the couple paid for, and when. Weddings already live keep sending:
 * they become Signature, paid today.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('plan', 20)->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('plan');
        });

        DB::table('weddings')->where('status', 'active')->update(['plan' => 'signature', 'paid_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['plan', 'paid_at']);
        });
    }
};
