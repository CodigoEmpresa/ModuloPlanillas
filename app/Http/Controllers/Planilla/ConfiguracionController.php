<?php

namespace App\Http\Controllers\Planilla;

use App\Http\Controllers\Controller;
use App\Modulos\Personas\Documento;
use App\Modulos\Personas\Pais;
use App\Modulos\Personas\Etnia;
use App\Modulos\Planilla\Configuracion;
use Illuminate\Http\Request;
use Validator;

class ConfiguracionController extends Controller {

	protected $Usuario;

    public function __construct()
    {
        if (isset($_SESSION['Usuario']))
            $this->Usuario = $_SESSION['Usuario'];
    }

    public function index(Request $request, $Id_Persona)
    {
        $configuracion = Configuracion::where('Usuario', $Id_Persona)->first();

        $formulario = [
            'configuracion' => $configuracion,
            'usuario' => $Id_Persona,
            'documentos' => Documento::all(),
            'paises' => Pais::all(),
            'etnias' => Etnia::all(),
            'status' => session('status')
        ];

        $data = [
            'seccion' => 'usuario',
            'formulario' => view('idrd.planilla.configuracion.formulario', $formulario)
        ];
    	
        return view('form', $data);
    }

    public function store(Request $request)
    {
        if ($request->input('id') == 0)
        {
            $configuracion = new Configuracion;
        } else {
            $configuracion = Configuracion::where('Id', $request->input('id'))->first();
            $this->borrarImagen($configuracion->Firma);
        }

        $this->process($configuracion, $request->input());

        return redirect('personas/'.$_SESSION['Usuario'][0].'/editar')
            ->with(['status' => 'success']);
    }

    private function process($configuracion, $input)
    {
        $configuracion->Usuario = $_SESSION['Usuario'][0];
        $configuracion->Firma = $this->crearImagen($input['firma']);

        $configuracion->save();
    }

    private function crearImagen($imagen)
    {
        $img = str_replace('data:image/png;base64,', '', $imagen);
        $img = str_replace(' ', '+', $img);
        $name = uniqid('img_').'.png';
        $path = public_path().'/Firmas/';
        @mkdir($path, 0666, true);
        $fileData = base64_decode($img);
        file_put_contents($path.$name, $fileData);

        return $name;
    }

    private function borrarImagen($imagen)
    {
        unlink(public_path().'/Firmas/'.$imagen);
    }
}