<?php

namespace App\Modulos\Planilla;

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
        return $this->balongsTo('App\Modulos\Planilla\Contrato', 'Id_Contrato');
    }

    public function recurso()
    {
        return $this->belongsTo('App\Modulos\Planilla\Recurso', 'Id_Recurso');
    }

    public function planilla()
    {
    	return $this->belongsTo('App\Modulos\Planilla\Planilla', 'Id_Planilla');
    }
}
