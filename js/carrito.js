$(document).ready(function(){
    contar_productos();
    recuperar_carrito_LS();
    recuperar_carrito_LS_compra();
    calcularTotal();

    $(document).on('click', '.agregar-carrito', (e)=>{
        const elemento =$(this)[0].activeElement.parentElement.parentElement.parentElement.parentElement;
        const id=$(elemento).attr('prodId');
        const nombre=$(elemento).attr('prodNombre');
        const concentracion=$(elemento).attr('prodConcentracion');
        const adicional=$(elemento).attr('prodAdicional');
        const precio=$(elemento).attr('prodPrecio');
        const laboratorio=$(elemento).attr('prodLaboratorio');
        const tipo=$(elemento).attr('prodTipo');
        const presentacion=$(elemento).attr('prodPresentacion');
        const avatar=$(elemento).attr('prodAvatar');
        const stock=$(elemento).attr('prodStock');
        
        const producto={
            id: id,
            nombre: nombre,
            concentracion: concentracion,
            adicional: adicional,
            precio: precio,
            laboratorio: laboratorio,
            tipo: tipo,
            presentacion: presentacion,
            avatar: avatar,
            stock: stock,
            cantidad: 1
        }
        //para asegurar que no se añade mas de una vez un producto al carrito y al localStorage
        let id_producto;
        let productos;
        productos=recuperarLS();
        productos.forEach(productoLS => {
            if(productoLS.id===producto.id){
                id_producto=productoLS.id;
            }
        });
        if(id_producto===producto.id){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El producto ya está en el carrito.'
              })
        }else{
            template=`
                <tr prodId="${producto.id}">
                    <td>${producto.id}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.concentracion}</td>
                    <td>${producto.adicional}</td>
                    <td>${producto.precio}</td>
                    <td><button class="borrar-producto btn btn-danger"><i class="fas fa-times-circle"></i></button></td>
                </tr>
            `;
            $('#lista').append(template);
            //guarda información en localstorage del producto, así no se pierde si se actualiza la página
            agregarLS(producto);
            contar_productos();
        }
    })

    $(document).on('click', '.borrar-producto', (e)=>{
        const elemento =$(this)[0].activeElement.parentElement.parentElement;
        const id=$(elemento).attr('prodId');
        elemento.remove();
        eliminar_producto_LS(id);
        contar_productos();
        calcularTotal();
    })

    $(document).on('click', '#vaciar-carrito', (e)=>{
        $('#lista').empty();
        vaciarLS();
        contar_productos();
    });

    $(document).on('click', '#procesar-pedido', (e)=>{
        procesar_pedido();
    });

    $(document).on('click', '#procesar-compra', (e)=>{
        procesar_compra();
    });

    //para determinar si hay productos almacenados con anterioridad, si los hay, hay que recuperarlos
    function recuperarLS(){
        let productos;
        /// triple === es para comparacion estricta, considera el tipo de dato además del valor
        if(localStorage.getItem('productos')===null){
            productos=[];
        }else{
            productos=JSON.parse(localStorage.getItem('productos'))
        }
        return productos;
    }

    function agregarLS(producto){
        let productos;
        productos=recuperarLS();
        productos.push(producto);
        //localStorage no guarda objetos, se tiene que convertir la información a string json
        localStorage.setItem('productos', JSON.stringify(productos));
    }

    function recuperar_carrito_LS() {
        let productos, id_producto;
        productos=recuperarLS();
        funcion="buscar_id";
        productos.forEach(producto => {
            id_producto=producto.id;
            $.post('../controlador/productoController.php', {funcion, id_producto}, (response)=>{
                let template_carrito='';
                let json=JSON.parse(response);
                //solo se trae un objeto producto, no hace falta bucle
                template_carrito=`
                <tr prodId="${json.id}">
                    <td>${json.id}</td>
                    <td>${json.nombre}</td>
                    <td>${json.concentracion}</td>
                    <td>${json.adicional}</td>
                    <td>${json.precio}</td>
                    <td><button class="borrar-producto btn btn-danger"><i class="fas fa-times-circle"></i></button></td>
                </tr>
                `;
                $('#lista').append(template_carrito);
            });
        });
    }

    function recuperar_carrito_LS_compra() {
        let productos, id_producto;
        productos=recuperarLS();
        funcion="buscar_id";
        productos.forEach(producto => {
            id_producto=producto.id;
            $.post('../controlador/productoController.php', {funcion, id_producto}, (response)=>{
                let template_compra='';
                let json=JSON.parse(response);
                //solo se trae un objeto producto, no hace falta bucle
                template_compra=`
                <tr prodId="${producto.id}" prodPrecio="${json.precio}">
                    <td>${json.nombre}</td>
                    <td>${json.stock}</td>
                    <td class="precio">${json.precio}</td>
                    <td>${json.concentracion}</td>
                    <td>${json.adicional}</td>
                    <td>${json.laboratorio}</td>
                    <td>${json.presentacion}</td>
                    <td>
                        <input type="number" min="1" class="form-control cantidad_producto" value="${producto.cantidad}">
                    </td>
                    <td class="subtotales">
                        <h5>${json.precio*producto.cantidad}</h5>
                    </td>
                    <td><button class="borrar-producto btn btn-danger"><i class="fas fa-times-circle"></i></button></td>
                </tr>
                `;
                $('#lista-compra').append(template_compra);
            });
        });
    }

    //modifica el valor del subtotal para que muestre en la tabla el valor correcto de cantidad*precio
    $('#cp').keyup((e)=>{
        let id, cantidad, producto, precio, productos, montos;
        producto=$(this)[0].activeElement.parentElement.parentElement;
        id=$(producto).attr('prodId');
        precio=$(producto).attr('prodPrecio');
        cantidad=producto.querySelector('input').value;
        //querySelectorAll selecciona todos los subtotales
        montos=document.querySelectorAll('.subtotales');
        productos=recuperarLS();
        productos.forEach(function(prod, indice){
            if(prod.id===id){
                prod.cantidad=cantidad;
                prod.precio=precio;
                montos[indice].innerHTML=`<h5>${(cantidad*precio).toFixed(2)}</h5>`;
            }
        });
        localStorage.setItem('productos', JSON.stringify(productos));
        calcularTotal();
    });

    $(document).on('click', '#actualizar', (e)=>{
        let productos, precios;
        precios=document.querySelectorAll('.precio');
        console.log(precios);
        productos=recuperarLS();
        productos.forEach(function(producto, indice){
            producto.precio=precios[indice].textContent;
        });
        localStorage.setItem('productos', JSON.stringify(productos));
        calcularTotal();
    });

    function calcularTotal(){
        let productos, subtotal, con_igv, total_sin_descuento, pago, vuelto, descuento;
        let total=0, igv=0.18;
        productos=recuperarLS();
        productos.forEach(producto => {
            let subtotal_producto=Number(producto.precio*producto.cantidad);
            total=total+subtotal_producto;
        });
        pago=$('#pago').val();
        descuento=$('#descuento').val();
        //para que el resultado sea float y se muestren solo 2 decimales
        con_igv=parseFloat(total*igv).toFixed(2);
        subtotal=parseFloat(total-con_igv).toFixed(2);
        total_sin_descuento=total.toFixed(2);

        total=total-descuento;
        vuelto=pago-total;
        $('#subtotal').html(subtotal);
        $('#con_igv').html(con_igv);
        $('#total_sin_descuento').html(total_sin_descuento);
        $('#total').html(total.toFixed(2));
        $('#vuelto').html(vuelto.toFixed(2));
    }

    function eliminar_producto_LS(id){
        let productos;
        productos=recuperarLS();
        productos.forEach(function(producto, indice) {
            if(producto.id===id){
                //borra el producto con indice=indice, y borra sólo 1 elemento
                productos.splice(indice, 1);
            }
        });
        localStorage.setItem('productos', JSON.stringify(productos));
    }

    function vaciarLS(){
        localStorage.clear();
    }

    function contar_productos(){
        let productos;
        let contador=0;
        productos=recuperarLS();
        productos.forEach(producto=>{
            contador++;
        });
        //return contador;
        $('#contador').html(contador);
    }

    function procesar_pedido(){
        let productos;
        productos=recuperarLS();
        if(productos.length===0){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El carrito está vacío.'
              })
        }else{
            location.href='../vista/adm_compra.php';
        }
    }

    function procesar_compra(){
        let nombre, dni;
        nombre=$('#cliente').val();
        dni=$('#dni').val();
        //si length==0, no hay productos en el carrito, no se puede seguir
        if(recuperarLS().length==0){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No hay productos en el carrito.'
              }).then(function(){
                location.href='../vista/adm_catalogo.php';
              });
        }
        else if(nombre==''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Se necesita un nombre de cliente.'
              }).then(function(){
                location.href='../vista/adm_catalogo.php';
              });
        }
        else{
            verificarStock().then(error=>{
                //console.log(error);
                if(error==0){
                    registrar_compra(nombre, dni);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Compra realizada',
                        showConfirmButton: false,
                        timer: 1500
                      })
                      vaciarLS();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Hay problemas con el stock de algún producto.'
                      })
                }
            });
        }
    }

    function registrar_compra(nombre, dni){
        funcion='registrar_compra';
        let total=$('#total').get(0).textContent;
        let productos=recuperarLS();
        let json=JSON.stringify(productos);
        $.post('../controlador/compraController.php', {funcion, total, nombre, dni, json}, (response)=>{
            console.log(response);
        })
    }

    //devuelve 0 si se puede proceder con la compra, 1 si hay problemas
    async function verificarStock(){
        let productos;
        funcion='verificar_stock';
        productos=recuperarLS();
        const response=await fetch('../controlador/productoController.php', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body: 'funcion='+funcion+'&&productos='+JSON.stringify(productos)
        })
        let error=await response.text();
        return error;
    }
})