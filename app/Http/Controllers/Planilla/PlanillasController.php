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
use App\Modulos\Planilla\Modelos\Saldo;
use Illuminate\Http\Request;
use Session;
use Validator;
use Idrd\Usuarios\Repo\PersonaInterface;

class PlanillasController extends Controller
{

	protected $Usuario;
	protected $repositorio_personas;

	public function __construct(PersonaInterface $repositorio_personas)
	{
		if (isset($_SESSION['Usuario']))
			$this->Usuario = $_SESSION['Usuario'];

		$this->repositorio_personas = $repositorio_personas;
	}

    public function index(Request $request)
	{
		if ($request->has('vector_modulo'))
		{	
			$vector = urldecode($request->input('vector_modulo'));
			$user_array = unserialize($vector);
			$permissions_array = $user_array;

			$permisos = [
				'crear_bancos' => intval($permissions_array[1]),
				'editar_bancos' => intval($permissions_array[2]),
				'eliminar_bancos' => intval($permissions_array[3]),
				'crear_fuentes' => intval($permissions_array[4]),
				'editar_fuentes' => intval($permissions_array[5]),
				'eliminar_fuentes' => intval($permissions_array[6]),
				'crear_rubros' => intval($permissions_array[7]),
				'editar_rubros' => intval($permissions_array[8]),
				'eliminar_rubros' => intval($permissions_array[9]),
				'crear_componentes' => intval($permissions_array[10]),
				'editar_componentes' => intval($permissions_array[11]),
				'eliminar_componentes' => intval($permissions_array[12]),
				'crear_contratos' => intval($permissions_array[13]),
				'editar_contratos' => intval($permissions_array[14]),
				'eliminar_contratos' => intval($permissions_array[15]),
				'crear_contratistas' => intval($permissions_array[16]),
				'editar_contratistas' => intval($permissions_array[17]),
				'eliminar_contratistas' => intval($permissions_array[18]),
				'crear_planillas' => intval($permissions_array[19]),
				'editar_planillas' => intval($permissions_array[20]),
				'eliminar_planillas' => intval($permissions_array[21]),
				'revisar_planillas' => intval($permissions_array[22])
			];

			$_SESSION['Usuario'] = $user_array;
			$persona = $this->repositorio_personas->obtener($_SESSION['Usuario'][0]);

			$_SESSION['Usuario']['Persona'] = $persona;
			$_SESSION['Usuario']['Permisos'] = $permisos;
			$this->Usuario = $_SESSION['Usuario']; // [0]=> string(5) "71766" [1]=> string(1) "1"
		} else {
			if(!isset($_SESSION['Usuario']))
				$_SESSION['Usuario'] = '';
		}

		if ($_SESSION['Usuario'] == '')
			return redirect()->away('http://www.idrd.gov.co/SIM/Presentacion/');

		return redirect('/welcome');
	}

	public function welcome()
	{
		$data['seccion'] = '';
		return view('welcome', $data);
	}

	public function planillas()
	{
		$perPage = 10;

		// Se cargan las planillas dependiendo del perfil del usuario
		if ($_SESSION['Usuario']['Permisos']['revisar_planillas'])
			$elementos = Planilla::with('recursos', 'fuente', 'rubros')
							->where('Id_Planilla', '<>', '0')
							->where(function($query)
							{
								$query->where('Estado', '2')
									->orWhere('Estado', '3');
							})
							->orderBy('created_at', 'DESC')
							->paginate($perPage);
		else
			$elementos = Planilla::with('recursos', 'fuente', 'rubros')
							->where('Id_Planilla', '<>', '0')
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

		return  response()->json($planilla);	
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
		$planilla['Usuario'] = $this->Usuario[0]; 
       	$planilla['Numero'] = $request->input('Numero');
       	$planilla['Titulo'] = $request->input('Titulo');
       	$planilla['Colectiva'] = $request->input('Colectiva');
       	$planilla['Descripcion'] = $request->input('Descripcion');
       	$planilla['Observaciones'] = $request->input('Observaciones');
		$planilla['Desde'] = $request->input('Desde');
		$planilla['Hasta'] = $request->input('Hasta');

		$planilla->save();

		// iniciar sincronizacion de rubros solo si es nuevo o se quiere editar una planilla y selecciona reindexar contratos
		if ($id == 0 || $request->input('agregar_contratos_eliminados') !== null)
		{
			$planilla['Estado'] = '1';
			$planilla->rubros()->sync($request->input('Rubros'));
		}

		// iniciar sincronizacion de recursos
		$recursos = Recurso::where('Id_Fuente', $request->input('Id_Fuente'))
						->whereIn('Id_Rubro', $request->input('Rubros'))
						->get();

		if (count($recursos) > 0)
		{
			$to_sync = [];
			foreach ($recursos as $recurso) {
				$contrato = $recurso->contrato;
				
				//aqui validaciones de contrato
				if($contrato->Estado == 'finalizado')
					continue;

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
							->join('Contratistas', 'Contratos.Id_Contratista', '=', 'Contratistas.Id_Contratista')
							->whereIn('Id_Contrato', $contratos_en_recursos)
							->orderBy('Contratistas.Id_Banco')
							->get();

		foreach ($contratos as $key => $contrato) 
		{
			$recursos_contratos = Recurso::with('rubro', 'saldos', 'fuente', 'componente')
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
					'Ingreso_Base_Gravado_384' => $temp->pivot['Ingreso_Base_Gravado_384'],
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
					'Retefuente_1607' => $temp->pivot['Retefuente_1607'],
					'Retefuente_384' => $temp->pivot['Retefuente_384'],
					'Otros_Descuentos_Expresion' => $temp->pivot['Otros_Descuentos_Expresion'],
					'Otros_Descuentos' => $temp->pivot['Otros_Descuentos'],
					'Otras_Bonificaciones' => $temp->pivot['Otras_Bonificaciones'],
					'Cod_Retef' => $temp->pivot['Cod_Retef'],
					'Cod_Seven' => $temp->pivot['Cod_Seven'],
					'Total_Deducciones' => $temp->pivot['Total_Deducciones'],
					'Declarante' => $temp->pivot['Declarante'],
					'Neto_Pagar' => $temp->pivot['Neto_Pagar']
				];

				$recursos_contratos[$key_2]['saldo'] = $recurso_2->saldos()->sum('Total_Pagado');
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

		$recursos = json_decode($request->input('_planilla'));

		$to_sync = [];
		foreach ($recursos as $recurso)
		{
			$to_sync[$recurso->Id] = [
				'Dias_Trabajados' => $recurso->Dias_Trabajados,
				'Total_Pagar' => $recurso->Total_Pagar,
				'UVT' => $recurso->Con_VC_UVT,
				'EPS' => $recurso->EPS,
				'Pension' => $recurso->Pension,
				'ARL' => $recurso->ARL,
				'Medicina_Prepagada' => $recurso->Medicina_Prepagada,
				'Hijos' => $recurso->Hijos,
				'AFC' => $recurso->AFC,
				'Ingreso_Base_Gravado_384' => $recurso->Ingreso_Base_Gravado_384,
				'Ingreso_Base_Gravado_1607' => $recurso->Ingreso_Base_Gravado_1607,
				'Ingreso_Base_Gravado_25' => $recurso->Ingreso_Base_Gravado_25,
				'Base_UVR_Ley_1607' => $recurso->Base_UVR_Ley_1607,
				'Base_UVR_Art_384' => $recurso->Base_UVR_Art_384,
				'Base_ICA' => $recurso->Base_ICA,
				'PCUL' => $recurso->PCUL,
				'PPM' => $recurso->PPM,
				'Total_ICA' => $recurso->Total_ICA,
				'DIST' => $recurso->DIST,
				'Retefuente' => $recurso->Retefuente,
				'Retefuente_1607' => $recurso->Retefuente_1607,
				'Retefuente_384' => $recurso->Retefuente_384,
				'Otros_Descuentos_Expresion' => $recurso->Otros_Descuentos_Expresion,
				'Otros_Descuentos' => $recurso->Otros_Descuentos,
				'Otras_Bonificaciones' => $recurso->Otras_Bonificaciones,
				'Cod_Retef' => $recurso->Cod_Retef,
				'Cod_Seven' => $recurso->Cod_Seven,
				'Total_Deducciones' => $recurso->Total_Deducciones,
				'Declarante' => $recurso->Declarante,
				'Neto_Pagar' => $recurso->Neto_Pagar
			];
		}

		$planilla->recursos()->sync($to_sync);
		$planilla->touch();

		//obetener contratos para afectar saldos si es necesario
		$recursos_actualizados = $planilla->recursos;
		$contratos_en_recursos = [];
		foreach ($recursos_actualizados as $recurso) 
		{
			if (!in_array($recurso['Id_Contrato'], $contratos_en_recursos))
				$contratos_en_recursos[] = $recurso['Id_Contrato'];
		}

		switch ($request->input('Estado')) 
		{
			case '1': //Edición
			case '2': //Verificación
					$planilla->Estado = $request->input('Estado');
					$planilla->saldos()->delete();
				break;
			case '3': //En ejecución
					$planilla->Estado = $request->input('Estado');
					$planilla->Verificador = $this->Usuario[0]; 
					$saldos = [];
					$planilla->saldos()->delete();
					
					foreach ($contratos_en_recursos as $id_contrato) 
					{
						$Fecha_Registro = date('Y-m-d');
						$operacion = 'sumar';

						foreach ($recursos_actualizados as $recurso)
						{
							if ($recurso['Id_Contrato'] == $id_contrato)
							{
								$saldo = new Saldo ([
									'Id_Contrato' => $id_contrato,
									'Id_Recurso' => $recurso['Id'],
									'Fecha_Registro' => $Fecha_Registro,
									'Total_Pagado' => $recurso->pivot['Total_Pagar'],
									'operacion' => $operacion
								]);

								$planilla->saldos()->save($saldo);
							}
						}
					}
				break;
			default:
				break;
		}

		$planilla->save();

		//finalizar contratos ejecutados...
		foreach ($contratos_en_recursos as $id_contrato) 
		{
			$contrato = Contrato::with('saldos')->find($id_contrato);
			$total_saldo = $contrato->saldos()->sum('Total_Pagado');
			switch ($planilla->Estado) {
				case '1': //Edición
				case '2': //Verificación
						$contrato->Estado = 'pendiente';
					break;
				case '3': //En ejecución
						if ($contrato->Tipo_Modificacion == 'terminado')
						$contrato->Estado = 'finalizado';
					break;
				default:
					break;
			}

			if ($total_saldo >= $contrato->Total_Contrato)
			{
				$contrato->Estado = 'finalizado';
			}

			$contrato->save();
		}

		return redirect()->to('planillas/'.$planilla['Id_Planilla'].'/recursos')
						->with('status', 'success');
	}

	public function eliminar(Request $request, $Id_Planilla)
	{
		$planilla = Planilla::find($Id_Planilla);
		$planilla->delete();

		return response()->json(array('status' => 'ok'));
	}

	public function logout()
	{
		$_SESSION['Usuario'] = '';
		Session::set('Usuario', ''); 

		return redirect()->to('/');
	}
}
