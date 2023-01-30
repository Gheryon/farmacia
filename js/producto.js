$(document).ready(function() {
  var funcion;
  var edit=false;
  $('.select2').select2();
  rellenar_laboratorios();
  rellenar_tipos();
  rellenar_presentaciones();
  rellenar_proveedores();
  buscar_producto();

  function rellenar_laboratorios() {
      funcion="rellenar_laboratorios";
      $.post('../controlador/laboratorioController.php', {funcion}, (response)=>{
          const laboratorios= JSON.parse(response);
          let template='';
          laboratorios.forEach(laboratorio => {
              template+=`
              <option value="${laboratorio.id}">${laboratorio.nombre}</option>
              `;
          });
          $('#laboratorio').html(template);
      })
  }

  function rellenar_tipos() {
      funcion="rellenar_tipos";
      $.post('../controlador/tipoController.php', {funcion}, (response)=>{
          const tipos= JSON.parse(response);
          let template='';
          tipos.forEach(tipo => {
              template+=`
              <option value="${tipo.id}">${tipo.nombre}</option>
              `;
          });
          $('#tipo').html(template);
      })
  }

  function rellenar_presentaciones() {
      funcion="rellenar_presentaciones";
      $.post('../controlador/presentacionController.php', {funcion}, (response)=>{
          const presentaciones= JSON.parse(response);
          let template='';
          presentaciones.forEach(presentacion => {
              template+=`
              <option value="${presentacion.id}">${presentacion.nombre}</option>
              `;
          });
          $('#presentacion').html(template);
      })
  }

  function rellenar_proveedores() {
    funcion="rellenar_proveedores";
    $.post('../controlador/proveedorController.php', {funcion}, (response)=>{
        const proveedores= JSON.parse(response);
        let template='';
        proveedores.forEach(proveedor => {
            template+=`
            <option value="${proveedor.id}">${proveedor.nombre}</option>
            `;
        });
        $('#proveedor').html(template);
    })
  }

  $('#form-crear-producto').submit(e=>{
    let id=$('#id_edit_prod').val();
    let nombre=$('#nombre-producto').val();
    let concentracion=$('#concentracion').val();
    let adicional=$('#adicional').val();
    let precio=$('#precio').val();
    let laboratorio=$('#laboratorio').val();
    let tipo=$('#tipo').val();
    let presentacion=$('#presentacion').val();
    if(edit==true){
        funcion='editar';
    }else{
        funcion="crear";
    }
    $.post('../controlador/productoController.php', {funcion, id, nombre, concentracion, adicional, precio, laboratorio, tipo, presentacion}, (response)=>{
      if(response=='add'){
        $('#add').hide('slow');
        $('#add').show(1000);
        $('#add').hide(3000);
        $('#laboratorio').val().trigger('change');
        $('#tipo').val().trigger('change');
        $('#presentacion').val().trigger('change');
        buscar_producto();
      }
      if(response=='edit'){
        $('#edit').hide('slow');
        $('#edit').show(1000);
        $('#edit').hide(3000);
        $('#laboratorio').val().trigger('change');
        $('#tipo').val().trigger('change');
        $('#presentacion').val().trigger('change');
        buscar_producto();
      }
      if(response=='noadd' || response=='noedit'){
        $('#noadd').hide('slow');
        $('#noadd').show(1000);
        $('#noadd').hide(3000);
      }
      //resetea los campos de la card
      $('#form-crear-producto').trigger('reset');
      edit=false;
    })
    e.preventDefault();
  });

  function buscar_producto(consulta) {
    funcion="buscar";
    $.post('../controlador/productoController.php', {funcion, consulta}, (response)=>{
      console.log(response);
        const productos=JSON.parse(response);
        let template='';
        productos.forEach(producto => {
          template+=`
          <div prodId="${producto.id}" prodNombre="${producto.nombre}" prodPrecio="${producto.precio}" prodConcentracion="${producto.concentracion}" prodAdicional="${producto.adicional}" prodLaboratorio="${producto.laboratorio_id}" prodTipo="${producto.tipo_id}" prodPresentacion="${producto.presentacion_id}" prodAvatar="${producto.avatar}" class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
        <div class="card bg-light d-flex flex-fill">
          <div class="card-header text-muted border-bottom-0">
          <i class="fas fa-lg fa-cubes mr-1"></i>${producto.stock}
          </div>
          <div class="card-body pt-0">
            <div class="row">
              <div class="col-7">
                <h2 class="lead"><b>${producto.nombre}</b></h2>
                <h4 class="lead"><b><i class="fas fa-lg fa-dollar-sign mr-1"></i>${producto.precio}</b></h4>
                <ul class="ml-4 mb-0 fa-ul text-muted">
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-mortar-pestle"></i></span> Concentración: ${producto.concentracion}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-prescription-bottle-alt"></i></span> Adicional: ${producto.adicional}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-flask"></i></span> Laboratorio: ${producto.laboratorio}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-copyright"></i></span> Tipo: ${producto.tipo}</li>
                  <li class="small"><span class="fa-li"><i class="fas fa-lg fa-pills"></i></span> Presentación: ${producto.presentacion}</li>
                </ul>
              </div>
              <div class="col-5 text-center">
                <img src="${producto.avatar}" alt="user-avatar" class="img-circle img-fluid">
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="text-right">
              <button class="avatar btn btn-sm bg-teal" title="Cambiar avatar" type="button" data-toggle="modal" data-target="#cambiar-logo-prod">
                <i class="fas fa-image"></i>
              </button>
              <button class="editar btn btn-sm btn-success" title="Editar" type="button" data-toggle="modal" data-target="#crear-producto">
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
      $('#productos').html(template);
    });
  }

  $(document).on('keyup','#buscar-producto',function(){
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
      const id=$(elemento).attr('prodId');
      const avatar=$(elemento).attr('prodAvatar');
      const nombre=$(elemento).attr('prodNombre');
      $('#funcion').val(funcion);
      $('#id_logo_prod').val(id);
      $('#avatar').val(avatar);
      $('#logo-actual').attr('src', avatar);
      $('#nombre-logo').html(nombre);
  });

  $('#form-logo-prod').submit(e=>{
      let formData = new FormData($('#form-logo-prod')[0]);
      $.ajax({
          url:'../controlador/productoController.php',
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
              buscar_producto();
          }else{
              $('#noedit').hide('slow');
              $('#noedit').show(1000);
              $('#noedit').hide(3000);
              $('#form-logo-prod').trigger('reset');
          }
      });
      e.preventDefault();
  });

  $(document).on('click', '.editar', (e)=>{
      const elemento =$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
      const id=$(elemento).attr('prodId');
      const nombre=$(elemento).attr('prodNombre');
      const concentracion=$(elemento).attr('prodConcentracion');
      const adicional=$(elemento).attr('prodAdicional');
      const precio=$(elemento).attr('prodPrecio');
      const laboratorio=$(elemento).attr('prodLaboratorio');
      const tipo=$(elemento).attr('prodTipo');
      const presentacion=$(elemento).attr('prodPresentacion');
      
      $('#id_edit_prod').val(id);
      $('#nombre-producto').val(nombre);
      $('#concentracion').val(concentracion);
      $('#adicional').val(adicional);
      $('#precio').val(precio);
      //.trigger('change'); para que se pueda hacer el cambio en los select
      $('#laboratorio').val(laboratorio).trigger('change');
      $('#tipo').val(tipo).trigger('change');
      $('#presentacion').val(presentacion).trigger('change');
      edit=true;
  });

  $(document).on('click', '.borrar', (e)=>{
      funcion="borrar";
      //se usan 2 parentElement para llegar al tr desde el button #cambiar-logo-lab en el que se hace click
      const elemento =$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
      const id=$(elemento).attr('prodId');
      const nombre=$(elemento).attr('prodNombre');
      const avatar=$(elemento).attr('prodAvatar');

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
              $.post('../controlador/productoController.php', {id, funcion}, (response)=>{
                console.log(response);
                  edit=false;
                  if(response=='borrado'){
                      swalWithBootstrapButtons.fire(
                        'Eliminado',
                        'El producto '+nombre+' ha sido eliminado.',
                        'success'
                      )
                      buscar_producto();
                  }else{
                      swalWithBootstrapButtons.fire(
                        'No se pudo borrar',
                        'El producto '+nombre+' no se ha borrado porque existe stock disponible.',
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
              'El producto '+nombre+' no se ha borrado',
              'error'
            )
          }
        })
  });

  $(document).on('click', '#button-reporte-productos', (e)=>{
    mostrar_loader('generar_reporte');
    funcion='reporte_productos';
    $.post('../controlador/productoController.php', {funcion}, (response)=>{
      if(response==''){
        cerrar_loader('exito_reporte');
        window.open('../pdf/pdf-'+funcion+'.pdf',"_blank");
      }else{
        cerrar_loader('error_reporte');
      }
    })
  });

  function mostrar_loader(mensaje){
    var texto=null;
    var mostrar=false;
    switch (mensaje) {
      case 'generar_reporte':
        texto='Generando reporte pdf, espere por favor...';
        mostrar=true;
        break;

    }
    if(mostrar==true){
      Swal.fire({
        title: 'Generando reporte pdf.',
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
      case 'exito_reporte':
        tipo='success';
        texto='Reporte pdf generado correctamente.';
        mostrar=true;
        break;

      case 'error_reporte':
        tipo='error';
        texto='No se pudo generar el reporte, vuelva a intentarlo.';
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
});