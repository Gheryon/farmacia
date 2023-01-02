$(document).ready(function() {
  $('#aviso-exito').hide();
  $('#aviso-error').hide();

  $('#form-recuperar').submit(e=>{
    let email=$('#email-recuperar').val();
    let dni=$('#dni-recuperar').val();
    if(email==''||dni==''){
      $('#aviso-error').show();
      $('#aviso-error').text("Rellene todos los campos.");
    }else{
      $('#aviso-error').hide();
      let funcion='verificar';
      $.post('../controlador/recuperarController.php',{funcion, email, dni}, (response)=>{
        $('#aviso-exito').hide();
        $('#aviso-error').hide();
        if(response=='encontrado'){
          let funcion='recuperar';
          $.post('../controlador/recuperarController.php',{funcion, email, dni}, (response2)=>{
            console.log(response2);
            if(response2=='enviado'){
              $('#aviso-exito').show();
              $('#aviso-exito').text("Contraseña restablecida.");
            }else{
              $('#aviso-error').show();
              $('#aviso-error').text("No se pudo restablecer la contraseña.");
            }
            $('#form-recuperar').trigger('reset');
          });
        }else{
          $('#aviso-error').show();
          $('#aviso-error').text("El correo y el dni no están registrados o están incorrectos");
        }
      })
    }
    e.preventDefault();
  })
})