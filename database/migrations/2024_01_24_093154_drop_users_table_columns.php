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
    {      if (Schema::hasColumn('users', 'manager_id')){

        Schema::table('users', function (Blueprint $table) {
           
            $table->dropForeign('manager_fk_9191168');
            $table->dropIndex('manager_fk_9191168');
            $table->dropColumn('manager_id');
        
        });    
  
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
