$(function(){
  
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

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
});
