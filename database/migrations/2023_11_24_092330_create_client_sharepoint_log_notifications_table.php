<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_sharepoint_log_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('client_code');
            $table->string('Sharepoint_folder_name');
            $table->tinyInteger('emailed')->default(0)->comment("0->not sent/ 1->sent");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_sharepoint_log_notifications');
    }
};