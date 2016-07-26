$(function(e)
{
	var myBoard = new DrawingBoard.Board('drawingboard', {
	 	droppable: true,
	 	stretchImg: true,
	 	enlargeYourContainer: true,
	 	webStorage: false,
	 	controls: [
	 		'DrawingMode',
			{ Navigation: { back: true, forward: true } }
		],
	});

	$('#form-firma').on('submit', function(e)
	{
		var img = myBoard.getImg();
		img = (myBoard.blankCanvas == img) ? '' : img;

		if(img.length > 0)
		{
			$('input[name="firma"]').val(img);
		}
	});

	myBoard.addControl('Upload');

	var firma = $('#firma-actual').prop('src');
	
	if(firma.length > 0)
	{
		myBoard.setImg(firma);
	}
});

/*

	$('#guardar_imagen').on('click', function(e)
	{
		var img = myBoard.getImg();
		img = (myBoard.blankCanvas == img) ? '' : img;

		if(img.length > 0)
		{
			var nuevo = $('input[name="id_imagen"]').val() == '0';
			$.post(
				$(this).data('url'),
				{
					id: $('input[name="id"]').val(),
					id_empresa: $('input[name="id_empresa"]').val(),
					id_imagen: $('input[name="id_imagen"]').val(),
					id_cargador: $('input[name="id_cargador"]').val(),
					imagen: img,
					comentarios: $('input[name="comentarios"]').val()
				},
				function(data) 
				{
					if(nuevo)
					{
						var html = '<div class="col-xs-3 col-sm-1 col-md-1 image"><img src="'+(url+'/'+data['imagen_original'])+'" alt="" width="100%" /></div>';
						$('.galeria .row').append(html);
					} else {
						$('img[id="'+$('input[name="id_imagen"]').val()+'"]').prop('src', url+'/'+data['imagen_editada']).prop('title', data['comentarios']);
					}
				},
				'json'
			).done(
				function()
				{
					$('#modal_album_editor').modal('hide');
				}
			);
		}
	});

	$('#eliminar_imagen').on('click', function(e)
	{
		$.post(
			$(this).data('url'),
			{
				id_empresa: $('input[name="id_empresa"]').val(),
				id_imagen: $('input[name="id_imagen"]').val()
			},
			function(data) 
			{
				$('img[id="'+$('input[name="id_imagen"]').val()+'"]').closest('div').remove();
			},
			'json'
		).done(
			function()
			{
				$('#modal_album_editor').modal('hide');
			}
		);
	});

	$('.galeria').delegate('.image', 'click', function(e)
	{
		var img = $(this).find('img');
		myBoard.setImg(img.prop('src'));
		$('input[name="id_imagen"]').val(img.attr('id'));
		$('input[name="comentarios"]').val(img.attr('title'));
		$('#eliminar_imagen').show();
		$('#modal_album_editor').modal('show');
	});

	$('#nueva_imagen').on('click', function(e)
	{
		myBoard.resetBackground();
		$('input[name="id_imagen"]').val(0);
		$('input[name="comentarios"]').val('');
		$('#eliminar_imagen').hide();
		$('#modal_album_editor').modal('show');
	});

*/