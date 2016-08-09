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
use Idrd\Usuarios\Repo\PersonaInterface;
use Illuminate\Http\Request;
use Session;
use Validator;
use Carbon;
use PHPExcel; 
use PHPExcel_IOFactory;

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
				'revisar_planillas' => intval($permissions_array[22]),
				'asignar_bitacora' => intval($permissions_array[23]),
				'generar_archivo_plano' => intval($permissions_array[24])
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
		$estados = [];

		$elementos = Planilla::with('recursos', 'fuente', 'rubros')
							->where('Id_Planilla', '<>', '0')
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
		$planilla = Planilla::find($Id_Planilla);
		$contratos = $this->popularRecursos($Id_Planilla);
		$lista = [
			'titulo' => 'Editar planilla',
			'title' => 'Planilla N° '.$planilla['Numero'],
			'config' => config('planillas.'.date('Y')),
			'planilla' => $planilla,
			'bitacora' => false,
	        'elementos' => $contratos,
	        'documentos' => Documento::all(),
	        'bancos' => Banco::all(),
            'status' => session('status'),
		];

		return view('idrd.planilla.planillas.recursos', $lista);
	}

	public function bitacora(Request $request, $Id_Planilla)
	{
		$planilla = Planilla::find($Id_Planilla);
		$contratos = $this->popularRecursos($Id_Planilla);
		$lista = [
			'titulo' => 'Editar planilla',
			'title' => 'Planilla N° '.$planilla['Numero'],
			'config' => config('planillas.'.date('Y')),
			'planilla' => $planilla,
			'bitacora' => true,
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

		$bitacora = $request->input('_bitacora') == '1' ? true : false;
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

			if ($bitacora)
				$to_sync[$recurso->Id]['Bitacora'] = $recurso->Bitacora;
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
			case '3': //Aprobar
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
			case '4':
				$planilla->Estado = $request->input('Estado');
				break;
			case '5':
				$planilla->Estado = $request->input('Estado');
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

						if ($total_saldo >= $contrato->recursos()->sum('Saldo_CRP'))
							$contrato->Estado = 'finalizado';
					break;
				default:
					break;
			}

			$contrato->save();
		}


		return redirect()->to('planillas/'.$planilla['Id_Planilla'].($bitacora ? '/bitacora' : '/recursos' ))
						->with('status', 'success');
	}

	public function eliminar(Request $request, $Id_Planilla)
	{
		$planilla = Planilla::find($Id_Planilla);
		$planilla->delete();

		return response()->json(array('status' => 'ok'));
	}

	public function generarArchivoPlano(Request $request)
	{
		$Id_Planilla = $request->input('Id_Planilla');
		list($codigo_bodega, $codigo_operacion) = explode(',', $request->input('Subdireccion'));
		$planilla = Planilla::find($Id_Planilla);
		$contratos = $this->popularRecursos($Id_Planilla);
		$planilla->Estado = 5;
		$planilla->save();

		foreach ($contratos as $contrato) 
		{
			foreach ($contrato->recursos as $recurso) 
			{
				for($i=1; $i<4; $i++)
				{
					$codigo_arbol = [
						0 => '',
						1 => $planilla->fuente['Codigo'],
						2 => '99',
						3 => '99'
					];

					$data[] = [
						'Estado_Actualizacion' => 'A',
						'Usuario_que_Actualiza' => $this->Usuario['Persona']->Primer_Apellido.' '.$this->Usuario['Persona']->Primer_Nombre,
						'Fecha_Actualizacion' => date('d/m/Y'),
						'Codigo_Empresa' => '2',
						'Codigo_Tipo_de_Operacion' => $codigo_operacion,
						'Numero_Factura' => $recurso->planillado['Bitacora'],
						'Año_Proceso' => date('Y'),
						'Mes_Proceso' => date('n'),
						'Dia_Proceso' => date('j'),
						'Codigo_Arbol_Sucursal' => '',
						'Codigo_Proveedor' => $contrato->contratista['Cedula'],
						'Codigo_Detalle_de_Proveedor' => '1',
						'Codigo_del_contacto_detalle_proveedor' => '1',
						'Codigo_de_la_actividad_del_proveedor' => '9609',
						'Codigo_Moneda' => '1',
						'Valor_de_la_Tasa' => '1',
						'Año_Tasa' => date('Y'),
						'Mes_Tasa' => date('n'),
						'Dia_Tasa' => date('j'),
						'Descripcion_Factura' => $contrato['Objeto'],
						'Prefijo_Factura' => '.',
						'Número_de_factura' => $recurso->planillado['Bitacora'],
						'Tipo_de_Documento' => 'F',
						'Valor_Total' => $recurso->planillado['Total_Pagar'],
						'Consecutivo_Detalle_Factura' => '1',
						'Codigo_Producto' => '460005',
						'Codigo_Bodega' => $codigo_bodega,
						'Codigo_Unidad_Medida' => '1',
						'Cantidad' => '1',
						'Valor' => $recurso->planillado['Total_Pagar'],
						'Tipo_descuento' => 'P',
						'Porcentaje_o_valor_descuento' => '0',
						'Descripcion_Detalle' => $contrato['Objeto'],
						'Codigo_Tipo_de_Arbol' => $i,
						'Codigo_Arbol' =>$codigo_arbol[$i],
						'Tipo_Distribucion' => 'P',
						'Valor_Distribucion' => '0',
						'Porcentaje_Distribucion' => '100',
						'Destino_del_producto' => '114',
						'Fecha_Prestacion_de_servicio' =>  Carbon::createFromFormat('Y-m-d', $planilla->Hasta)->format('dmY'),
						'Año_de_radicación_de_factura' => date('Y'),
						'Mes_de_radicación_de_factura' => date('n'),
						'Día_de_radicación_de_factura' => date('j'),
						'Autorizado_por' => '0',
						'Numero_Bitacora_de_Radicacion' => $recurso->planillado['Bitacora']
					];
				}
			}
		}

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A1');
		$objPHPExcel->getActiveSheet()->setTitle('Planilla');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="archivo.xls"');
        $objWriter->save('php://output');
	}

	public function logout()
	{
		$_SESSION['Usuario'] = '';
		Session::set('Usuario', ''); 

		return redirect()->to('/');
	}

	private function popularRecursos($Id_Planilla)
	{

		$planilla = Planilla::with('recursos')->find($Id_Planilla);
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
					'Neto_Pagar' => $temp->pivot['Neto_Pagar'],
					'Bitacora' => $temp->pivot['Bitacora'],
				];

				$recursos_contratos[$key_2]['saldo'] = $recurso_2->saldos()->sum('Total_Pagado');
			}

			$contratos[$key]['recursos'] = $recursos_contratos;
		}

		return $contratos;
	}
}
