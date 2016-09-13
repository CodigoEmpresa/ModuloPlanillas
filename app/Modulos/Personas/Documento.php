<?php

namespace App\Modulos\Personas;

use Idrd\Usuarios\Repo\Documento as MDocumento;

class Documento extends MDocumento
{
    function contratistas()
	{
		return $this->hasMany('App\Modulos\Planillas\Contratista', 'Id_TipoDocumento');
	} 
}
