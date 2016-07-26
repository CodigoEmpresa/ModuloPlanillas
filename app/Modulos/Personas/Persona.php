<?php

namespace App\Modulos\Personas;

use Idrd\Usuarios\Repo\Persona as MPersona;

class Persona extends MPersona
{
	public function planillasCreadas()
	{
		return $this->hasMany('App\Modulos\Planilla\Modelos\Planilla', 'Usuario');
	}

	public function planillasAprobadas()
	{
		return $this->hasMany('App\Modulos\Planilla\Modelos\Planilla', 'Verificador');
	}

	public function configuracion()
	{
		return $this->hasOne('App\Modulos\Planilla\Modelos\Configuracion', 'Usuario');
	}
}
