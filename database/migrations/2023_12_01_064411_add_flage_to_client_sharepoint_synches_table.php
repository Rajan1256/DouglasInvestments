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
        Schema::table('client_sharepoint_synches', function (Blueprint $table) {
            $table->tinyInteger('process_flage')->default(0)->comment("0->not completed/ 1->completed");
            $table->tinyInteger('flage')->default(0)->comment("0->not removed/ 1->removed");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_sharepoint_synches', function (Blueprint $table) {
            //
        });
    }
};
