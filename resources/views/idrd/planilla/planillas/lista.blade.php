@section('script')
	@parent
	
	<script src="{{ asset('public/Js/planillas/planillas/planillas.js') }}"></script>
@stop

<div id="main_list" class="row" data-url="{{ url('planillas') }}">
	<div class="col-xs-12">
		<h4>{{ $titulo }}</h4>
	</div>
	<div class="col-xs-12">
		<hr>
	</div>
	@if ($_SESSION['Usuario']['Permisos']['crear_planillas'])
		<div class="col-xs-12">
			<button id="crear" class="btn btn-primary">Crear planilla</button>
		</div>
	@endif
	<div class="col-xs-12">
		<br>
	</div>
	<div class="col-xs-12">
		@if(count($elementos) == 0 && $_SESSION['Usuario']['Permisos']['crear_planillas'])
			No se ha creado ninguna planilla haga click en el boton "Crear planilla".
		@elseif(count($elementos) == 0 && $_SESSION['Usuario']['Permisos']['revisar_planillas'])
			No se han enviado planillas para revisar en el momento.
		@endif
		<ul class="list-group" id="lista">
			@foreach($elementos as $planilla)
				<li class="list-group-item">
					<h5 class="list-group-item-heading uppercase">
						Planilla N° {{ $planilla['Numero'] }} elaborada el: {{ $planilla->created_at->format('d/m/Y') }}
						<small class="text-muted">
							Última modificación: {{ $planilla->updated_at->format('d/m/Y') }}
						</small>
						<?php
							switch ($planilla->Estado) 
							{
								case '1':
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Edición" class="text-danger"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									break;
								case '2':
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Edición" class="text-danger"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Validación" class="text-warning"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									break;
								case '3':
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Edición" class="text-danger"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Validación" class="text-warning"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Aprobada" class="text-success"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									break;
								case '4':
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Edición" class="text-danger"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Validación" class="text-warning"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Aprobada" class="text-success"> <span class="glyphicon glyphicon-ok-circle"></span> </small> &nbsp;';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Bitácora asignada" class="text-success"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									break;
								case '5':
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Edición" class="text-success"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Validación" class="text-success"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Aprobada" class="text-success"> <span class="glyphicon glyphicon-ok-circle"></span> </small> &nbsp;';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Bitácora asignada" class="text-success"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									echo '<small data-toggle="tooltip" data-placement="bottom" title="Finalizada" class="text-success"> <span class="glyphicon glyphicon-ok-circle"></span> </small>';
									break;
								default:
									# code...
									break;
							}
						?>
						<a data-role="editar" data-rel="{{ $planilla['Id_Planilla'] }}" class="pull-right btn btn-primary btn-xs">
							<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						</a>
					</h5>
					<p class="list-group-item-text">
						<div class="row">
							<div class="col-xs-12">
								<small>
									<strong>{{ $planilla['Titulo'].' - '.$planilla['Colectiva'] }}</strong><br>
									{{ $planilla['Descripcion'] }}<br><br>
								</small>
							</div>
							<div class="col-xs-12">
								<small>
									<strong>Periodo</strong>: 
									{{ Carbon::createFromFormat('Y-m-d', $planilla['Desde'])->format('d/m/Y') }} - {{  Carbon::createFromFormat('Y-m-d', $planilla['Hasta'])->format('d/m/Y') }} <br>
									<strong>Fuente</strong>:
									{{ $planilla->fuente['Codigo'].' '.$planilla->fuente['Nombre'] }} <br>
									<strong>Rubros</strong>:
									@foreach($planilla->rubros as $rubro) {{ $rubro['Codigo'].', '}}  @endforeach
									<br><br>
								</small>
							</div>
							<div class="col-xs-12">
								@if ($_SESSION['Usuario']['Permisos']['editar_planillas'] || $_SESSION['Usuario']['Permisos']['revisar_planillas'])
									<a href="{{ url('planillas/'.$planilla['Id_Planilla'].'/recursos') }}" class="btn btn-default btn-xs" target="_blank">Consultar</a>	
								@endif
								@if ($_SESSION['Usuario']['Permisos']['asignar_bitacora'] && $planilla->Estado > 2)
									<a href="{{ url('planillas/'.$planilla['Id_Planilla'].'/bitacora') }}" class="btn btn-default btn-xs" target="_blank">Bitácora</a>
								@endif
								@if ($_SESSION['Usuario']['Permisos']['generar_archivo_plano'] && $planilla->Estado > 3)
									<a data-rel="{{ $planilla['Id_Planilla'] }}" href="#" data-role="export" class="btn btn-default btn-xs">Exportar</a>
								@endif
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
		        		<div class="col-xs-12 form-group">
	        				<label for="Observaciones" class="control-label">Observaciones</label>
	        				<textarea name="Observaciones" class="form-control"></textarea>
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
			    			<label>
							    <input type="checkbox" name="agregar_contratos_eliminados" value="">
							    Agregar contratos eliminados previamente.
							</label>
			    		</div>
		      		</fieldset>
		      	</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="Id_Planilla" value="0">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		@if ($_SESSION['Usuario']['Permisos']['eliminar_planillas'])
	        			<button type="button" id="eliminar" class="btn btn-danger oculto" data-rel="">Eliminar</button>  		
					@endif
	        		<button type="submit" class="btn btn-primary">Guardar</button>
	      		</div>
	    	</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modal_print_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form action="{{ url('/planillas/archivo') }}" id="print_form">
			<div class="modal-content">
				<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    			<h4 class="modal-title" id="myModalLabel">Generar archivo plano.</h4>
	  			</div>
	  			<div class="modal-body">
		      		<fieldset>
		      			<div class="col-xs-12 col-md-6 from-group">
		      				<label for="">Subdirección</label>
		      				<select name="Subdireccion" class="form-control">
		      					<option value="100,2414">Administrativa y financiera</option>
		      					<option value="300,2420">Construcciones</option>
		      					<option value="200,2418">Deportes y recreación</option>
		      					<option value="400,2419">Parques</option>
		      				</select>
		      			</div>
		      			<div class="col-xs-12 col-md-6 form-group">
		      				<label for="">Destinos de producto</label>
		      				<select name="Destino" class="form-control">
		      					<option value="111">111 - Tiempo Libre Tiempo Activo</option>
								<option value="112">112 - Construccion y Adecuacion de Parques y E</option>
								<option value="113">113 - Bogota Participativa</option>
								<option value="114">114 - Bogota Forjador de Campeones</option>
								<option value="115">115 - Parques Inclusivos</option>
								<option value="116">116 - Acciones Metropolitanas para la Conviven</option>
								<option value="117">117 - Bogota es mi Parche</option>
								<option value="118">118 - Corredores Vitales</option>
								<option value="119">119 - Parques para la Revitalizacion del Centr</option>
								<option value="120">120 - Pedalea por Bogota</option>
								<option value="121">121 - Fortalecimiento Institucional</option>
								<option value="140">140 - Probidad y Transparencia en el IDRD</option>
								<option value="134">134 - Jornada Escolar 40 Horas</option>
								<option value="174">174 - Deporte Mejor para Todos</option>
								<option value="175">175 - Rendimiento Deportivo 100 X 100</option>
								<option value="176">176 - Tiempo Escolar y Complementario</option>
								<option value="177">177 - Sostenibilidad y Mejoramiento de Parques</option>
								<option value="178">178 - Construccion y Adecuacion de Parques y E</option>
								<option value="179">179 - Recreacion Activa 35</option>
								<option value="180">180 - Fortalecimeinto de la gestion Institucio</option>
								<option value="181">181 - Modernizacion Administrativa</option>
								<option value="182">182 - Mejoramiento de las Tecnologia de la Información</option>
		      				</select>
		      			</div>
		      		</fieldset>
		      	</div>
		      	<div class="modal-footer">
		      		<input type="hidden" name="_method" value="GET">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
	      			<input type="hidden" name="Id_Planilla" value="0">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		<button type="submit" class="btn btn-primary">Exportar</button>
	      		</div>
	  		</div>
	  	</form>
	</div>
</div>
