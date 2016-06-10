$(function(){

	var to_sync = [];
	var SM = $('input[name="sm"]').val();
	var UVT = $('input[name="uvt"]').val();
	var BASE = SM * 100 / 40;

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
					'Otros_Descuentos': '',
					'Otras_Bonificaciones': '',
					'Total_Deducciones': '',
					'Declarante': '',
					'Neto_Pagar': ''
				});
			}
		});
	});

	$('input[name^="dias_"]').on('keyup', function(e)
	{
		var tr = $(this).closest('tr');
		var recursos = (tr.data('recursos')+'').split(',');
		var variables = (tr.data('variables')+'').split(',');
		var total_medicina_prepagada = variables[3];
		var dias = parseInt($(this).val());
		var formaPago = $(this).data('tipo');
		var pago_mensual = 0;

		var Total_Pagar = 0;
		var Pago_Recurso = 0;
		var Con_VC_UVT = 0;
		var Pago_EPS = 0;
		var Pago_Pension = 0;
		var Pago_ARL = 0;
		var Total_Pago_Mensual = 0;
		var Hijos = 0;
		var Medicina_Prepagada = 0;

		// calculo por recursos.
		$.each(recursos, function(i, e)
		{
			var recurso = $.grep(to_sync, function(o, i)
			{
				return o.Id == e;
			});

			var Valor_CRP = $('td[data-recurso="'+e+'"][data-role="Valor_CRP"]').data('value');
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

			Total_Pagar += Pago_Recurso;
			Total_Pago_Mensual += Pago_Mensual;

			if (recurso.length)
			{
				recurso[0].Dias_Trabajados = dias;
				recurso[0].Total_Pagar = Pago_Recurso;
				recurso[0].Con_VC_UVT = parseFloat(Pago_Recurso / UVT).toFixed(2);
			}

			//asignar resultados y calcular totales
			$('td[data-recurso="'+e+'"][data-role="Total_Pagar"]').attr('data-value', Pago_Recurso);
			$('td[data-recurso="'+e+'"][data-role="Total_Pagar"] span[data-role="value"]').text(accounting.formatNumber(Pago_Recurso, 0, '.'));
		});

		//calculo acumulado recursos
		switch (formaPago)
		{
			case 'Mes':
			case 'Dia':
				Pago_EPS = ((Pago_Mensual > BASE ? (Pago_Mensual * 0.4 * 0.125) : (SM * 0.125)) / 30) * dias;
				Pago_Pension = ((Pago_Mensual > BASE ? (Pago_Mensual * 0.4 * 0.16) : (SM * 0.16)) / 30) * dias;
				Pago_ARL = ((Pago_Mensual > BASE ? (Pago_Mensual * 0.4 * 0.01044) : (SM * 0.01044)) / 30) * dias;
			break;
			case 'Fecha':
				Pago_EPS = (Pago_Mensual > BASE ? (Pago_Mensual * 0.4 * 0.125) : (dias * 0.125)) * (dias > 0 ? 1 : 0);
				Pago_Pension = (Pago_Mensual > BASE ? (Pago_Mensual * 0.4 * 0.16) : (dias * 0.16)) * (dias > 0 ? 1 : 0);
				Pago_ARL = (Pago_Mensual > BASE ? (Pago_Mensual * 0.4 * 0.01044) : (dias * 0.01044)) * (dias > 0 ? 1 : 0);
			break;
		}

		if(variables[0] == 1)
			Medicina_Prepagada = total_medicina_prepagada / 12;

		if(variables[1] == 1)
			Hijos = Total_Pagar * 0.1;

		$.each(recursos, function(i, e)
		{
			var recurso = $.grep(to_sync, function(o, i)
			{
				return o.Id == e;
			});

			recurso[0].Pago_EPS = Math.round(Pago_EPS);
			recurso[0].Pago_Pension = Math.round(Pago_Pension);
			recurso[0].Pago_ARL = Math.round(Pago_ARL);
			recurso[0].Hijos = Math.round(Hijos);
		});

		Con_VC_UVT += Total_Pagar / UVT;
		tr.find('td[data-role="UVT"] span[data-role="value"]').text(accounting.formatNumber(Con_VC_UVT, 2, ','));
		tr.find('td[data-role="EPS"] span[data-role="value"]').text(accounting.formatNumber(Pago_EPS, 0, '.'));
		tr.find('td[data-role="Pension"] span[data-role="value"]').text(accounting.formatNumber(Pago_Pension, 0, '.'));
		tr.find('td[data-role="ARL"] span[data-role="value"]').text(accounting.formatNumber(Pago_ARL, 0, '.'));
		tr.find('td[data-role="Hijos"] span[data-role="value"]').text(Hijos == 0 ? '--' : accounting.formatNumber(Hijos, 0, '.'));
		tr.find('td[data-role="Medicina_Prepagada"] span[data-role="value"]').text(Medicina_Prepagada == 0 ? '--' : accounting.formatNumber(Medicina_Prepagada, 0, '.'));

		console.log(to_sync);
	});

	$('#formulario').on('submit', function(e)
	{
		e.preventDefault();
	});

	$("#recursos").tableHeadFixer({"head" : false, "left" : 3}); 

});