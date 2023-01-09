$(document).ready(function() {
  $('#aviso-exito').hide();
  $('#aviso-error').hide();

  $('#form-recuperar').submit(e=>{
    mostrar_loader('recuperar_password');
    let email=$('#email-recuperar').val();
    let dni=$('#dni-recuperar').val();
    if(email==''||dni==''){
      $('#aviso-error').show();
      $('#aviso-error').text("Rellene todos los campos.");
      cerrar_loader('');
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
              cerrar_loader('exito_envio');
              $('#aviso-exito').show();
              $('#aviso-exito').text("Contraseña restablecida.");
            }else{
              cerrar_loader('error_envio');
              $('#aviso-error').show();
              $('#aviso-error').text("No se pudo restablecer la contraseña.");
            }
            $('#form-recuperar').trigger('reset');
          });
        }else{
          cerrar_loader('error_usuario');
          $('#aviso-error').show();
          $('#aviso-error').text("El correo y el dni no están registrados o están incorrectos");
        }
      })
    }
    e.preventDefault();
  })

  function mostrar_loader(mensaje){
    var texto=null;
    var mostrar=false;
    switch (mensaje) {
      case 'recuperar_password':
        texto='Enviando correo, espere por favor...';
        mostrar=true;
        break;

    }
    if(mostrar==true){
      Swal.fire({
        title: 'Enviando correo',
        text: texto,
        showConfirmButton: false
      });
    }
  }

  function cerrar_loader(mensaje){
    var tipo=null;
    var texto=null;
    var mostrar=false;
    switch (mensaje) {
      case 'exito_envio':
        tipo='success';
        texto='Correo enviado correctamente.';
        mostrar=true;
        break;

      case 'error_envio':
        tipo='error';
        texto='No se pudo enviar el correo, vuelva a intentarlo.';
        mostrar=true;
        break;

      case 'error_usuario':
        tipo='error';
        texto='El usuario no se ha encontrado.';
        mostrar=true;
        break;

      default:
        Swal.close();
        break;
    }
    if(mostrar==true){
      Swal.fire({
        position: 'center',
        icon: tipo,
        text: texto,
        showConfirmButton: false
      });
    }
  }
})