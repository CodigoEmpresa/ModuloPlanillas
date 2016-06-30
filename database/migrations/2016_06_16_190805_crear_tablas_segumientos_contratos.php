<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablasSegumientosContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Suspenciones', function($table)
        {
            $table->increments('Id');
            $table->integer('Id_Contrato')->unsigned();
            $table->date('Fecha_Inicio');
            $table->date('Fecha_Terminacion');

            $table->foreign('Id_Contrato')->references('Id_Contrato')->on('Contratos')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('Saldos', function($table)
        {
            $table->increments('Id');            
            $table->integer('Id_Planilla')->unsigned();
            $table->integer('Id_Contrato')->unsigned();
            $table->integer('Id_Recurso')->unsigned();
            $table->date('Fecha_Registro')->nullable();
            $table->bigInteger('Total_Pagado')->unsigned();
            $table->enum('operacion', ['sumar', 'restar'])->nullable();

            $table->foreign('Id_Planilla')->references('Id_Planilla')->on('Planillas')->onDelete('cascade');
            $table->foreign('Id_Contrato')->references('Id_Contrato')->on('Contratos')->onDelete('cascade');
            $table->foreign('Id_Recurso')->references('Id')->on('Recursos')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Saldos', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Recurso']);
            $table->dropForeign(['Id_Contrato']);
            $table->dropForeign(['Id_Planilla']);
        });

        Schema::table('Suspenciones', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Contrato']);
        });

        Schema::drop('Saldos');
        Schema::drop('Suspenciones');
    }
}
