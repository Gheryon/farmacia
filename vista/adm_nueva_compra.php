<?php
session_start();
//solo se permite a esta seccion al usuario root --> 3, sino se vuelve a login
if($_SESSION['us_tipo']==3){
    include_once 'layouts/header.php';
    ?>

  <title>Adm | Nueva compra</title>

<?php include_once 'layouts/nav.php';?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Nueva compra </h1>
            
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="adm_catalogo.php">Home</a></li>
              <li class="breadcrumb-item active">Nueva compra</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section>
      <div class="container-fluid">
        <div class="card card-success">
          <div class="card-header">
              <h3 class="card-title">Crear compra</h3>
                
          </div>
          <div class="card-body row">
            <div class="card col-sm-3 p-3">
              <div class="alert alert-danger text-center" id="noadd-compra" style='display:none;'>
                <span id='error-compra'><i class="fas fa-times m-1"></i>no se agrego</span>
              </div>
              <form id="form-crear-compra">
                <div class="form-group ">
                  <label for="codigo">Codigo: </label>
                  <input id="codigo"type="text" class="form-control" placeholder="Ingrese codigo" required>
                </div>
                <div class="form-group">
                  <label for="fecha_compra">Fecha de compra: </label>
                  <input id="fecha_compra"type="date" class="form-control" placeholder="Ingrese fecha de compra" required>
                </div>
                <div class="form-group">
                  <label for="fecha_entrega">Fecha de entrega: </label>
                  <input id="fecha_entrega"type="date" class="form-control" placeholder="Ingrese fecha de entrega" required>
                </div>
                <div class="form-group">
                  <label for="total">Total</label>
                  <input id="total"type="number" step="any" class="form-control" value='1' placeholder="Ingrese total" required>
                </div>
                <div class="form-group">
                  <label for="estado">Estado de pago</label>
                  <select  id="estado" class="form-control select2" style="width: 100%"></select>
                </div>
                <div class="form-group">
                  <label for="proveedor">Proveedor</label>
                  <select  id="proveedor" class="form-control select2" style="width: 100%"></select>
                </div>
              </form>
            </div>
            <div class="card col-sm-9 p-3">
              <div class="card p-3">
                <div class="alert alert-success text-center" id="add-prod" style='display:none;'>
                  <span><i class="fas fa-check m-1"></i>Se agrego correctamente</span>
                </div>
                <div class="alert alert-danger text-center" id="noadd-prod" style='display:none;'>
                  <span id='error'><i class="fas fa-times m-1"></i>no se agrego</span>
                </div>
                <div class="form-group">
                  <label for="producto">Producto</label>
                  <select  id="producto" class="form-control select2" style="width: 100%"></select>
                </div>
                <div class="form-group">
                  <label for="codigo_lote">Codigo</label>
                  <input id="codigo_lote"type="text" class="form-control" placeholder="Ingrese codigo de lote" required>
                </div>
                <div class="form-group">
                  <label for="cantidad">Cantidad</label>
                  <input id="cantidad"type="number" class="form-control" value='1' placeholder="Ingrese cantidad" required>
                </div>
                <div class="form-group ">
                  <label for="vencimiento">Vencimiento: </label>
                  <input id="vencimiento"type="date" class="form-control" placeholder="Ingrese vencimiento" required>
                </div>
                <div class="form-group">
                  <label for="precio_compra">Precio de compra</label>
                  <input id="precio_compra"type="number" step="any" class="form-control" value='1' placeholder="Ingrese precio de compra" required>
                </div>
                <div class="form-group text-right">
                  <button class="agregar-producto btn bg-gradient-success ml-2">Agregar</button>
                </div>
              </div>
            </div>
            <div class="card col-sm-12">
              <table class="table table-hover text-nowrap table-responsive">
                <thead class='table-success'>
                  <tr>
                    <th>Producto</th>
                    <th>Codigo</th>
                    <th>Cantidad</th>
                    <th>Vencimiento</th>
                    <th>Precio de compra</th>
                    <th>Operacion</th>
                  </tr>
                </thead>
                <tbody id="registros_compra" class='table-active'>
                </tbody>
              </table>
              <button class="crear-compra btn bg-gradient-info text-center">Crear compra</button>
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
<script src="../js/nueva_compra.js"></script>