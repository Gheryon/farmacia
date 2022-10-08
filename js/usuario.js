$(document).ready(function(){
    var funcion='';
    var id_usuario = $('#id_usuario').val();
    var edit=false;
    //console.log(id_usuario);
    buscar_usuario(id_usuario);
    function buscar_usuario(dato) {
        funcion='buscar_usuario';
        $.post('../controlador/usuarioController.php',{dato, funcion}, (response)=>{
            //console.log(response);
            let nombre='';
            let apellidos='';
            let edad='';
            let dni='';
            let tipo='';
            let telefono='';
            let residencia='';
            let correo='';
            let sexo='';
            let adicional='';
            //parse decodifica el json de string a los valores
            const usuario = JSON.parse(response);
            nombre+=`${usuario.nombre}`;
            apellidos+=`${usuario.apellidos}`;
            edad+=`${usuario.edad}`;
            dni+=`${usuario.dni}`;
            if(usuario.tipo=='Root'){
                tipo+=`<h1 class="badge badge-danger">${usuario.tipo}</h1>`;
              }
              if(usuario.tipo=='Administrador'){
                tipo+=`<h1 class="badge badge-warning">${usuario.tipo}</h1>`;
              }
              if(usuario.tipo=='Tecnico'){
                tipo+=`<h1 class="badge badge-info">${usuario.tipo}</h1>`;
              }
            telefono+=`${usuario.telefono}`;
            residencia+=`${usuario.residencia}`;
            correo+=`${usuario.correo}`;
            sexo+=`${usuario.sexo}`;
            adicional+=`${usuario.adicional}`;
            $('#nombre_us').html(nombre);
            $('#apellidos_us').html(apellidos);
            $('#edad').html(edad);
            $('#dni_us').html(dni);
            $('#us_tipo').html(tipo);
            $('#telefono_us').html(telefono);
            $('#residencia_us').html(residencia);
            $('#correo_us').html(correo);
            $('#sexo_us').html(sexo);
            $('#adicional_us').html(adicional);
            $('#avatar-content').attr('src',usuario.avatar);
            $('#avatar-modal-pass').attr('src',usuario.avatar);
            $('#avatar-modal-avatar').attr('src',usuario.avatar);
            $('#avatar-nav').attr('src',usuario.avatar);
        })
    }
    //con $(algo)--> se analiza ese algo, si es document, se analiza todo el documento
    $(document).on('click','.edit', (e)=>{
        funcion='capturar_datos';
        edit=true;
        $.post('../controlador/usuarioController.php',{funcion, id_usuario}, (response)=>{
            //console.log(response);
            const usuario = JSON.parse(response);
            $('#telefono').val(usuario.telefono);
            $('#residencia').val(usuario.residencia);
            $('#correo').val(usuario.correo);
            $('#sexo').val(usuario.sexo);
            $('#adicional').val(usuario.adicional);
        })
    });

    //solo se analiza el #form-usuario cuando se hace submit
    $('#form-usuario').submit(e=>{
        if(edit==true){
            //el id #telefono es el del formulario
            let telefono=$('#telefono').val();
            let residencia=$('#residencia').val();
            let correo=$('#correo').val();
            let sexo=$('#sexo').val();
            let adicional=$('#adicional').val();
            funcion='editar_usuario';
            $.post('../controlador/usuarioController.php', {id_usuario, funcion, telefono, residencia, correo, sexo, adicional},(response)=>{
            if(response=='editado'){
                //mostrar el alert de editado
                $('#editado').hide('slow');
                $('#editado').show(1000);
                $('#editado').hide(3000);
                //resetea los campos de la card
                $('#form-usuario').trigger('reset');
            }
            edit=false;
            //para actualizar el card "sobre mi"
            buscar_usuario(id_usuario);
            })
        }else{
            //mostrar el alert de no editado
            $('#no-editado').hide('slow');
            $('#no-editado').show(1000);
            $('#no-editado').hide(3000);
            //resetea los campos de la card
            $('#form-usuario').trigger('reset');
        }
        e.preventDefault();
    });

    $('#form-pass').submit(e=>{
        let oldpass=$('#oldpass').val();
        let newpass=$('#newpass').val();
        funcion='cambiar_contra';
        e.preventDefault();
        $.post('../controlador/usuarioController.php', {id_usuario, funcion, oldpass, newpass},(response)=>{
            if(response=='update'){
                //mostrar el alert de editado
                $('#update').hide('slow');
                $('#update').show(1000);
                $('#update').hide(3000);
                //resetea los campos de la card
                $('#form-pass').trigger('reset');
            }else{
                //mostrar el alert de editado
                $('#noupdate').hide('slow');
                $('#noupdate').show(1000);
                $('#noupdate').hide(3000);
                //resetea los campos de la card
                $('#form-pass').trigger('reset');
            }
        })
    })

    $('#form-avatar').submit(e=>{
        let formData = new FormData($('#form-avatar')[0]);
        $.ajax({
            url:'../controlador/usuarioController.php',
            type:'POST',
            data:formData,
            cache:false,
            processData:false,
            contentType:false
        }).done(function(response){
            //console.log(response);
            //se reemplazan los avatares del modal y del content
            const json=JSON.parse(response);
            if(json.alert=='edit'){

                $('#avatar-modal').attr('src',json.ruta);
                $('#edit').hide('slow');
                $('#edit').show(1000);
                $('#edit').hide(3000);
                $('#form-avatar').trigger('reset');
                $('#avatar-modal-avatar').attr('src',json.ruta);
                buscar_usuario(id_usuario);
            }else{
                $('#noedit').hide('slow');
                $('#noedit').show(1000);
                $('#noedit').hide(3000);
                $('#form-avatar').trigger('reset');
            }
        });
        e.preventDefault();
    })
})