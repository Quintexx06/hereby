<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Who guests write to with questions, shown in the site's footer.
     */
    public function up(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('contact_name', 80)->nullable()->after('sends_reminders');
            $table->string('contact_email')->nullable()->after('contact_name');
            $table->string('contact_phone', 40)->nullable()->after('contact_email');
        });
    }

    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn(['contact_name', 'contact_email', 'contact_phone']);
        });
    }
};
