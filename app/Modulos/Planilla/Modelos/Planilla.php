<?php

namespace App\Modulos\Planilla\Modelos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planilla extends Model
{
    use SoftDeletes;
    
    protected $table = 'Planillas';
    protected $primaryKey = 'Id_Planilla';

    public function fuente()
    {
    	return $this->belongsTo('App\Modulos\Planilla\Modelos\Fuente', 'Id_Fuente');
    }

    public function rubros()
    {
    	return $this->belongsToMany('App\Modulos\Planilla\Modelos\Rubro', 'PlanillasRubros', 'Id_Planilla', 'Id_Rubro');
    }

    public function recursos()
    {
    	return $this->belongsToMany('App\Modulos\Planilla\Modelos\Recurso', 'PlanillasRecursos', 'Id_Planilla', 'Id_Recurso')
    				->withPivot('Dias_Trabajados', 'Total_Pagar', 'UVT', 'EPS', 'Pension', 'ARL', 'Medicina_Prepagada', 'Hijos', 'AFC', 'Ingreso_Base_Gravado_1607', 'Ingreso_Base_Gravado_25', 'Base_UVR_Ley_1607', 'Base_UVR_Art_384', 'Base_ICA', 'PCUL', 'PPM', 'Total_ICA', 'DIST', 'Retefuente', 'Otros_Descuentos', 'Otras_Bonificaciones', 'Total_Deducciones', 'Declarante', 'Neto_Pagar');
    }
}
