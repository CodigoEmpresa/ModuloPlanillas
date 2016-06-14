<?php

namespace App\Modulos\Planilla\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recurso extends Model
{    
    use SoftDeletes;
    
    protected $table = 'Recursos';
    protected $primaryKey = 'Id';
    protected $fillable = ['Id_Fuente', 'Id_Rubro', 'Id_Componente', 'Id_Contrato', 'Numero_Registro', 'Valor_CRP', 'Saldo_CRP', 'Expresion', 'Pago_Mensual'];

    public function contrato()
    {
    	return $this->belongsTo('App\Modulos\Planilla\Modelos\Contrato', 'Id_Contrato');
    }

    public function rubro()
    {
    	return $this->belongsTo('App\Modulos\Planilla\Modelos\Rubro', 'Id_Rubro');
    }

    public function fuente()
    {
    	return $this->belongsTo('App\Modulos\Planilla\Modelos\Fuente', 'Id_Fuente');
    }

    public function componente()
    {
    	return $this->belongsTo('App\Modulos\Planilla\Modelos\Componente', 'Id_Componente');
    }

    public function planillas()
    {
        return $this->belongsToMany('App\Modulos\Planilla\Modelos\Planilla', 'PlanillasRecursos', 'Id_Recurso', 'Id_Planilla')
                    ->withPivot('Dias_Trabajados', 'Total_Pagar', 'UVT', 'EPS', 'Pension', 'ARL', 'Medicina_Prepagada', 'Hijos', 'AFC', 'Ingreso_Base_Gravado_384', 'Ingreso_Base_Gravado_1607', 'Ingreso_Base_Gravado_25', 'Base_UVR_Ley_1607', 'Base_UVR_Art_384', 'Base_ICA', 'PCUL', 'PPM', 'Total_ICA', 'DIST', 'Retefuente', 'Retefuente_1607', 'Retefuente_384', 'Otros_Descuentos', 'Otras_Bonificaciones', 'Cod_Retef', 'Cod_Seven', 'Total_Deducciones', 'Declarante', 'Neto_Pagar');
    }
}
