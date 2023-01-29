<?php
session_start();
//solo se permite a esta seccion al usuario root --> 3, sino se vuelve a login
if($_SESSION['us_tipo']==3){
    include_once 'layouts/header.php';
    ?>

  <title>Adm | Gestión compras</title>

<?php include_once 'layouts/nav.php';?>

<!-- Modal -->
<div class="modal fade" id="vistaCompra" tabindex="-1" role="dialog" aria-labelledby="vistaCompra" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Detalle de compra</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="codigo_compra">Código compra: </label>
            <span id="codigo_compra"></span>
          </div>
          <div class="form-group">
            <label for="fecha_compra">Fecha de compra: </label>
            <span id="fecha_compra"></span>
          </div>
          <div class="form-group">
            <label for="fecha_entrega">Fecha de entrega: </label>
            <span id="fecha_entrega"></span>
          </div>
          <div class="form-group">
            <label for="estado_compra_vista">Estado: </label>
            <span id="estado_compra_vista"></span>
          </div>
          <div class="form-group">
            <label for="proveedor">Proveedor: </label>
            <span id="proveedor"></span>
          </div>
          <table class="table table-hover text-nowrap table-responsive">
            <thead class="table-success">
              <tr>
                <th>0</th>
                <th>Código</th>
                <th>Cantidad</th>
                <th>Vencimiento</th>
                <th>Precio</th>
                <th>Producto</th>
                <th>Laboratorio</th>
                <th>Presentación</th>
                <th>Tipo</th>
              </tr>
            </thead>
            <tbody class="table-warning" id="detalles">

            </tbody>
          </table>
            <div class="float-right input-group-append">
              <h3 class="m-3">Total:</h3>
              <h3 class="m-3" id="total"></h3>
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

<div class="modal fade" id="cambiarEstado" tabindex="-1" role="dialog" aria-labelledby="cambiar-estado" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Cambiar estado</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="alert alert-danger text-center" id='noedit' style='display:none'>
              <span><i class="fas fa-times m-1"></i>No se pudo editar</span>
          </div>
          <div class="alert alert-success text-center" id='edit' style='display:none'>
              <span><i class="fas fa-check m-1"></i>Estado cambiado</span>
          </div>
          <form id="form-editar">
            <div class="form-group">
              <label for="estado_compra">Estado</label>
              <select name="estado_compra" id="estado_compra" class="form-control select2" style="width: 100%"></select>
              <input type="hidden" id="id_compra">
            </div>
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
            <h1>Gestión compras <a href="adm_nueva_compra.php" class="btn bg-gradient-primary ml-2">Nueva compra</a></h1>
            
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="adm_catalogo.php">Home</a></li>
              <li class="breadcrumb-item active">Gestión compras</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section>
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Buscar lote</h3>
                    <div class="input-group">
                        <input type="text" id="buscar-lote" placeholder="Nombre de producto"class="form-control float-left">
                        <div class="input-group-append">
                            <button class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                  <table id="compras" class="table table-dark table-hover">
                    <thead>
                      <tr>
                        <th>0</th>
                        <th>ID | Código</th>
                        <th>Fecha de compra</th>
                        <th>Fecha de entrega</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Proveedor</th>
                        <th>Opciones</th>
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
<script src="../js/compras.js"></script>