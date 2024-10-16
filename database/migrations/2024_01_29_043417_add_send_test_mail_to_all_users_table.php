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
        Schema::table('send_test_mail_to_all_users', function (Blueprint $table) {
            $table->string('replyto_name')->nullable();
            $table->string('replyto_email')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('send_test_mail_to_all_users', function (Blueprint $table) {
            $table->dropColumn('replyto_name');
            $table->dropColumn('replyto_email');
        });
    }
};
