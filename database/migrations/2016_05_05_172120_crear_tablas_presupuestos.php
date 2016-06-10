<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablasPresupuestos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Fuentes', function($table)
        {
            $table->increments('Id_Fuente');
            $table->string('Nombre');
            $table->string('Codigo', 20);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('Rubros', function($table)
        {
            $table->increments('Id_Rubro');
            $table->string('Nombre');
            $table->string('Codigo', 20);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('Componentes', function($table)
        {
            $table->increments('Id_Componente');
            $table->string('Nombre');
            $table->string('Codigo', 20);

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('Recursos', function($table)
        {
            $table->increments('Id');
            $table->integer('Id_Fuente')->unsigned();
            $table->integer('Id_Rubro')->unsigned();
            $table->integer('Id_Contrato')->unsigned();
            $table->integer('Id_Componente')->unsigned();
            $table->integer('Numero_Registro')->unsigned();
            $table->integer('Valor_CRP')->unsigned();
            $table->integer('Saldo_CRP')->unsigned();
            $table->string('Expresion')->nullable();
            $table->integer('Pago_Mensual')->unsigned();

            $table->foreign('Id_Fuente')->references('Id_Fuente')->on('Fuentes')->onDelete('cascade');
            $table->foreign('Id_Rubro')->references('Id_Rubro')->on('Rubros')->onDelete('cascade');
            $table->foreign('Id_Componente')->references('Id_Componente')->on('Componentes')->onDelete('cascade');
            $table->foreign('Id_Contrato')->references('Id_Contrato')->on('Contratos')->onDelete('cascade');

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
        Schema::table('Recursos', function(Blueprint $table){
            $table->dropForeign(['Id_Fuente']);
            $table->dropForeign(['Id_Rubro']);
            $table->dropForeign(['Id_Componente']);
            $table->dropForeign(['Id_Contrato']);
        });

        Schema::drop('Recursos');
        Schema::drop('Componentes');
        Schema::drop('Rubros');
        Schema::drop('Fuentes');
    }
}
