$(document).ready(function(){
    var tipo_usuario= $('#tipo_usuario').val();
    //si el tipo de usuario==2, tecnico, no se muestra boton de crear usuario
    if(tipo_usuario==2){
      $('#button-crear-usuario').hide();
    }
    buscar_datos();
    var funcion;
    function buscar_datos(consulta) {
        funcion='buscar_usuarios_adm';
        $.post('../controlador/usuarioController.php', {consulta, funcion},(response)=>{
            const usuarios= JSON.parse(response);
            let template='';
            console.log(tipo_usuario);
            usuarios.forEach(usuario => {
                template+=`
                <div usuarioId="${usuario.id}"class="col-12 col-sm6 col-md-4 d-flex align-items-stretch flex-column">
              <div class="card bg-light d-flex flex-fill">
              <div class="card-header text-muted border-bottom-0">`;
                if(usuario.tipo_usuario==3){
                  template+=`<h1 class="badge badge-danger">${usuario.tipo}</h1>`;
                }
                if(usuario.tipo_usuario==2){
                  template+=`<h1 class="badge badge-warning">${usuario.tipo}</h1>`;
                }
                if(usuario.tipo_usuario==1){
                  template+=`<h1 class="badge badge-info">${usuario.tipo}</h1>`;
                }
                template+=`
              </div>
              <div class="card-body pt-0">
                <div class="row">
                  <div class="col-7">
                    <h2 class="lead"><b>${usuario.nombre} ${usuario.apellidos}</b></h2>
                    <p class="text-muted text-sm"><b>Sobre mí: </b> ${usuario.adicional} </p>
                    <ul class="ml-4 mb-0 fa-ul text-muted">
                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-id-card"></i></span> DNI: + ${usuario.dni}</li>
                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-birthday-cake"></i></span> Edad #: + ${usuario.edad}</li>
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Residencia: ${usuario.residencia}</li>
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Teléfono #: + ${usuario.telefono}</li>
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> Correo #: + ${usuario.correo}</li>
                      <li class="small"><span class="fa-li"><i class="fas fa-lg fa-smile-wink"></i></span> Sexo #: + ${usuario.sexo}</li>
                    </ul>
                  </div>
                  <div class="col-5 text-center">
                    <img src="${usuario.avatar}" alt="user-avatar" class="img-circle img-fluid">
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <div class="text-right">`;
                //comprobar si usuario logueado es root
                if(tipo_usuario==3){
                  //si es root, puede eliminar a todos menos a root
                  if(usuario.tipo_usuario!=3){
                    template+=`
                    <button class="borrar-usuario btn btn-danger mr-1  type="button" data-toggle="modal" data-target="#confirmar">
                      <i class="fas fa-window-close mr-1"></i>Eliminar
                  </button>
                    `;
                  }
                  //si el usuario es un técnico, root puede ascenderlo
                  if(usuario.tipo_usuario==2){
                    template+=`
                    <button class="ascender btn btn-primary ml-1" type="button" data-toggle="modal" data-target="#confirmar">
                      <i class="fas fa-sort-amount-up mr-1"></i>Ascender
                  </button>
                    `;
                  }
                  if(usuario.tipo_usuario==1){
                    template+=`
                    <button class="descender btn btn-secondary ml-1" type="button" data-toggle="modal" data-target="#confirmar">
                      <i class="fas fa-sort-amount-down mr-1"></i>Descender
                  </button>
                    `;
                  }
                }else{
                  //usuario logueado no es root.
                  //si es 1=>administrador, solo muestra boton borrar a tecnicos, no a administradores o root
                  if(tipo_usuario==1 && usuario.tipo_usuario!=1 && usuario.tipo_usuario!=3){
                    template+=`
                    <button class="borrar-usuario btn btn-danger  type="button" data-toggle="modal" data-target="#confirmar">
                      <i class="fas fa-window-close mr-1"></i>Eliminar
                  </button>
                    `;
                  }
                }
                 template+=`
                </div>
              </div>
            </div>
            </div>
                `;
            });
            $('#usuarios').html(template);
        });
    }
    $(document).on('keyup','#buscar',function(){
        let valor = $(this).val();
        if(valor!=""){
            buscar_datos(valor);
        }else{
            buscar_datos();
        }
    });

    $('#form-crear-usuario').submit(e=>{
      let nombre= $('#nombre').val();
      let apellidos= $('#apellidos').val();
      let edad= $('#edad').val();
      let dni= $('#dni').val();
      let pass= $('#pass').val();
      funcion='crear_nuevo_usuario';
      $.post('../controlador/usuarioController.php',{nombre, apellidos, edad, dni, pass, funcion},(response)=>{
        if(response=='add'){
          //mostrar el alert de éxito
          $('#add').hide('slow');
          $('#add').show(1000);
          $('#add').hide(3000);
          //resetea los campos de la card
          $('#form-crear-usuario').trigger('reset');
          buscar_datos();
        }else{
          //mostrar el alert de error
          $('#noadd').hide('slow');
          $('#noadd').show(1000);
          $('#noadd').hide(3000);
          //resetea los campos de la card
          $('#form-crear-usuario').trigger('reset');
        }
      });
      //para prevenir la actualización por defecto de la página
      e.preventDefault();
    });

    $(document).on('click', '.ascender',(e)=>{
      //se quiere acceder al elemento usuarioid de la card y guardarlo en elemento, para ello hay que subir 4 veces desde donde está el boton ascender
      const elemento=$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
      //console.log(elemento);
      const id=$(elemento).attr('usuarioId');
      //console.log(id);
      funcion='ascender';
      $('#id_user').val(id);
      $('#funcion').val(funcion);
    });

    $(document).on('click', '.descender',(e)=>{
      //se quiere acceder al elemento usuarioid de la card y guardarlo en elemento, para ello hay que subir 4 veces desde donde está el boton ascender
      const elemento=$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
      //console.log(elemento);
      const id=$(elemento).attr('usuarioId');
      //console.log(id);
      funcion='descender';
      $('#id_user').val(id);
      $('#funcion').val(funcion);
    });

    $(document).on('click', '.borrar-usuario',(e)=>{
      //se quiere acceder al elemento usuarioid de la card y guardarlo en elemento, para ello hay que subir 4 veces desde donde está el boton ascender
      const elemento=$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
      //console.log(elemento);
      const id=$(elemento).attr('usuarioId');
      //console.log(id);
      funcion='borrar_usuario';
      $('#id_user').val(id);
      $('#funcion').val(funcion);
    });

    $('#form-confirmar').submit(e=>{
      let pass=$('#pass').val();
      let id_usuario=$('#id_user').val();
      funcion=$('#funcion').val();
      $.post('../controlador/usuarioController.php', {pass, id_usuario, funcion}, (response)=>{
        if(response=='ascendido'|| response=='descendido'|| response=='borrado')
        {
          $('#confirmado').hide('slow');
          $('#confirmado').show(1000);
          $('#confirmado').hide(3000);
          //resetea los campos de la card
          $('#form-confirmar').trigger('reset');
          buscar_datos();
        }else{
          $('#rechazado').hide('slow');
          $('#rechazado').show(1000);
          $('#rechazado').hide(3000);
          //resetea los campos de la card
          $('#form-confirmar').trigger('reset');
        }
      });
      e.preventDefault();
    });
})