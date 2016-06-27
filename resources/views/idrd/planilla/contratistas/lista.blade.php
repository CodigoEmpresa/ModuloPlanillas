@section('script')
	@parent

    <script src="{{ asset('public/Js/planillas/contratistas/contratistas.js') }}"></script>	
@stop

<div id="main_list" class="row" data-url="{{ url('contratistas') }}">
	<div class="col-xs-12">
		<h4>Contratistas</h4>
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
		<button id="crear" data-role="crear" class="btn btn-primary" type="button">Crear contratista</button>
	</div>
	<div class="col-xs-12">
		<br>
	</div>
	<div class="col-xs-12">
		@if(count($contratistas) == 0)
			No se ha creado ningun contratista en el momento haga click en el boton <strong>Crear contratista</strong> para crear uno.
		@endif
		<ul class="list-group" id="lista">
			@foreach($contratistas as $contratista)
				<li class="list-group-item">
	                <h5 class="list-group-item-heading">
	                    {{ strtoupper($contratista['Nombre']) }}
	                    <a data-role="editar" data-rel="{{ $contratista['Id_Contratista'] }}" class="pull-right btn btn-primary btn-xs">
	                    	<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
	                    </a>
	                </h5>
	                <p class="list-group-item-text">
                        <div class="row">
                            <div class="col-xs-12">
                            	<small><strong>Identificación:</strong> {{ $contratista->tipoDocumento['Nombre_TipoDocumento'].' '.$contratista['Cedula'] }}</small>
                            </div>
                            <div class="col-xs-12">
                            	<br>
                            	<a href="{{ url('contratistas/'.$contratista['Id_Contratista'].'/contratos') }}" class="btn btn-default btn-xs">Contratos</a>
                            </div>
                        </div>
	                </p>
	            </li>
			@endforeach
		</ul>
	</div>
	<div id="paginador" class="col-xs-12">
		{!! $contratistas->render() !!}
	</div>
</div>

<!-- Modal formulario  contratistas -->
<div class="modal fade" id="modal_main_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form action="" id="main_form">
			<div class="modal-content">
				<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    			<h4 class="modal-title" id="myModalLabel">Crear o editar un contratista.</h4>
	  			</div>
	      		<div class="modal-body">
		      		<fieldset>
		      			<div id="errores" class="col-xs-12" style="display: none;">
		      				<div class="alert alert-danger" role="alert">
		      					<strong>Solucione los siguientes inconvenientes:</strong>
		      					<ul>
		      						
		      					</ul>
		      				</div>
		      			</div>
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
		        				<label class="control-label" for="Nombre">* Nombre <small class="text-muted">(APELLIDOS y NOMBRES )</small></label>
		        				<input type="text" name="Nombre" class="form-control uppercase">
		        			</div>
		        		</div>
						<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
		        				<label class="control-label" for="Id_Banco">* Banco </label>
		        				<select name="Id_Banco" id="Id_Banco" class="form-control selectpicker" data-live-search="true">
		        					<option value="">Seleccionar</option>
		        					@foreach($bancos as $banco)
		        						<option value="{{ $banco['Id_Banco'] }}">{{ $banco['Nombre'] }}</option>
		        					@endforeach
		        				</select>
		        			</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
		        				<label class="control-label" for="Numero_Cta">* Cuenta </label>
		        				<input type="text" name="Numero_Cta" class="form-control">
		        			</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Tipo_Cuenta">* Tipo de cuenta</label><br>
				        		<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-default">
										<input type="radio" name="Tipo_Cuenta" value="CA" autocomplete="off"> <span class="text-default">CA</span>
									</label>
									<label class="btn btn-default">
										<input type="radio" name="Tipo_Cuenta" value="CC" autocomplete="off"> <span class="text-default">CC</span>
									</label>
								</div>
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Nivel_Riesgo_ARL">* Nivel de riesgo</label><br>
				        		<select name="Nivel_Riesgo_ARL" id="Nivel_Riesgo_ARL" class="form-control selectpicker" data-live-search="true">
				        			<option value="1">I</option>
				        			<option value="2">II</option>
				        			<option value="3">III</option>
				        			<option value="4">IV</option>
				        			<option value="5">V</option>
				        		</select>
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Hijos">* Hijos</label><br>
				        		<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-default">
										<input type="radio" name="Hijos" value="1" autocomplete="off"> <span class="text-default">Si</span>
									</label>
									<label class="btn btn-default">
										<input type="radio" name="Hijos" value="0" autocomplete="off"> <span class="text-default">No</span>
									</label>
								</div>
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Hijos_Cantidad">Número de hijos</label><br>
		        				<input type="text" name="Hijos_Cantidad" class="form-control">
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Medicina_Prepagada">* Medicina prepagada</label><br>
				        		<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-default">
										<input type="radio" name="Medicina_Prepagada" value="1" autocomplete="off"> <span class="text-default">Si</span>
									</label>
									<label class="btn btn-default">
										<input type="radio" name="Medicina_Prepagada" value="0" autocomplete="off"> <span class="text-default">No</span>
									</label>
								</div>
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Medicina_Prepagada_Cantidad">Total medicina prepagada ({{ date("Y", strtotime("-1 year")) }})</label><br>
		        				<input type="text" name="Medicina_Prepagada_Cantidad" class="form-control"  placeholder="$" data-currency>
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Declarante">* Declarante</label><br>
				        		<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-default">
										<input type="radio" name="Declarante" value="1" autocomplete="off"> <span class="text-default">Si</span>
									</label>
									<label class="btn btn-default">
										<input type="radio" name="Declarante" value="0" autocomplete="off"> <span class="text-default">No</span>
									</label>
								</div>
							</div>
		        		</div>
		        		<div class="col-xs-12 col-md-6">
		        			<div class="form-group">
			        			<label class="control-label" for="Activo">* Activo</label><br>
				        		<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-default">
										<input type="radio" name="Activo" value="1" autocomplete="off"> <span class="text-default">Si</span>
									</label>
									<label class="btn btn-default">
										<input type="radio" name="Activo" value="0" autocomplete="off"> <span class="text-default">No</span>
									</label>
								</div>
							</div>
		        		</div>
		      		</fieldset>
		      	</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="Id_Contratista" value="0">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		<button type="submit" class="btn btn-primary">Guardar</button>
	      		</div>
	    	</div>
		</form>
	</div>
</div>