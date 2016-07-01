@section('script')
	@parent
	
	<script data-recargable="true" src="{{ asset('public/Js/planillas/contratos/contratos.js') }}"></script>
@stop
<div id="main_list" class="row" data-url="{{ url('contratos') }}">
	<div class="col-xs-12">
		<h4>Crear/Editar contrato</h4>
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
	    <form id="form-contrato" action="{{ url('contratos') }}" method="post">
	    	<div class="row">
		    	<div class="col-xs-12">
		    		<span class="label-form-section">Datos del contrato: <br></span>
		    	</div>
	    	</div>
			<div class="row">
			    <div class="col-xs-12 col-md-3 form-group {{ $errors->has('Numero') ? 'has-error' : '' }}">
			    	<label for="Numero">* Número de contrato</label>
			    	<input type="text" name="Numero" value="{{ $contrato ? $contrato['Numero'] : old('Numero') }}" class="form-control">
			    </div>
			    <div class="col-xs-12 col-md-3 form-group {{ $errors->has('Fecha_Inicio') ? 'has-error' : '' }}">
			    	<label for="Fecha_Inicio">* Fecha de inicio</label>
			    	<input type="text" name="Fecha_Inicio" value="{{ $contrato ? $contrato['Fecha_Inicio'] : old('Fecha_Inicio') }}" class="form-control">
			    </div>
			    <div class="col-xs-12 col-md-3 form-group {{ $errors->has('Fecha_Terminacion') ? 'has-error' : '' }}">
			    	<label for="Fecha_Terminacion">* Fecha de terminación</label>
			    	<input type="text" name="Fecha_Terminacion" value="{{ $contrato ? $contrato['Fecha_Terminacion'] : old('Fecha_Terminacion') }}" class="form-control">
			    </div>
			    @if ($contrato && $contrato['Tipo_Modificacion'])
				    <div class="col-xs-12 col-md-3 form-group">
				    	<label>Fecha <small>({{ 'concepto: '.$contrato['Tipo_Modificacion'] }})</small></label>
				    	<p class="form-control-static">{{ $contrato['Fecha_Terminacion_Modificada'] }}</p>
				    </div>
			    @endif
			</div>
			<div class="row">
				<div class="col-xs-12 form-group">
					<label for="Objeto">Objeto</label>
					<textarea name="Objeto" class="form-control" cols="30" rows="10">{{ $contrato ? $contrato['Objeto'] : old('Objeto') }}</textarea>
				</div>
				<div class="col-md-3 form-group {{ $errors->has('Total_Contrato') ? 'has-error' : '' }}">
					<label for="Total_Contrato">* Total contrato</label>
					<input type="text" name="Total_Contrato" value="{{ $contrato ? $contrato['Total_Contrato'] : old('Total_Contrato') }}" class="form-control" placeholder="$" data-currency>
				</div>
				<div class="col-md-3 form-group">
					<label for="Total_Ejecutado"> Total ejecutado</label>
					<input type="text" name="Total_Ejecutado" value="{{ $contrato ? $contrato->saldos()->sum('Total_Pagado') : 0 }}" class="form-control" placeholder="$" data-currency readonly>
				</div>
				<div class="col-md-3 form-group {{ $errors->has('Tipo_Pago') ? 'has-error' : '' }}">
					<label for="Tipo_Pago">* Tipo de pago</label>
					<select name="Tipo_Pago" id="" data-value="{{ $contrato ? $contrato['Tipo_Pago'] : old('Tipo_Pago') }}" class="form-control">
						<option value="">Seleccionar</option>
						<option value="Dia">Dia</option>
						<option value="Fecha">Fecha</option>
						<option value="Mes">Mes</option>
					</select>
				</div>
			</div>
			<div class="row">
		    	<div class="col-xs-12">
		    		<span class="label-form-section">Rubros, fuentes y componentes: <br></span>
		    	</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-xs-12">
	    			<input type="button" class="btn btn-default btn-sm" id="agregar_rubro" value="Agregar">
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-xs-12">
	    			<br>
	    		</div>
	    		<div class="col-xs-12">
	    			<?php 
    					$temp = $recursos && $status != 'error' ? $recursos : json_decode(old('_recursos'));
    					$recursos_contrato = [];

    					if ($recursos && $status != 'error')
    					{
    						foreach ($temp as $r) 
    						{
    							$saldos = 0;

    							if($r->saldos)
    								$saldos = $r->saldos()->sum('Total_Pagado');
    							
    							$r_temp = [];
    							$r_temp['Id'] = $r['Id'];
    							$r_temp['Numero_Registro'] = $r['Numero_Registro'];
    							$r_temp['Valor_CRP'] = $r['Valor_CRP'];
    							$r_temp['Saldo_CRP'] = $r['Saldo_CRP'];
    							$r_temp['Saldo_Acumulado'] = $saldos;
    							$r_temp['Expresion'] = $r['Expresion'];
    							$r_temp['Pago_Mensual'] = $r['Pago_Mensual'];
    							$r_temp['Fuente'] = [
									'Id_Fuente' => $r->fuente['Id_Fuente'],
									'Nombre' => $r->fuente['Codigo'].' - '.$r->fuente['Nombre']
								];
								$r_temp['Rubro'] = [
									'Id_Rubro' => $r->rubro['Id_Rubro'],
									'Nombre' => $r->rubro['Codigo'].' - '.$r->rubro['Nombre']
								];
								$r_temp['Componente'] = [
									'Id_Componente' => $r->componente['Id_Componente'],
									'Nombre' => $r->componente['Codigo'].' - '.$r->componente['Nombre']
								];
								array_push($recursos_contrato, $r_temp);
    						}
    					} else if($temp) {
    						foreach ($temp as $r) 
    						{
    							$r_temp = [];
    							$r_temp['Id'] = $r->Id;
    							$r_temp['Numero_Registro'] = $r->Numero_Registro;
    							$r_temp['Valor_CRP'] = $r->Valor_CRP;
    							$r_temp['Saldo_CRP'] = $r->Saldo_CRP;
    							$r_temp['Saldo_Acumulado'] = 0;
    							$r_temp['Expresion'] = $r->Expresion;
    							$r_temp['Pago_Mensual'] = $r->Pago_Mensual;
    							$r_temp['Fuente'] = [
									'Id_Fuente' => $r->Fuente->id,
									'Nombre' => $r->Fuente->valor
								];
								$r_temp['Rubro'] = [
									'Id_Rubro' => $r->Rubro->id,
									'Nombre' => $r->Rubro->valor
								];
								$r_temp['Componente'] = [
									'Id_Componente' => $r->Componente->id,
									'Nombre' => $r->Componente->valor,
								];
								array_push($recursos_contrato, $r_temp);
    						}
    					}
    					$i = 0;
    				?>
		    		<table id="rubros" class="table table-hover table-bordered table-min table-select" data-total="{{ count($recursos_contrato) }}">
		    			<thead>
		    				<tr>
		    					<th width="14%">Registro</th>
		    					<th width="14%">Fuente</th>
		    					<th width="14%">Rubro</th>
		    					<th width="14%">Componente</th>
		    					<th width="14%">Valor CRP</th>
		    					<th width="14%">Saldo CRP</th>
		    					<th width="14%">Pago mensual</th>
		    				</tr>
		    			</thead>
		    			<tbody>
		    				@if(is_array($recursos_contrato))
		    					<?php $total_valor_crp = $total_saldo_crp = $total_pago_mensual = 0 ?>
		    					@foreach($recursos_contrato as $recurso)
		    						<?php 
		    							$total_valor_crp += $recurso['Valor_CRP'];
		    							$total_saldo_crp += $recurso['Saldo_CRP'];
		    							$total_pago_mensual += $recurso['Pago_Mensual'];
		    							$i++; 
		    						?>
		    						<tr data-temp-id="{{ $recurso['Id'] }}" data-expresion="{{ $recurso['Expresion'] }}" data-unique="{{ $i }}">
										<td data-rel="Numero_Registro" data-val="{{ $recurso['Numero_Registro'] }}">{{ $recurso['Numero_Registro'] }}</td>
										<td data-rel="Id_Fuente" data-val="{{ $recurso['Fuente']['Id_Fuente'] }}">{{ $recurso['Fuente']['Nombre'] }}</td>
										<td data-rel="Id_Rubro" data-val="{{ $recurso['Rubro']['Id_Rubro'] }}">{{ $recurso['Rubro']['Nombre'] }}</td>
										<td data-rel="Id_Componente" data-val="{{ $recurso['Componente']['Id_Componente'] }}">{{ $recurso['Componente']['Nombre'] }}</td>
										<td data-rel="Valor_CRP" data-val="{{ $recurso['Valor_CRP'] }}" align="right"><span class="pull-left">$</span>{{ number_format($recurso['Valor_CRP'], 0, '', '.') }}</td>
										<td data-rel="Saldo_CRP" data-val="{{ $recurso['Saldo_CRP'] }}" data-acumulado={{ $recurso['Saldo_Acumulado'] }} align="right"><span class="pull-left">$</span>{{ number_format($recurso['Saldo_CRP'] - $recurso['Saldo_Acumulado'], 0, '', '.') }}</td>
										<td data-rel="Pago_Mensual" data-val="{{ $recurso['Pago_Mensual'] }}" align="right"><span class="pull-left">$</span>{{ number_format($recurso['Pago_Mensual'], 0, '', '.') }}</td>
									</tr>
		    					@endforeach
		    				@endif
		    			</tbody>
		    			<tfoot>
		    				<tr>
		    					<td colspan="4" align="right">
		    						<strong>Total</strong>
		    					</td>
		    					<td data-rel="total_valor_crp" align="right">
		    						<span class="pull-left">$</span>
		    						<span data-role="value"></span>
		    					</td>
		    					<td data-rel="total_saldo_crp" align="right">
		    						<span class="pull-left">$</span>
		    						<span data-role="value"></span>
		    					</td>
		    					<td data-rel="total_pago_mensual" align="right">
		    						<span class="pull-left">$</span>
		    						<span data-role="value"></span>
		    					</td>
		    				</tr>
		    			</tfoot>
		    		</table>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-xs-12">
	    			<hr>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-xs-6">
			    	<div id="suspender-contrato" style="{{ $contrato ? '' : 'display: none' }}">
			    		<div class="row">
					    	<div class="col-xs-12">
					    		<span class="label-form-section">Suspender contrato: <br></span>
					    	</div>
					    	<div class="col-xs-12 col-md-2">
					    		<input type="button" class="btn btn-default btn-sm" value="Agregar" id="agregar_suspencion">
					    	</div>
					    	<div class="col-xs-12">
					    		<br>
					    	</div>
					    	<?php 
		    					$temp = $contrato && $status != 'error' && $contrato->suspenciones ? $contrato->suspenciones : json_decode(old('_suspenciones'));
		    					$suspenciones_contrato = [];
		    					if ($temp && $status != 'error')
		    					{
		    						foreach ($temp as $r) 
		    						{
		    							$r_temp = [];
		    							$r_temp['Id'] = $r['Id'];
		    							$r_temp['Fecha_Inicio'] = $r['Fecha_Inicio'];
		    							$r_temp['Fecha_Terminacion'] = $r['Fecha_Terminacion'];
										array_push($suspenciones_contrato, $r_temp);
		    						}
		    					} else if($temp) {
		    						foreach ($temp as $r) 
		    						{
		    							$r_temp = [];
		    							$r_temp['Id'] = $r->Id;
		    							$r_temp['Fecha_Inicio'] = $r->Fecha_Inicio;
		    							$r_temp['Fecha_Terminacion'] = $r->Fecha_Terminacion;
										array_push($suspenciones_contrato, $r_temp);
		    						}
		    					}
		    					$i = 0;
		    				?>
					    	<div class="col-xs-12">
					    		<table id="suspenciones" class="table table-hover table-bordered table-min table-select" data-total="{{ $contrato ? count($contrato->suspenciones) : 0 }}">
					    			<thead>
					    				<tr>
					    					<th width="10%" align="center">N°</th>
					    					<th width="45%">Inicio</th>
					    					<th width="45%">Fin</th>
					    				</tr>
					    			</thead>
					    			<tbody>
					    				@if(is_array($suspenciones_contrato))
					    					@foreach($suspenciones_contrato as $suspencion)
					    						<?php $i++ ?>
					    						<tr data-temp-id="{{ $suspencion['Id'] }}" data-unique="{{ $i }}">
					    							<td align="center">{{ $i }}</td>
					    							<td data-rel="Fecha_Inicio" data-val="{{ $suspencion['Fecha_Inicio'] }}">{{ $suspencion['Fecha_Inicio'] }}</td>
					    							<td data-rel="Fecha_Terminacion" data-val="{{ $suspencion['Fecha_Terminacion'] }}">{{ $suspencion['Fecha_Terminacion'] }}</td>
					    						</tr>
					    					@endforeach
					    				@endif
					    			</tbody>
					    		</table>
					    	</div>
				    	</div>
			    	</div>
		    	</div>
				<div class="col-xs-6">
			    	<div id="finalizar-contrato" style="{{ $contrato ? '' : 'display: none' }}">
						<div class="row">
					    	<div class="col-xs-12">
					    		<span class="label-form-section label-form-section-alert"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Terminar contrato: <br></span>
					    	</div>
				    	</div>
				    	<div class="row">
				    		<div class="col-xs-12">
				    			<div class="checkbox">
				    				<label for="finalizar">
				    					<input type="hidden" name="finalizar" value="0">
				    					<input type="checkbox" id="finalizar" name="finalizar" value="1" {{ $contrato && ($contrato['Tipo_Modificacion']  == 'terminado' || old('finalizar') == '1') ? 'checked' : '' }}> Terminar contrato
				    				</label>
				    			</div>
				    		</div>
				    		<div class="col-xs-6 form-group {{ $errors->has('Terminado_Por') ? 'has-error' : '' }}">
				    			<label for="Terminado_Por">Finalizado por: </label>
				    			<select name="Terminado_Por" id="Terminado_Por" class="form-control" data-value="{{ $contrato && $contrato['Tipo_Modificacion']  == 'terminado' ? $contrato['Terminado_Por'] : old('Terminado_Por') }}">
				    				<option value="">Seleccionar</option>
				    				<option value="contratista">Contratista</option>
									<option value="entidad">Entidad</option>
									<option value="mutuo">Mutuo</option>
				    			</select>
				    		</div>
				    		<div class="col-xs-6 form-group {{ $errors->has('Fecha_Terminacion_Modificada') ? 'has-error' : '' }}">
				    			<label for="Fecha_Terminacion_Modificada">Nueva fecha terminación</label>
				    			<input type="text" class="form-control" name="Fecha_Terminacion_Modificada" data-role="datepicker" value="{{ $contrato && $contrato['Tipo_Modificacion']  == 'terminado' ? $contrato['Fecha_Terminacion_Modificada'] : old('Fecha_Terminacion_Modificada') }}">
				    		</div>
				    		<div class="col-xs-6 form-group">
				    			<label for="Saldo_A_Favor">Saldo a favor (dias ó fechas)</label>
				    			<input type="text" name="Saldo_A_Favor" class="form-control" value="{{ $contrato ? $contrato['Saldo_A_Favor'] : old('Saldo_A_Favor') }}" data-number>
				    		</div>
				    	</div>
				    </div>
			    </div>
		    </div>
	    	<div class="row">
	    		<div class="col-xs-12">
	    			<hr>
	    		</div>
	    	</div>
			<div class="row">
				<div class="col-xs-12">
					<input type="hidden" name="_method" value="{{ $contrato ? 'PUT' : 'POST' }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="_recursos" value="{{ old('_recursos') }}">
					<input type="hidden" name="_suspenciones" value="{{ old('_suspenciones') }}">
					<input type="hidden" name="Id_Contrato" value="{{ $contrato ? $contrato['Id_Contrato'] : 0 }}">
					<input type="hidden" name="Id_Contratista" value="{{ $contratista['Id_Contratista'] }}">
					@if(!$contrato || ($contrato && $contrato->Estado != 'finalizado'))
						<input type="submit" class="btn btn-primary" value="Guardar">
						@if($contrato)
							<a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_eliminar_contrato">Eliminar</a>
						@endif
					@endif
					<a href="{{ Session::has('back') ? Session::get('back') : url('contratistas/'.$contratista['Id_Contratista'].'/contratos') }}" class="btn btn-default">Cerrar</a>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<br><br>
				</div>
			</div>
	    </form>
    </div>
</div>

@if($contrato)
	<div class="modal fade" id="modal_eliminar_contrato" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    			<h4 class="modal-title" id="myModalLabel">Eliminar contrato.</h4>
	  			</div>
	      		<div class="modal-body">
		    		Realmente desea eliminar el contrato N° {{ $contrato['Numero'] }}
	      		</div>
	      		<div class="modal-footer">
	        		<a href="{{ url('contratos/'.$contrato['Id_Contrato'].'/eliminar') }}" class="btn btn-danger">Eliminar</a>
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
@endif

<div class="modal fade" id="modal_form_rubro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form action="" id="form_persona">
			<div class="modal-content">
				<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    			<h4 class="modal-title" id="myModalLabel">Agregar o editar.</h4>
	  			</div>
	      		<div class="modal-body">
		      		<fieldset>
		      			<div class="errores col-xs-12" style="display: none;">
		      				<div class="alert alert-danger" role="alert">
		      					<strong>Solucione los siguientes inconvenientes:</strong>
		      					<ul>
		      						
		      					</ul>
		      				</div>
		      			</div>
			    		<div class="col-xs-12 col-md-4 form-group">
			    			<label for="Numero_Registro">Número de registro</label>
			    			<input type="text" class="form-control" value="" name="Numero_Registro">
			    		</div>
			    		<div class="col-xs-12 form-group">
			    			<label for="Fuente">Fuente</label>
			    			<select name="Fuente" id="Fuente" class="selectpicker form-control" data-live-search="true">
			    				@foreach($fuentes as $fuente)
			    					<option value="{{ $fuente['Id_Fuente'] }}">{{ $fuente['Codigo'].' - '.$fuente['Nombre'] }}</option>
			    				@endforeach
			    			</select>
			    		</div>
			    		<div class="col-xs-12 form-group">
			    			<label for="Rubro">Rubro</label>
			    			<select name="Rubro" id="Rubro" class="selectpicker form-control" data-live-search="true">
			    				@foreach($rubros as $rubro)
			    					<option value="{{ $rubro['Id_Rubro'] }}">{{ $rubro['Codigo'].' - '.$rubro['Nombre'] }}</option>
			    				@endforeach
			    			</select>
			    		</div>
			    		<div class="col-xs-12 form-group">
			    			<label for="Componente">Componente</label>
			    			<select name="Componente" id="Componente" class="selectpicker form-control" data-live-search="true">
			    				@foreach($componentes as $componente)
			    					<option value="{{ $componente['Id_Componente'] }}">{{ $componente['Codigo'].' - '.$componente['Nombre'] }}</option>
			    				@endforeach
			    			</select>
			    		</div>
			    		<div class="col-xs-12 col-md-6 form-group">
			    			<label for="Valor_CRP">Valor CRP</label>
			    			<input type="text" class="form-control" value="" name="Valor_CRP" placeholder="$" data-number data-currency>
			    		</div>
			    		<div class="col-xs-12 col-md-6 form-group">
			    			<label for="Expresion">Expresión (ej crp/12)</label>
			    			<input type="text" class="form-control" value="" id="Expresion" name="Expresion" placeholder="=">
			    		</div>
			    		<div class="col-xs-12 col-md-6 form-group">
			    			<label for="Saldo_CRP">Saldo CRP</label>
			    			<input type="text" class="form-control" value="" name="Saldo_CRP" placeholder="$" data-number data-currency>
			    		</div>
			    		<div class="col-xs-12 col-md-6 form-group">
			    			<label for="Saldo_Calculado">Saldo calculado:</label>
			    			<br>
			    			<p class="form-control-static" data-role="Saldo_Calculado"><span>$</span><span data-role="value"></span></p>
			    		</div>
			    		<div class="col-xs-12 col-md-6 form-group">
			    			<label for="Pago_Mensual">Pago mensual</label>
			    			<input type="text" class="form-control" value="" name="Pago_Mensual" readonly="" placeholder="$" data-currency>
			    		</div>
		        	</fieldset>
	      		</div>
	      		<div class="modal-footer">
	      			<input type="hidden" name="Id" value="0">
	      			<input type="hidden" name="Unique" value="">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		<button type="button" id="eliminar_rubro" class="btn btn-danger oculto">Eliminar</button>
	        		<button type="button" id="guardar_rubro" class="btn btn-primary">Guardar</button>
	      		</div>
	    	</div>
    	</form>
  	</div>
</div>

<div class="modal fade" id="modal_form_suspencion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form action="" id="form_suspencion">
			<div class="modal-content">
				<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	    			<h4 class="modal-title" id="myModalLabel">Agregar suspención.</h4>
	  			</div>
	      		<div class="modal-body">
	      			<fieldset>
	      				<div class="errores col-xs-12" style="display: none;">
		      				<div class="alert alert-danger" role="alert">
		      					<strong>Solucione los siguientes inconvenientes:</strong>
		      					<ul>
		      						
		      					</ul>
		      				</div>
		      			</div>
	      				<div class="col-xs-12 col-md-6 form-group">
	      					<label for="Fecha_Inicio">Inicio</label>
	      					<input type="text" class="form-control" name="Fecha_Inicio_Suspencion">
	      				</div>
	      				<div class="col-xs-12 col-md-6 form-group">
	      					<label for="Fecha_Fin">Fin</label>
	      					<input type="text" class="form-control" name="Fecha_Fin_Suspencion">
	      				</div>
	      			</fieldset>
	  			</div>
	  			<div class="modal-footer">
	      			<input type="hidden" name="Id" value="0">
	      			<input type="hidden" name="Unique" value="">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		<button type="button" id="eliminar_suspencion" class="btn btn-danger oculto">Eliminar</button>
					<button type="button" id="guardar_suspencion" class="btn btn-primary">Guardar</button>
	      		</div>
	  		</div>
		</form>
	</div>
</div>