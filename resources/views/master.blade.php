<!DOCTYPE html>

<html lang="es">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="csrf-token" content="{{ csrf_token() }}" />

      <link rel="icon" href="{{ asset('public/Img/idrd_icon.ico') }}">    
      @section('style')
          <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
          <link rel="stylesheet" href="{{ asset('public/Css/jquery-ui.css') }}" media="screen">    
          <link rel="stylesheet" href="{{ asset('public/Css/bootstrap.min.css') }}" media="screen">    
          <link rel="stylesheet" href="{{ asset('public/Css/bootstrap-select.min.css') }}" media="screen">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css" media="screen">
          <link rel="stylesheet" href="{{ asset('public/Css/sticky-footer.css') }}" media="screen">
          <link rel="stylesheet" href="{{ asset('public/Css/app.css') }}" media="screen">
      @show

      @section('script')
          <script src="{{ asset('public/Js/jquery.js') }}"></script>
          <script src="{{ asset('public/Js/jquery-ui.js') }}"></script>
          <script src="{{ asset('public/Js/datepicker-es.js') }}"></script>
          <script src="{{ asset('public/Js/inputmask.js') }}"></script>
          <script src="{{ asset('public/Js/inputmask.extensions.js') }}"></script>
          <script src="{{ asset('public/Js/inputmask.numeric.extensions.js') }}"></script>
          <script src="{{ asset('public/Js/jquery.inputmask.js') }}"></script>
          <script src="{{ asset('public/Js/accounting.min.js') }}"></script>
          <script src="{{ asset('public/Js/moment.min.js') }}"></script>
          <script src="{{ asset('public/Js/moment-range.min.js') }}"></script>
          <script src="{{ asset('public/Js/parser.js') }}"></script>
          <script src="{{ asset('public/Js/bootstrap.min.js') }}"></script>
          <script src="{{ asset('public/Js/bootstrap-select.min.js') }}"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
          <script src="{{ asset('public/Js/defaults-es_CL.min.js') }}"></script>
          <script src="{{ asset('public/Js/main.js') }}"></script>
      @show

      <title>Planillas de pago</title>
  </head>

  <body>
      
       <!-- Menu Módulo -->
       <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <a href="#" class="navbar-brand">SIM</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
              <li class="{{ $seccion && $seccion == 'Planillas' ? 'active' : '' }}">
                <a href="{{ url('planillas') }}">Planillas de pago</a>
              </li>
              <li class="dropdown {{ $seccion && ($seccion == 'Contratistas' || $seccion == 'Contratos') ? 'active' : '' }}">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="themes">Contratación <span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="themes">
                  <li class="{{ $seccion && $seccion == 'Contratos' ? 'active' : '' }}"><a href="{{ url('contratos') }}">Contratos</a></li>
                  <li class="{{ $seccion && $seccion == 'Contratistas' ? 'active' : '' }}"><a href="{{ url('contratistas') }}">Contratistas</a></li>
                </ul>
              </li>
              <li class="dropdown {{ $seccion && ($seccion == 'Bancos' || $seccion == 'Fuentes' || $seccion == 'Rubros' || $seccion == 'Componentes') ? 'active' : '' }}">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">Parametros <span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="download">
                  <li class="{{ $seccion && $seccion == 'Bancos' ? 'active' : '' }}"><a href="{{ url('bancos') }}">Bancos</a></li>
                  <li class="{{ $seccion && $seccion == 'Fuentes' ? 'active' : '' }}"><a href="{{ url('fuentes') }}">Fuentes</a></li>
                  <li class="{{ $seccion && $seccion == 'Rubros' ? 'active' : '' }}"><a href="{{ url('rubros') }}">Rubros</a></li>
                  <li class="{{ $seccion && $seccion == 'Componentes' ? 'active' : '' }}"><a href="{{ url('componentes') }}">Componentes</a></li>
                </ul>
              </li>
              <li class="{{ $seccion && $seccion == 'Personas' ? 'active' : '' }}">
                <a href="{{ url('personas') }}">Administración</a>
              </li>
            </ul>
            
            <ul class="nav navbar-nav navbar-right">
              <li><a href="http://www.idrd.gov.co/sitio/idrd/" target="_blank">I.D.R.D</a></li>
              <li><a href="{{ url('logout') }}">Cerrar Sesión</a></li>
            </ul>

          </div>
        </div>
      </div>
      <!-- FIN Menu Módulo -->
        
      <!-- Contenedor información módulo -->
      </br></br>
      <div class="container">
          <div class="page-header" id="banner">
            <div class="row">
              <div class="col-lg-8 col-md-7 col-sm-6">
                <h1>Planillas de pago</h1>
                <p class="lead"><h4>Módulo para la gestión y control de las planillas de pago</h4></p>
              </div>
              <div class="col-lg-4 col-md-5 col-sm-6">
                 <div align="right"> 
                    <img src="{{ asset('public/Img/IDRD.JPG') }}" width="50%" heigth="50%"/>
                 </div>                    
              </div>
            </div>
          </div>        
      </div>
      <!-- FIN Contenedor información módulo -->

      <!-- Contenedor panel principal -->
      <div class="container">
          @yield('content')
      </div>
      <div id="loading-image">
          <div class="sk-cube-grid">
              <div class="sk-cube sk-cube1"></div>
              <div class="sk-cube sk-cube2"></div>
              <div class="sk-cube sk-cube3"></div>
              <div class="sk-cube sk-cube4"></div>
              <div class="sk-cube sk-cube5"></div>
              <div class="sk-cube sk-cube6"></div>
              <div class="sk-cube sk-cube7"></div>
              <div class="sk-cube sk-cube8"></div>
              <div class="sk-cube sk-cube9"></div>
          </div>
      </div>
      <!-- FIN Contenedor panel principal -->
  </body>

</html>





