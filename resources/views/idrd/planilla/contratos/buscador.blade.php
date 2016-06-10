@section('script')
	@parent

    <script src="{{ asset('public/Js/planillas/contratos/buscador.js') }}"></script>	
@stop

<div id="main_list" class="row" data-url="{{ url('contratos') }}">
	<div class="col-xs-12">
		<h4>Contratos</h4>
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
		<a href="{{ url('/contratistas') }}" class="btn btn-primary">Crear contrato</a>
	</div>
	<div class="col-xs-12">
		<br>
	</div>
	<div class="col-xs-12">
		@if(count($contratos) == 0)
			No se ha creado ningun contrato para crear uno vaya al modulo de contratistas y cree uno.
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
