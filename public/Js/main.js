$(function(){
  
  $('#loading-image').bind('ajaxStart', function(){
      $(this).show();
  }).bind('ajaxStop', function(){
      $(this).hide();
  });

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $.datepicker.setDefaults( $.datepicker.regional[ "es" ] );

  $('input[data-role="datepicker"]').datepicker({
      dateFormat: 'yy-mm-dd',
      yearRange: "-100:+100",
      changeMonth: true,
      changeYear: true,
  });

  $('body').delegate('input[type="text"][data-number]', 'keypress', function(event) {
      if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
          event.preventDefault();
      }
  });

  $('body').delegate('.close_tab', 'click', function(event) {
      BootstrapDialog.confirm(
          {
              title: $('<h4 class="modal-title">Alerta</h4>'),
              message: $(this).data('title'),
              type: BootstrapDialog.TYPE_DEFAULT,
              closable: true,
              btnOKClass: 'btn-primary',
              btnOKLabel: 'Aceptar',
              btnCancelLabel: 'Cancelar',
              callback: function(result){
                  if(result) {
                      close();
                  }
              }
          }
      );
  });

  $('input[type="text"][data-currency]').inputmask({ alias: "currency", groupSeparator:".", radixPoint:",", removeMaskOnSubmit: true});

  $('select').each(function(i, e)
  {
      if ($(this).attr('data-value'))
      {
          if ($.trim($(this).data('value')) !== '')
          {
              var dato = $(this).data('value');
              $(this).val(dato);
          }
      }
  });

  $('input.readonly').prop('readonly', true)
      .prop('tabindex', -1)
      .on('click', function(i, e){
          $(this).prop('readonly', false);
          $(this).focus();
      }).on('blur', function(i, e){
          $(this).prop('readonly', true);
      });
  

});
