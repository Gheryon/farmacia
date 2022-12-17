$(document).ready(function() {
    var funcion;
    var edit=false;
    buscar_prov();

    $('#form-crear-proveedor').submit(e=>{
        let id=$('#id_edit_prov').val();
        let nombre=$('#nombre').val();
        let telefono=$('#telefono').val();
        let correo=$('#correo').val();
        let direccion=$('#direccion').val();
        if(edit==true){
            funcion='editar'
        }else{
            funcion='crear';
        }

        $.post('../controlador/proveedorController.php', {id, nombre, telefono, correo, direccion, funcion}, (response)=>{
            console.log(response);
            if(response=='add'){
                $('#add-prov').hide('slow');
                $('#add-prov').show(1000);
                $('#add-prov').hide(3000);
                buscar_prov();
            }
            if(response=='edit'){
                $('#edit-prov').hide('slow');
                $('#edit-prov').show(1000);
                $('#edit-prov').hide(3000);
                buscar_prov();
            }
            if(response=='noadd' || response=='noedit'){
                $('#noadd-prov').hide('slow');
                $('#noadd-prov').show(1000);
                $('#noadd-prov').hide(3000);
            }
            $('#form-crear-proveedor').trigger('reset');
            edit=false;
        });
        e.preventDefault();
    });

    function buscar_prov(consulta) {
        funcion="buscar";
        $.post('../controlador/proveedorController.php', {funcion, consulta}, (response)=>{
            const proveedores=JSON.parse(response);
            let template='';
            proveedores.forEach(proveedor => {
                template+=`
                <div provId="${proveedor.id}" provNombre="${proveedor.nombre}" provTelefono="${proveedor.telefono}" provCorreo="${proveedor.correo}" provDireccion="${proveedor.direccion}" provAvatar="${proveedor.avatar}" class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
              <div class="card bg-light d-flex flex-fill">
                <div class="card-header text-muted border-bottom-0">
                <h1 class="badge badge-success">Proveedor</h1>
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>${proveedor.nombre}</b></h2>
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Dirección: ${proveedor.direccion}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Teléfono:${proveedor.telefono}</li>
                        <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> Correo: ${proveedor.correo}</li>
                      </ul>
                    </div>
                    <div class="col-5 text-center">
                      <img src="${proveedor.avatar}" alt="user-avatar" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-right">
                    <button class="avatar btn btn-sm bg-teal" title="Cambiar logo" type="button" data-toggle="modal" data-target="#cambiar-logo-prov">
                      <i class="fas fa-image"></i>
                    </button>
                    <button class="editar btn btn-sm btn-success" title="Editar" type="button" data-toggle="modal" data-target="#crear-proveedor">
                      <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button class="borrar btn btn-sm btn-danger" title="Borrar">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
                `;
            });
            $('#proveedores').html(template);
        });
    }

    $(document).on('keyup','#buscar-proveedor',function(){
        let valor = $(this).val();
        if(valor!=""){
            buscar_producto(valor);
        }else{
            buscar_producto();
        }
    });

    $(document).on('click', '.avatar', (e)=>{
        funcion="cambiar_avatar";
        const elemento =$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id=$(elemento).attr('provId');
        const avatar=$(elemento).attr('provAvatar');
        const nombre=$(elemento).attr('provNombre');
        $('#funcion').val(funcion);
        $('#id_logo_prov').val(id);
        $('#avatar').val(avatar);
        $('#logo-actual').attr('src', avatar);
        $('#nombre-logo').html(nombre);
    });

    $(document).on('click', '.editar', (e)=>{
        const elemento =$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id=$(elemento).attr('provId');
        const nombre=$(elemento).attr('provNombre');
        const direccion=$(elemento).attr('provDireccion');
        const telefono=$(elemento).attr('provTelefono');
        const correo=$(elemento).attr('provCorreo');
        $('#id_edit_prov').val(id);
        $('#nombre').val(nombre);
        $('#direccion').val(direccion);
        $('#telefono').val(telefono);
        $('#correo').val(correo);
        edit=true;
    });

    $('#form-logo-prov').submit(e=>{
        let formData = new FormData($('#form-logo-prov')[0]);
        $.ajax({
            url:'../controlador/proveedorController.php',
            type:'POST',
            data:formData,
            cache:false,
            processData:false,
            contentType:false
        }).done(function(response){
            console.log(response);
            const json= JSON.parse(response);
            if(json.alert=='edit')
            {
                $('#logo-actual').attr('src', json.ruta);
                $('#edit').hide('slow');
                $('#edit').show(1000);
                $('#edit').hide(3000);
                $('#form-logo-prod').trigger('reset');
                buscar_prov();
            }else{
                $('#noedit').hide('slow');
                $('#noedit').show(1000);
                $('#noedit').hide(3000);
                $('#form-logo-prod').trigger('reset');
            }
        });
        e.preventDefault();
    });

    $(document).on('click', '.borrar', (e)=>{
        funcion="borrar";
        //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
        const elemento =$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id=$(elemento).attr('provId');
        const nombre=$(elemento).attr('provNombre');
        const avatar=$(elemento).attr('provAvatar');

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
                $.post('../controlador/proveedorController.php', {id, funcion}, (response)=>{
                    //edit=false;
                    if(response=='borrado'){
                        swalWithBootstrapButtons.fire(
                          'Eliminado',
                          'El proveedor '+nombre+' ha sido eliminado.',
                          'success'
                        )
                        buscar_prov();
                    }else{
                        swalWithBootstrapButtons.fire(
                          'No se pudo borrar',
                          'El proveedor '+nombre+' no se ha borrado porque es necesario para un lote',
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
                'El proveedor '+nombre+' no se ha borrado',
                'error'
              )
            }
          })
    });  
});