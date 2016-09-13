<?php

namespace App\Modulos\Planilla;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Rubro extends Model
{
    use SoftDeletes;
    
    protected $table = 'Rubros';
    protected $primaryKey = 'Id_Rubro';
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['recursos'];

    public function recursos()
    {
        return $this->hasMany('App\Modulos\Planilla\Recurso', 'Id_Rubro');
    }

    public function planillas()
    {
    	return $this->belongsToMany('App\Modulos\Planilla\Planilla', 'PlanillasRubros', 'Id_Rubro', 'Id_Planilla');
    }
}
