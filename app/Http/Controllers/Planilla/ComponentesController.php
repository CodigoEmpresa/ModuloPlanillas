<?php

namespace App\Http\Controllers\Planilla;

use App\Http\Requests;
use App\Modulos\Planilla\Modelos\Componente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class ComponentesController extends Controller
{
    public function index()
	{
		$perPage = 10;

		$elementos = Componente::with('recursos')
							->orderBy('Codigo', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Crear y editar componentes',
	        'elementos' => $elementos
		];

		$datos = [
			'seccion' => 'componentes',
			'lista'	=> view('idrd.planilla.componentes.lista', $lista)
		];

		return view('list', $datos);
	}

	public function obtener(Request $requests, $Id_Componente)
	{
		return Componente::find($Id_Componente);
	}

	public function eliminar(Request $request, $Id_Componente)
	{
		$componente = Componente::find($Id_Componente);

		return response()->json(array('status' => 'ok'));
	}

	public function procesar(Request $request)
	{
		$id = $request->input('Id_Componente');
		$validator = Validator::make($request->all(),
			[
				'Codigo' => 'required|unique:mysql.Componentes,Codigo'.($id == '0' ? '' : ','.$id.',Id_Componente'),
				'Nombre' => 'required'
        	],
        	[
				'Nombre.required' => 'El campo nombre es requerido',
				'Codigo.required' => 'El campo código es requerido',
				'Codigo.unique' => 'El código ya ha sido implementado'
        	]
        );

        if ($validator->fails())
            return response()->json(array('status' => 'error', 'errors' => $validator->errors()));
        
        if($id == '0')
        	$componente = new Componente;
        else
        	$componente = Componente::find($id);

		$componente['Codigo'] = $request->input('Codigo');
		$componente['Nombre'] = $request->input('Nombre');

		$componente->save();

        return response()->json(array('status' => 'ok'));
	}
}
