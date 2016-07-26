@section('style')
	@parent

	<link rel="stylesheet" href="{{ asset('public/Css/drawingboard.css') }}">
	<link rel="stylesheet" href="{{ asset('public/Css/drawingboard.nocontrol.css') }}">
@stop
@section('script')
	@parent

    <script src="{{ asset('public/Js/drawingboard.js') }}"></script>	
    <script src="{{ asset('public/Js/drawingboard.nocontrol.js') }}"></script>	
    <script src="{{ asset('public/Js/usuarios/usuarios.js') }}"></script>	
    <script src="{{ asset('public/Js/planillas/configuracion/configuracion.js') }}"></script>	
@stop

<form id="form-firma" action="{{ url('configuracion') }}" method="post">
	<div id="main_persona" class="row" data-url="{{ url(config('usuarios.prefijo_ruta')) }}">
		<div id="personas" class="col-xs-12">
			<a data-role="editar" data-rel="{{ $usuario }}" class="btn btn-primary">
				Editar mis datos personales
			</a>
		</div>
		<div class="col-xs-12">
			<br>
		</div>
		<div class="col-xs-12 col-md-6" class="form-group">
			<label for="">Firma (400 * 200)</label>
			<div id="drawingboard" style="width: 400px; height: 200px;"></div>
		</div>
		<div class="col-xs-12 col-md-6" class="form-group">
			<label for="">Firma actual</label><br>
			<img src="{{ $configuracion ? asset('public/Firmas/'.$configuracion['Firma']) : '' }}" alt="" id="firma-actual" style="width: 400px; height: 200px; margin-top:35px;">
		</div>
		<div class="col-xs-12">
			<br>
			<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
			<input type="hidden" name="firma" value="{{ $configuracion ? $configuracion['Firma'] : '' }}">
			<input type="hidden" name="id" value="{{ $configuracion ? $configuracion['Id'] : 0 }}">
			<input type="submit" value="Guardar" class="btn btn-primary">
		</div>
	</div>
</form>

<!-- Modal formulario  persona -->
<div class="modal fade" id="modal_form_persona" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form action="" id="form_persona">
			<div class="modal-content">
				<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    			<h4 class="modal-title" id="myModalLabel">Crear o editar persona.</h4>
	  			</div>
	      		<div class="modal-body">
		      		<fieldset>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
		        				<label class="control-label" for="Id_TipoDocumento">* Tipo documento </label>
		        				<select name="Id_TipoDocumento" id="" class="form-control">
		        					<option value="">Seleccionar</option>
		        					@foreach($documentos as $documento)
		        						<option value="{{ $documento['Id_TipoDocumento'] }}">{{ $documento['Descripcion_TipoDocumento'] }}</option>
		        					@endforeach
		        				</select>
		        			</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
		        				<label class="control-label" for="Cedula">* Documento </label>
		        				<input type="text" name="Cedula" class="form-control">
		        			</div>
		        		</div>
		        		<div class="col-xs-12">
		        			<div class="form-group">
		        				<label class="control-label" for="Primer_Apellido">* Primer apellido </label>
		        				<input type="text" name="Primer_Apellido" class="form-control">
		        			</div>
		        		</div>
		        		<div class="col-xs-12">
		        			<div class="form-group">
		        				<label class="control-label" for="Segundo_Apellido">Segundo apellido </label>
		        				<input type="text" name="Segundo_Apellido" class="form-control">
		        			</div>
		        		</div>
		        		<div class="col-xs-12">
		        			<div class="form-group">
		        				<label class="control-label" for="Primer_Nombre">* Primer nombre </label>
		        				<input type="text" name="Primer_Nombre" class="form-control">
		        			</div>
		        		</div>
		        		<div class="col-xs-12">
		        			<div class="form-group">
		        				<label class="control-label" for="Segundo_Nombre">Segundo nombre </label>
		        				<input type="text" name="Segundo_Nombre" class="form-control">
		        			</div>
		        		</div>
		        		<div class="col-xs-12">
		        			<hr>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Fecha_Nacimiento">* Fecha de nacimiento</label>
		        				<input type="text" name="Fecha_Nacimiento" data-role="datepicker" class="form-control">
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Id_Genero">* Genero</label><br>
				        		<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-default">
										<input type="radio" name="Id_Genero" value="1" autocomplete="off"> <span class="text-success">M</span>
									</label>
									<label class="btn btn-default">
										<input type="radio" name="Id_Genero" value="2" autocomplete="off"> <span class="text-danger">F</span>
									</label>
								</div>
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
		        				<label class="control-label" for="Id_Etnia">* Etnia </label>
		        				<select name="Id_Etnia" id="" class="form-control">
		        					<option value="">Seleccionar</option>
		        					@foreach($etnias as $etnia)
		        						<option value="{{ $etnia['Id_Etnia'] }}">{{ $etnia['Nombre_Etnia'] }}</option>
		        					@endforeach
		        				</select>
		        			</div>
		        		</div>
		        		<div class="col-xs-12">
		        			<hr>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
		        				<label class="control-label" for="Id_Pais">* Pais </label>
		        				<select name="Id_Pais" id="" class="form-control">
		        					<option value="">Seleccionar</option>
		        					@foreach($paises as $pais)
		        						<option value="{{ $pais['Id_Pais'] }}">{{ $pais['Nombre_Pais'] }}</option>
		        					@endforeach
		        				</select>
		        			</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
		        				<label class="control-label" for="Nombre_Ciudad">Ciudad </label>
		        				<select name="Nombre_Ciudad" id="" class="form-control" data-value="">
		        					<option value="">Seleccionar</option>
		        				</select>
		        			</div>
		        		</div>
		        	</fieldset>
	      		</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="Id_Persona" value="0">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		<button type="submit" class="btn btn-primary">Guardar</button>
	      		</div>
	    	</div>
    	</form>
  	</div>
</div>