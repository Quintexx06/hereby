<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Venues, photographers and planners (spec 2026-10-15-partner-accounts),
     * the weddings whose couples connected them, and tour requests.
     */
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->string('name', 120);
            $table->string('slug')->unique();
            $table->string('code', 9)->unique();
            $table->boolean('is_showcase')->default(false);
            $table->string('email')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('website')->nullable();
            $table->string('address', 200)->nullable();
            $table->string('postcode', 4)->nullable();
            $table->string('town', 80)->nullable();
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->text('about')->nullable();
            $table->text('travel')->nullable();
            $table->text('stay')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->after('is_admin')->constrained()->nullOnDelete();
        });

        Schema::create('partner_wedding', function (Blueprint $table) {
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
            $table->primary(['partner_id', 'wedding_id']);
        });

        Schema::table('guest_photos', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->after('wedding_id')->constrained()->nullOnDelete();
        });

        Schema::create('venue_enquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('email');
            $table->date('preferred_date')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_enquiries');
        Schema::table('guest_photos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('partner_id');
        });
        Schema::dropIfExists('partner_wedding');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('partner_id');
        });
        Schema::dropIfExists('partners');
    }
};
