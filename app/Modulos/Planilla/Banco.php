<?php

namespace App\Modulos\Planilla;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banco extends Model
{
    use SoftDeletes;
    
    protected $table = 'Bancos';
    protected $primaryKey = 'Id_Banco';

    public function contratistas()
    {
    	return $this->hasMany('App\Modulos\Planilla\Contratista', 'Id_Banco');
    }
}
