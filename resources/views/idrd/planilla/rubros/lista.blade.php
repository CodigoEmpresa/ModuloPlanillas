@section('script')
	@parent
	
	<script data-recargable="true" src="{{ asset('public/Js/planillas/rubros/rubros.js') }}"></script>
@stop
<div id="main_list" class="row" data-url="{{ url('rubros') }}">
	<div class="col-xs-12">
		<h4>{{ $titulo }}</h4>
	</div>
	<div id="alerta" class="col-xs-12" style="display:none;">
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Datos actualizados satisfactoriamente.
		</div>
	</div>
	<div class="col-xs-12">
		<hr>
	</div>
	<div class="col-xs-12">
		<button id="crear" class="btn btn-primary">Crear rubro</button>
	</div>
	<div class="col-xs-12">
		<br>	
	</div>
	<div class="col-xs-12">
		@if(count($elementos) == 0)
			0 items encontrados.
		@endif
		<ul class="list-group" id="lista">
			@foreach($elementos as $elemento)
				<li class="list-group-item">
				    <h5 class="list-group-item-heading">
				        {{ $elemento['Codigo'].' '.$elemento['Nombre'] }}
				        <a data-role="editar" data-rel="{{ $elemento['Id_Rubro'] }}" data-contratos="{{ count($elemento->recursos) }}"  class="pull-right btn btn-primary btn-xs">
	                    	<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
	                    </a>
				    </h5>
				    <p class="list-group-item-text">
				    	<div class="row">
                            <div class="col-xs-12">
                            	<small>
                            		<strong>Total contratos:</strong> {{ count($elemento->recursos) }} <br>
                            	</small>
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
	    			<h4 class="modal-title" id="myModalLabel">Crear, editar o eliminar una rubro.</h4>
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
		        		<div class="col-xs-12 col-md-4">
		        			<div class="form-group">
		        				<label class="control-label" for="Codigo">* Codigo </label>
		        				<input type="text" name="Codigo" class="form-control">
		        			</div>
		        		</div>
		        		<div class="col-xs-12 col-md-8">
		        			<div class="form-group">
		        				<label class="control-label" for="Nombre">* Nombre</label>
		        				<input type="text" name="Nombre" class="form-control">
		        			</div>
		        		</div>
		        		<div id="mensaje_no_eliminar" class="col-xs-12 oculto">
		        			<small class="text-info">No se puede eliminar un rubro con contratos asignados.</small>
		        		</div>
		      		</fieldset>
		      	</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="Id_Rubro" value="0">
	        		<button type="submit" class="btn btn-primary">Guardar</button>
	        		<button type="button" id="eliminar" class="btn btn-danger oculto" data-rel="">Eliminar</button>
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      		</div>
	    	</div>
		</form>
	</div>
</div>