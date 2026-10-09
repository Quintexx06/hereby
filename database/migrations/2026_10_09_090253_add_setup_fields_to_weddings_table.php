<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            // A draft has no date until the couple reaches the date step.
            $table->date('wedding_date')->nullable()->change();

            $table->string('status', 16)->default('draft')->after('slug');
            $table->string('setup_step', 16)->nullable()->after('status');
            $table->timestamp('setup_completed_at')->nullable()->after('setup_step');

            $table->string('partner_one', 80)->nullable()->after('couple_names');
            $table->string('partner_two', 80)->nullable()->after('partner_one');

            $table->string('celebration', 16)->nullable();
            $table->string('guest_estimate', 16)->nullable();
            $table->json('languages')->nullable();

            $table->string('venue_name', 120)->nullable();
            $table->string('venue_address', 200)->nullable();
            $table->string('venue_postcode', 8)->nullable();
            $table->string('venue_town', 80)->nullable();
            $table->decimal('venue_lat', 9, 6)->nullable();
            $table->decimal('venue_lng', 9, 6)->nullable();
            $table->string('venue_reference', 64)->nullable();

            $table->index(['owner_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropIndex(['owner_id', 'status']);
            $table->dropColumn([
                'status', 'setup_step', 'setup_completed_at', 'partner_one', 'partner_two',
                'celebration', 'guest_estimate', 'languages', 'venue_name', 'venue_address',
                'venue_postcode', 'venue_town', 'venue_lat', 'venue_lng', 'venue_reference',
            ]);
        });
    }
};
