$(document).ready(function() {
    //buscar_tipo() justo al principio hace que automáticamente se muestren todos los laboratorios al usuario
    buscar_tipo();
    var funcion;
    var edit=false;

    $('#form-crear-tipo').submit(e=>{
        let nombre_tipo=$('#nombre-tipo').val();
        let id_editado=$('#id_editar_tipo').val();
        //si edit es false, se crea un laboratorio, si es true, se modifica, asi se puede usar el mismo modal para crear y editar laboratorio
        if(edit==false){
            funcion='crear';
        }else{
            funcion='editar';
        }
        $.post('../controlador/tipoController.php', {nombre_tipo, id_editado, funcion}, (response)=>{
            console.log(response);
            if(response=='add'){
                $('#add-tipo').hide('slow');
                $('#add-tipo').show(1000);
                $('#add-tipo').hide(3000);
                //resetea los campos de la card
                $('#form-crear-tipo').trigger('reset');
                buscar_tipo();
            }
            if(response=='noadd'){
                $('#noadd-tipo').hide('slow');
                $('#noadd-tipo').show(1000);
                $('#noadd-tipo').hide(3000);
                //resetea los campos de la card
                $('#form-crear-tipo').trigger('reset');
            }
            if(response=='edit'){
                $('#edit-tipo').hide('slow');
                $('#edit-tipo').show(1000);
                $('#edit-tipo').hide(3000);
                //resetea los campos de la card
                $('#form-crear-tipo').trigger('reset');
                buscar_tipo();
            }
            edit=false;
        })
        e.preventDefault();
    });

    function buscar_tipo(consulta){
        funcion='buscar';
        $.post('../controlador/tipoController.php', {consulta, funcion}, (response)=>{
            const tipos = JSON.parse(response);
            let template='';
            tipos.forEach(tipo => {
                template+=`
                    <tr tipoId="${tipo.id}" tipoNombre="${tipo.nombre}">
                        <td>
                            <button class="editar-tipo btn btn-success" title="Editar laboratorio" type="button" data-toggle="modal" data-target="#crearTipo"><i class="fas fa-pencil-alt"></i></button>
                            <button class="borrar-tipo btn btn-danger" title="Borrar laboratorio"><i class="fas fa-trash"></i></button>
                        </td>
                        <td>"${tipo.nombre}"</td>
                    </tr>
                `;
            });
            $('#tipos').html(template);
        })
    }
    //con el atributo .on, se ejecuta cada vez que se pulsa una tecla
    $(document).on('keyup', '#buscar-tipo', function(){
        let valor = $(this).val();
        if(valor!='')
        {
            buscar_tipo(valor);
        }else{
            buscar_tipo();
        }
    });
    $(document).on('click', '.borrar-tipo', (e)=>{
        funcion="borrar";
        //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
        const elemento=$(this)[0].activeElement.parentElement.parentElement;
        const id=$(elemento).attr('tipoid');
        const nombre=$(elemento).attr('tiponombre');

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
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No eliminar',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
                $.post('../controlador/tipoController.php', {id, funcion}, (response)=>{
                    edit=false;
                    if(response=='borrado'){
                        swalWithBootstrapButtons.fire(
                          'Eliminado',
                          'El tipo '+nombre+' ha sido eliminado.',
                          'success'
                        )
                        buscar_tipo();
                        
                    }else{
                        swalWithBootstrapButtons.fire(
                          'No se pudo borrar',
                          'El tipo '+nombre+' no se ha borrado porque lo está usando un producto',
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
                'El tipo '+nombre+' no se ha borrado',
                'error'
              )
            }
          })
    });

    $(document).on('click', '.editar-tipo', (e)=>{
        //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
        const elemento=$(this)[0].activeElement.parentElement.parentElement;
        const id=$(elemento).attr('tipoId');
        const nombre=$(elemento).attr('tipoNombre');
        $('#id_editar_tipo').val(id);
        $('#nombre-tipo').val(nombre);
        edit=true;
    });
});