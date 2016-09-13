<?php

namespace App\Modulos\Planilla;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Suspencion extends Model
{
    use SoftDeletes;
    
    protected $table = 'Suspenciones';
    protected $primaryKey = 'Id';
    protected $dates = ['deleted_at'];
    protected $fillable = ['Id_Contrato', 'Fecha_Inicio', 'Fecha_Terminacion'];

    public function contrato()
    {
        return $this->balongsTo('App\Modulos\Planilla\Contrato', 'Id_Contrato');
    }
}
