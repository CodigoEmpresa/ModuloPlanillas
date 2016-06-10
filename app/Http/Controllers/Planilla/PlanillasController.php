<?php

namespace App\Http\Controllers\Planilla;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modulos\Personas\Documento;
use App\Modulos\Planilla\Modelos\Banco;
use App\Modulos\Planilla\Modelos\Planilla;
use App\Modulos\Planilla\Modelos\Contrato;
use App\Modulos\Planilla\Modelos\Recurso;
use App\Modulos\Planilla\Modelos\Fuente;
use App\Modulos\Planilla\Modelos\Rubro;
use Illuminate\Http\Request;
use Session;
use Validator;

class PlanillasController extends Controller
{

	protected $Usuario;

	public function __construct()
	{
		if (isset($_SESSION['Usuario']))
			$this->Usuario = $_SESSION['Usuario'];
	}

    public function index(Request $request)
	{
		if ($request->has('vector_modulo'))
		{	
			$vector = urldecode($request->input('vector_modulo'));
			$user_array = unserialize($vector);
			$_SESSION['Usuario'] = $user_array;
			$this->Usuario = $_SESSION['Usuario']; // [0]=> string(5) "71766" [1]=> string(1) "1" 
		} else {
			if(!isset($_SESSION['Usuario']))
				$_SESSION['Usuario'] = '';
		}

		if ($_SESSION['Usuario'] == '')
			return redirect()->away('http://www.idrd.gov.co/SIM/Presentacion/');

		$perPage = 10;

		$elementos = Planilla::with('recursos', 'fuente', 'rubros')
							->where('Usuario', $this->Usuario[0])
							->orderBy('created_at', 'DESC')
							->paginate($perPage);

		$fuentes_recursos = Recurso::groupBy('Id_Fuente')
						->get();

		$fuentes = Fuente::whereIn('Id_Fuente', $fuentes_recursos->lists('Id_Fuente'))
						->get();

		$lista = [
			'titulo' => 'Crear y editar planillas',
	        'elementos' => $elementos,
	        'fuentes' => $fuentes
		];

		$datos = [
			'seccion' => 'Planillas',
			'lista'	=> view('idrd.planilla.planillas.lista', $lista)
		];

		return view('list', $datos);
	}

	public function obtener(Request $request, $Id_Planilla)
	{
		$planilla = Planilla::with('recursos', 'fuente', 'rubros')
							->where('Usuario', $this->Usuario[0])
							->find($Id_Planilla);

		return $planilla;	
	}

	public function procesar(Request $request)
	{
		$id = $request->input('Id_Planilla');
		$validator = Validator::make($request->all(),
			[
				'Numero' => 'required',
				'Id_Fuente' => 'required',
				'Desde' => 'required|date',
				'Hasta' => 'required|date',
				'Rubros' => 'required'
        	],
        	[
				'Numero.required' => 'El campo número es requerido',
				'Desde.required' => 'El campo de fecha desde es requerido',
				'Desde.date' => 'El campo de fecha desde debe contener una fecha con el formato yyyy-mm-dd',
				'Hasta.required' => 'El campo de fecha hasta es requerido',
				'Hasta.date' => 'El campo de fecha hasta debe contener una fecha con el formato yyyy-mm-dd',
				'Id_Fuente.required' => 'El campo fuente es requerido',
				'Rubros.required' => 'El campo rubros es requerido'
        	]
        );

        if ($validator->fails())
            return response()->json(array('status' => 'error', 'errors' => $validator->errors()));
        
        if ($id == '0')
        	$planilla = new Planilla;
        else
        	$planilla = Planilla::find($id);

		$planilla['Id_Fuente'] = $request->input('Id_Fuente');
       	$planilla['Numero'] = $request->input('Numero');
       	$planilla['Usuario'] = $this->Usuario[0];
       	$planilla['Descripcion'] = $request->input('Descripcion');
		$planilla['Desde'] = $request->input('Desde');
		$planilla['Hasta'] = $request->input('Hasta');
		$planilla['Estado'] = 'Pendiente';

		$planilla->save();

		// iniciar sincronizacion de rubros
		$planilla->rubros()->sync($request->input('Rubros'));

		// iniciar sincronizacion de recursos
		$recursos = Recurso::where('Id_Fuente', $request->input('Id_Fuente'))
						->whereIn('Id_Rubro', $request->input('Rubros'))
						->get();

		if (count($recursos) > 0)
		{
			$to_sync = [];
			foreach ($recursos as $recurso) {
				$contrato = $recurso->contrato;
				$to_sync[] = $recurso['Id'];
			}

			$planilla->recursos()->sync($to_sync);
		}

		return response()->json(array('status' => 'ok'));
	}

	public function recursos(Request $request, $Id_Planilla)
	{
		$planilla = Planilla::with('recursos')
						->find($Id_Planilla);
		$recursos = $planilla->recursos;
		$contratos_en_recursos = [];
		foreach ($recursos as $recurso) 
		{
			if (!in_array($recurso['Id_Contrato'], $contratos_en_recursos))
				$contratos_en_recursos[] = $recurso['Id_Contrato'];
		}

		$contratos = Contrato::with('contratista', 'contratista.tipoDocumento', 'contratista.banco')
							->whereIn('Id_Contrato', $contratos_en_recursos)
							->get();

		foreach ($contratos as $key => $contrato) 
		{
			$recursos_contratos = Recurso::with('rubro', 'fuente', 'componente')
										->whereIn('Id', $recursos->lists('Id'))
										->where('Id_Contrato', $contrato['Id_Contrato'])
										->get();

			foreach ($recursos_contratos as $key_2 => $recurso_2) 
			{
				$pivote_en_planilla = $recursos->filter(function($item) use ($recurso_2)
				{
					return $item['Id'] == $recurso_2['Id'];
				});

				$temp = $pivote_en_planilla->first();
				
				$recursos_contratos[$key_2]['planillado'] = [
					'Dias_Trabajados' => $temp->pivot['Dias_Trabajados'],
					'Total_Pagar' => $temp->pivot['Total_Pagar'],
					'UVT' => $temp->pivot['UVT'],
					'EPS' => $temp->pivot['EPS'],
					'Pension' => $temp->pivot['Pension'],
					'ARL' => $temp->pivot['ARL'],
					'Medicina_Prepagada' => $temp->pivot['Medicina_Prepagada'],
					'Hijos' => $temp->pivot['Hijos'],
					'AFC' => $temp->pivot['AFC'],
					'Ingreso_Base_Gravado_1607' => $temp->pivot['Ingreso_Base_Gravado_1607'],
					'Ingreso_Base_Gravado_25' => $temp->pivot['Ingreso_Base_Gravado_25'],
					'Base_UVR_Ley_1607' => $temp->pivot['Base_UVR_Ley_1607'],
					'Base_UVR_Art_384' => $temp->pivot['Base_UVR_Art_384'],
					'Base_ICA' => $temp->pivot['Base_ICA'],
					'PCUL' => $temp->pivot['PCUL'],
					'PPM' => $temp->pivot['PPM'],
					'Total_ICA' => $temp->pivot['Total_ICA'],
					'DIST' => $temp->pivot['DIST'],
					'Retefuente' => $temp->pivot['Retefuente'],
					'Otros_Descuentos' => $temp->pivot['Otros_Descuentos'],
					'Otras_Bonificaciones' => $temp->pivot['Otras_Bonificaciones'],
					'Total_Deducciones' => $temp->pivot['Total_Deducciones'],
					'Declarante' => $temp->pivot['Declarante'],
					'Neto_Pagar' => $temp->pivot['Neto_Pagar']
				];
			}

			$contratos[$key]['recursos'] = $recursos_contratos;
		}

		$lista = [
			'titulo' => 'Editar planilla',
			'title' => 'Planilla N° '.$planilla['Numero'],
			'config' => config('planillas.'.date('Y')),
			'planilla' => $planilla,
	        'elementos' => $contratos,
	        'documentos' => Documento::all(),
	        'bancos' => Banco::all(),
            'status' => session('status'),
		];

		return view('idrd.planilla.planillas.recursos', $lista);
	}

	public function rubrosFuentes(Request $request, $Id_Fuente)
	{
		$rubros_recursos = Recurso::where('Id_Fuente', $Id_Fuente)
								->groupBy('Id_Rubro')
								->get();

		$rubros = Rubro::whereIn('Id_Rubro', $rubros_recursos->lists('Id_Rubro'))
						->get();

		if ($rubros->count())
		{
			return response()->json(array('status' => 'ok', 'rubros' => $rubros)); 
		} else {
			return response()->json(array('status' => 'error'));
		}
	}

	public function sincronizarRecursos(Request $request)
	{
		$config = config('planillas.'.date('Y'));
		$planilla = Planilla::with('recursos')
					->find($request->input('Id_Planilla'));

		$to_sync = [];
		foreach ($planilla->recursos as $recurso) 
		{
			$to_sync[$recurso['Id']] = [
				'Dias_Trabajados' => $Dias_Trabajados,
				'Total_Pagar' => $Total_Pagar,
				'UVT' => round($Con_VC_UVT, 2),
				/*'EPS' => ,
				'Pension' => ,
				'ARL' => ,
				'Vivienda' => ,
				'Hijos_U_Otros' => ,
				'AFC' => ,
				'Ingreso_Base_Gravado_1607' => ,
				'Ingreso_Base_Gravado_25' => ,
				'Base_UVR_Ley_1607' => ,
				'Base_UVR_Art_384' => ,
				'Base_ICA' => ,
				'PCUL' => ,
				'PPM' => ,
				'Total_ICA' => ,
				'DIST' => ,
				'Retefuente' => ,
				'Otros_Descuentos' => ,
				'Otras_Bonificaciones' => ,
				'Total_Deducciones' => ,
				'Declarante' => ,
				'Neto_Pagar' => ,
				*/
			];
		}
		
		$planilla->recursos()->sync($to_sync);

		return redirect()->to('planillas/'.$planilla['Id_Planilla'].'/recursos')
						->with('status', 'success');
	}

	public function logout()
	{
		$_SESSION['Usuario'] = '';
		Session::set('Usuario', ''); 

		return redirect()->to('/');
	}
}
