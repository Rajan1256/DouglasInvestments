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
        Schema::create('client_sharepoint_synche_year_logs', function (Blueprint $table) {
            $table->id();
            $table->string('client_code');
            $table->string('investment_company');
            $table->integer('financial_year')->length(4)->unsigned();
            $table->integer('invest_companie_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_sharepoint_synche_year_logs');
    }
};
