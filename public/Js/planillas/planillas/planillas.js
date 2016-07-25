$(function(){
	var URL = $('#main_list').data('url');
	var LISTA_ACTUAL =  $('#lista').html();

	var popular_modal_principal = function(data)
	{
		$('input[name="Numero"]').val(data.Numero);
		$('input[name="Titulo"]').val(data.Titulo);
		$('input[name="Colectiva"]').val(data.Colectiva);
		$('textarea[name="Descripcion"]').val(data.Descripcion);
		$('textarea[name="Observaciones"]').val(data.Observaciones);
		$('input[name="Desde"]').val(data.Desde);
		$('input[name="Hasta"]').val(data.Hasta);
		$('select[name="Id_Fuente"]').selectpicker('val', data.Id_Fuente);
		var rubros = '';
		if (data.rubros.length > 0)
		{
			$.each(data.rubros, function(i, e){
				rubros += e.Id_Rubro.toString()+(i == data.rubros.length - 1 ? '':',');
			});
		}

		$('select[name="Rubros[]"]').data('value', rubros);
		$('input[name="Id_Planilla"]').val(data.Id_Planilla);
		$('select[name="Id_Fuente"]').trigger('changed.bs.select');

		if (data.Id_Planilla == 0)
		{
			$('#agregar_contratos_eliminados').hide();
		} else {
			$('#agregar_contratos_eliminados').show();
		}
	};

	$('input[name="Desde"]').datepicker({
		defaultDate: '+1w',
      	yearRange: '-100:+100',
      	dateFormat: 'yy-mm-dd',
      	changeYear: true,
		changeMonth: true,
		numberOfMonths: 3,
		onClose: function( selectedDate ) {
			$('input[name="Hasta"]').datepicker( "option", "minDate", selectedDate );
		}
    });

    $('input[name="Hasta"]').datepicker({
		defaultDate: '+1w',
      	yearRange: '-100:+100',
      	dateFormat: 'yy-mm-dd',
      	changeYear: true,
		changeMonth: true,
		numberOfMonths: 3,
		onClose: function( selectedDate ) {
			$('input[name="Desde"]').datepicker( "option", "maxDate", selectedDate );
		}
    });

	var popular_errores_modal = function(data)
	{
		$('#main_form .form-group').removeClass('has-error');
		$('#errores ul').html('');
		var selector = '';
		for (var error in data){
		    if (typeof data[error] !== 'function') {
		        switch(error)
		        {
		        	case 'Id_Fuente':
		        	case 'Rubros':
		        		selector = 'select';
		        	break;

		        	case 'Numero':
		        	case 'Desde':
		        	case 'Hasta':
		        		selector = 'input';
		        	break;
		        }
		        $('#errores ul').append('<li>'+data[error]+'</li>');
		        if (error == 'Rubros')
		        	$('#main_form '+selector+'[name="'+error+'[]"]').closest('.form-group').addClass('has-error');
		        else
		        	$('#main_form '+selector+'[name="'+error+'"]').closest('.form-group').addClass('has-error');
		    }
		}
		$('#errores').fadeIn();
	};

	//eventos

	$('select[name="Id_Fuente"]').on('changed.bs.select', function(event, clickedIndex, newValue, oldValue)
	{
		$.get(
			URL+'/service/rubros/'+$(this).selectpicker('val'),
			{},
			function(data)
			{
				$('select[name="Rubros[]"]').html('');
				
				if(data.status == 'ok')
				{
					$.each(data.rubros, function(i, e){
						$('select[name="Rubros[]"]').append('<option value="'+e.Id_Rubro+'">'+e.Codigo+' - '+e.Nombre+'</option>');
					});
				}
				var value = $('select[name="Rubros[]"]').data('value');
				$('select[name="Rubros[]"]').selectpicker('val', value.split(','));
				$('select[name="Rubros[]"]').selectpicker('refresh');
			}
		).done(function(){
			$('#modal_main_form').modal('show');
		});
	});

	$('#lista').delegate('a[data-role="editar"]', 'click', function(e){
		var id = $(this).data('rel');
		$.get(
			URL+'/service/obtener/'+id,
			{},
			function(data)
			{	
				if(data)
				{
					$('#eliminar').attr('data-rel', id).show();
					popular_modal_principal(data);
				}
			},
			'json'
		);
		
		e.preventDefault();
	});

	$('#crear').on('click', function(e)
	{
		var data = {
			Numero: '',
			Titulo: '',
			Colectiva: '',
			Descripcion: '',
			Observaciones: '',
			Desde: '',
			Hasta: '',
			Id_Fuente: '',
			rubros: [],
			Id_Planilla: '0',
		}

		$('#eliminar').attr('data-rel', '').hide();
		popular_modal_principal(data);
	});

	$('#eliminar').on('click', function(e)
	{
		if ($(this).data('rel') != '')
		{
			$.get(
				URL+'/service/delete/'+$(this).data('rel'),
				{},
				function(data)
				{
					$('#alerta').show();
					$('#modal_main_form').modal('hide');

					window.location.reload();
				},
				'json'
			);

			e.preventDefault();
		}
	});

	$('#main_form').on('submit', function(e){
		$.post(
			URL+'/service/procesar',
			$(this).serialize(),
			function(data){
				if(data.status == 'error')
				{
					popular_errores_modal(data.errors);
				} else {
					$('#alerta').show();
					$('#modal_main_form').modal('hide');

					window.location.reload();
				}
			},
			'json'
		);

		e.preventDefault();
	});
});