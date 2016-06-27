@section('script')
	@parent
	
	<script src="{{ asset('public/Js/planillas/planillas/planillas.js') }}"></script>
@stop

<div id="main_list" class="row" data-url="{{ url('planillas') }}">
	<div class="col-xs-12">
		<h4>{{ $titulo }}</h4>
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
		<button id="crear" class="btn btn-primary">Crear planilla</button>
	</div>
	<div class="col-xs-12">
		<br>
	</div>
	<div class="col-xs-12">
		@if(count($elementos) == 0)
			No se ha creado ninguna planilla haga click en el boton "Crear planilla".
		@endif
		<ul class="list-group" id="lista">
			@foreach($elementos as $planilla)
				<li class="list-group-item">
					<h5 class="list-group-item-heading uppercase">
						Planilla N° {{ $planilla['Numero'] }} elaborada el: {{ $planilla->created_at->format('d/m/Y') }}
						<small class="text-muted">
							Última modificación: {{ $planilla->updated_at->format('d/m/Y') }}
						</small>
						<a data-role="editar" data-rel="{{ $planilla['Id_Planilla'] }}" class="pull-right btn btn-primary btn-xs">
							<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						</a>
					</h5>
					<p class="list-group-item-text">
						<div class="row">
							<div class="col-xs-12">
								<small>
									<strong>{{ $planilla['Titulo'] }}</strong><br>
									{{ $planilla['Descripcion'] }}<br><br>
								</small>
							</div>
							<div class="col-xs-12">
								<small>
									<strong>Fuente</strong>:
									{{ $planilla->fuente['Codigo'].' '.$planilla->fuente['Nombre'] }} <br>
									<strong>Rubros</strong>:
									@foreach($planilla->rubros as $rubro) {{ $rubro['Codigo'].', '}}  @endforeach
									<br><br>
								</small>
							</div>
							<div class="col-xs-12">
								<a href="{{ url('planillas/'.$planilla['Id_Planilla'].'/recursos') }}" class="btn btn-default btn-xs" target="_blank">Consultar</a>
							</div>
						</div>
					</p>
				</li>
			@endforeach
		</ul>
	</div>
	<div id="paginador" class="col-xs-12">
		{!! $elementos->render() !!}
	</div>
</div>

<div class="modal fade" id="modal_main_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form action="" id="main_form">
			<div class="modal-content">
				<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    			<h4 class="modal-title" id="myModalLabel">Crear o editar una planilla.</h4>
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
		      			<div class="col-xs-12">
		      				<div class="row">
				        		<div class="col-xs-6 col-md-4 form-group">
			        				<label class="control-label" for="Numero">* Número</label>
			        				<input type="text" name="Numero" class="form-control" data-number="true">
				        		</div>
			        		</div>
		        		</div>
		        		<div class="col-xs-6 form-group">
	        				<label for="Titulo" class="control-label">Titulo</label>
	        				<input type="text" name="Titulo" class="form-control">
		        		</div>
		        		<div class="col-xs-6 form-group">
	        				<label for="Colectiva" class="control-label">Colectiva</label>
	        				<input type="text" name="Colectiva" class="form-control">
		        		</div>
		        		<div class="col-xs-12 form-group">
	        				<label for="Descripcion" class="control-label">Descripción</label>
	        				<textarea name="Descripcion" class="form-control"></textarea>
		        		</div>
        				<div class="col-xs-12 col-md-6 form-group">
    						<label for="Desde" class="control-label">* Desde</label>
    						<input type="text" name="Desde" class="form-control">
        				</div>
        				<div class="col-xs-12 col-md-6 form-group">
    						<label for="Hasta" class="control-label">* Hasta</label>
    						<input type="text" name="Hasta" class="form-control">
        				</div>
        				<div class="col-xs-12 form-group">
    						<label for="Id_Fuente" class="control-label">* Fuente</label>
							<select name="Id_Fuente" id="Id_Fuente" class="selectpicker form-control" data-live-search="true">
								@foreach($fuentes as $fuente)
			    					<option value="{{ $fuente['Id_Fuente'] }}">{{ $fuente['Codigo'].' - '.$fuente['Nombre'] }}</option>
			    				@endforeach
							</select>
        				</div>
			    		<div class="col-xs-12 form-group">
			    			<label for="Rubros" class="control-label">* Rubros</label>
			    			<select name="Rubros[]" id="Rubros" class="selectpicker form-control" data-value="" data-live-search="true" multiple>
			    				
			    			</select>
			    		</div>
			    		<div id="agregar_contratos_eliminados" class="col-xs-12 checkbox oculto">
			    			<label class="text-muted">
							    <input type="checkbox" name="agregar_contratos_eliminados" value="">
							    Agregar contratos eliminados previamente.
							</label>
			    		</div>
		      		</fieldset>
		      	</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="Id_Planilla" value="0">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		<button type="submit" class="btn btn-primary">Guardar</button>
	      		</div>
	    	</div>
		</form>
	</div>
</div>
