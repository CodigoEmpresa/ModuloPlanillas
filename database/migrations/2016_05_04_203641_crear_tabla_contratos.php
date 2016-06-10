<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaContratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Contratos', function($table)
        {
            $table->increments('Id_Contrato');
            $table->integer('Numero')->unsigned();
            $table->bigInteger('Usuario')->unsigned();
            $table->text('Objeto')->nullable();
            $table->date('Fecha_Inicio');
            $table->date('Fecha_Terminacion');
            $table->bigInteger('Total_Contrato')->unsigned();
            $table->bigInteger('Total_Ejecutado')->unsigned();
            $table->integer('Dias_Trabajados')->unsigned()->default(0);
            $table->enum('Tipo_Pago', ['Dia', 'Fecha', 'Mes']);
            $table->integer('Id_Contratista')->unsigned();
            $table->foreign('Id_Contratista')->references('Id_Contratista')->on('Contratistas')->onDelete('cascade');

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
        Schema::table('Contratos', function(Blueprint $table){
            $table->dropForeign(['Id_Contratista']);
        });

        Schema::drop('Contratos');
    }
}
