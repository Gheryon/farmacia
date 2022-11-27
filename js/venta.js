$(document).ready(function(){
  let funcion="listar";
  $.post('../controlador/ventaController.php', {funcion}, (response)=>{
    console.log(JSON.parse(response));
  });

  //con datatables, el numero de columnas debe coincidir con el numero de columnas de la tabla, sino da error
  $('#tabla-venta').DataTable({
    ajax: {
      "url": "../controlador/ventaController.php",
      "method": "POST",
      "data": {funcion:funcion}
    },
    columns: [
      { data: 'id_venta' },
      { data: 'fecha' },
      { data: 'cliente' },
      { data: 'dni' },
      { data: 'total' },
      { data: 'vendedor' },
    ],
    });
});