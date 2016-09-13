<?php

namespace App\Http\Controllers\Planilla;

use App\Http\Requests;
use App\Modulos\Planilla\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class BancosController extends Controller
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

		$elementos = Banco::with('contratistas')
							->orderBy('Codigo', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Crear y editar bancos',
	        'elementos' => $elementos
		];

		$datos = [
			'seccion' => 'Bancos',
			'lista'	=> view('idrd.planilla.bancos.lista', $lista)
		];

		return view('list', $datos);
	}

	public function obtener(Request $requests, $Id_Banco)
	{
		return Banco::with('contratistas')->find($Id_Banco);
	}

	public function eliminar(Request $request, $Id_Banco)
	{
		$banco = Banco::find($Id_Banco);

		return response()->json(array('status' => 'ok'));
	}

	public function procesar(Request $request)
	{
		$id = $request->input('Id_Banco');
		$validator = Validator::make($request->all(),
			[
				'Codigo' => 'required|unique:mysql.Bancos,Codigo'.($id == '0' ? '' : ','.$id.',Id_Banco'),
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
        	$banco = new Banco;
        else
        	$banco = Banco::find($id);

		$banco['Codigo'] = $request->input('Codigo');
		$banco['Nombre'] = $request->input('Nombre');

		$banco->save();

        return response()->json(array('status' => 'ok'));
	}
}
