$(function()
{
	var rubros = [];
	var duracion = 0;

	var validarRubro = function(rubro)
	{
		$('#modal_form_rubro .form-group').removeClass('has-error');
		$('#errores ul').html('');
		var errores = false;

		if(rubro.Numero_Registro == '')
		{
			errores = true;
			$('input[name="Numero_Registro"]').closest('.form-group').addClass('has-error');
			$('#errores ul').append('<li>El campo numero de registro es requerido</li>');
		}

		if(rubro.Valor_CRP == '')
		{
			errores = true;
			$('input[name="Valor_CRP"]').closest('.form-group').addClass('has-error');
			$('#errores ul').append('<li>El campo valor crp es requerido</li>');
		}

		if(rubro.Saldo_CRP == '')
		{
			errores = true;
			$('input[name="Saldo_CRP"]').closest('.form-group').addClass('has-error');
			$('#errores ul').append('<li>El campo saldo crp es requerido</li>');
		}

		if(rubro.Expresion == '')
		{
			errores = true;
			$('input[name="Expresion"]').closest('.form-group').addClass('has-error');
			$('#errores ul').append('<li>El campo expresión es requerido</li>');
		}

		if(rubro.Pago_Mensual == '')
		{
			errores = true;
			$('input[name="Pago_Mensual"]').closest('.form-group').addClass('has-error');
			$('#errores ul').append('<li>El campo pago mensual es requerido</li>');
		}

		if(rubro.Fuente.id == null)
		{
			errores = true;
			$('select[name="Fuente"]').closest('.form-group').addClass('has-error');
			$('#errores ul').append('<li>El campo fuente es requerido</li>');
		}

		if(rubro.Rubro.id == null)
		{
			errores = true;
			$('select[name="Rubro"]').closest('.form-group').addClass('has-error');
			$('#errores ul').append('<li>El campo rubro es requerido</li>');
		}

		if(rubro.Componente.id == null)
		{
			errores = true;
			$('select[name="Componente"]').closest('.form-group').addClass('has-error');
			$('#errores ul').append('<li>El campo componente es requerido</li>');
		}

		if(errores)
			$('#errores').fadeIn();
		
		return errores;
	};

	function popularModal(rubro)
	{
		$('#errores').fadeOut();
		$('#modal_form_rubro .form-group').removeClass('has-error');
		$('input[name="Id"]').val(rubro.Id);
		$('input[name="Unique"]').val(rubro.Unique);
		$('input[name="Numero_Registro"]').val(rubro.Numero_Registro);
		$('input[name="Valor_CRP"]').val(rubro.Valor_CRP);
		$('input[name="Saldo_CRP"]').val(rubro.Saldo_CRP);
		$('input[name="Expresion"]').val(rubro.Expresion);
		$('input[name="Pago_Mensual"]').val(rubro.Pago_Mensual);
		$('select[name="Fuente"]').selectpicker('val', rubro.Fuente.id);
		$('select[name="Rubro"]').selectpicker('val', rubro.Rubro.id);
		$('select[name="Componente"]').selectpicker('val', rubro.Componente.id);

		if(rubro.Id != '')
			$('#eliminar_rubro').fadeIn();
		else 
			$('#eliminar_rubro').fadeOut();

		$('#modal_form_rubro').modal('show');
	}

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

    $('#guardar_rubro').on('click', function(e)
    {
    	var nuevo = $('input[name="Id"]').val() == '';

    	var rubro = {
    		"Id": nuevo ? '0' : $('input[name="Id"]').val(),
    		"Unique": $('#rubros tbody tr').length + 1,
    		"Numero_Registro": $('input[name="Numero_Registro"]').val(),
			"Fuente": {
				"id": $('select[name="Fuente"]').val(),
				"valor": $('select[name="Fuente"] option:selected').text()
			},
			"Rubro": 
			{
				"id": $('select[name="Rubro"]').val(),
				"valor": $('select[name="Rubro"] option:selected').text()
			},
			"Componente": 
			{
				"id": $('select[name="Componente"]').val(),
				"valor": $('select[name="Componente"] option:selected').text()
			},
			"Valor_CRP": $('input[name="Valor_CRP"]').inputmask('unmaskedvalue'),
			"Saldo_CRP": $('input[name="Saldo_CRP"]').inputmask('unmaskedvalue'),
			"Expresion": $('input[name="Expresion"]').val(),
			"Pago_Mensual": $('input[name="Pago_Mensual"]').inputmask('unmaskedvalue')
    	};
    	
    	if(!validarRubro(rubro))
    	{
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
			$('#modal_form_rubro').modal('hide');
    	}
    });

    $('#eliminar_rubro').on('click', function(e)
    {
    	var unique = $('input[name="Unique"]').val();
    	var temp = $.grep(rubros, function(o, i)
    	{
    		return o.Unique == unique;
    	}, true);

    	rubros = temp;

		pintarRubros();
		$('#modal_form_rubro').modal('hide');
    });

    $('#form-contrato').on('submit', function(e)
    {
    	$('input[name="_recursos"]').val(JSON.stringify(rubros));
    });

    popularRubros();

});