$(function(){
	var URL = $('#main_list').data('url');
	var LISTA_ACTUAL =  $('#lista').html();

	var reset = function(e)
	{
		$('input[name="buscador"]').val('');
		$('#buscar span').removeClass('glyphicon-remove').addClass('glyphicon-search');
		$('#lista').html(LISTA_ACTUAL);
		$('#paginador').fadeIn();
	};

	var buscar = function(e)
	{
		var key = $('input[name="buscador"]').val();
		if (key.length > 2)
		{
			$('#buscar span').removeClass('glyphicon-search').addClass('glyphicon-remove');
			$('#buscar').data('role', 'reset');

			$.get(
				URL+'/service/buscar/'+key,
				{}, 
				function(data)
				{
					if(data.length > 0)
					{
						var html = '';
						$('#lista').html('');
						$.each(data, function(i, e){
							html = '<li class="list-group-item">'+
						                '<h5 class="list-group-item-heading">'+
						                    ''+e['Nombre'].toUpperCase()+''+
						                    '<a data-role="editar" data-rel="'+e['Id_Contratista']+'" class="pull-right btn btn-primary btn-xs">'+
						                    	'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>'+
						                    '</a>'+
						                '</h5>'+
						                '<p class="list-group-item-text">'+
						                    '<div class="row">'+
						                        '<div class="col-xs-12">'+
						                        	'<small><strong>Identificación: </strong>'+e.tipo_documento['Nombre_TipoDocumento']+' '+e['Cedula']+'</small>'+
						                        '</div>'+
					                            '<div class="col-xs-12">'+
				                                	'<br>'+
				                                	'<a href="'+URL+'/'+e['Id_Contratista']+'/contratos" class="btn btn-default btn-xs">Contratos</a>'+
				                                '</div>'+
						                    '</div>'+
						                '</p>'+
						            '</li>';
							$('#lista').append(html);
						});
						$('#paginador').fadeOut();
					}
				},
				'json'
			);
		} else if(key.length == 0){
			reset(e);
		}
	};

	function popular_modal_principal(data)
	{
		$('select[name="Id_TipoDocumento"]').val(data['Id_TipoDocumento']);
		$('select[name="Id_Banco"]').selectpicker('val', data['Id_Banco']);

		$('input[name="Id_Contratista"]').val(data['Id_Contratista']);
		$('input[name="Nombre"]').val(data['Nombre']);
		$('input[name="Cedula"]').val(data['Cedula']);
		
		$('input[name="Numero_Cta"]').val(data['Numero_Cta']);
		$('input[name="Tipo_Cuenta"]').removeAttr('checked').parent('.btn').removeClass('active');
		$('input[name="Tipo_Cuenta"][value="'+data['Tipo_Cuenta']+'"]').trigger('click');

		$('input[name="Activo"]').removeAttr('checked').parent('.btn').removeClass('active');
		$('input[name="Activo"][value="'+data['Activo']+'"]').trigger('click');

		$('input[name="Hijos"]').removeAttr('checked').parent('.btn').removeClass('active');
		$('input[name="Hijos"][value="'+data['Hijos']+'"]').trigger('click');
		$('input[name="Hijos_Cantidad"]').val(data['Hijos_Cantidad']);

		$('input[name="Declarante"]').removeAttr('checked').parent('.btn').removeClass('active');
		$('input[name="Declarante"][value="'+data['Declarante']+'"]').trigger('click');
		
		$('input[name="Medicina_Prepagada"]').removeAttr('checked').parent('.btn').removeClass('active');
		$('input[name="Medicina_Prepagada"][value="'+data['Medicina_Prepagada']+'"]').trigger('click');
		$('input[name="Medicina_Prepagada_Cantidad"]').val(data['Medicina_Prepagada_Cantidad']);
		
		$('select[name="Nivel_Riesgo_ARL"]').selectpicker('val', data['Nivel_Riesgo_ARL']);

		$('#modal_main_form').modal('show');
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
		        	case 'Id_TipoDocumento':
		        	case 'Id_Banco':
		        	case 'Nivel_Riesgo_ARL':
		        		selector = 'select';
		        	break;

		        	case 'Nombre':
		        	case 'Cedula':
		        	case 'Numero_Cta':
		        	case 'Tipo_Cuenta':
		        	case 'Activo':
		        	case 'Hijos':
		        	case 'Hijos_Cantidad':
		        	case 'Declarante':
		        	case 'Medicina_Prepagada':
		        	case 'Medicina_Prepagada_Cantidad':
		        		selector = 'input';
		        	break;
		        }
		        $('#errores ul').append('<li>'+data[error]+'</li>');
		        $('#main_form '+selector+'[name="'+error+'"]').closest('.form-group').addClass('has-error');
		    }
		}
		$('#errores').fadeIn();
	};

	//eventos
	$('input[name="buscador"]').on('keyup', function(e){
		var code = e.which; //http://stackoverflow.com/questions/3462995/jquery-keydown-keypress-keyup-enterkey-detection
    	if(code==13)
    		buscar(e);
	});
	
	$('#buscar').on('click', function(e)
	{
		var role = $(this).data('role');
		switch(role)
		{
			case 'buscar':
				$(this).data('role', 'reset');
				buscar(e);
			break;

			case 'reset':
				$(this).data('role', 'buscar');
				reset(e);
			break;
		}
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
					popular_modal_principal(data);
				}
			},
			'json'
		);
	});

	$('#crear').on('click', function(e)
	{
		var data = {
			Id_Contratista: 0,
			Nombre: '',
			Cedula: '',
			Numero_Cta: '',
			Tipo_Cuenta: '',
        	Activo: '',
        	Hijos: '',
        	Declarante: '',
        	Medicina_Prepagada: '',
        	Hijos_Cantidad: '',
        	Medicina_Prepagada_Cantidad: '',
        	Nivel_Riesgo_ARL: '',
			Id_TipoDocumento: '',
			Id_Banco: ''
		}

		popular_modal_principal(data);
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