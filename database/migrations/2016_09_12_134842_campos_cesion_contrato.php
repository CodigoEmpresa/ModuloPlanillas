<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CamposCesionContrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Contratos', function($table)
        {
            $table->boolean('Cedido')->default(0)->after('Estado');
            $table->integer('Id_Contratista_Que_Cede')->nullable()->unsigned()->after('Id_Contratista');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Contratos', function($table)
        {
            $table->dropColumn('Cedido');
            $table->dropColumn('Id_Contratista_Que_Cede');
        });
    }
}
