<?php 

namespace App\Modulos\Planilla\Traits;

trait DocumentoContratistas 
{
	function contratistas()
	{
		return $this->hasMany('App\Modulos\Planillas\Modelos\Contratista', 'Id_TipoDocumento');
	} 
}