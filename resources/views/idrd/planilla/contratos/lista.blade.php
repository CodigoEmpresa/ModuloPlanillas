<div id="main_list" class="row" data-url="{{ url('contratos') }}">
	<div class="col-xs-12">
		<h4>Lista de contratos</h4>
	</div>
	<div id="alerta" class="col-xs-12" style="display:none;">
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			Datos actualizados satisfactoriamente.
		</div>
	</div>
	<div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
            	<h5 class="list-group-item-heading">
	                {{ strtoupper($contratista['Nombre']) }}
	            </h5>
            	<small>Identificación: {{ $contratista->tipoDocumento['Nombre_TipoDocumento'].' '.$contratista['Cedula'] }}</small>
            </div>
            <div class="col-xs-12">
            	<hr>
            </div>
        </div>
	</div>
	<div class="col-xs-12">
		<a href="{{ url('contratos/'.$contratista['Id_Contratista'].'/crear') }}" class="btn btn-primary">Crear contrato</a>
		<a href="{{ url('contratistas') }}" class="btn btn-default">Volver</a>
	</div>
	<div class="col-xs-12">
		<br>
    </div>
	<div class="col-xs-12">
		@if(count($contratos) == 0)
			No se ha registrado ningún contrato hasta el momento, haga click en <strong>Crear contrato</strong> para crear uno.
		@endif
		<ul class="list-group" id="lista">
			@foreach($contratos as $contrato)
				<li class="list-group-item">
	                <h5 class="list-group-item-heading">
	                    Contrato N° {{ $contrato['Numero'] }}
	                    <a href="{{ url('contratos/'.$contrato['Id_Contratista'].'/editar/'.$contrato['Id_Contrato']) }}" class="pull-right btn btn-primary btn-xs">
	                    	<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
	                    </a>
	                </h5>
	                <p class="list-group-item-text">
                        <div class="row">
                            <div class="col-xs-12">
                            	<small>
                            		<strong>Objeto:</strong> {{ $contrato['Objeto'] }} <br>
                            		<strong>Duración:</strong> {{ $contrato['Fecha_Inicio'] }} hasta {{ $contrato['Fecha_Terminacion'] }}
                            	</small>
                            </div>
                        </div>
	                </p>
	            </li>
			@endforeach
		</ul>
	</div>
	<div id="paginador" class="col-xs-12">
		{!! $contratos->render() !!}
	</div>
</div>