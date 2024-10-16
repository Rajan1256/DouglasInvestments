<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('invest_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('investment_company');
            $table->string('investment_short_code');
            $table->timestamps();
        });
    }
}
