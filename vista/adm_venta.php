<?php
session_start();
//solo se permite a esta seccion al usuario root --> 3, y al daministrador -->1 sino se vuelve a login
if($_SESSION['us_tipo']==3||$_SESSION['us_tipo']==1){
    include_once 'layouts/header.php';
    ?>

  <title>Adm | Gestión ventas</title>

<?php include_once 'layouts/nav.php';?>

<!-- Modal -->
<div class="modal fade" id="editar-lote" tabindex="-1" role="dialog" aria-labelledby="cambio-contrasena" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Editar lote</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="alert alert-success text-center" id='edit-lote' style='display:none'>
              <span><i class="fas fa-check m-1"></i>Lote editado</span>
          </div>
          <form id="form-editar-lote">
            <div class="form-group">
              <label for="cogigo_lote">Código lote: </label>
              <label id="codigo_lote">Código lote</label>
            </div>
            <div class="form-group">
              <label for="stock">Stock</label>
              <input id="stock" type="number" class="input form-control" placeholder="Introduce stock" required>
            </div>
            <input type="hidden" id="id_lote_prod">
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gestión ventas
            
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="adm_catalogo.php">Home</a></li>
              <li class="breadcrumb-item active">Gestión ventas</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section>
      <div class="container-fluid">
        <div class="card card-success">
          <div class="card-header">
              <h3 class="card-title">Buscar ventas</h3>
              
          </div>
          <div class="card-body">
            <table id="tabla-venta" class="display table table-hover text-nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Fecha</th>
                  <th>Cliente</th>
                  <th>Dni</th>
                  <th>Total</th>
                  <th>Vendedor</th>
                </tr>
              </thead>
            </table>
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
<script src="../js/datatables.js"></script>
<script src="../js/venta.js"></script>