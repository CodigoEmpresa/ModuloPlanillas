<?php

use App\Modulos\Planilla\Traits\DocumentoContratistas as DocumentoContratistas;
namespace App\Modulos\Personas;

use Idrd\Usuarios\Repo\Etnia as MEtnia;

class Etnia extends MEtnia
{
    use DocumentoContratistas;
}
