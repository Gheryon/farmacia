$(document).ready(function() {
    //buscar_pre() justo al principio hace que automáticamente se muestren todos los laboratorios al usuario
    buscar_pre();
    var funcion;
    var edit=false;

    $('#form-crear-presentacion').submit(e=>{
        let nombre_presentacion=$('#nombre-presentacion').val();
        let id_editado=$('#id_editar_pre').val();
        //si edit es false, se crea un laboratorio, si es true, se modifica, asi se puede usar el mismo modal para crear y editar laboratorio
        if(edit==false){
            funcion='crear';
        }else{
            funcion='editar';
        }
        $.post('../controlador/presentacionController.php', {nombre_presentacion, id_editado, funcion}, (response)=>{
            console.log(response);
            if(response=='add'){
                $('#add-pre').hide('slow');
                $('#add-pre').show(1000);
                $('#add-pre').hide(3000);
                //resetea los campos de la card
                $('#form-crear-presentacion').trigger('reset');
                buscar_pre();
            }
            if(response=='noadd'){
                $('#noadd-pre').hide('slow');
                $('#noadd-pre').show(1000);
                $('#noadd-pre').hide(3000);
                //resetea los campos de la card
                $('#form-crear-presentacion').trigger('reset');
            }
            if(response=='edit'){
                $('#edit-pre').hide('slow');
                $('#edit-pre').show(1000);
                $('#edit-pre').hide(3000);
                //resetea los campos de la card
                $('#form-crear-presentacion').trigger('reset');
                buscar_pre();
            }
            edit=false;
        })
        e.preventDefault();
    });

    function buscar_pre(consulta){
        funcion='buscar';
        $.post('../controlador/presentacionController.php', {consulta, funcion}, (response)=>{
            const presentaciones = JSON.parse(response);
            let template='';
            presentaciones.forEach(presentacion => {
                template+=`
                    <tr preId="${presentacion.id}" preNombre="${presentacion.nombre}">
                        <td>
                            <button class="editar-pre btn btn-success" title="Editar presentación" type="button" data-toggle="modal" data-target="#crearPresentacion"><i class="fas fa-pencil-alt"></i></button>
                            <button class="borrar-pre btn btn-danger" title="Borrar presentación"><i class="fas fa-trash"></i></button>
                        </td>
                        <td>"${presentacion.nombre}"</td>
                    </tr>
                `;
            });
            $('#presentaciones').html(template);
        })
    }
    //con el atributo .on, se ejecuta cada vez que se pulsa una tecla
    $(document).on('keyup', '#buscar-presentacion', function(){
        let valor = $(this).val();
        if(valor!='')
        {
            buscar_pre(valor);
        }else{
            buscar_pre();
        }
    });
    $(document).on('click', '.borrar-pre', (e)=>{
        funcion="borrar";
        //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
        const elemento=$(this)[0].activeElement.parentElement.parentElement;
        const id=$(elemento).attr('preId');
        const nombre=$(elemento).attr('preNombre');

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
                $.post('../controlador/presentacionController.php', {id, funcion}, (response)=>{
                    edit=false;
                    if(response=='borrado'){
                        swalWithBootstrapButtons.fire(
                          'Eliminado',
                          'La presentación '+nombre+' ha sido eliminada.',
                          'success'
                        )
                        buscar_pre();
                        
                    }else{
                        swalWithBootstrapButtons.fire(
                          'No se pudo borrar',
                          'La presentación '+nombre+' no se ha borrado porque lo está usando un producto',
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
                'La presentación '+nombre+' no se ha borrado',
                'error'
              )
            }
          })
    });

    $(document).on('click', '.editar-pre', (e)=>{
        //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
        const elemento=$(this)[0].activeElement.parentElement.parentElement;
        const id=$(elemento).attr('preId');
        const nombre=$(elemento).attr('preNombre');
        $('#id_editar_pre').val(id);
        $('#nombre-presentacion').val(nombre);
        edit=true;
    });
});