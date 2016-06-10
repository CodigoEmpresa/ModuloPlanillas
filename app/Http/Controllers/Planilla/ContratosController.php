<?php

namespace App\Http\Controllers\Planilla;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modulos\Personas\Documento;
use App\Modulos\Planilla\Modelos\Contrato;
use App\Modulos\Planilla\Modelos\Contratista;
use App\Modulos\Planilla\Modelos\Fuente;
use App\Modulos\Planilla\Modelos\Rubro;
use App\Modulos\Planilla\Modelos\Componente;
use App\Modulos\Planilla\Modelos\Recurso;
use Session;
use Validator;

class ContratosController extends Controller{

    protected $Usuario;

    public function __construct()
    {
        if (isset($_SESSION['Usuario']))
            $this->Usuario = $_SESSION['Usuario'];
    }

    public function index(Request $request)
    {
        Session::put('back', $request->url());
        $perPage = 10;
        $contratos = Contrato::where('Usuario', $this->Usuario[0])
                        ->orderBy('Numero')
                        ->paginate($perPage);
        $lista = [
            'contratos' => $contratos
        ];

        $datos = [
            'seccion' => 'Contratos',
            'lista' => view('idrd.planilla.contratos.buscador', $lista)
        ];

        return view('list', $datos);
    }

    public function buscar(Request $request, $key)
    {
        $contratos = Contrato::where('Usuario', $this->Usuario[0])
                            ->where('Numero', 'LIKE', '%'.$key.'%')
                            ->get();

        return response()->json($contratos);
    } 

    public function obtenerPorContratista(Request $request, $Id_Contratista)
    {
        Session::put('back', $request->url());
    	$perPage = 10;
    	$contratista = Contratista::with('tipoDocumento')->find($Id_Contratista);
		$contratos = Contrato::where('Usuario', $this->Usuario[0])
                        ->where('Id_Contratista', $Id_Contratista)
						->orderBy('Numero')
						->paginate($perPage);

		$lista = [
			'contratista' => $contratista,
			'contratos' => $contratos
		];

		$datos = [
			'seccion' => 'Contratistas',
			'lista'	=> view('idrd.planilla.contratos.lista', $lista)
		];

		return view('list', $datos);
    }

    public function crear(Request $request, $Id_Contratista)
    {
    	$contratista = Contratista::with('tipoDocumento')->find($Id_Contratista);

		$fuentes = Fuente::all();
		$rubros = Rubro::all();
		$componentes = Componente::all();

    	$formulario = [
    		'contratista' => $contratista,
    		'fuentes' => $fuentes,
    		'rubros' => $rubros,
    		'componentes' => $componentes,
            'status' => session('status'),
    		'contrato' => null,
            'recursos' => null
    	];

    	$datos = [
    		'seccion' => 'Contratos',
    		'formulario' => view('idrd.planilla.contratos.formulario', $formulario)
    	];

    	return view('form', $datos);
    }

    public function guardar(Request $request)
    {
        $id = $request->input('Id_Contrato');
        $validator = Validator::make($request->all(),
            [
                'Numero' => 'required|numeric|unique:mysql.Contratos,Numero'.($id == '0' ? '' : ','.$id.',Id_Contrato'),
                'Fecha_Inicio' => 'required|date',
                'Fecha_Terminacion' => 'required|date|after:Fecha_Inicio',
                'Total_Contrato' => 'required|numeric',
                'Total_Ejecutado' => 'required|numeric',
                'Tipo_Pago' => 'required',
                'Id_Contratista' => 'exists:Contratistas,Id_Contratista',
                '_recursos' => 'min:3'
            ],
            [
                'Numero.required' => 'El campo número de contrato es requerido',
                'Numero.unique' => 'El número de contrato ya ha sido registrado',
                'Fecha_Inicio.required' => 'El campo fecha de inicio es requerido',
                'Fecha_Terminacion.required' => 'El campo fecha de terminación es requerido',
                'Total_Contrato.required' => 'El campo total contrato es requerido',
                'Total_Ejecutado.required' => 'El campo total ejecutado es requerido',
                'Tipo_Pago.required' => 'El campo tipo de pago es requerido',
                '_recursos.min' => 'Debe ingresar al menos una fuente de financiación para el contrato'
            ]
        );
        
        if ($validator->fails())
            return redirect()->to('contratos/'.$request->input('Id_Contratista').'/crear')
                        ->withErrors($validator)
                        ->withInput()
                        ->with(['status' => 'error']);

        $contrato = $this->procesar(new Contrato, $request);
        return redirect()->to('contratos/'.$request->input('Id_Contratista').'/editar/'.$contrato['Id_Contrato'])
                        ->with(['status' => 'success']);
    }

    public function editar(Request $request, $Id_Contratista, $Id_Contrato)
    {
        $contratista = Contratista::with('tipoDocumento')->find($Id_Contratista);
        $contrato = Contrato::find($Id_Contrato);
        $recursos = Recurso::with('rubro', 'fuente', 'componente')->where('Id_Contrato', $Id_Contrato)->get();
        $fuentes = Fuente::all();
        $rubros = Rubro::all();
        $componentes = Componente::all();

        $formulario = [
            'contratista' => $contratista,
            'fuentes' => $fuentes,
            'rubros' => $rubros,
            'componentes' => $componentes,
            'status' => session('status'),
            'contrato' => $contrato,
            'recursos' => $recursos
        ];

        $datos = [
            'seccion' => 'Contratos',
            'formulario' => view('idrd.planilla.contratos.formulario', $formulario)
        ];

        return view('form', $datos);
    }

    public function actualizar(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'Numero' => 'required|numeric',
                'Fecha_Inicio' => 'required|date',
                'Fecha_Terminacion' => 'required|date',
                'Total_Contrato' => 'required|numeric',
                'Total_Ejecutado' => 'required|numeric',
                'Tipo_Pago' => 'required',
                'Id_Contratista' => 'exists:Contratistas,Id_Contratista',
                '_recursos' => 'min:3'
            ],
            [
                'Numero.required' => 'El campo número de contrato es requerido',
                'Fecha_Inicio.required' => 'El campo fecha de inicio es requerido',
                'Fecha_Terminacion.required' => 'El campo fecha de terminación es requerido',
                'Total_Contrato.required' => 'El campo total contrato es requerido',
                'Total_Ejecutado.required' => 'El campo total ejecutado es requerido',
                'Tipo_Pago.required' => 'El campo tipo de pago es requerido',
                '_recursos.min' => 'Debe ingresar al menos una fuente de financiación para el contrato'
            ]
        );
        
        if ($validator->fails())
            return redirect()->to('contratos/'.$request->input('Id_Contratista').'/editar/'.$request->input('Id_Contrato'))
                        ->withErrors($validator)
                        ->withInput()
                        ->with(['status' => 'error']);

        $contrato = $this->procesar(Contrato::find($request->input('Id_Contrato')), $request);
        return redirect()->to('contratos/'.$request->input('Id_Contratista').'/editar/'.$contrato['Id_Contrato'])
                        ->with(['status' => 'success']);
    }

    public function eliminar(Request $request, $Id_Contrato)
    {
        $contrato = Contrato::find($Id_Contrato);
        $contrato->delete();

        return redirect()->away(Session::get('back'));
    }

    protected function procesar($contrato, $request)
    {
        $recursos = json_decode($request->input('_recursos'));
        $contrato['Numero'] = $request->input('Numero');
        $contrato['Usuario'] = $this->Usuario[0];
        $contrato['Objeto'] = $request->input('Objeto');
        $contrato['Fecha_Inicio'] = $request->input('Fecha_Inicio');
        $contrato['Fecha_Terminacion'] = $request->input('Fecha_Terminacion');
        $contrato['Total_Contrato'] = $request->input('Total_Contrato');
        $contrato['Total_Ejecutado'] = $request->input('Total_Ejecutado');
        $contrato['Tipo_Pago'] = $request->input('Tipo_Pago');
        $contrato['Dias_Trabajados'] = 0;
        $contrato['Id_Contratista'] = $request->input('Id_Contratista');
        $contrato->save();
        $to_sync = [];

        foreach ($recursos as $recurso) 
        {
            $temp = [
                'Id' => $recurso->Id,
                'Id_Rubro' => $recurso->Rubro->id,
                'Id_Fuente' => $recurso->Fuente->id,
                'Id_Componente' => $recurso->Componente->id,
                'Id_Contrato' => $contrato->Id_Contrato,
                'Numero_Registro' => $recurso->Numero_Registro,
                'Valor_CRP' => $recurso->Valor_CRP,
                'Saldo_CRP' => $recurso->Saldo_CRP,
                'Expresion' => $recurso->Expresion,
                'Pago_Mensual' => $recurso->Pago_Mensual
            ];

            $to_sync[] = $temp;
        }

        $id_registrados = [];

        //actualizar existentes
        foreach ($contrato->recursos as $key => $recurso) 
        {
            $fkey = array_search($recurso['Id'], array_column($to_sync, 'Id'));
            
            if (is_numeric($fkey))
            {
                $id_registrados[] = $recurso['Id'];
                $recurso['Id_Rubro'] = $to_sync[$fkey]['Id_Rubro'];
                $recurso['Id_Fuente'] = $to_sync[$fkey]['Id_Fuente'];
                $recurso['Id_Componente'] = $to_sync[$fkey]['Id_Componente'];
                $recurso['Id_Contrato'] = $to_sync[$fkey]['Id_Contrato'];
                $recurso['Numero_Registro'] = $to_sync[$fkey]['Numero_Registro'];
                $recurso['Valor_CRP'] = $to_sync[$fkey]['Valor_CRP'];
                $recurso['Saldo_CRP'] = $to_sync[$fkey]['Saldo_CRP'];
                $recurso['Expresion'] = $to_sync[$fkey]['Expresion'];
                $recurso['Pago_Mensual'] = $to_sync[$fkey]['Pago_Mensual'];
                $recurso->save();
            }   
        }

        //eliminar no existentes
        if(count($id_registrados) > 0)
            $contrato->recursos()->whereNotIn('Id', $id_registrados)->delete();

        //agregar nuevos
        foreach ($to_sync as $data_recurso) {
            if($data_recurso['Id'] == 0)
            {
                $nuevo = new Recurso([
                    'Id_Rubro' => $data_recurso['Id_Rubro'],
                    'Id_Fuente' => $data_recurso['Id_Fuente'],
                    'Id_Componente' => $data_recurso['Id_Componente'],
                    'Id_Contrato' => $data_recurso['Id_Contrato'],
                    'Numero_Registro' => $data_recurso['Numero_Registro'],
                    'Valor_CRP' => $data_recurso['Valor_CRP'],
                    'Saldo_CRP' => $data_recurso['Saldo_CRP'],
                    'Expresion' => $data_recurso['Expresion'],
                    'Pago_Mensual' => $data_recurso['Pago_Mensual']
                ]);

                $nuevo->save();
            }
        }

        return $contrato;
    }
}
