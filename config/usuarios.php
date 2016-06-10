<?php

return array( 
 
  'conexion' => 'db_principal', 
   
  'prefijo_ruta' => 'personas', 
  'seccion' => 'Personas', 
 
  'modelo_persona' => 'App\Modulos\Personas\Persona', 
  'modelo_documento' => 'App\Modulos\Personas\Documento', 
  'modelo_pais' => 'App\Modulos\Personas\Pais', 
  'modelo_ciudad' => 'App\Modulos\Personas\Ciudad', 
  'modelo_genero' => 'App\Modulos\Personas\Genero', 
  'modelo_etnia' => 'App\Modulos\Personas\Etnia', 
   
  //vistas que carga las vistas 
  'vista_lista' => 'list', 
 
  //lista 
  'lista'  => 'idrd.usuarios.lista', 
);