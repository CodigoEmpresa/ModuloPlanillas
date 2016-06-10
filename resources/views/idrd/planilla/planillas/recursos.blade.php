@extends('master_planillas', ['title' => $title])

@section('script')
	@parent
	
	<script src="{{ asset('public/Js/tableHeadFixer.js') }}"></script>
	<script src="{{ asset('public/Js/planillas/planillas/recursos.js') }}"></script>
@stop
@section('content')
	<div id="main_list" class="row" data-url="{{ url('planillas') }}" data-url-contratistas="{{ url('contratistas') }}">
		<div class="col-xs-12">
			<h4>{{ $titulo }}</h4>
		</div>
		@if($status == 'success')
		<div id="alerta" class="col-xs-12">
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				Datos actualizados satisfactoriamente.
			</div>
		</div>
		@elseif($status == 'error')
			<div class="col-xs-12">
			    <div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<strong>Solucione los siguientes inconvenientes y vuelva a intentarlo</strong>
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			</div>
		@endif
		<div class="col-xs-12">
	    	<h5 class="list-group-item-heading">
	            Planilla N° {{ strtoupper($planilla['Numero']) }}
	        </h5>
	    	<small>
	    		<strong>Periodo: </strong> {{ $planilla['Desde'] }} hasta: {{ $planilla['Hasta'] }} <br>
				<strong>Rubro(s): </strong> @foreach($planilla->rubros as $rubro) {{ $rubro['Codigo'].' '}}  @endforeach <br>
				<strong>Descripción: </strong> {{ $planilla['Descripcion'] }}
			</small>
		</div>
		<div class="col-xs-12">
			<br>
		</div>
		<form id="formulario" action="{{ url('planillas/recursos') }}" method="post">
			<div class="col-xs-12">
				@if(count($elementos) == 0)
					No se registro ningún contrato para esta planilla.
				@endif
				<table id="recursos" class="table table-min table-bordered table-planilla">
					<thead>
						<tr>
							<th class="fixed first" width="30">N°</th>
							<th class="fixed" width="210">Nombre contratista</th>
							<th class="fixed" width="120">Número cedula</th>
							<th width="100">Número cuenta</th>
							<th width="150">Banco</th>
							<th width="250">Objeto del contrato</th>
							<th width="100">N° contrato</th>
							<th width="100">Fecha inicio</th>
							<th width="100">Fecha termino</th>
							<th width="80">Rubro</th>
							<th width="80">N° Reg</th>
							<th width="120">Valor CRP</th>
							<th width="120">Saldo CRP</th>
							<th width="120">Pago Mensual</th>
							<th width="120">Dias trabajados</th>
							<th width="120">Total a pagar</th>
							<th width="120">CON. V.C. A UVT</th>
							<th width="120">Pago EPS</th>
							<th width="120">Pago pension</th>
							<th width="120">A.R.L.</th>
							<th width="120">Med. Prepagada</th>
							<th width="120">Hijos u otros (hasta 10%)</th>
							<th width="120">A.F.C.</th>
							<th width="120">Ingreso base gravado 384</th>
							<th width="120">Ingreso base gravado 1607</th>
							<th width="120">Ingreso base gravado (-) 25%</th>
							<th width="120">Base uvr ley 1607</th>
							<th width="120">Base uvr art 384</th>
							<th width="120">Base ica</th>
							<th width="120">Est. pcul. 0,5%</th>
							<th width="120">Est. ppm. 0,5%</th>
							<th width="120">Total ica 0.966%</th>
							<th width="120">Ap. u.dist. 1%</th>
							<th width="120">Retefuente</th>
							<th width="120">Otros descuen.</th>
							<th width="120">Total deducciones</th>
							<th width="120">Declarante</th>
							<th width="120">Neto a pagar</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=0 ?>
						@foreach($elementos as $contrato)
							<?php 
								$rowspan = count($contrato->recursos);
								$recursos = '';
								$con_vc_uvt = 0;
								foreach ($contrato->recursos as $recurso) 
								{
									$recursos .= $recurso['Id'].',';
									$con_vc_uvt += $recurso->planillado['UVT'] ? $recurso->planillado['UVT'] : 0;
									$pago_eps = $recurso->planillado['EPS'];
									$pago_pension = $recurso->planillado['Pension'];
									$pago_arl = $recurso->planillado['ARL'];
									$medicina_prepagada = $recurso->planillado['Medicina_Prepagada'];
									$hijos = $recurso->planillado['Hijos'];
									$afc = $recurso->planillado['AFC'];
									$ingreso_base_gravado_1607 = $recurso->planillado['Ingreso_Base_Gravado_1607'];
								}
								
								$recursos = substr($recursos, 0, strlen($recursos)-1);
							?>
							<tr data-contrato="{{ $contrato['Id_Contrato'] }}" data-recursos="{{ $recursos }}" data-variables="{{ $contrato->contratista['Medicina_Prepagada'].','.$contrato->contratista['Hijos'].','.$contrato->contratista['AFC'].','.$contrato->contratista['Medicina_Prepagada_Cantidad'] }}">
								<td class="fixed first" rowspan="{{ $rowspan }}" align="center">{{ ++$i }}</td>
								<td class="fixed" rowspan="{{ $rowspan }}"><a href="" target="_blank">{{ $contrato->contratista['Nombre'] }}</a></td>
								<td class="fixed" rowspan="{{ $rowspan }}" align="right">{{ $contrato->contratista['Cedula'] }}</td>
								<td rowspan="{{ $rowspan }}" align="right">{{ $contrato->contratista['Numero_Cta'] }}</td>
								<td rowspan="{{ $rowspan }}">{{ $contrato->contratista->banco['Nombre'] }}</td>
								<td rowspan="{{ $rowspan }}">{{ $contrato['Objeto'] }}</td>
								<td rowspan="{{ $rowspan }}">{{ $contrato['Numero'] }}</td>
								<td rowspan="{{ $rowspan }}">{{ $contrato['Fecha_Inicio'] }}</td>
								<td rowspan="{{ $rowspan }}">{{ $contrato['Fecha_Terminacion'] }}</td>
								<td data-recurso="{{ $contrato->recursos[0]['Id'] }}" data-role="Codigo" width="80"> {{ substr($contrato->recursos[0]->rubro['Codigo'], -3) }} </td>
								<td data-recurso="{{ $contrato->recursos[0]['Id'] }}" data-role="Numero_Registro" width="80"> {{ $contrato->recursos[0]['Numero_Registro'] }} </td>
								<td data-recurso="{{ $contrato->recursos[0]['Id'] }}" data-role="Valor_CRP" data-value="{{ $contrato->recursos[0]['Valor_CRP'] }}" width="120" align="right"> 
									<span class="pull-left">$</span> {{ number_format($contrato->recursos[0]['Valor_CRP'], 0, '.', '.') }}
								</td>
								<td data-recurso="{{ $contrato->recursos[0]['Id'] }}" data-role="Saldo_CRP" data-value="{{ $contrato->recursos[0]['Saldo_CRP'] }}" width="120" align="right"> 
									<span class="pull-left">$</span> {{ number_format($contrato->recursos[0]['Saldo_CRP'], 0, '.', '.') }}
								</td>
								<td data-recurso="{{ $contrato->recursos[0]['Id'] }}" data-role="Pago_Mensual" data-value="{{ $contrato->recursos[0]['Pago_Mensual'] }}" width="120" align="right"> 
									<span class="pull-left">$</span> {{ number_format($contrato->recursos[0]['Pago_Mensual'], 0, '.', '.') }}
								</td>
								<td rowspan="{{ $rowspan }}" class="input">
   									<input type="text" class="important" name="dias_{{ $contrato['Id_Contrato'] }}" data-tipo="{{ $contrato['Tipo_Pago'] }}" title="@if($contrato['Tipo_Pago'] == 'Mes') Mes @elseif($contrato['Tipo_Pago'] == 'Dia') Dia @elseif($contrato['Tipo_Pago'] == 'Fecha o evento') Fecha @endif" placeholder="@if($contrato['Tipo_Pago'] == 'Mes') Mes @elseif($contrato['Tipo_Pago'] == 'Dia') Dia @elseif($contrato['Tipo_Pago'] == 'Fecha') Fecha @endif" value="{{ $contrato->recursos[0]->planillado['Dias_Trabajados'] }}" autocomplete="off">
   								</td>
								<td data-recurso="{{ $contrato->recursos[0]['Id'] }}" data-role="Total_Pagar" data-value="0" width="120" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $contrato->recursos[0]->planillado['Total_Pagar'] ? number_format($contrato->recursos[0]->planillado['Total_Pagar'], 0, '', '.') : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="UVT" align="right">
									<span data-role="value">{{ $con_vc_uvt > 0 ? $con_vc_uvt : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="EPS" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $pago_eps > 0 ? $pago_eps : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="Pension" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $pago_pension > 0 ? $pago_pension : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="ARL" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $pago_arl > 0 ? $pago_arl : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="Medicina_Prepagada" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $medicina_prepagada > 0 ? $medicina_prepagada : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="Hijos" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $hijos > 0 ? $hijos : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="AFC" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $afc > 0 ? $afc : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="Ingreso_Base_Gravado_1607" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $ingreso_base_gravado_1607 > 0 ? ingreso_base_gravado_1607 : '--' }}</span>
								</td>
								<td rowspan="{{ $rowspan }}" data-role="Ingreso_Base_Gravado_25"></td>
								<td rowspan="{{ $rowspan }}" data-role="Base_UVR_Ley_1607"></td>
								<td rowspan="{{ $rowspan }}" data-role="Base_UVR_Art_384"></td>
								<td rowspan="{{ $rowspan }}" data-role="Base_ICA"></td>
								<td rowspan="{{ $rowspan }}" data-role="PCUL"></td>
								<td rowspan="{{ $rowspan }}" data-role="PPM"></td>
								<td rowspan="{{ $rowspan }}" data-role="Total_ICA"></td>
								<td rowspan="{{ $rowspan }}" data-role="DIST"></td>
								<td rowspan="{{ $rowspan }}" data-role="Retefuente"></td>
								<td rowspan="{{ $rowspan }}" data-role="Otros_Descuentos"></td>
								<td rowspan="{{ $rowspan }}" data-role="Otras_Bonificaciones"></td>
								<td rowspan="{{ $rowspan }}" data-role="Total_Deducciones"></td>
								<td rowspan="{{ $rowspan }}" data-role="Declarante"></td>
								<td rowspan="{{ $rowspan }}" data-role="Neto_Pagar"></td>
							</tr>
							@if($rowspan > 1)
								@for($j=1; $j<count($contrato->recursos); $j++)
									<tr>
										<td data-recurso="{{ $contrato->recursos[$j]['Id'] }}" data-role="Codigo" width="80"> {{ substr($contrato->recursos[$j]->rubro['Codigo'], -3) }} </td>
										<td data-recurso="{{ $contrato->recursos[$j]['Id'] }}" data-role="Numero_Registro" width="80"> {{ $contrato->recursos[$j]['Numero_Registro'] }} </td>
										<td data-recurso="{{ $contrato->recursos[$j]['Id'] }}" data-role="Valor_CRP" data-value="{{ $contrato->recursos[$j]['Valor_CRP'] }}" width="120" align="right"> <span class="pull-left">$</span> {{ number_format($contrato->recursos[$j]['Valor_CRP'], 0, '.', '.') }} </td>
										<td data-recurso="{{ $contrato->recursos[$j]['Id'] }}" data-role="Saldo_CRP" data-value="{{ $contrato->recursos[$j]['Saldo_CRP'] }}" width="120" align="right"> <span class="pull-left">$</span> {{ number_format($contrato->recursos[$j]['Saldo_CRP'], 0, '.', '.') }} </td>
										<td data-recurso="{{ $contrato->recursos[$j]['Id'] }}" data-role="Pago_Mensual" data-value="{{ $contrato->recursos[$j]['Pago_Mensual'] }}" width="120" align="right"> <span class="pull-left">$</span>{{ number_format($contrato->recursos[$j]['Pago_Mensual'], 0, '.', '.') }}</td>
										<td data-recurso="{{ $contrato->recursos[$j]['Id'] }}" data-role="Total_Pagar" data-value="0" width="120" align="right"><span class="pull-left">$</span><span data-role="value">{{ $contrato->recursos[0]->planillado['Total_Pagar'] ? number_format($contrato->recursos[$j]->planillado['Total_Pagar'], 0, '', '.') : '--' }}</span></td>
									</tr>
								@endfor
							@endif
						@endforeach
					</tbody>
					<tfoot>
						<td class="fixed first"></td>
						<td></td>
						<td></td>
						<td colspan="11">
						</td>
						<td>
							<strong>Subtotal</strong>
						</td>
						<td colspan="23">
							
						</td>
					</tfoot>
				</table>
				<input type="hidden" name="_method" value="POST">
				<input type="hidden" name="_planilla" value="">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="Id_Planilla" value="{{ $planilla['Id_Planilla'] }}">
				<input type="hidden" name="uvt" value="{{ $config['uvt'] }}">
				<input type="hidden" name="sm" value="{{ $config['sm'] }}">
			</div>
			<div class="col-xs-12">
				<br>
			</div>
			<div class="col-xs-12">
				<input type="submit" class="btn btn-primary" value="Guardar">
			</div>
		</form>
	</div>
@stop