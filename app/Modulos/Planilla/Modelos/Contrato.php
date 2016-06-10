<?php

namespace App\Modulos\Planilla\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Contrato extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'Contratos';
    protected $primaryKey = 'Id_Contrato';
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['recursos'];

    public function contratista()
    {
    	return $this->belongsTo('App\Modulos\Planilla\Modelos\Contratista', 'Id_Contratista');
    }

    public function recursos()
    {
        return $this->hasMany('App\Modulos\Planilla\Modelos\Recurso', 'Id_Contrato');
    }
}