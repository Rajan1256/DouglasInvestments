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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('manager_id');
          //  $table->foreign('manager_id', 'manager_fk_portfolio_1212')->references('id')->on('portfolio_managers');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('manager_fk_portfolio_1212');
            $table->dropIndex('manager_fk_portfolio_1212');
            $table->dropColumn('manager_id');
        });
    }
};
