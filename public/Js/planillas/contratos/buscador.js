$(function()
{
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
		if (key.length >= 2)
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
						$.each(data, function(i, e)
						{
							html = '<li class="list-group-item">'+
										'<h5 class="list-group-item-heading">'+
											e['Numero']+
											'<a href="'+URL+'/'+e['Id_Contratista']+'/editar/'+e['Id_Contrato']+'" class="pull-right btn btn-primary btn-xs">'+
												'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>'+
											'</a>'+
										'</h5>'+
										'<p class="list-group-item-text">'+
											'<div class="row">'+
												'<div class="col-xs-12">'+
													'<div class="row">'+
														'<div class="col-xs-12">'+
															'<small>'+
																'<strong>Objeto:</strong> '+e['Objeto']+' <br>'+
																'<strong>Duración:</strong> '+e['Fecha_Inicio']+' hasta '+e['Fecha_Terminacion']+
															'</small>'+
														'</div>'+
													'</div>'+
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
});

