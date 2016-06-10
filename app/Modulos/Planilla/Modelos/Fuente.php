<?php

namespace App\Modulos\Planilla\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Fuente extends Model
{
    use SoftDeletes;
    
    protected $table = 'Fuentes';
    protected $primaryKey = 'Id_Fuente';
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['recursos'];
    
    public function recursos()
    {
        return $this->hasMany('App\Modulos\Planilla\Modelos\Recurso', 'Id_Fuente');
    }

    public function planillas()
    {
    	return $this->hasMany('App\Modulos\Planilla\Modelos\Planilla', 'Id_Fuente');
    }
}
