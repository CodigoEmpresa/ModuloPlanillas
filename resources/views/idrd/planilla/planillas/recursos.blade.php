@extends('master_planillas', ['title' => $title])

@section('script')
	@parent
	
	<script src="{{ asset('public/Js/tableHeadFixer.js') }}"></script>
	<script src="{{ asset('public/Js/planillas/planillas/recursos.js') }}"></script>
@stop
@section('content')
	<div id="main_list" class="row" data-url="{{ url('planillas') }}" data-url-contratistas="{{ url('contratistas') }}">
		<div class="col-xs-12">
			<h4 class="uppercase">
				<span>Planilla N° {{ $planilla['Numero'] }} elaborada el: {{ $planilla->created_at->format('d/m/Y') }}</span>
				<small class="text-muted">
					Última modificación: {{ $planilla->updated_at->format('d/m/Y') }}
				</small>
			</h4>
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
			<p class="list-group-item-text">
				<div class="row">
					<div class="col-xs-6">
						<small>
							<strong>{{ $planilla['Titulo'] }}</strong><br>
							{{ $planilla['Descripcion'] }}<br><br>
							<strong>Fuente</strong><br>
							{{ $planilla->fuente['Codigo'].' '.$planilla->fuente['Nombre'] }} <br><br>
							<strong>Rubros</strong><br>
							@foreach($planilla->rubros as $rubro) {{ $rubro['Codigo'].', '}}  @endforeach
						</small>
					</div>
					<div class="col-xs-6" style="padding-left:24px;">
						<small>
							<strong class="uppercase">Ordenación de pago colectiva {{ $planilla['Colectiva'] }} <br> subdirección técnica de recreación y deportes.</strong> <br><br>
							<strong>Periodo de pago de cuenta</strong>
							<table class="table table-bordered table-min" style="width: 202px;">
								<thead>
									<tr>
										<th align="center">Dia</th>
										<th align="center">Mes</th>
										<th align="center">Año</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td align="center">{{ Carbon::createFromFormat('Y-m-d', $planilla->Desde)->format('d') }}</td>
										<td align="center">{{ Carbon::createFromFormat('Y-m-d', $planilla->Desde)->format('m') }}</td>
										<td align="center">{{ Carbon::createFromFormat('Y-m-d', $planilla->Desde)->format('Y') }}</td>
									</tr>
									<tr>
										<td align="center">{{ Carbon::createFromFormat('Y-m-d', $planilla->Hasta)->format('d') }}</td>
										<td align="center">{{ Carbon::createFromFormat('Y-m-d', $planilla->Hasta)->format('m') }}</td>
										<td align="center">{{ Carbon::createFromFormat('Y-m-d', $planilla->Hasta)->format('Y') }}</td>
									</tr>
								</tbody>
							</table>
						</small>
					</div>
				</div>
			</p>
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
							<th width="120">Base UVR ley 1607</th>
							<th width="120">Base UVR art 384</th>
							<th width="120">Base ICA</th>
							<th width="120">Est. pcul. 0,5%</th>
							<th width="120">Est. ppm. 0,5%</th>
							<th width="120">Total ICA 0.966%</th>
							<th width="120">Ap. u.dist. 1%</th>
							<th width="120">Retefuente</th>
							<th width="120">Otros descuentos</th>
							<th width="120">Total deducciones</th>
							<th width="120">Declarante</th>
							<th width="120">Neto a pagar</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$i=0;
							$total_pagar = 0;
							$total_pcul = 0;
							$total_ppm = 0;
							$total_general_ica = 0;
							$total_dist = 0;
							$total_retefuente = 0;
							$total_general_deducciones = 0;
							$total_neto_pagar = 0;
						?>
						@foreach($elementos as $contrato)
							<?php 
								$rowspan = count($contrato->recursos);
								$recursos = '';

								foreach ($contrato->recursos as $recurso) 
								{
									$recursos .= $recurso['Id'].',';
									$con_vc_uvt = $recurso->planillado['UVT'];
									$pago_eps = $recurso->planillado['EPS'];
									$pago_pension = $recurso->planillado['Pension'];
									$pago_arl = $recurso->planillado['ARL'];
									$medicina_prepagada = $recurso->planillado['Medicina_Prepagada'];
									$hijos = $recurso->planillado['Hijos'];
									$afc = $recurso->planillado['AFC'];
									$ingreso_base_gravado_384 = $recurso->planillado['Ingreso_Base_Gravado_384'];
									$ingreso_base_gravado_1607 = $recurso->planillado['Ingreso_Base_Gravado_1607'];
									$ingreso_base_gravado_25 = $recurso->planillado['Ingreso_Base_Gravado_25'];
									$base_uvr_ley_1607 = $recurso->planillado['Base_UVR_Ley_1607'];
									$base_uvr_art_384 = $recurso->planillado['Base_UVR_Art_384'];
									$base_ica = $recurso->planillado['Base_ICA'];
									$pcul = $recurso->planillado['PCUL'];
									$ppm = $recurso->planillado['PPM'];
									$total_ica = $recurso->planillado['Total_ICA'];
									$dist = $recurso->planillado['DIST'];
									$retefuente = $recurso->planillado['Retefuente'];
									$otros_descuentos = $recurso->planillado['Otros_Descuentos'];
									$total_deducciones = $recurso->planillado['Total_Deducciones'];
									$declarante = $recurso->planillado['Declarante'];
									$neto_pagar = $recurso->planillado['Neto_Pagar'];

									$total_pagar += $recurso['Pago_Mensual'];
								}
								
								$total_pcul += $pcul;
								$total_ppm += $ppm;
								$total_general_ica += $total_ica;
								$total_dist += $dist;
								$total_retefuente += $retefuente;
								$total_general_deducciones += $total_deducciones;
								$total_neto_pagar += $neto_pagar;
								$recursos = substr($recursos, 0, strlen($recursos)-1);
							?>
							<tr data-contrato="{{ $contrato['Id_Contrato'] }}" data-recursos="{{ $recursos }}" data-variables="{{ $contrato->contratista['Medicina_Prepagada'].','.$contrato->contratista['Hijos'].','.$contrato->contratista['AFC'].','.$contrato->contratista['Medicina_Prepagada_Cantidad'] }}">
								<td class="fixed first vcenter" rowspan="{{ $rowspan }}" align="center">{{ ++$i }}</td>
								<td class="fixed vcenter uppercase" rowspan="{{ $rowspan }}">{{ $contrato->contratista['Nombre'] }}</td>
								<td class="fixed vcenter" rowspan="{{ $rowspan }}" align="right">{{ $contrato->contratista['Cedula'] }}</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" align="right">{{ $contrato->contratista['Numero_Cta'] }}</td>
								<td class="vcenter" rowspan="{{ $rowspan }}">{{ $contrato->contratista->banco['Nombre'] }}</td>
								<td class="vcenter" rowspan="{{ $rowspan }}">{{ $contrato['Objeto'] }}</td>
								<td class="vcenter" rowspan="{{ $rowspan }}">{{ $contrato['Numero'] }}</td>
								<td class="vcenter" rowspan="{{ $rowspan }}">{{ $contrato['Fecha_Inicio'] }}</td>
								<td class="vcenter" rowspan="{{ $rowspan }}">{{ $contrato['Fecha_Terminacion'] }}</td>
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
								<td class="input" rowspan="{{ $rowspan }}">
   									<input type="text" class="important" name="dias_{{ $contrato['Id_Contrato'] }}" data-tipo="{{ $contrato['Tipo_Pago'] }}" title="@if($contrato['Tipo_Pago'] == 'Mes') Mes @elseif($contrato['Tipo_Pago'] == 'Dia') Dia @elseif($contrato['Tipo_Pago'] == 'Fecha o evento') Fecha @endif" placeholder="@if($contrato['Tipo_Pago'] == 'Mes') Mes @elseif($contrato['Tipo_Pago'] == 'Dia') Dia @elseif($contrato['Tipo_Pago'] == 'Fecha') Fecha @endif" value="{{ $contrato->recursos[0]->planillado['Dias_Trabajados'] }}" autocomplete="off">
   								</td>
								<td data-recurso="{{ $contrato->recursos[0]['Id'] }}" data-role="Total_Pagar" data-value="0" width="120" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $contrato->recursos[0]->planillado['Total_Pagar'] ? number_format($contrato->recursos[0]->planillado['Total_Pagar'], 0, '', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="UVT" align="right">
									<span data-role="value">{{ $con_vc_uvt > 0 ? $con_vc_uvt : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="EPS" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $pago_eps > 0 ? number_format($pago_eps, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Pension" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $pago_pension > 0 ? number_format($pago_pension, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="ARL" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $pago_arl > 0 ? number_format($pago_arl, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Medicina_Prepagada" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $medicina_prepagada > 0 ? number_format($medicina_prepagada, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Hijos" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $hijos > 0 ? number_format($hijos, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="input" rowspan="{{ $rowspan }}" data-role="AFC" align="right">
									<input type="text" class="important currency readonly" name="afc_{{ $contrato['Id_Contrato'] }}" value="{{ $afc }}" autocomplete="off" data-currency>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Ingreso_Base_Gravado_384" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $ingreso_base_gravado_384 > 0 ? number_format($ingreso_base_gravado_384, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Ingreso_Base_Gravado_1607" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $ingreso_base_gravado_1607 > 0 ? number_format($ingreso_base_gravado_1607, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Ingreso_Base_Gravado_25" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $ingreso_base_gravado_25 > 0 ? number_format($ingreso_base_gravado_25, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Base_UVR_Ley_1607" align="right">
									<span data-role="value">{{ $base_uvr_ley_1607 > 0 ? $base_uvr_ley_1607 : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Base_UVR_Art_384" align="right">
									<span data-role="value">{{ $base_uvr_art_384 > 0 ? $base_uvr_art_384 : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Base_ICA" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $base_ica > 0 ? number_format($base_ica, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="PCUL" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $pcul > 0 ? number_format($pcul, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="PPM" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $ppm > 0 ? number_format($ppm, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Total_ICA" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $total_ica > 0 ? number_format($total_ica, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="DIST" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $dist > 0 ? number_format($dist, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Retefuente" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $retefuente > 0 ? number_format($retefuente, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="input" rowspan="{{ $rowspan }}" data-role="Otros_Descuentos">
									<input type="text" class="important currency readonly" name="otros_descuentos_{{ $contrato['Id_Contrato'] }}" value="{{ $otros_descuentos }}" autocomplete="off" data-currency>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Total_Deducciones" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $total_deducciones > 0 ? number_format($total_deducciones, 0, '.', '.') : '--' }}</span>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Declarante" align="center">
									<input type="checkbox" name="declarante_{{ $contrato['Id_Contrato'] }}"  onclick="return false;" onkeydown="return false;" {{ $contrato->contratista['Declarante'] ? 'checked' : '' }}>
								</td>
								<td class="vcenter" rowspan="{{ $rowspan }}" data-role="Neto_Pagar" align="right">
									<span class="pull-left">$</span><span data-role="value">{{ $neto_pagar > 0 ? number_format($neto_pagar, 0, '.', '.') : '--' }}</span>
								</td>
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
						<td class="fixed"></td>
						<td class="fixed"></td>
						<td colspan="11">
						</td>
						<td>
							<strong>Subtotal</strong>
						</td>
						<td align="right" data-role="total_pagar">
							<span class="pull-left">$</span><span data-role="value">{{ $total_pagar > 0 ? number_format($total_pagar, 0, '.', '.') : '--' }}</span>
						</td>
						<td colspan="13">
							
						</td>
						<td align="right" data-role="total_pcul">
							<span class="pull-left">$</span><span data-role="value">{{ $total_pcul > 0 ? number_format($total_pcul, 0, '.', '.') : '--' }}</span>
						</td>
						<td align="right" data-role="total_ppm">
							<span class="pull-left">$</span><span data-role="value">{{ $total_ppm > 0 ? number_format($total_ppm, 0, '.', '.') : '--' }}</span>
						</td>
						<td align="right" data-role="total_general_ica">
							<span class="pull-left">$</span><span data-role="value">{{ $total_general_ica > 0 ? number_format($total_general_ica, 0, '.', '.') : '--' }}</span>
						</td>
						<td align="right" data-role="total_dist">
							<span class="pull-left">$</span><span data-role="value">{{ $total_dist > 0 ? number_format($total_dist, 0, '.', '.') : '--' }}</span>
						</td>
						<td align="right" data-role="total_retefuente">
							<span class="pull-left">$</span><span data-role="value">{{ $total_retefuente > 0 ? number_format($total_retefuente, 0, '.', '.') : '--' }}</span>
						</td>
						<td></td>
						<td align="right" data-role="total_general_deducciones">
							<span class="pull-left">$</span><span data-role="value">{{ $total_general_deducciones > 0 ? number_format($total_general_deducciones, 0, '.', '.') : '--' }}</span>
						</td>
						<td></td>
						<td align="right" data-role="total_neto_pagar">
							<span class="pull-left">$</span><span data-role="value">{{ $total_neto_pagar > 0 ? number_format($total_neto_pagar, 0, '.', '.') : '--' }}</span>
						</td>
					</tfoot>
				</table>
				<input type="hidden" name="_method" value="POST">
				<input type="hidden" name="_planilla" value="">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="Id_Planilla" value="{{ $planilla['Id_Planilla'] }}">
				<input type="hidden" name="uvt" value="{{ $config['uvt'] }}">
				<input type="hidden" name="sm" value="{{ $config['sm'] }}">
				<input type="hidden" name="tabla_1607" value="{{ json_encode($config['tabla_1607']) }}">
				<input type="hidden" name="tabla_384" value="{{ json_encode($config['tabla_384']) }}">
			</div>
			<div class="col-xs-12">
				<br>
			</div>
			<div class="col-xs-12">
				<input type="submit" class="btn btn-primary" value="Guardar">
				<a href="#" class="btn btn-default close_tab" data-title="¿Realmente desea abandonar la edición de la planilla?">Cerrar</a>
			</div>
		</form>
	</div>
@stop