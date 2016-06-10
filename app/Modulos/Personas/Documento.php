<?php

namespace App\Modulos\Personas;

use Idrd\Usuarios\Repo\Documento as MDocumento;
use App\Modulos\Planilla\Traits\DocumentoContratistas as hasManyPersonas;

class Documento extends MDocumento
{
    use hasManyPersonas;
}
