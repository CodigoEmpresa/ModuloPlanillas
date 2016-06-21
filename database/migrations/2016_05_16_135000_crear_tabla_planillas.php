<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaPlanillas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Planillas', function($table)
        {
            $table->increments('Id_Planilla');
            $table->integer('Id_Fuente')->unsigned();
            $table->bigInteger('Usuario')->unsigned();
            $table->integer('Numero')->unsigned();
            $table->string('Titulo')->nullable();
            $table->string('Colectiva')->nullable();
            $table->text('Descripcion')->nullable();
            $table->date('Desde');
            $table->date('Hasta');
            $table->enum('Estado', ['Pendiente', 'Procesada', 'Revisada', 'Reversada']);

            $table->foreign('Id_Fuente')->references('Id_Fuente')->on('Fuentes')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('PlanillasRubros', function($table)
        {
            $table->increments('Id');
            $table->integer('Id_Planilla')->unsigned();
            $table->integer('Id_Rubro')->unsigned();

            $table->foreign('Id_Planilla')->references('Id_Planilla')->on('Planillas')->onDelete('cascade');
            $table->foreign('Id_Rubro')->references('Id_Rubro')->on('Rubros')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('PlanillasRecursos', function($table)
        {
            $table->increments('Id');
            $table->integer('Id_Planilla')->unsigned();
            $table->integer('Id_Recurso')->unsigned();
            $table->smallInteger('Dias_Trabajados')->unsigned()->nullable();
            $table->bigInteger('Total_Pagar')->unsigned()->nullable();
            $table->float('UVT')->nullable();
            $table->integer('EPS')->unsigned()->nullable();
            $table->integer('Pension')->unsigned()->nullable();
            $table->integer('ARL')->unsigned()->nullable();
            $table->integer('Medicina_Prepagada')->unsigned()->nullable();
            $table->integer('Hijos')->unsigned()->nullable();
            $table->integer('AFC')->unsigned()->nullable();
            $table->integer('Ingreso_Base_Gravado_384')->unsigned()->nullable();
            $table->integer('Ingreso_Base_Gravado_1607')->unsigned()->nullable();
            $table->integer('Ingreso_Base_Gravado_25')->unsigned()->nullable();
            $table->float('Base_UVR_Ley_1607')->nullable();
            $table->float('Base_UVR_Art_384')->nullable();
            $table->integer('Base_ICA')->unsigned()->nullable();
            $table->integer('PCUL')->unsigned()->nullable();
            $table->integer('PPM')->unsigned()->nullable();
            $table->integer('Total_ICA')->unsigned()->nullable();
            $table->integer('DIST')->unsigned()->nullable();
            $table->integer('Retefuente')->unsigned()->nullable();
            $table->integer('Retefuente_1607')->unsigned()->nullable();
            $table->integer('Retefuente_384')->unsigned()->nullable();
            $table->integer('Otros_Descuentos')->unsigned()->nullable();
            $table->integer('Otras_Bonificaciones')->unsigned()->nullable();
            $table->integer('Cod_Retef')->unsigned()->nullable();
            $table->integer('Cod_Seven')->unsigned()->nullable();
            $table->integer('Total_Deducciones')->unsigned()->nullable();
            $table->boolean('Declarante')->nullable();
            $table->integer('Neto_Pagar')->unsigned()->nullable();

            $table->foreign('Id_Planilla')->references('Id_Planilla')->on('Planillas')->onDelete('cascade');
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
        Schema::table('PlanillasRecursos', function(Blueprint $table){
            $table->dropForeign(['Id_Planilla']);
            $table->dropForeign(['Id_Recurso']);
        });

        Schema::table('PlanillasRubros', function(Blueprint $table){
            $table->dropForeign(['Id_Planilla']);
            $table->dropForeign(['Id_Rubro']);
        });

        Schema::drop('PlanillasRecursos');
        Schema::drop('PlanillasRubros');
        Schema::drop('Planillas');
    }
}
