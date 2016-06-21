<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampoEstadoContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Contratos', function(Blueprint $table){
            $table->enum('Estado', ['finalizado', 'pendiente'])->nullable()->after('Tipo_Pago');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Contratos', function(Blueprint $table){
           $table->dropColumn('Estado');
        });
    }
}
