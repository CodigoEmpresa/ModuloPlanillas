$(function(){

	var to_sync = [];
	var SM = $('input[name="sm"]').val();
	var UVT = $('input[name="uvt"]').val();
	var TABLA_1607 =  $.makeArray($.parseJSON($('input[name="tabla_1607"]').val()));
	var TABLA_384 =  $.makeArray($.parseJSON($('input[name="tabla_384"]').val()));
	var BASE = SM * 100 / 40;
	var URL = $('#main_list').data('url');
	var URL_CONTRATOs = $('#main_list').data('url-contratos');

	var actualizar_resumenes = function()
	{
		var temp = $('#rubros').text();
		var rubros = temp.split(',');
		var total = 0;

		//total rubros
		$.each(rubros, function(i, e)
		{
			var sub_total = 0;
			$('#recursos tbody td[data-role="Total_Pagar"][data-rubro="'+e+'"]').each(function(ir, er)
			{
				sub_total += parseInt($(er).attr('data-value') == '' ? 0 : $(er).attr('data-value'));
			});

			total += sub_total;
			$('#resumen_1 tr[data-rubro="'+e+'"] span[data-role="value"]').text(sub_total == 0 ? '--' : accounting.formatNumber(sub_total, 0, '.'));
		});

		$('#resumen_1 span[data-role="total_rubros"]').text(total == 0 ? '--' : accounting.formatNumber(total, 0, '.'));
	}

	var actualizar_totales = function()
	{
		var total_pagar = 0;
		var total_pcul = 0;
		var total_ppm = 0;
		var total_general_ica = 0;
		var total_dist = 0;
		var total_retefuente = 0;
		var total_general_deducciones = 0;
		var total_neto_pagar = 0;
		var total_descuentos = 0;

		var totales = {
			'otros_bancos': {
				'total_pagar': 0,
				'total_pcul': 0,
				'total_ppm': 0,
				'total_general_ica': 0,
				'total_dist': 0,
				'total_retefuente': 0,
				'total_general_deducciones': 0,
				'total_neto_pagar': 0,
				'total_descuentos': 0
			},
			'cheques': {
				'total_pagar': 0,
				'total_pcul': 0,
				'total_ppm': 0,
				'total_general_ica': 0,
				'total_dist': 0,
				'total_retefuente': 0,
				'total_general_deducciones': 0,
				'total_neto_pagar': 0,
				'total_descuentos': 0
			},
			'davivienda': {
				'total_pagar': 0,
				'total_pcul': 0,
				'total_ppm': 0,
				'total_general_ica': 0,
				'total_dist': 0,
				'total_retefuente': 0,
				'total_general_deducciones': 0,
				'total_neto_pagar': 0,
				'total_descuentos': 0
			}
		};

		$.each(to_sync, function(i, e)
		{
			if(e.Banco == '51')
			{
				totales.davivienda.total_pagar += e.Total_Pagar;
				totales.davivienda.total_pcul += e.PCUL;
				totales.davivienda.total_ppm += e.PPM;
				totales.davivienda.total_general_ica += e.Total_ICA;
				totales.davivienda.total_dist += e.DIST;
				totales.davivienda.total_retefuente += e.Retefuente;
				totales.davivienda.total_general_deducciones += e.Total_Deducciones;
				totales.davivienda.total_neto_pagar += e.Neto_Pagar;
			} else if(e.Banco == '00') {
				totales.cheques.total_pagar += e.Total_Pagar;
				totales.cheques.total_pcul += e.PCUL;
				totales.cheques.total_ppm += e.PPM;
				totales.cheques.total_general_ica += e.Total_ICA;
				totales.cheques.total_dist += e.DIST;
				totales.cheques.total_retefuente += e.Retefuente;
				totales.cheques.total_general_deducciones += e.Total_Deducciones;
				totales.cheques.total_neto_pagar += e.Neto_Pagar;
			} else {
				totales.otros_bancos.total_pagar += e.Total_Pagar;
				totales.otros_bancos.total_pcul += e.PCUL;
				totales.otros_bancos.total_ppm += e.PPM;
				totales.otros_bancos.total_general_ica += e.Total_ICA;
				totales.otros_bancos.total_dist += e.DIST;
				totales.otros_bancos.total_retefuente += e.Retefuente;
				totales.otros_bancos.total_general_deducciones += e.Total_Deducciones;
				totales.otros_bancos.total_neto_pagar += e.Neto_Pagar;
			}
		});

		total_pagar = totales.davivienda.total_pagar + totales.cheques.total_pagar + totales.otros_bancos.total_pagar;
		total_pcul = totales.davivienda.total_pcul + totales.cheques.total_pcul + totales.otros_bancos.total_pcul;
		total_ppm = totales.davivienda.total_ppm + totales.cheques.total_ppm + totales.otros_bancos.total_ppm;
		total_general_ica = totales.davivienda.total_general_ica + totales.cheques.total_general_ica + totales.otros_bancos.total_general_ica;
		total_dist = totales.davivienda.total_dist + totales.cheques.total_dist + totales.otros_bancos.total_dist;
		total_retefuente = totales.davivienda.total_retefuente + totales.cheques.total_retefuente + totales.otros_bancos.total_retefuente;
		total_general_deducciones = totales.davivienda.total_general_deducciones + totales.cheques.total_general_deducciones + totales.otros_bancos.total_general_deducciones;
		total_neto_pagar = totales.davivienda.total_neto_pagar + totales.cheques.total_neto_pagar + totales.otros_bancos.total_neto_pagar;

		total_descuentos = total_pcul + total_ppm + total_general_ica + total_dist + total_retefuente + total_general_deducciones;

		$('td[data-role="otr_total_pagar"] span[data-role="value"]').text(totales.otros_bancos.total_pagar == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_pagar, 0, '.'));
		$('td[data-role="otr_total_pcul"] span[data-role="value"]').text(totales.otros_bancos.total_pcul == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_pcul, 0, '.'));
		$('td[data-role="otr_total_ppm"] span[data-role="value"]').text(totales.otros_bancos.total_ppm == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_ppm, 0, '.'));
		$('td[data-role="otr_total_general_ica"] span[data-role="value"]').text(totales.otros_bancos.total_general_ica == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_general_ica, 0, '.'));
		$('td[data-role="otr_total_dist"] span[data-role="value"]').text(totales.otros_bancos.total_dist == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_dist, 0, '.'));
		$('td[data-role="otr_total_retefuente"] span[data-role="value"]').text(totales.otros_bancos.total_retefuente == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_retefuente, 0, '.'));
		$('td[data-role="otr_total_general_deducciones"] span[data-role="value"]').text(totales.otros_bancos.total_general_deducciones == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_general_deducciones, 0, '.'));
		$('td[data-role="otr_total_neto_pagar"] span[data-role="value"]').text(totales.otros_bancos.total_neto_pagar == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_neto_pagar, 0, '.'));
		$('td[data-role="otr_total_descuentos"] span[data-role="value"]').text(totales.otros_bancos.total_descuentos == 0 ? '--' : accounting.formatNumber(totales.otros_bancos.total_descuentos, 0, '.'));

		$('td[data-role="che_total_pagar"] span[data-role="value"]').text(totales.cheques.total_pagar == 0 ? '--' : accounting.formatNumber(totales.cheques.total_pagar, 0, '.'));
		$('td[data-role="che_total_pcul"] span[data-role="value"]').text(totales.cheques.total_pcul == 0 ? '--' : accounting.formatNumber(totales.cheques.total_pcul, 0, '.'));
		$('td[data-role="che_total_ppm"] span[data-role="value"]').text(totales.cheques.total_ppm == 0 ? '--' : accounting.formatNumber(totales.cheques.total_ppm, 0, '.'));
		$('td[data-role="che_total_general_ica"] span[data-role="value"]').text(totales.cheques.total_general_ica == 0 ? '--' : accounting.formatNumber(totales.cheques.total_general_ica, 0, '.'));
		$('td[data-role="che_total_dist"] span[data-role="value"]').text(totales.cheques.total_dist == 0 ? '--' : accounting.formatNumber(totales.cheques.total_dist, 0, '.'));
		$('td[data-role="che_total_retefuente"] span[data-role="value"]').text(totales.cheques.total_retefuente == 0 ? '--' : accounting.formatNumber(totales.cheques.total_retefuente, 0, '.'));
		$('td[data-role="che_total_general_deducciones"] span[data-role="value"]').text(totales.cheques.total_general_deducciones == 0 ? '--' : accounting.formatNumber(totales.cheques.total_general_deducciones, 0, '.'));
		$('td[data-role="che_total_neto_pagar"] span[data-role="value"]').text(totales.cheques.total_neto_pagar == 0 ? '--' : accounting.formatNumber(totales.cheques.total_neto_pagar, 0, '.'));
		$('td[data-role="che_total_descuentos"] span[data-role="value"]').text(totales.cheques.total_descuentos == 0 ? '--' : accounting.formatNumber(totales.cheques.total_descuentos, 0, '.'));

		$('td[data-role="dav_total_pagar"] span[data-role="value"]').text(totales.davivienda.total_pagar == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_pagar, 0, '.'));
		$('td[data-role="dav_total_pcul"] span[data-role="value"]').text(totales.davivienda.total_pcul == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_pcul, 0, '.'));
		$('td[data-role="dav_total_ppm"] span[data-role="value"]').text(totales.davivienda.total_ppm == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_ppm, 0, '.'));
		$('td[data-role="dav_total_general_ica"] span[data-role="value"]').text(totales.davivienda.total_general_ica == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_general_ica, 0, '.'));
		$('td[data-role="dav_total_dist"] span[data-role="value"]').text(totales.davivienda.total_dist == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_dist, 0, '.'));
		$('td[data-role="dav_total_retefuente"] span[data-role="value"]').text(totales.davivienda.total_retefuente == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_retefuente, 0, '.'));
		$('td[data-role="dav_total_general_deducciones"] span[data-role="value"]').text(totales.davivienda.total_general_deducciones == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_general_deducciones, 0, '.'));
		$('td[data-role="dav_total_neto_pagar"] span[data-role="value"]').text(totales.davivienda.total_neto_pagar == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_neto_pagar, 0, '.'));
		$('td[data-role="dav_total_descuentos"] span[data-role="value"]').text(totales.davivienda.total_descuentos == 0 ? '--' : accounting.formatNumber(totales.davivienda.total_descuentos, 0, '.'));

		$('td[data-role="total_pagar"] span[data-role="value"]').text(total_pagar == 0 ? '--' : accounting.formatNumber(total_pagar, 0, '.'));
		$('td[data-role="total_pcul"] span[data-role="value"]').text(total_pcul == 0 ? '--' : accounting.formatNumber(total_pcul, 0, '.'));
		$('td[data-role="total_ppm"] span[data-role="value"]').text(total_ppm == 0 ? '--' : accounting.formatNumber(total_ppm, 0, '.'));
		$('td[data-role="total_general_ica"] span[data-role="value"]').text(total_general_ica == 0 ? '--' : accounting.formatNumber(total_general_ica, 0, '.'));
		$('td[data-role="total_dist"] span[data-role="value"]').text(total_dist == 0 ? '--' : accounting.formatNumber(total_dist, 0, '.'));
		$('td[data-role="total_retefuente"] span[data-role="value"]').text(total_retefuente == 0 ? '--' : accounting.formatNumber(total_retefuente, 0, '.'));
		$('td[data-role="total_general_deducciones"] span[data-role="value"]').text(total_general_deducciones == 0 ? '--' : accounting.formatNumber(total_general_deducciones, 0, '.'));
		$('td[data-role="total_neto_pagar"] span[data-role="value"]').text(total_neto_pagar == 0 ? '--' : accounting.formatNumber(total_neto_pagar, 0, '.'));
		$('td[data-role="total_descuentos"] span[data-role="value"]').text(total_descuentos == 0 ? '--' : accounting.formatNumber(total_descuentos, 0, '.'));

		actualizar_resumenes();
	}

	var calcular_totales_contratos = function(t, e)
	{
		var tr = t.closest('tr');
		var $input_dias = tr.find('input[name^="dias_"]');
		var $input_afc = tr.find('input[name^="afc_"]');
		var $input_otros_descuentos = tr.find('input[name^="otros_descuentos_"]');
		var $input_declarante = tr.find('input[name^="declarante_"]');
		var recursos = (tr.data('recursos')+'').split(',');
		var variables = (tr.data('variables')+'').split(',');
		var total_medicina_prepagada = variables[2];
		var banco = tr.data('banco');
		var dias = parseInt($input_dias.val() == '' ? 0 : $input_dias.val());

		if($.isNumeric(dias))
		{
			var formaPago = $input_dias.data('tipo');
			var pago_mensual = 0;
			var Total_Pagar = 0;
			var Pago_Recurso = 0;
			var Con_VC_UVT = 0;
			var Pago_EPS = 0;
			var Pago_Pension = 0;
			var Pago_ARL = 0;
			var Total_Pago_Mensual = 0;
			var Medicina_Prepagada = 0;
			var Hijos = 0;
			var AFC = $input_afc.inputmask('unmaskedvalue');
			var Ingreso_Base_Gravado_384 = 0;
			var Ingreso_Base_Gravado_1607 = 0;
			var Ingreso_Base_Gravado_25 = 0;
			var Base_UVR_Ley_1607 = 0;
			var Base_UVR_Art_384 = 0;
			var Base_ICA = 0;
			var PCUL = 0;
			var PPM = 0;
			var Total_ICA = 0;
			var DIST = 0;
			var Retefuente = 0;
			var Retefuente_1607 = 0;
			var Retefuente_384 = 0;
			var Otros_Descuentos_Expresion = $input_otros_descuentos.data('expresion');
			var Otros_Descuentos = $input_otros_descuentos.val();
			var Cod_Retef = 0;
			var Cod_Seven = 0;
			var Total_Deducciones = 0;
			var Declarante = $input_declarante.is(':checked') ? 1 : 0;
			var Neto_Pagar = 0;

			AFC = AFC == null ? 0 : parseInt(AFC);
			Otros_Descuentos = Otros_Descuentos == null || Otros_Descuentos == '' ? 0 : parseInt(Otros_Descuentos);

			// calculo por recursos.
			$.each(recursos, function(i, e)
			{
				var recurso = $.grep(to_sync, function(o, i)
				{
					return o.Id == e;
				});

				var Valor_CRP = $('td[data-recurso="'+e+'"][data-role="Valor_CRP"]').data('value');
				var Saldo_CRP = $('td[data-recurso="'+e+'"][data-role="Saldo_CRP"]').data('value');

				Pago_Mensual = $('td[data-recurso="'+e+'"][data-role="Pago_Mensual"]').data('value');
				
				switch(formaPago)
				{
					case 'Mes':
					case 'Dia':
						Pago_Recurso = Math.round((Pago_Mensual/30) * dias);
					break;
					case 'Fecha':
						Pago_Recurso = Math.round(Pago_Mensual * dias);
					break;
				}

				console.log(Pago_Recurso+'>'+Saldo_CRP);
				
				if (Pago_Recurso > Saldo_CRP)
					$input_dias.addClass('danger');
				else
					$input_dias.removeClass('danger');

				Total_Pagar += Pago_Recurso;
				Total_Pago_Mensual += Pago_Mensual;

				if (recurso.length)
				{
					recurso[0].Dias_Trabajados = dias;
					recurso[0].Total_Pagar = Pago_Recurso;
				}

				//asignar resultados y calcular totales
				$('td[data-recurso="'+e+'"][data-role="Total_Pagar"]').attr('data-value', Pago_Recurso);
				$('td[data-recurso="'+e+'"][data-role="Total_Pagar"] span[data-role="value"]').text(accounting.formatNumber(Pago_Recurso, 0, '.'));
			});

			//calculo acumulado recursos
			Con_VC_UVT = Total_Pagar / UVT;
			Con_VC_UVT = parseFloat(Con_VC_UVT).toFixed(2);

			switch (formaPago)
			{
				case 'Mes':
				case 'Dia':
					Pago_EPS = ((Total_Pago_Mensual > BASE ? (Total_Pago_Mensual * 0.4 * 0.125) : (SM * 0.125)) / 30) * dias;
					Pago_Pension = ((Total_Pago_Mensual > BASE ? (Total_Pago_Mensual * 0.4 * 0.16) : (SM * 0.16)) / 30) * dias;
					Pago_ARL = ((Total_Pago_Mensual > BASE ? (Total_Pago_Mensual * 0.4 * 0.01044) : (SM * 0.01044)) / 30) * dias;
				break;
				case 'Fecha':
					Pago_EPS = (Total_Pago_Mensual > BASE ? (Total_Pago_Mensual * 0.4 * 0.125) : (dias * 0.125)) * (dias > 0 ? 1 : 0);
					Pago_Pension = (Total_Pago_Mensual > BASE ? (Total_Pago_Mensual * 0.4 * 0.16) : (dias * 0.16)) * (dias > 0 ? 1 : 0);
					Pago_ARL = (Total_Pago_Mensual > BASE ? (Total_Pago_Mensual * 0.4 * 0.01044) : (dias * 0.01044)) * (dias > 0 ? 1 : 0);
				break;
			}

			if(variables[0] == 1)
				Medicina_Prepagada = total_medicina_prepagada / 12;

			if(variables[1] == 1)
				Hijos = Total_Pagar * 0.1;

			Ingreso_Base_Gravado_384 = Total_Pagar - (Pago_EPS + Pago_Pension + Pago_ARL);
			Ingreso_Base_Gravado_1607 = (Con_VC_UVT < 95 ? 0 : Total_Pagar - (Pago_EPS + Pago_Pension + Pago_ARL + Medicina_Prepagada + Hijos + AFC)) + (Total_Pagar == 0 ? 0 : 0);
			Ingreso_Base_Gravado_25 = Ingreso_Base_Gravado_1607 - (Ingreso_Base_Gravado_1607 * 0.25);
			Base_UVR_Ley_1607 = Ingreso_Base_Gravado_25 / UVT;
			Base_UVR_Art_384 = Ingreso_Base_Gravado_384 / UVT;
			Base_ICA = (Total_Pagar - (Pago_EPS + Pago_Pension)) > 0 ? (Total_Pagar - (Pago_EPS + Pago_Pension)) : 0;
			PCUL = Total_Pagar * 0.005;
			PPM = Total_Pagar * 0.005;
			Total_ICA = Base_ICA * 0.00966;
			DIST = Total_Pagar * 0.01;

			$.each(TABLA_1607[0], function(k, v){
				if(k > Base_UVR_Ley_1607)
					return false;
				Retefuente_1607 += (Base_UVR_Ley_1607 > k ? ((Base_UVR_Ley_1607-k)*v[0]) + v[1] : 0) * UVT;
			});

			$.each(TABLA_384[0], function(k, v) {
				if(k > Base_UVR_Art_384)
					return false;
				Retefuente_384 = Base_UVR_Art_384 > k ? (UVT * v[0]) : 0;
			});

			Retefuente_1607 = Math.round(Retefuente_1607);
			Retefuente_384 = Math.round(Retefuente_384);
			Retefuente = Math.max(Retefuente_1607, Retefuente_384);

			Total_Deducciones = PCUL + PPM + Total_ICA + DIST + Retefuente;
			Neto_Pagar = Total_Pagar - Total_Deducciones - Otros_Descuentos;

			if(Base_UVR_Ley_1607 > 95 && Base_UVR_Ley_1607 < 150.01)
				Cod_Retef = 24360517;
			else if (Base_UVR_Ley_1607 > 150 && Base_UVR_Ley_1607 < 360)
				Cod_Retef = 24360518;
			else if (Base_UVR_Ley_1607 > 360 && Base_UVR_Ley_1607 < 360.01)
				Cod_Retef = 24360519;
			else if (Base_UVR_Ley_1607 > 250 && Base_UVR_Ley_1607 < 300.01)
				Cod_Retef = 24360520;

			if(Base_UVR_Ley_1607 > 95 && Base_UVR_Ley_1607 < 150.01)
				Cod_Seven = 19;
			else if(Base_UVR_Ley_1607 > 150 && Base_UVR_Ley_1607 < 200.01)
				Cod_Seven = 20;
			else if(Base_UVR_Ley_1607 > 200 && Base_UVR_Ley_1607 < 250.01)
				Cod_Seven = 21;
			else if(Base_UVR_Ley_1607 > 250 && Base_UVR_Ley_1607 < 300.01)
				Cod_Seven = 22;

			$.each(recursos, function(i, e)
			{
				var recurso = $.grep(to_sync, function(o, i)
				{
					return o.Id == e;
				});

				recurso[0].Con_VC_UVT = parseFloat(Con_VC_UVT).toFixed(2);
				recurso[0].EPS = Math.round(Pago_EPS);
				recurso[0].Pension = Math.round(Pago_Pension);
				recurso[0].ARL = Math.round(Pago_ARL);
				recurso[0].Medicina_Prepagada = Math.round(Medicina_Prepagada);
				recurso[0].Hijos = Math.round(Hijos);
				recurso[0].AFC = Math.round(AFC);
				recurso[0].Ingreso_Base_Gravado_384 = Math.round(Ingreso_Base_Gravado_384);
				recurso[0].Ingreso_Base_Gravado_1607 = Math.round(Ingreso_Base_Gravado_1607);
				recurso[0].Ingreso_Base_Gravado_25 = Math.round(Ingreso_Base_Gravado_25);
				recurso[0].Base_UVR_Ley_1607 = parseFloat(Base_UVR_Ley_1607).toFixed(2);
				recurso[0].Base_UVR_Art_384 = parseFloat(Base_UVR_Art_384).toFixed(2);
				recurso[0].Base_ICA = Math.round(Base_ICA);
				recurso[0].PCUL = Math.round(PCUL);
				recurso[0].PPM = Math.round(PPM);
				recurso[0].Total_ICA = Math.round(Total_ICA);
				recurso[0].DIST = Math.round(DIST);
				recurso[0].Retefuente = Math.round(Retefuente);
				recurso[0].Retefuente_1607 = Retefuente_1607;
				recurso[0].Retefuente_384 = Retefuente_384;
				recurso[0].Otros_Descuentos_Expresion = Otros_Descuentos_Expresion;
				recurso[0].Otros_Descuentos = Otros_Descuentos;
				recurso[0].Cod_Retef = Cod_Retef;
				recurso[0].Cod_Seven = Cod_Seven;
				recurso[0].Total_Deducciones = Total_Deducciones;
				recurso[0].Declarante = Declarante;
				recurso[0].Neto_Pagar = Neto_Pagar;
				recurso[0].Banco = banco;
			});

			tr.find('td[data-role="UVT"] span[data-role="value"]').text(accounting.formatNumber(Con_VC_UVT, 2, ','));
			tr.find('td[data-role="EPS"] span[data-role="value"]').text(accounting.formatNumber(Pago_EPS, 0, '.'));
			tr.find('td[data-role="Pension"] span[data-role="value"]').text(accounting.formatNumber(Pago_Pension, 0, '.'));
			tr.find('td[data-role="ARL"] span[data-role="value"]').text(accounting.formatNumber(Pago_ARL, 0, '.'));
			tr.find('td[data-role="Hijos"] span[data-role="value"]').text(Hijos == 0 ? '--' : accounting.formatNumber(Hijos, 0, '.'));
			tr.find('td[data-role="Medicina_Prepagada"] span[data-role="value"]').text(Medicina_Prepagada == 0 ? '--' : accounting.formatNumber(Medicina_Prepagada, 0, '.'));
			tr.find('td[data-role="Ingreso_Base_Gravado_384"] span[data-role="value"]').text(Ingreso_Base_Gravado_384 == 0 ? '--' : accounting.formatNumber(Ingreso_Base_Gravado_384, 0, '.'));
			tr.find('td[data-role="Ingreso_Base_Gravado_1607"] span[data-role="value"]').text(Ingreso_Base_Gravado_1607 == 0 ? '--' : accounting.formatNumber(Ingreso_Base_Gravado_1607, 0, '.'));
			tr.find('td[data-role="Ingreso_Base_Gravado_25"] span[data-role="value"]').text(Ingreso_Base_Gravado_25 == 0 ? '--' : accounting.formatNumber(Ingreso_Base_Gravado_25, 0, '.'));
			tr.find('td[data-role="Base_UVR_Ley_1607"] span[data-role="value"]').text(Base_UVR_Ley_1607 == 0 ? '--' : accounting.formatNumber(Base_UVR_Ley_1607, 2, ','));
			tr.find('td[data-role="Base_UVR_Art_384"] span[data-role="value"]').text(Base_UVR_Art_384 == 0 ? '--' : accounting.formatNumber(Base_UVR_Art_384, 2, ','));
			tr.find('td[data-role="Base_ICA"] span[data-role="value"]').text(Base_ICA == 0 ? '--' : accounting.formatNumber(Base_ICA, 0, '.'));
			tr.find('td[data-role="PCUL"] span[data-role="value"]').text(PCUL == 0 ? '--' : accounting.formatNumber(PCUL, 0, '.'));
			tr.find('td[data-role="PPM"] span[data-role="value"]').text(PPM == 0 ? '--' : accounting.formatNumber(PPM, 0, '.'));
			tr.find('td[data-role="Total_ICA"] span[data-role="value"]').text(Total_ICA == 0 ? '--' : accounting.formatNumber(Total_ICA, 0, '.'));
			tr.find('td[data-role="DIST"] span[data-role="value"]').text(DIST == 0 ? '--' : accounting.formatNumber(DIST, 0, '.'));
			tr.find('td[data-role="Retefuente"] span[data-role="value"]').text(Retefuente == 0 ? '--' : accounting.formatNumber(Retefuente, 0, '.'));
			tr.find('td[data-role="Cod_Retef"] span[data-role="value"]').text(Cod_Retef == 0 ? '--' : Cod_Retef);
			tr.find('td[data-role="Cod_Seven"] span[data-role="value"]').text(Cod_Seven == 0 ? '--' : Cod_Seven);
			tr.find('td[data-role="Total_Deducciones"] span[data-role="value"]').text(Total_Deducciones == 0 ? '--' : accounting.formatNumber(Total_Deducciones, 0, '.'));
			tr.find('td[data-role="Neto_Pagar"] span[data-role="value"]').text(Neto_Pagar == 0 ? '--' : accounting.formatNumber(Neto_Pagar, 0, '.'));

			actualizar_totales();
		}
	}

	$('#recursos tbody tr').each(function(i, e)
	{
		var recursos = ($(e).data('recursos')+'').split(',');
		
		$.each(recursos, function(i, e)
		{
			if (e != "undefined")
			{
				to_sync.push({
					'Id': e,
					'Dias_Trabajados': '',
					'Total_Pagar': '',
					'Con_VC_UVT': '',
					'EPS': '',
					'Pension': '',
					'ARL': '',
					'Medicina_Prepagada': '',
					'Hijos': '',
					'AFC': '',
					'Ingreso_Base_Gravado_384': '',
					'Ingreso_Base_Gravado_1607': '',
					'Ingreso_Base_Gravado_25': '',
					'Base_UVR_Ley_1607': '',
					'Base_UVR_Art_384': '',
					'Base_ICA': '',
					'PCUL': '',
					'PPM': '',
					'Total_ICA': '',
					'DIST': '',
					'Retefuente': '',
					'Retefuente_1607': '',
					'Retefuente_384': '',
					'Otros_Descuentos_Expresion': '',
					'Otros_Descuentos': '',
					'Otras_Bonificaciones': '',
					'Cod_Retef': '',
					'Cod_Seven': '',
					'Total_Deducciones': '',
					'Declarante': '',
					'Neto_Pagar': '',
					'Banco': '',
				});
			}
		});
	});

	$('input[name^="dias_"], input[name^="afc_"]').on('keyup', function(e)
	{
		calcular_totales_contratos($(this), e);
	});

	/*Otros descuentos formulación*/
	$('input[name^="otros_descuentos_"]').on('blur', function(e)
	{
		var expresion = $(this).val();
		var tr = $(this).closest('tr');

		if (expresion != '')		
		{
			$(this).attr('data-expresion', expresion);
			var scope = {
	    		
	    	}
	    	try{
	    		var expr = Parser.parse(expresion);
	    		var ress = expr.evaluate(scope);
	    		$(this).val(parseFloat(ress).toFixed(0));
	    	} catch(err) {
	    		$(this).val(0);
	    	}

			calcular_totales_contratos($(this), e);

		} else {
			$(this).val(0);
		}
	}).on('focus', function(e)
	{
		var expresion = $(this).attr('data-expresion');

		$(this).val(expresion);
	}).on('keydown', function(e)
	{
		if(e.which === 13)
		{
			$(this).blur();
			e.preventDefault();
			return false;
		}
		
	});

	$('#recursos').tableHeadFixer({"head" : true, "left" : 4});

	$('#formulario').on('submit', function(e)
	{
		$('input[name^="dias_"]').trigger('keyup');
		$('input[name="_planilla"]').val(JSON.stringify(to_sync));
	});

	$('a[data-role="detail"]').on('click', function(e){
		var tr = $(this).closest('tr');
		var id = tr.data('contrato');

		$.get(
			URL_CONTRATOs+'/'+id+'/serviceObtener',
			{},
			function(data)
			{
				var html = 	'<div class="row">';

								'<div class="col-xs-12 col-md-6 form-group">'+
									'<label for="">Eventualidades</label>'+
									'<p class="form-control-static first-uppercase">'+(data.Tipo_Modificacion ? ' '+data.Tipo_Modificacion : 'ninguna')+'</p>'+
								'</div>';

				if(data.Tipo_Modificacion)
				{
					html += '<div class="col-xs-12">'+
								'<div class="alert '+(data.Tipo_Modificacion == 'terminado' ? 'alert-danger' : 'alert-warning')+' alert-dismissible" role="alert">'+
									'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
									'<strong><span class="glyphicon glyphicon-alert"></span> Atención!</strong> <br /><br />'+
										(data.Tipo_Modificacion == 'terminado' ? 
											'Este contrato sera finalizado el dia <strong>'+data.Fecha_Terminacion_Modificada+'</strong> y tiene un saldo vigente por: <strong>'+data.Saldo_A_Favor+(data.Tipo_Pago != 'Mes' ? data.Tipo_Pago+' (s)' : ' Dia(s)')+'</strong>'
											: 
											'A este contrato se le han realizado suspenciones'
										)+
								'.</div>'+
							'</div>';
				}

				html += '<div class="col-xs-12 col-md-6 form-group">'+
							'<label for="">N°</label>'+
							'<p class="form-control-static first-uppercase">'+data.Numero+'</p>'+
						'</div>'+
						'<div class="col-xs-12 col-md-6 form-group">'+
							'<label for="">Estado</label>'+
							'<p class="form-control-static first-uppercase">'+(data.Estado ? data.Estado : 'en proceso')+'</p>'+
						'</div>'+
						'<div class="col-xs-12 form-group">'+ //pendiente cargar saldos
							'<label for="">Historial de pagos ('+data.saldos.length+')</label><br>'+
							'<table class="table table-min table-bordered">'+
								'<thead>'+
									'<tr>'+
										'<th width="7%">N°</th>'+
										'<th width="30%">Fecha</th>'+
										'<th width="30%">Planilla</th>'+
										'<th width="33%">Valor</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody>';
								var total_saldos = 0;
								$.each(data.saldos, function(i, e)
								{
									total_saldos += e.Total_Pagado;
									html += '<tr>'+
												'<td>'+(i+1)+'</td>'+
												'<td>'+e.Fecha_Registro+'</td>'+
												'<td> <a href="'+URL+'/'+e.planilla['Id_Planilla']+'/recursos">'+e.planilla['Titulo']+'</a></td>'+
												'<td align="right"><span class="pull-left">$</span>'+accounting.formatNumber(e.Total_Pagado, 0, '.')+'</td>'+
											'</tr>';
								});
						html += '</tbody>'+
								'<tfoot>'+
									'<tr>'+
										'<td colspan="3">Total</td>'+
										'<td align="right"><span class="pull-left">$</span>'+accounting.formatNumber(total_saldos, 0, '.')+'</td>'+
									'</tr>'+
								'</tfoot>'+
							'</table>'+
						'</div>'+
						'<div class="col-xs-12 form-group">'+ //pendiente cargar suspenciones
							'<label for="">Historial de suspenciones ('+data.suspenciones.length+')</label><br>'+
							'<table class="table table-min table-bordered">'+
								'<thead>'+
									'<tr>'+
										'<th width="7%">N°</th>'+
										'<th width="30%">Fecha inicio</th>'+
										'<th width="30%">Fecha fin</th>'+
										'<th width="33%">Dias</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody>';
								var total_dias = 0;
								$.each(data.suspenciones, function(i, e)
								{
									var Fecha_Inicio = moment(e.Fecha_Inicio);
									var Fecha_Terminacion = moment(e.Fecha_Terminacion);
									total_dias += Fecha_Terminacion.diff(Fecha_Inicio, 'days');
									html += '<tr>'+
												'<td>'+(i+1)+'</td>'+
												'<td>'+e.Fecha_Inicio+'</td>'+
												'<td>'+e.Fecha_Terminacion+'</td>'+
												'<td align="right">'+Fecha_Terminacion.diff(Fecha_Inicio, 'days')+'</td>'+
											'</tr>';
								});
						html += '</tbody>'+
								'<tfoot>'+
									'<tr>'+
										'<td colspan="3">Total</td>'+
										'<td align="right">'+total_dias+'</td>'+
									'</tr>'+
								'</tfoot>'+
							'</table>'+
						'</div>'+
					'</div>';

				$('#modal_form_contrato').find('.modal-body').html(html);
				$('#modal_form_contrato').attr('data-contrato', data.Id_Contrato);
			},
			'json'
		).done(function()
			{
				$('#modal_form_contrato').modal('show');
			}
		);

		e.preventDefault();
	});

	$('#remover_contrato').on('click', function(e)
	{
		var id = $('#modal_form_contrato').attr('data-contrato');
		var recursos_char = $('#recursos tr[data-role="contenedor_contrato"][data-contrato="'+id+'"]').data('recursos');
		var recursos = recursos_char.toString().split(',');

		var temp = $.grep(to_sync, function(o, i)
		{
			return $.inArray(o.Id, recursos) > -1;
		}, true);

		to_sync = temp;
		$('#recursos tr[data-contrato="'+id+'"]').remove();

		$('#modal_form_contrato').modal('hide');
		actualizar_totales();
	});

	$('input[name^="dias_"]').trigger('keyup');
});

//http://stackoverflow.com/questions/1009808/enter-key-press-behaves-like-a-tab-in-javascript}

$(document).keydown(function(e) {

  // Set self as the current item in focus
  var self = $(':focus'),
      // Set the form by the current item in focus
      form = self.parents('form:eq(0)'),
      focusable;

  // Array of Indexable/Tab-able items
  focusable = form.find('input,a,select,button,textarea,div[contenteditable=true]').filter(':visible').filter(':tabbable');

  function enterKey(){
    if (e.which === 13 && !self.is('textarea,div[contenteditable=true]')) { // [Enter] key

      // If not a regular hyperlink/button/textarea
      if ($.inArray(self, focusable) && (!self.is('a,button'))){
        // Then prevent the default [Enter] key behaviour from submitting the form
        e.preventDefault();
      } // Otherwise follow the link/button as by design, or put new line in textarea

      // Focus on the next item (either previous or next depending on shift)
      focusable.eq(focusable.index(self) + (e.shiftKey ? -1 : 1)).focus();

      return false;
    }
  }
  // We need to capture the [Shift] key and check the [Enter] key either way.
  if (e.shiftKey) { enterKey() } else { enterKey() }
});