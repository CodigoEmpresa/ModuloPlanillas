<?php

namespace App\Modulos\Planilla\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Saldo extends Model
{
    use SoftDeletes;
    
    protected $table = 'Saldos';
    protected $primaryKey = 'Id';
    protected $dates = ['deleted_at'];
    protected $fillable = ['Id_Contrato', 'Id_Recurso', 'Fecha_Registro', 'Total_Pagado', 'operacion'];

    public function contrato()
    {
        return $this->balongsTo('App\Modulos\Planilla\Modelos\Contrato', 'Id_Contrato');
    }

    public function recurso()
    {
        return $this->belongsTo('App\Modulos\Planilla\Modelos\Recurso', 'Id_Recurso');
    }

    public function planilla()
    {
    	return $this->belongsTo('App\Modulos\Planilla\Modelos\Planilla', 'Id_Planilla');
    }
}
