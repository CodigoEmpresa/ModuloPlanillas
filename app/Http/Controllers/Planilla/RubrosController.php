<?php

namespace App\Http\Controllers\Planilla;

use App\Http\Requests;
use App\Modulos\Planilla\Modelos\Rubro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class RubrosController extends Controller
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

		$elementos = Rubro::with('recursos')
							->orderBy('Codigo', 'ASC')
							->paginate($perPage);

		$lista = [
			'titulo' => 'Crear y editar rubros',
	        'elementos' => $elementos
		];

		$datos = [
			'seccion' => 'Rubros',
			'lista'	=> view('idrd.planilla.rubros.lista', $lista)
		];

		return view('list', $datos);
	}

	public function obtener(Request $requests, $Id_Rubro)
	{
		return Rubro::find($Id_Rubro);
	}

	public function eliminar(Request $request, $Id_Rubro)
	{
		$rubro = Rubro::find($Id_Rubro);

		return response()->json(array('status' => 'ok'));
	}

	public function procesar(Request $request)
	{
		$id = $request->input('Id_Rubro');
		$validator = Validator::make($request->all(),
			[
				'Codigo' => 'required|unique:mysql.Rubros,Codigo'.($id == '0' ? '' : ','.$id.',Id_Rubro'),
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
        	$rubro = new Rubro;
        else
        	$rubro = Rubro::find($id);

		$rubro['Codigo'] = $request->input('Codigo');
		$rubro['Nombre'] = $request->input('Nombre');

		$rubro->save();

        return response()->json(array('status' => 'ok'));
	}
}
