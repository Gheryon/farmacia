$(document).ready(function(){
  $('#cat-carrito').show();
  buscar_producto();
  mostrar_lotes_riesgo();
  var datatable

  function buscar_producto(consulta) {
    funcion="buscar";
    $.post('../controlador/productoController.php', {funcion, consulta}, (response)=>{
      //console.log(response);
      const productos=JSON.parse(response);
      let template='';
      productos.forEach(producto => {
        template+=`
        <div prodId="${producto.id}" prodStock="${producto.stock}" prodNombre="${producto.nombre}" prodPrecio="${producto.precio}" prodConcentracion="${producto.concentracion}" prodAdicional="${producto.adicional}" prodLaboratorio="${producto.laboratorio_id}" prodTipo="${producto.tipo_id}" prodPresentacion="${producto.presentacion_id}" prodAvatar="${producto.avatar}" class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
      <div class="card bg-light d-flex flex-fill">
        <div class="card-header text-muted border-bottom-0">
        <i class="fas fa-lg fa-cubes mr-1"></i>${producto.stock}
        </div>
        <div class="card-body pt-0">
          <div class="row">
            <div class="col-7">
            <h2 class="lead"><b>Código: ${producto.id}</b></h2>
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
            <button class="agregar-carrito btn btn-sm btn-primary" title="Agregar al carrito">
              <i class="fas fa-plus-square mr-2"></i>Agregar al carrito
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

  function mostrar_lotes_riesgo() {
    funcion="buscar_lotes_riesgo";
    $.post('../controlador/loteController.php', {funcion}, (response)=>{
      //console.log(response);
      const lotes=JSON.parse(response);
      datatable = $('#lotes').DataTable({
        data:lotes,
        columns: [
          { data: 'id' },
          { data: 'nombre' },
          { data: 'stock' },
          { data: 'estado' },
          { data: 'laboratorio' },
          { data: 'presentacion' },
          { data: 'proveedor' },
          { data: 'mes' },
          { data: 'dia' }          
        ],
        //para que en la columna de estado aparezcan los badges de colores
        columnDefs:[{
            "render":function(data,type,row){
              let campo='';
              if(row.estado=='danger'){
                campo=`<h1 class="badge badge-danger">${row.estado}</h1>`;
              }
              if(row.estado=='warning'){
                campo=`<h1 class="badge badge-warning">${row.estado}</h1>`;
              }
              return campo;
            },
            "targets":[3]
          }
        ],
        //destroy necesario para renovar la datatable
        'destroy': true,
        "language": espannol
        });
    })
  }
});

let espannol={
      "processing": "Procesando...",
      "lengthMenu": "Mostrar _MENU_ registros",
      "zeroRecords": "No se encontraron resultados",
      "emptyTable": "Ningún dato disponible en esta tabla",
      "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "infoFiltered": "(filtrado de un total de _MAX_ registros)",
      "search": "Buscar:",
      "infoThousands": ",",
      "loadingRecords": "Cargando...",
      "paginate": {
          "first": "Primero",
          "last": "Último",
          "next": "Siguiente",
          "previous": "Anterior"
      },
      "aria": {
          "sortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sortDescending": ": Activar para ordenar la columna de manera descendente"
      },
      "buttons": {
          "copy": "Copiar",
          "colvis": "Visibilidad",
          "collection": "Colección",
          "colvisRestore": "Restaurar visibilidad",
          "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
          "copySuccess": {
              "1": "Copiada 1 fila al portapapeles",
              "_": "Copiadas %ds fila al portapapeles"
          },
          "copyTitle": "Copiar al portapapeles",
          "csv": "CSV",
          "excel": "Excel",
          "pageLength": {
              "-1": "Mostrar todas las filas",
              "_": "Mostrar %d filas"
          },
          "pdf": "PDF",
          "print": "Imprimir",
          "renameState": "Cambiar nombre",
          "updateState": "Actualizar",
          "createState": "Crear Estado",
          "removeAllStates": "Remover Estados",
          "removeState": "Remover",
          "savedStates": "Estados Guardados",
          "stateRestore": "Estado %d"
      },
      "autoFill": {
          "cancel": "Cancelar",
          "fill": "Rellene todas las celdas con <i>%d<\/i>",
          "fillHorizontal": "Rellenar celdas horizontalmente",
          "fillVertical": "Rellenar celdas verticalmentemente"
      },
      "decimal": ",",
      "searchBuilder": {
          "add": "Añadir condición",
          "button": {
              "0": "Constructor de búsqueda",
              "_": "Constructor de búsqueda (%d)"
          },
          "clearAll": "Borrar todo",
          "condition": "Condición",
          "conditions": {
              "date": {
                  "after": "Despues",
                  "before": "Antes",
                  "between": "Entre",
                  "empty": "Vacío",
                  "equals": "Igual a",
                  "notBetween": "No entre",
                  "notEmpty": "No Vacio",
                  "not": "Diferente de"
              },
              "number": {
                  "between": "Entre",
                  "empty": "Vacio",
                  "equals": "Igual a",
                  "gt": "Mayor a",
                  "gte": "Mayor o igual a",
                  "lt": "Menor que",
                  "lte": "Menor o igual que",
                  "notBetween": "No entre",
                  "notEmpty": "No vacío",
                  "not": "Diferente de"
              },
              "string": {
                  "contains": "Contiene",
                  "empty": "Vacío",
                  "endsWith": "Termina en",
                  "equals": "Igual a",
                  "notEmpty": "No Vacio",
                  "startsWith": "Empieza con",
                  "not": "Diferente de",
                  "notContains": "No Contiene",
                  "notStartsWith": "No empieza con",
                  "notEndsWith": "No termina con"
              },
              "array": {
                  "not": "Diferente de",
                  "equals": "Igual",
                  "empty": "Vacío",
                  "contains": "Contiene",
                  "notEmpty": "No Vacío",
                  "without": "Sin"
              }
          },
          "data": "Data",
          "deleteTitle": "Eliminar regla de filtrado",
          "leftTitle": "Criterios anulados",
          "logicAnd": "Y",
          "logicOr": "O",
          "rightTitle": "Criterios de sangría",
          "title": {
              "0": "Constructor de búsqueda",
              "_": "Constructor de búsqueda (%d)"
          },
          "value": "Valor"
      },
      "searchPanes": {
          "clearMessage": "Borrar todo",
          "collapse": {
              "0": "Paneles de búsqueda",
              "_": "Paneles de búsqueda (%d)"
          },
          "count": "{total}",
          "countFiltered": "{shown} ({total})",
          "emptyPanes": "Sin paneles de búsqueda",
          "loadMessage": "Cargando paneles de búsqueda",
          "title": "Filtros Activos - %d",
          "showMessage": "Mostrar Todo",
          "collapseMessage": "Colapsar Todo"
      },
      "select": {
          "cells": {
              "1": "1 celda seleccionada",
              "_": "%d celdas seleccionadas"
          },
          "columns": {
              "1": "1 columna seleccionada",
              "_": "%d columnas seleccionadas"
          },
          "rows": {
              "1": "1 fila seleccionada",
              "_": "%d filas seleccionadas"
          }
      },
      "thousands": ".",
      "datetime": {
          "previous": "Anterior",
          "next": "Proximo",
          "hours": "Horas",
          "minutes": "Minutos",
          "seconds": "Segundos",
          "unknown": "-",
          "amPm": [
              "AM",
              "PM"
          ],
          "months": {
              "0": "Enero",
              "1": "Febrero",
              "10": "Noviembre",
              "11": "Diciembre",
              "2": "Marzo",
              "3": "Abril",
              "4": "Mayo",
              "5": "Junio",
              "6": "Julio",
              "7": "Agosto",
              "8": "Septiembre",
              "9": "Octubre"
          },
          "weekdays": [
              "Dom",
              "Lun",
              "Mar",
              "Mie",
              "Jue",
              "Vie",
              "Sab"
          ]
      },
      "editor": {
          "close": "Cerrar",
          "create": {
              "button": "Nuevo",
              "title": "Crear Nuevo Registro",
              "submit": "Crear"
          },
          "edit": {
              "button": "Editar",
              "title": "Editar Registro",
              "submit": "Actualizar"
          },
          "remove": {
              "button": "Eliminar",
              "title": "Eliminar Registro",
              "submit": "Eliminar",
              "confirm": {
                  "_": "¿Está seguro que desea eliminar %d filas?",
                  "1": "¿Está seguro que desea eliminar 1 fila?"
              }
          },
          "error": {
              "system": "Ha ocurrido un error en el sistema (<a target=\"\\\" rel=\"\\ nofollow\" href=\"\\\">Más información&lt;\\\/a&gt;).<\/a>"
          },
          "multi": {
              "title": "Múltiples Valores",
              "info": "Los elementos seleccionados contienen diferentes valores para este registro. Para editar y establecer todos los elementos de este registro con el mismo valor, hacer click o tap aquí, de lo contrario conservarán sus valores individuales.",
              "restore": "Deshacer Cambios",
              "noMulti": "Este registro puede ser editado individualmente, pero no como parte de un grupo."
          }
      },
      "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
      "stateRestore": {
          "creationModal": {
              "button": "Crear",
              "name": "Nombre:",
              "order": "Clasificación",
              "paging": "Paginación",
              "search": "Busqueda",
              "select": "Seleccionar",
              "columns": {
                  "search": "Búsqueda de Columna",
                  "visible": "Visibilidad de Columna"
              },
              "title": "Crear Nuevo Estado",
              "toggleLabel": "Incluir:"
          },
          "emptyError": "El nombre no puede estar vacio",
          "removeConfirm": "¿Seguro que quiere eliminar este %s?",
          "removeError": "Error al eliminar el registro",
          "removeJoiner": "y",
          "removeSubmit": "Eliminar",
          "renameButton": "Cambiar Nombre",
          "renameLabel": "Nuevo nombre para %s",
          "duplicateError": "Ya existe un Estado con este nombre.",
          "emptyStates": "No hay Estados guardados",
          "removeTitle": "Remover Estado",
          "renameTitle": "Cambiar Nombre Estado"
      }
  }