<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampoBitacoraRecursosPlanillas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('PlanillasRecursos', function($table){
            $table->bigInteger('Bitacora')->default(0)->unsigned()->nullable()->after('Neto_Pagar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('PlanillasRecursos', function(Blueprint $table){
           $table->dropColumn('Bitacora');
        });
    }
}
