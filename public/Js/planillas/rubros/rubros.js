$(function(){
	var URL = $('#main_list').data('url');
	var LISTA_ACTUAL =  $('#lista').html();

	function popular_modal_principal(data)
	{
		$('input[name="Id_Rubro"]').val(data['Id_Rubro']);
		$('input[name="Codigo"]').val(data['Codigo']);
		$('input[name="Nombre"]').val(data['Nombre']);

		$('#modal_main_form').modal('show');
		$('#eliminar').attr('data-rel', '').show();
	};

	var popular_errores_modal = function(data)
	{
		$('#main_form .form-group').removeClass('has-error');
		$('#errores ul').html('');
		var selector = '';
		for (var error in data){
		    if (typeof data[error] !== 'function') {
		        switch(error)
		        {
		        	case 'Codigo':
		        	case 'Nombre':
		        		selector = 'input';
		        	break;
		        }
		        $('#errores ul').append('<li>'+data[error]+'</li>');
		        $('#main_form '+selector+'[name="'+error+'"]').closest('.form-group').addClass('has-error');
		    }
		}
		$('#errores').fadeIn();
	};

	$('#lista').delegate('a[data-role="editar"]', 'click', function(e){
		var id = $(this).data('rel');
		var contratos = $(this).data('contratos');

		$.get(
			URL+'/service/obtener/'+id,
			{},
			function(data)
			{	
				if(data)
				{
					popular_modal_principal(data);

					if(contratos == 0)
					{
						$('#eliminar').attr('data-rel', data.Id_Rubro).show();
						$('#mensaje_no_eliminar').hide();
					} else {
						$('#mensaje_no_eliminar').show();
					}
				}
			},
			'json'
		);
	});

	$('#crear').on('click', function(e)
	{
		var data = {
			Id_Rubro: 0,
			Codigo: '',
			Nombre: ''
		}

		$('#mensaje_no_eliminar').hide();
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
			function(data)
			{
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