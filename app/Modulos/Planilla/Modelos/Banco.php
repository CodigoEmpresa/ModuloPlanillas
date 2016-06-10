<?php

namespace App\Modulos\Planilla\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banco extends Model
{
    use SoftDeletes;
    
    protected $table = 'Bancos';
    protected $primaryKey = 'Id_Banco';

    public function contratistas()
    {
    	return $this->hasMany('App\Modulos\Planilla\Modelos\Contratista', 'Id_Banco');
    }
}
