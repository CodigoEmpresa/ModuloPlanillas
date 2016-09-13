<?php

namespace App\Modulos\Planilla;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    public $timestamps = false;
    
    protected $table = 'Configuracion';
    protected $primaryKey = 'Id';
    protected $fillable = ['Usuario', 'Firma'];
    

    public function usuario()
    {
        return $this->belongsTo('App\Modulos\Personas\Persona', 'Usuario');
    }
}
