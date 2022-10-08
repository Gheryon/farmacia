$(document).ready(function() {
    //buscar_lab() justo al principio hace que automáticamente se muestren todos los laboratorios al usuario
    buscar_lab();
    var funcion;
    var edit=false;

    $('#form-crear-laboratorio').submit(e=>{
        let nombre_laboratorio=$('#nombre-laboratorio').val();
        let id_editado=$('#id_editar_lab').val();
        //si edit es false, se crea un laboratorio, si es true, se modifica, asi se puede usar el mismo modal para crear y editar laboratorio
        if(edit==false){
            funcion='crear';
        }else{
            funcion='editar';
        }
        $.post('../controlador/laboratorioController.php', {nombre_laboratorio, id_editado, funcion}, (response)=>{
            //console.log(response);
            if(response=='add'){
                $('#add-laboratorio').hide('slow');
                $('#add-laboratorio').show(1000);
                $('#add-laboratorio').hide(3000);
                //resetea los campos de la card
                $('#form-crear-laboratorio').trigger('reset');
                buscar_lab();
            }
            if(response=='noadd'){
                $('#noadd-laboratorio').hide('slow');
                $('#noadd-laboratorio').show(1000);
                $('#noadd-laboratorio').hide(3000);
                //resetea los campos de la card
                $('#form-crear-laboratorio').trigger('reset');
            }
            if(response=='edit'){
                $('#edit-laboratorio').hide('slow');
                $('#edit-laboratorio').show(1000);
                $('#edit-laboratorio').hide(3000);
                //resetea los campos de la card
                $('#form-crear-laboratorio').trigger('reset');
                buscar_lab();
            }
            edit=false;
        })
        e.preventDefault();
    });

    function buscar_lab(consulta){
        funcion='buscar';
        $.post('../controlador/laboratorioController.php', {consulta, funcion}, (response)=>{
            const laboratorios = JSON.parse(response);
            let template='';
            laboratorios.forEach(laboratorio => {
                template+=`
                    <tr labId="${laboratorio.id}" labNombre="${laboratorio.nombre}" labAvatar="${laboratorio.avatar}">
                        <td>
                            <button class="avatar btn btn-info" title="Cambiar logo" type="button" data-toggle="modal" data-target="#cambiar-logo-lab"><i class="far fa-image"></i></button>
                            <button class="editar btn btn-success" title="Editar laboratorio" type="button" data-toggle="modal" data-target="#crearLaboratorio"><i class="fas fa-pencil-alt"></i></button>
                            <button class="borrar btn btn-danger" title="Borrar laboratorio"><i class="fas fa-trash"></i></button>
                        </td>
                        <td><img src="${laboratorio.avatar}" class="img-fluid rounded" width="70" height="70"></td>
                        <td>"${laboratorio.nombre}"</td>
                    </tr>
                `;
            });
            $('#laboratorios').html(template);
        })
    }
    //con el atributo .on, se ejecuta cada vez que se pulsa una tecla
    $(document).on('keyup', '#buscar-laboratorio', function(){
        let valor = $(this).val();
        if(valor!='')
        {
            buscar_lab(valor);
        }else{
            buscar_lab();
        }
    });

    $(document).on('click', '.avatar', (e)=>{
        funcion="cambiar_logo";
        //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
        const elemento=$(this)[0].activeElement.parentElement.parentElement;
        const id=$(elemento).attr('labid');
        const nombre=$(elemento).attr('labnombre');
        const avatar=$(elemento).attr('labavatar');
        $('#logoactual').attr('src',avatar);
        $('#nombre_logo').html(nombre);
        $('#funcion').val(funcion);
        $('#id_logo_lab').val(id);
    });

    $('#form-logo-lab').submit(e=>{
        let formData = new FormData($('#form-logo-lab')[0]);
        $.ajax({
            url:'../controlador/laboratorioController.php',
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
                buscar_lab();
                $('#logoactual').attr('src',json.ruta);
                $('#edit').hide('slow');
                $('#edit').show(1000);
                $('#edit').hide(3000);
                $('#form-logo-lab').trigger('reset');
                //$('#avatar-modal-avatar').attr('src',json.ruta);
                buscar_usuario(id_usuario);
            }else{
                $('#noedit').hide('slow');
                $('#noedit').show(1000);
                $('#noedit').hide(3000);
                $('#form-logo-lab').trigger('reset');
            }
        });
        e.preventDefault();
    })
    $(document).on('click', '.borrar', (e)=>{
        funcion="borrar";
        //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
        const elemento=$(this)[0].activeElement.parentElement.parentElement;
        const id=$(elemento).attr('labid');
        const nombre=$(elemento).attr('labnombre');
        const avatar=$(elemento).attr('labavatar');

        //https://sweetalert2.github.io para más informacion
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger mr-1'
            },
            buttonsStyling: false
          })
          
          swalWithBootstrapButtons.fire({
            title: '¿Seguro de eliminar '+nombre+'?',
            text: "La acción no podrá deshacerse",
            imageUrl: ''+avatar+'',
            imageWidth: 100,
            imageHeight: 100,
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No eliminar',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controlador/laboratorioController.php', {id, funcion}, (response)=>{
                    edit=false;
                    if(response=='borrado'){
                        swalWithBootstrapButtons.fire(
                          'Eliminado',
                          'El laboratorio '+nombre+' ha sido eliminado.',
                          'success'
                        )
                        
                    }else{
                        swalWithBootstrapButtons.fire(
                          'No se pudo borrar',
                          'El laboratorio '+nombre+' no se ha borrado porque lo está usando un producto',
                          'error'
                        )
                    }
                })
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
              swalWithBootstrapButtons.fire(
                'Cancelado',
                'El laboratorio '+nombre+' no se ha borrado',
                'error'
              )
            }
          })
    });

    $(document).on('click', '.editar', (e)=>{
        //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
        const elemento=$(this)[0].activeElement.parentElement.parentElement;
        const id=$(elemento).attr('labid');
        const nombre=$(elemento).attr('labnombre');
        $('#id_editar_lab').val(id);
        $('#nombre-laboratorio').val(nombre);
        edit=true;
    });
});