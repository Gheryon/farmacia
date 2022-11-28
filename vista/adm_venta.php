<?php
session_start();
//solo se permite a esta seccion al usuario root --> 3, y al daministrador -->1 sino se vuelve a login
if($_SESSION['us_tipo']==3||$_SESSION['us_tipo']==1){
    include_once 'layouts/header.php';
    ?>

  <title>Adm | Gestión ventas</title>

<?php include_once 'layouts/nav.php';?>

<!-- Modal -->
<div class="modal fade" id="vista-venta" tabindex="-1" role="dialog" aria-labelledby="vista-venta" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Registros de venta</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="codigo_venta">Código venta: </label>
            <span id="codigo_venta"></span>
          </div>
          <div class="form-group">
            <label for="fecha_venta">Fecha: </label>
            <span id="fecha_venta"></span>
          </div>
          <div class="form-group">
            <label for="cliente_venta">Cliente: </label>
            <span id="cliente_venta"></span>
          </div>
          <div class="form-group">
            <label for="dni_cliente_venta">Dni: </label>
            <span id="dni_cliente_venta"></span>
          </div>
          <div class="form-group">
            <label for="vendedor_venta">Vendedor: </label>
            <span id="vendedor_venta"></span>
          </div>
          <table class="table table-hover text-nowrap">
            <thead class="table-success">
              <tr>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Producto</th>
                <th>concentracion</th>
                <th>Adicional</th>
                <th>Laboratorio</th>
                <th>Presentacion</th>
                <th>Tipo</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody class="table-warning" id="registros">

            </tbody>
          </table>
            <div class="float-right input-group-append">
              <h3 class="m-3">Total:</h3>
              <h3 class="m-3" id="total_venta"></h3>
            </div>
        </div>
        <div class="card-footer">
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
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
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