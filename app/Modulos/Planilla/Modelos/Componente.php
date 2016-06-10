<?php

namespace App\Modulos\Planilla\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Componente extends Model
{
    use SoftDeletes;
    
    protected $table = 'Componentes';
    protected $primaryKey = 'Id_Componente';
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['recursos'];

    public function recursos()
    {
        return $this->hasMany('App\Modulos\Planilla\Modelos\Recurso', 'Id_Componente');
    }
}
