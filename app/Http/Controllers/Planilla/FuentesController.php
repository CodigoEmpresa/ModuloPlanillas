<?php

namespace App\Http\Controllers\Planilla;

use App\Http\Requests;
use App\Modulos\Planilla\Fuente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class FuentesController extends Controller
{

	protected $Usuario;

    public function __construct()
    {
        if (isset($_SESSION['Usuario']))
            $this->Usuario = $_SESSION['Usuario'];
    }
    
    public function index()
	{
		$perPage = 10;

		$elementos = Fuente::with('recursos')
							->orderBy('Codigo', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Crear y editar fuentes',
	        'elementos' => $elementos
		];

		$datos = [
			'seccion' => 'Fuentes',
			'lista'	=> view('idrd.planilla.fuentes.lista', $lista)
		];

		return view('list', $datos);
	}

	public function obtener(Request $requests, $Id_fuente)
	{
		return Fuente::find($Id_fuente);
	}

	public function eliminar(Request $request, $Id_fuente)
	{
		$fuente = Fuente::find($Id_fuente);

		return response()->json(array('status' => 'ok'));
	}

	public function procesar(Request $request)
	{
		$id = $request->input('Id_Fuente');
		$validator = Validator::make($request->all(),
			[
				'Codigo' => 'required|unique:mysql.Fuentes,Codigo'.($id == '0' ? '' : ','.$id.',Id_Fuente'),
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
        	$fuente = new Fuente;
        else
        	$fuente = Fuente::find($id);

		$fuente['Codigo'] = $request->input('Codigo');
		$fuente['Nombre'] = $request->input('Nombre');

		$fuente->save();

        return response()->json(array('status' => 'ok'));
	}
}
