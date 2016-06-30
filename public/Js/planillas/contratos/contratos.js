$(function()
{
	var rubros = [];
	var suspenciones = [];
	var duracion = 0;

	// actualizar totales
	var actualizarTotales = function()
	{
		var total_valor_crp = 0;
		var total_saldo_crp = 0;
		var total_pago_mensual = 0;
		$.each(rubros, function(i, e)
		{
			total_valor_crp += parseInt(e.Valor_CRP);
			total_saldo_crp += parseInt(e.Saldo_CRP);
			total_pago_mensual += parseInt(e.Pago_Mensual);
		});

		$('td[data-rel="total_valor_crp"] span[data-role="value"]').text(total_valor_crp == 0 ? '--' : accounting.formatNumber(total_valor_crp, 0, '.'));
		$('td[data-rel="total_saldo_crp"] span[data-role="value"]').text(total_saldo_crp == 0 ? '--' : accounting.formatNumber(total_saldo_crp, 0, '.'));
		$('td[data-rel="total_pago_mensual"] span[data-role="value"]').text(total_pago_mensual == 0 ? '--' : accounting.formatNumber(total_pago_mensual, 0, '.'));
	}

	// validacion modales 
	var validarRubro = function(rubro)
	{
		$('#modal_form_rubro .form-group').removeClass('has-error');
		$('#modal_form_rubro .errores ul').html('');
		var errores = false;

		if(rubro.Numero_Registro == '')
		{
			errores = true;
			$('#modal_form_rubro input[name="Numero_Registro"]').closest('.form-group').addClass('has-error');
			$('.errores ul').append('<li>El campo numero de registro es requerido</li>');
		}

		if(rubro.Valor_CRP == '')
		{
			errores = true;
			$('#modal_form_rubro input[name="Valor_CRP"]').closest('.form-group').addClass('has-error');
			$('#modal_form_rubro .errores ul').append('<li>El campo valor crp es requerido</li>');
		}

		if(rubro.Saldo_CRP == '')
		{
			errores = true;
			$('#modal_form_rubro input[name="Saldo_CRP"]').closest('.form-group').addClass('has-error');
			$('#modal_form_rubro .errores ul').append('<li>El campo saldo crp es requerido</li>');
		}

		if(rubro.Expresion == '')
		{
			errores = true;
			$('#modal_form_rubro input[name="Expresion"]').closest('.form-group').addClass('has-error');
			$('#modal_form_rubro .errores ul').append('<li>El campo expresión es requerido</li>');
		}

		if(rubro.Pago_Mensual == '')
		{
			errores = true;
			$('#modal_form_rubro input[name="Pago_Mensual"]').closest('.form-group').addClass('has-error');
			$('#modal_form_rubro .errores ul').append('<li>El campo pago mensual es requerido</li>');
		}

		if(rubro.Fuente.id == null)
		{
			errores = true;
			$('#modal_form_rubro select[name="Fuente"]').closest('.form-group').addClass('has-error');
			$('#modal_form_rubro .errores ul').append('<li>El campo fuente es requerido</li>');
		}

		if(rubro.Rubro.id == null)
		{
			errores = true;
			$('#modal_form_rubro select[name="Rubro"]').closest('.form-group').addClass('has-error');
			$('#modal_form_rubro .errores ul').append('<li>El campo rubro es requerido</li>');
		}

		if(rubro.Componente.id == null)
		{
			errores = true;
			$('#modal_form_rubro select[name="Componente"]').closest('.form-group').addClass('has-error');
			$('#modal_form_rubro .errores ul').append('<li>El campo componente es requerido</li>');
		}

		if(errores)
			$('#modal_form_rubro .errores').fadeIn();
		
		return errores;
	};

	var validarSuspencion = function(suspencion)
	{
		$('#modal_form_suspencion .form-group').removeClass('has-error');
		$('#modal_form_suspencion .errores ul').html('');
		var errores = false;

		if(suspencion.Fecha_Inicio == '')
		{
			errores = true;
			$('#modal_form_suspencion input[name="Fecha_Inicio_Suspencion"]').closest('.form-group').addClass('has-error');
			$('#modal_form_suspencion .errores ul').append('<li>El campo fecha inicio es requerido</li>');
		}

		if(suspencion.Fecha_Terminacion == '')
		{
			errores = true;
			$('#modal_form_suspencion input[name="Fecha_Fin_Suspencion"]').closest('.form-group').addClass('has-error');
			$('#modal_form_suspencion .errores ul').append('<li>El campo fecha de terminación es requerido</li>');
		}

		if(errores)
			$('#modal_form_suspencion .errores').fadeIn();
		
		return errores;
	};

	// carga de datos modales
	function popularModal(rubro)
	{
		$('#modal_form_rubro .errores').fadeOut();
		$('#modal_form_rubro .form-group').removeClass('has-error');
		$('#modal_form_rubro input[name="Id"]').val(rubro.Id);
		$('#modal_form_rubro input[name="Unique"]').val(rubro.Unique);
		$('#modal_form_rubro input[name="Numero_Registro"]').val(rubro.Numero_Registro);
		$('#modal_form_rubro input[name="Valor_CRP"]').val(rubro.Valor_CRP);
		$('#modal_form_rubro input[name="Saldo_CRP"]').val(rubro.Saldo_CRP);
		$('#modal_form_rubro input[name="Expresion"]').val(rubro.Expresion);
		$('#modal_form_rubro input[name="Pago_Mensual"]').val(rubro.Pago_Mensual);
		$('#modal_form_rubro select[name="Fuente"]').selectpicker('val', rubro.Fuente.id);
		$('#modal_form_rubro select[name="Rubro"]').selectpicker('val', rubro.Rubro.id);
		$('#modal_form_rubro select[name="Componente"]').selectpicker('val', rubro.Componente.id);

		if(rubro.Id != '')
			$('#modal_form_rubro #eliminar_rubro').fadeIn();
		else 
			$('#modal_form_rubro #eliminar_rubro').fadeOut();

		$('#modal_form_rubro').modal('show');
	}

	function popularModalSuspencion(suspencion)
	{
		$('#modal_form_suspencion .errores').fadeOut();
		$('#modal_form_suspencion .form-group').removeClass('has-error');
		$('#modal_form_suspencion input[name="Id"]').val(suspencion.Id);
		$('#modal_form_suspencion input[name="Unique"]').val(suspencion.Unique);
		$('#modal_form_suspencion input[name="Fecha_Inicio_Suspencion"]').val(suspencion.Fecha_Inicio);
		$('#modal_form_suspencion input[name="Fecha_Fin_Suspencion"]').val(suspencion.Fecha_Terminacion);

		if(suspencion.Id != '')
			$('#modal_form_suspencion #eliminar_suspencion').fadeIn();
		else 
			$('#modal_form_suspencion #eliminar_suspencion').fadeOut();

		$('#modal_form_suspencion').modal('show');
	}

	// pitar registros en tablas
	function pintarRubros()
	{
		$('#rubros tbody').html('');	

		$.each(rubros, function(i, e)
		{
			$('#rubros tbody').append(
				'<tr data-temp-id="'+e.Id+'" data-expresion="'+e.Expresion+'" data-unique="'+e.Unique+'">'+
					'<td data-rel="Numero_Registro" data-val="'+e.Numero_Registro+'">'+e.Numero_Registro+'</td>'+
					'<td data-rel="Id_Fuente" data-val="'+e.Fuente.id+'">'+e.Fuente.valor+'</td>'+
					'<td data-rel="Id_Rubro" data-val="'+e.Rubro.id+'">'+e.Rubro.valor+'</td>'+
					'<td data-rel="Id_Componente" data-val="'+e.Componente.id+'">'+e.Componente.valor+'</td>'+
					'<td data-rel="Valor_CRP" data-val="'+e.Valor_CRP+'" align="right"><span class="pull-left">$</span>'+accounting.formatNumber(e.Valor_CRP, 0, '.')+'</td>'+
					'<td data-rel="Saldo_CRP" data-val="'+e.Saldo_CRP+'" align="right"><span class="pull-left">$</span>'+accounting.formatNumber(e.Saldo_CRP, 0, '.')+'</td>'+
					'<td data-rel="Pago_Mensual" data-val="'+e.Pago_Mensual+'" align="right"><span class="pull-left">$</span>'+accounting.formatNumber(e.Pago_Mensual, 0, '.')+'</td>'+
				'</tr>'
			);
		});
	};

	function pintarSuspeciones()
	{
		$('#suspenciones tbody').html('');	

		$.each(suspenciones, function(i, e)
		{
			$('#suspenciones tbody').append(
				'<tr data-temp-id="'+e.Id+'" data-unique="'+e.Unique+'">'+
					'<td>'+(i+1)+'</td>'+
					'<td data-rel="Fecha_Inicio" data-val="'+e.Fecha_Inicio+'">'+e.Fecha_Inicio+'</td>'+
					'<td data-rel="Fecha_Terminacion" data-val="'+e.Fecha_Terminacion+'">'+e.Fecha_Terminacion+'</td>'+
				'</tr>'
			);
		});
	}

	function popularRubros()
	{
		$('#rubros tbody tr').each(function(i, e){
			var tr = $(e);
			var rubro = {
				"Id": tr.data('temp-id'),
				"Unique": tr.data('unique'),
	    		"Numero_Registro": tr.find('td[data-rel="Numero_Registro"]').data('val'),
				"Fuente": {
					"id": tr.find('td[data-rel="Id_Fuente"]').data('val'),
					"valor": tr.find('td[data-rel="Id_Fuente"]').text()
				},
				"Rubro": 
				{
					"id": tr.find('td[data-rel="Id_Rubro"]').data('val'),
					"valor": tr.find('td[data-rel="Id_Rubro"]').text()
				},
				"Componente": 
				{
					"id": tr.find('td[data-rel="Id_Componente"]').data('val'),
					"valor": tr.find('td[data-rel="Id_Componente"]').text()
				},
				"Valor_CRP": tr.find('td[data-rel="Valor_CRP"]').data('val'),
				"Saldo_CRP": tr.find('td[data-rel="Saldo_CRP"]').data('val'),
				"Expresion": tr.data('expresion'),
				"Pago_Mensual": tr.find('td[data-rel="Pago_Mensual"]').data('val')
			};
			rubros.push(rubro);
		});
	}

	function popularSuspenciones()
	{
		$('#suspenciones tbody tr').each(function(i, e){
			var tr = $(e);
			var suspencion = {
				"Id": tr.data('temp-id'),
				"Unique": tr.data('unique'),
				"Fecha_Inicio": tr.find('td[data-rel="Fecha_Inicio"]').data('val'),
				"Fecha_Terminacion": tr.find('td[data-rel="Fecha_Terminacion"]').data('val')
			};
			suspenciones.push(suspencion);
		});
	}

	// configuración de calendarios
	$('input[name="Fecha_Inicio"]').datepicker({
		defaultDate: '+1w',
      	yearRange: '-100:+100',
      	dateFormat: 'yy-mm-dd',
      	changeYear: true,
		changeMonth: true,
		numberOfMonths: 3,
		onClose: function( selectedDate ) {
			$('input[name="Fecha_Terminacion"]').datepicker( "option", "minDate", selectedDate );
		}
    });

    $('input[name="Fecha_Terminacion"]').datepicker({
		defaultDate: '+1w',
      	yearRange: '-100:+100',
      	dateFormat: 'yy-mm-dd',
      	changeYear: true,
		changeMonth: true,
		numberOfMonths: 3,
		onClose: function( selectedDate ) {
			$('input[name="Fecha_Inicio"]').datepicker( "option", "maxDate", selectedDate );
		}
    });

	$('input[name="Fecha_Inicio_Suspencion"]').datepicker({
		defaultDate: '+1w',
      	yearRange: '-100:+100',
      	dateFormat: 'yy-mm-dd',
      	changeYear: true,
		changeMonth: true,
		numberOfMonths: 3,
		onClose: function( selectedDate ) {
			$('input[name="Fecha_Fin_Suspencion"]').datepicker( "option", "minDate", selectedDate );
		}
    });

    $('input[name="Fecha_Fin_Suspencion"]').datepicker({
		defaultDate: '+1w',
      	yearRange: '-100:+100',
      	dateFormat: 'yy-mm-dd',
      	changeYear: true,
		changeMonth: true,
		numberOfMonths: 3,
		onClose: function( selectedDate ) {
			$('input[name="Fecha_Inicio_Suspencion"]').datepicker( "option", "maxDate", selectedDate );
		}
    });

    //popular modales
    $('#rubros tbody').delegate('tr', 'click', function(e)
    {
    	var tr = $(this);
    	var rubro = $.grep(rubros, function(o, i)
    	{
    		return o.Id == tr.data('temp-id');
    	});

    	if(rubro)
    		popularModal(rubro[0]);
    });    

    $('#suspenciones tbody').delegate('tr', 'click', function(e)
    {
    	var tr = $(this);
    	var suspencion = $.grep(suspenciones, function(o, i)
    	{
    		return o.Id == tr.data('temp-id');
    	});

    	if(suspencion)
    		popularModalSuspencion(suspencion[0]);
    });

    //boton agregar nuevos elementos
    $('#agregar_rubro').on('click', function(e)
    {
    	var rubro = {
    		"Id": '',
    		"Unique": '',
    		"Numero_Registro": '',
			"Fuente": {
				"id": '',
				"valor": ''
			},
			"Rubro": 
			{
				"id": '',
				"valor": ''
			},
			"Componente": 
			{
				"id": '',
				"valor": ''
			},
			"Valor_CRP": '',
			"Saldo_CRP": '',
			"Expresion": '',
			"Pago_Mensual": ''
    	};

    	popularModal(rubro);
    });

    $('#agregar_suspencion').on('click', function(e)
    {
    	var suspencion = {
    		"Id": '',
    		"Unique": '',
			"Fecha_Inicio": '',
			"Fecha_Terminacion": ''
    	};

    	popularModalSuspencion(suspencion);
    });

    $('#Expresion, input[name="Valor_CRP"]').on('blur', function(e)
    {
    	if($('#Expresion').val() != '')
    	{
	    	var scope = {
	    		crp: parseFloat($('input[name="Valor_CRP"]').inputmask('unmaskedvalue'))
	    	}
	    	try{
	    		var expr = Parser.parse($('#Expresion').val());
	    		var ress = expr.evaluate(scope);
	    		$('input[name="Pago_Mensual"]').val(parseFloat(ress).toFixed(0));
	    	} catch(err) {
	    		$('input[name="Pago_Mensual"]').val('');
	    		$('input[name="Expresion"]').closest('.form-group').addClass('has-error');
	    	}
    	} else {
    		$('input[name="Pago_Mensual"]').val('');
    	}
    });

    //guardar nuevos elementos 
    $('#guardar_rubro').on('click', function(e)
    {
    	var nuevo = $('#modal_form_rubro input[name="Id"]').val() == '';

    	var rubro = {
    		"Id": nuevo ? '0' : $('#modal_form_rubro input[name="Id"]').val(),
    		"Unique": parseInt($('#rubros').attr('data-total')) + 1,
    		"Numero_Registro": $('#modal_form_rubro input[name="Numero_Registro"]').val(),
			"Fuente": {
				"id": $('#modal_form_rubro select[name="Fuente"]').val(),
				"valor": $('#modal_form_rubro select[name="Fuente"] option:selected').text()
			},
			"Rubro": 
			{
				"id": $('#modal_form_rubro select[name="Rubro"]').val(),
				"valor": $('#modal_form_rubro select[name="Rubro"] option:selected').text()
			},
			"Componente": 
			{
				"id": $('#modal_form_rubro select[name="Componente"]').val(),
				"valor": $('#modal_form_rubro select[name="Componente"] option:selected').text()
			},
			"Valor_CRP": $('#modal_form_rubro input[name="Valor_CRP"]').inputmask('unmaskedvalue'),
			"Saldo_CRP": $('#modal_form_rubro input[name="Saldo_CRP"]').inputmask('unmaskedvalue'),
			"Expresion": $('#modal_form_rubro input[name="Expresion"]').val(),
			"Pago_Mensual": $('#modal_form_rubro input[name="Pago_Mensual"]').inputmask('unmaskedvalue')
    	};

    	
    	if(!validarRubro(rubro))
    	{
    		$('#rubros').attr('data-total', rubro.Unique);
	    	if (nuevo)
	    	{
	    		rubros.push(rubro);
	    	} else {
	    		$.each(rubros, function(i, e) {
	    			if (e.Id == rubro.Id)
	    			{
	    				rubros[i] = rubro;
	    			}
	    		});
	    	}

			pintarRubros();
			actualizarTotales();
			$('#modal_form_rubro').modal('hide');
    	}
    });

    $('#guardar_suspencion').on('click', function(e)
    {
    	var nuevo = $('#modal_form_suspencion input[name="Id"]').val() == '';

    	var suspencion = {
    		"Id": nuevo ? '0' : $('#modal_form_suspencion input[name="Id"]').val(),
    		"Unique": parseInt($('#suspenciones').attr('data-total')) + 1,
			"Fecha_Inicio": $('#modal_form_suspencion input[name="Fecha_Inicio_Suspencion"]').val(),
			"Fecha_Terminacion": $('#modal_form_suspencion input[name="Fecha_Fin_Suspencion"]').val()
    	};
    	
    	if(!validarSuspencion(suspencion))
    	{
    		$('#suspenciones').attr('data-total', suspencion.Unique);
	    	if (nuevo)
	    	{
	    		suspenciones.push(suspencion);
	    	} else {
	    		$.each(suspenciones, function(i, e) {
	    			if (e.Id == suspencion.Id)
	    			{
	    				suspenciones[i] = suspencion;
	    			}
	    		});
	    	}

			pintarSuspeciones();
			$('#modal_form_suspencion').modal('hide');
    	}
    });

    //eliminar elementos modales
    $('#eliminar_rubro').on('click', function(e)
    {
    	var unique = $('#modal_form_rubro input[name="Unique"]').val();
    	var temp = $.grep(rubros, function(o, i)
    	{
    		return o.Unique == unique;
    	}, true);

    	rubros = temp;

		pintarRubros();
		$('#modal_form_rubro').modal('hide');
    });

    $('#eliminar_suspencion').on('click', function(e)
    {
    	var unique = $('#modal_form_suspencion input[name="Unique"]').val();
    	var temp = $.grep(suspenciones, function(o, i)
    	{
    		return o.Unique == unique;
    	}, true);

    	suspenciones = temp;

		pintarSuspeciones();
		$('#modal_form_suspencion').modal('hide');
    });

    $('#form-contrato').on('submit', function(e)
    {
    	$('input[name="_recursos"]').val(JSON.stringify(rubros));
    	$('input[name="_suspenciones"]').val(JSON.stringify(suspenciones));
    });

    popularRubros();
    popularSuspenciones();
    actualizarTotales();

});