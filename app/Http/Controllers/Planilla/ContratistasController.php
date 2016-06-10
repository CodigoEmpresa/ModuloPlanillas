<?php

namespace App\Http\Controllers\Planilla;

use App\Http\Controllers\Controller;
use App\Modulos\Personas\Documento;
use App\Modulos\Planilla\Modelos\Banco;
use App\Modulos\Planilla\Modelos\Contratista;
use Illuminate\Http\Request;
use Validator;

class ContratistasController extends Controller
{
	public function index()
	{
		$perPage = 10;

		$contratistas = Contratista::with('tipoDocumento')
							->orderBy('Nombre', 'ASC')
							->paginate($perPage);

		$lista = [
			'contratistas' => $contratistas,
	        'documentos' => Documento::all(),
	        'bancos' => Banco::all()
		];

		$datos = [
			'seccion' => 'Contratistas',
			'lista'	=> view('idrd.planilla.contratistas.lista', $lista)
		];

		return view('list', $datos);
	}

	public function procesar(Request $request)
	{
		$id = $request->input('Id_Contratista');
		$Id_TipoDocumento = $request->input('Id_TipoDocumento');
		$validator = Validator::make($request->all(),
			[
	            'Id_TipoDocumento' => 'required|min:1',
	            'Id_Banco' => 'required|min:1',
				'Cedula' => 'required|numeric|unique:mysql.Contratistas,Cedula'.($id == '0' ? '' : ','.$id.',Id_Contratista,Id_TipoDocumento,'.$Id_TipoDocumento),
				'Nombre' => 'required',
				'Numero_Cta' => 'required',
				'Activo' => 'required|in:0,1',
				'Hijos' => 'required|in:0,1',
				'AFC' => 'required|in:0,1',
				'Medicina_Prepagada' => 'required|in:0,1',
				'Hijos_Cantidad' => 'required_if:Hijos,1',
				'Medicina_Prepagada_Cantidad' => 'required_if:Medicina_Prepagada,1'
        	],
        	[
        		'Id_TipoDocumento.required' => 'El campo tipo de documento es requerido',
				'Id_Banco.required' => 'El campo banco es requerido',
				'Cedula.required' => 'El campo cedula es requerido',
				'Cedula.unique' => 'El documento ingresado ya existe en la base de datos',
				'Nombre.required' => 'El campo nombre es requerido',
				'Numero_Cta.required' => 'El campo numero de cuenta es requerido',
				'Activo.required' => 'El campo activo es requerido',
				'Hijos.required' => 'El campo hijos es requerido',
				'AFC.required' => 'El campo AFC es requerido',
				'Medicina_Prepagada.required' => 'El campo medicina prepagada es requerido',
				'Hijos_Cantidad.required_if' => 'El campo número de hijos es requerido cuando el campo hijos es igual a "Si"',
				'Medicina_Prepagada_Cantidad.required_if' => 'El campo total pagado medicina prepagada es requerido cuando el campo medicina prepagada es igual a "Si"'
        	]
        );

        if ($validator->fails())
            return response()->json(array('status' => 'error', 'errors' => $validator->errors()));
        
        if($id == '0')
        	$contratista = new Contratista;
        else
        	$contratista = Contratista::find($id);

        $contratista['Id_TipoDocumento'] = $request->input('Id_TipoDocumento');
		$contratista['Id_Banco'] = $request->input('Id_Banco');
		$contratista['Cedula'] = $request->input('Cedula');
		$contratista['Nombre'] = $request->input('Nombre');
		$contratista['Numero_Cta'] = $request->input('Numero_Cta');
		$contratista['Activo'] = $request->input('Activo');
		$contratista['Hijos'] = $request->input('Hijos');
		$contratista['Hijos_Cantidad'] = $request->input('Hijos_Cantidad');
		$contratista['AFC'] = $request->input('AFC');
		$contratista['Medicina_Prepagada'] = $request->input('Medicina_Prepagada');
		$contratista['Medicina_Prepagada_Cantidad'] = $request->input('Medicina_Prepagada_Cantidad');

		$contratista->save();

        return response()->json(array('status' => 'ok'));
	}

	public function obtener(Request $request, $id)
	{
		$contratista = Contratista::find($id);

		return response()->json($contratista);
	}

	public function buscar(Request $request, $key)
	{
		$contratistas = Contratista::with('tipoDocumento')
								->where('Nombre', 'LIKE', '%'.$key.'%')
								->orWhere('Cedula', 'LIKE', '%'.$key.'%')
								->get();

		return response()->json($contratistas);
	} 
}