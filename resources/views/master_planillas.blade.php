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

      <title>{{ isset($title) ? $title : 'Planillas de pago' }}</title>
  </head>

  <body style="padding: 10px;">
      <!-- Contenedor panel principal -->
      <div class="container-fluid">
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





