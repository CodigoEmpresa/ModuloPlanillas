<?php

namespace App\Modulos\Planilla\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Contratista extends Model
{
    use SoftDeletes, CascadeSoftDeletes;
    
    protected $table = 'Contratistas';
    protected $primaryKey = 'Id_Contratista';
    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['contratos'];

    public function banco()
    {
        return $this->belongsTo('App\Modulos\Planilla\Modelos\Banco', 'Id_Banco');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo('App\Modulos\Personas\Documento', 'Id_TipoDocumento');
    }

    public function contratos()
    {
        return $this->hasMany('App\Modulos\Personas\Contrato', 'Id_Contratista');
    }
}
