$(document).ready(function() {
  buscar_clientes();
  var funcion='';

  function buscar_clientes(consulta) {
    funcion="buscar";
    $.post('../controlador/clienteController.php', {funcion, consulta}, (response)=>{
      console.log(response);
      const clientes=JSON.parse(response);
      let template='';
      clientes.forEach(cliente => {
        template+=`
        <div clNombre="${cliente.nombre}" clId="${cliente.id}" clTelefono="${cliente.telefono}"clCorreo="${cliente.correo}" clAvatar="${cliente.avatar}" clAdicional="${cliente.adicional}" class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
        <div class="card bg-light d-flex flex-fill">
          <div class="card-header text-muted border-bottom-0">
          <h1 class="badge badge-success">cliente</h1>
          </div>
          <div class="card-body pt-0">
            <div class="row">
              <div class="col-7">
                <h2 class="lead"><b>${cliente.nombre}</b></h2>
                <ul class="ml-4 mb-0 fa-ul text-muted">
                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> DNI: ${cliente.dni}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Edad: ${cliente.edad}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Teléfono:${cliente.telefono}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> Correo: ${cliente.correo}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Sexo: ${cliente.sexo}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Adicional: ${cliente.adicional}</li>
                </ul>
              </div>
              <div class="col-5 text-center">
                <img src="${cliente.avatar}" alt="user-avatar" class="img-circle img-fluid">
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="text-right">
              <button class="editar btn btn-sm btn-success" title="Editar" type="button" data-toggle="modal" data-target="#editar-cliente">
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
      $('#clientes').html(template);
    });
  }

  $(document).on('keyup','#buscar-cliente',function(){
    let valor = $(this).val();
    if(valor!=""){
        buscar_clientes(valor);
    }else{
        buscar_clientes();
    }
  });

  $('#form-crear-cliente').submit(e=>{
    let nombre=$('#nombre').val();
    let apellidos=$('#apellidos').val();
    let dni=$('#dni').val();
    let edad=$('#edad').val();
    let telefono=$('#telefono').val();
    let correo=$('#correo').val();
    let sexo=$('#sexo').val();
    let adicional=$('#adicional').val();
    funcion='crear';
    $.post('../controlador/clienteController.php', {nombre, apellidos, dni, edad, telefono, correo, sexo, adicional, funcion}, (response)=>{
      console.log(response);
      if(response=='add'){
        $('#add-prov').hide('slow');
        $('#add-prov').show(1000);
        $('#add-prov').hide(3000);
        buscar_clientes();
      }
      if(response=='noadd'){
        $('#noadd-prov').hide('slow');
        $('#noadd-prov').show(1000);
        $('#noadd-prov').hide(3000);
      }
      $('#form-crear-proveedor').trigger('reset');
    });
    e.preventDefault();
  });

  $(document).on('click', '.editar', (e)=>{
    let elemento=$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
    let telefono=$(elemento).attr('clTelefono');
    let correo=$(elemento).attr('clCorreo');
    let adicional=$(elemento).attr('clAdicional');
    let id=$(elemento).attr('clId');

    $('#telefono_edit').val(telefono);
    $('#correo_edit').val(correo);
    $('#adicional_edit').val(adicional);
    $('#id_edit_cliente').val(id);
  });

  $('#form-editar-cliente').submit(e=>{
    let telefono=$('#telefono_edit').val();
    let correo=$('#correo_edit').val();
    let id=$('#id_edit_cliente').val();
    let adicional=$('#adicional_edit').val();
    funcion='editar';
    $.post('../controlador/clienteController.php', {id, telefono, correo, adicional, funcion}, (response)=>{
      console.log(response);
      if(response=='edit'){
        $('#edit').hide('slow');
        $('#edit').show(1000);
        $('#edit').hide(3000);
        buscar_clientes();
      }
      if(response=='noedit'){
        $('#noedit').hide('slow');
        $('#noedit').show(1000);
        $('#noedit').hide(3000);
      }
      $('#form-editar-cliente').trigger('reset');
    });
    e.preventDefault();
  });

  $(document).on('click', '.borrar', (e)=>{
    funcion="borrar";
    let elemento =$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
    let id=$(elemento).attr('clId');
    let nombre=$(elemento).attr('clNombre');
    let avatar=$(elemento).attr('clAvatar');

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
        $.post('../controlador/clienteController.php', {id, funcion}, (response)=>{
          if(response=='borrado'){
            swalWithBootstrapButtons.fire(
              'Eliminado',
              'El cliente '+nombre+' ha sido eliminado.',
              'success'
            )
            buscar_clientes();
          }else{
            swalWithBootstrapButtons.fire(
              'No se pudo borrar',
              'El cliente '+nombre+' no se ha borrado porque es necesario para un lote',
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
          'El cliente '+nombre+' no se ha borrado',
          'error'
        )
      }
    })
});  
})