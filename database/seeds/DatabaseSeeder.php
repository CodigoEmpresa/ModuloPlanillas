<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Bancos::class);
        $this->command->info('Tabla de Bancos alimentada satisfactoriamente');
        /*
        $this->call(Fuentes::class);
        $this->command->info('Tabla de Fuentes alimentada satisfactoriamente');
        $this->call(Rubros::class);
        $this->command->info('Tabla de Rubros alimentada satisfactoriamente');
        $this->call(Componentes::class);
        $this->command->info('Tabla de Componentes alimentada satisfactoriamente');
        */
    }
}

class Fuentes extends Seeder
{
	public function run()
	{
		DB::table('Fuentes')->delete();
		DB::table('Fuentes')->insert([
			[
				'Nombre' => 'Otros distritos',
				'Codigo' => '1101012'
			],[
				'Nombre' => 'Recursos administrativos',
				'Codigo' => '11030201'
			]
		]);
	}
}

class Rubros extends Seeder
{
	public function run()
	{
		DB::table('Rubros')->delete();
		DB::table('Rubros')->insert([
			[
				'Nombre' => 'Bogotá es mi parche',
				'Codigo' => '3311401080862'
			],[
				'Nombre' => 'Corredores vitales',
				'Codigo' => '3311401080867'
			],[
				'Nombre' => 'Pedalea por Bogotá',
				'Codigo' => '3311402190845'
			]
		]);
	}
}

class Componentes extends Seeder
{
	public function run()
	{
		DB::table('Componentes')->delete();
		DB::table('Componentes')->insert([
			[
				'Nombre' => 'Honorarios',
				'Codigo' => '3110203'
			],[
				'Nombre' => 'Remuneración Servicios Técnicos',
				'Codigo' => '3110204'
			],[
				'Nombre' => 'Dotación',
				'Codigo' => '3120101'
			]
		]);
	}
}

class Bancos extends Seeder
{
	public function run()
	{
		DB::table('Bancos')->delete();
		DB::table('Bancos')->insert([
			[
				'Codigo' => '51',
				'Nombre' => 'BANCO DAVIVIENDA S.A.'
			],[
				'Codigo' => '01',
				'Nombre' => 'BANCO DE BOGOTÁ'
			],[
				'Codigo' => '02',
				'Nombre' => 'BANCO POPULAR'
			],[
				'Codigo' => '06',
				'Nombre' => 'BANCO CORPBANCA COLOMBIA S.A.'
			],[
				'Codigo' => '07',
				'Nombre' => 'BANCOLOMBIA S.A.'
			],[
				'Codigo' => '09',
				'Nombre' => 'CITIBANK COLOMBIA'
			],[
				'Codigo' => '10',
				'Nombre' => 'BANCO GNB COLOMBIA S.A.'
			],[
				'Codigo' => '12',
				'Nombre' => 'BANCO GNB SUDAMERIS COLOMBIA'
			],[
				'Codigo' => '13',
				'Nombre' => 'BBVA COLOMBIA'
			],[
				'Codigo' => '14',
				'Nombre' => 'HELM BANK'
			],[
				'Codigo' => '19',
				'Nombre' => 'RED MULTIBANCA COLPATRIA S.A.'
			],[
				'Codigo' => '23',
				'Nombre' => 'BANCO DE OCCIDENTE'
			],[
				'Codigo' => '31',
				'Nombre' => 'BANCO DE COMERCIO EXTERIOR DE COLOMBIA S.A. (BANCOLDEX)'
			],[
				'Codigo' => '32',
				'Nombre' => 'BANCO CAJA SOCIAL - BCSC S.A.'
			],[
				'Codigo' => '40',
				'Nombre' => 'BANCO AGRARIO DE COLOMBIA S.A.'
			],[
				'Codigo' => '52',
				'Nombre' => 'BANCO AV VILLAS'
			],[
				'Codigo' => '53',
				'Nombre' => 'BANCO WWB S.A.'
			],[
				'Codigo' => '58',
				'Nombre' => 'BANCO PROCREDIT'
			],[
				'Codigo' => '59',
				'Nombre' => 'BANCAMIA'
			],[
				'Codigo' => '60',
				'Nombre' => 'BANCO PICHINCHA S.A.'
			],[
				'Codigo' => '61',
				'Nombre' => 'BANCOOMEVA'
			],[
				'Codigo' => '62',
				'Nombre' => 'BANCO FALABELLA S.A.'
			],[
				'Codigo' => '63',
				'Nombre' => 'BANCO FINANDINA S.A.'
			],[
				'Codigo' => '65',
				'Nombre' => 'BANCO SANTANDER DE NEGOCIOS COLOMBIA S.A. - BANCO SANTANDER'
			],[
				'Codigo' => '66',
				'Nombre' => 'BANCO COOPERATIVO COOPCENTRAL'
			],[
				'Codigo' => '00',
				'Nombre' => 'PAGO CON CHEQUE'
			]
		]);
	}
}
