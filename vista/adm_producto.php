<?php
session_start();
//si tipo==1, entonces es administrador, si es 3 es root, sino se vuelve a login
if($_SESSION['us_tipo']==1||$_SESSION['us_tipo']==3){
    include_once 'layouts/header.php';
    ?>

  <title>Adm | Gestión producto</title>

<?php include_once 'layouts/nav.php';?>

<!-- Modal -->
<div class="modal fade" id="crear-producto" tabindex="-1" role="dialog" aria-labelledby="cambio-contrasena" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Crear producto</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="alert alert-success text-center" id='add' style='display:none'>
              <span><i class="fas fa-check m-1"></i>Producto añadido</span>
          </div>
          <div class="alert alert-danger text-center" id='noadd' style='display:none'>
              <span><i class="fas fa-times m-1"></i>El producto ya existe en el sistema</span>
          </div>
          <div class="alert alert-success text-center" id='edit' style='display:none'>
              <span><i class="fas fa-check m-1"></i>El producto ha sido editado</span>
          </div>
          <form id="form-crear-producto">
            <div class="form-group">
              <label for="nombre-producto">Nombre</label>
              <input id="nombre-producto" type="text" class="input form-control" placeholder="Introduce nombre" required>
            </div>
            <div class="form-group">
              <label for="concentracion">Concentración</label>
              <input id="concentracion" type="text" class="input form-control" placeholder="Introduce concentración">
            </div>
            <div class="form-group">
              <label for="adicional">Adicional</label>
              <input id="adicional" type="text" class="input form-control" placeholder="Introduce información adicional">
            </div>
            <div class="form-group">
              <label for="precio">Precio</label>
              <input id="precio" type="number" step="any" class="input form-control" value='1' placeholder="Introduce precio" required>
            </div>
            <div class="form-group">
              <label for="laboratorio">Laboratorio</label>
              <select name="laboratorio" id="laboratorio" class="form-control select2" style="width: 100%"></select>
            </div>
            <div class="form-group">
              <label for="tipo">Tipo</label>
              <select name="tipo" id="tipo" class="form-control select2" style="width: 100%"></select>
            </div>
            <div class="form-group">
              <label for="presentacion">Presentación</label>
              <select name="presentacion" id="presentacion" class="form-control select2" style="width: 100%"></select>
            </div>
            <input type="hidden" id="id_edit_prod">
        </div>
        <div class="card-footer">
          <button type="submit" class="btn bg-gradient-primary float-right m-1">Guardar</button>
          <button type="button" data-dismiss="modal" class="btn btn-outline-secondary float-right m-1">Cerrar</button>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="cambiar-logo-prod" tabindex="-1" role="dialog" aria-labelledby="cambio-contrasena" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cambiar-logo-lab">Cambiar logo del producto</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
            <img id="logo-actual" src="../img/avatar2.jpg" alt="imagen-perfil" class="profile-user-image img-fluid img-circle">
        </div>
        <div class="text-center">
            <b id="nombre-logo"></b>
        </div>
        <div class="alert alert-success text-center" id='edit' style='display:none'>
            <span><i class="fas fa-check m-1"></i>Logo cambiado</span>
        </div>
        <div class="alert alert-danger text-center" id='noedit' style='display:none'>
            <span><i class="fas fa-times m-1"></i>No se pudo cambiar el logo, archivo no soportado</span>
        </div>
        <form id="form-logo-prod" enctype="multipart/form-data">
            <div class="input-group mb-3 ml-3 mt-2">
                <input type="file" name="avatar" class="input-group">
                <input type="hidden" name="funcion" id="funcion">
                <input type="hidden" name="id_logo_prod" id="id_logo_prod">
                <input type="hidden" name="avatar" id="avatar">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn bg-gradient-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="crear-reporte-pdf" tabindex="-1" role="dialog" aria-labelledby="cambio-contrasena" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Elegir formato de reporte</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="form-group text-center">
            <button id="button-reporte-productos" class="btn btn-outline-danger">Formato PDF <i class="far fa-file-pdf ml-2"></i></button>
            <button class="btn btn-outline-success">Formato EXCEL <i class="far fa-file-excel ml-2"></i></button>
          </div>
        </div>
        <div class="card-footer">
          
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-8">
            <h1>Gestión producto <button id="button-crear-producto" type="button" data-toggle="modal" data-target="#crear-producto" class="btn bg-gradient-primary ml-2">Crear producto</button> <button type="button" data-toggle="modal" data-target="#crear-reporte-pdf" class="btn bg-gradient-success ml-2">Reporte de productos</button></h1>
            
          </div>
          <div class="col-sm-4">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="adm_catalogo.php">Home</a></li>
              <li class="breadcrumb-item active">Gestión producto</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section>
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Buscar producto</h3>
                    <div class="input-group">
                        <input type="text" id="buscar-producto" placeholder="Nombre de producto"class="form-control float-left">
                        <div class="input-group-append">
                            <button class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div id="productos" class="row d-flex align-items-stretch">

                    </div>
                  </div>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </section>
  </div>
  <!-- /.content-wrapper -->

<?php
include_once 'layouts/footer.php';
}
else{
    header('Location: ../index.php');
}
?>
<script src="../js/producto.js"></script>