@section('script')
	@parent

    <script src="{{ asset('public/Js/usuarios/usuarios.js') }}"></script>	
@stop

<div id="main_persona" class="row" data-url="{{ url(config('usuarios.prefijo_ruta')) }}">
	<div class="col-xs-12">
		<h4>Personas</h4>
	</div>
	<div id="alerta" class="col-xs-12" style="display:none;">
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Datos actualizados satisfactoriamente.
		</div>
	</div>
	<div class="col-xs-12">
		<div class="input-group">
      		<input name="buscador" type="text" class="form-control" placeholder="Buscar">
      		<span class="input-group-btn">
        		<button id="buscar" data-role="buscar" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
      		</span>
    	</div>
	</div>
	<div class="col-xs-12">
		<hr>
	</div>
	<div class="col-xs-12">
        <button id="crear" data-role="crear" class="btn btn-primary" type="button">Crear persona</button>
	</div>
	<div class="col-xs-12">
		<br>
	</div>
	<div class="col-xs-12">
		<ul class="list-group" id="personas">
			@foreach($personas as $persona)
				<li class="list-group-item">
	                <h5 class="list-group-item-heading">
	                    {{ strtoupper($persona['Primer_Apellido'].' '.$persona['Segundo_Apellido'].' '.$persona['Primer_Nombre'].' '.$persona['Segundo_Nombre']) }}
	                    <a data-role="editar" data-rel="{{ $persona['Id_Persona'] }}" class="pull-right btn btn-primary btn-xs">
	                    	<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
	                    </a>
	                </h5>
	                <p class="list-group-item-text">
	                    <div class="row">
	                        <div class="col-xs-12">
	                            <div class="row">
	                                <div class="col-xs-12 col-sm-6 col-md-3"><small>Identificación: {{ $persona->tipoDocumento['Nombre_TipoDocumento'].' '.$persona['Cedula'] }}</small></div>
	                            </div>
	                        </div>
	                    </div>
	                </p>
	            </li>
			@endforeach
		</ul>
	</div>
	<div id="paginador" class="col-xs-12">
		{!! $personas->render() !!}
	</div>
</div>
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