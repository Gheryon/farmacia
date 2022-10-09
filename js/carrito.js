$(document).ready(function(){
    contar_productos();
    recuperar_carrito_LS();
    recuperar_carrito_LS_compra();

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
    })

    $(document).on('click', '#vaciar-carrito', (e)=>{
        $('#lista').empty();
        vaciarLS();
        contar_productos();
    });

    $(document).on('click', '#procesar-pedido', (e)=>{
        procesar_pedido();
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
        let productos;
        productos=recuperarLS();
        productos.forEach(producto => {
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
        });
    }

    function recuperar_carrito_LS_compra() {
        let productos;
        productos=recuperarLS();
        productos.forEach(producto => {
            template=`
            <tr prodId="${producto.id}">
                <td>${producto.nombre}</td>
                <td>${producto.stock}</td>
                <td>${producto.precio}</td>
                <td>${producto.concentracion}</td>
                <td>${producto.adicional}</td>
                <td>${producto.laboratorio}</td>
                <td>${producto.presentacion}</td>
                <td>
                    <input type="number" min="1" class="form-control cantidad_producto" value="${producto.cantidad}">
                </td>
                <td>
                    <h5 class="subtotales">${producto.precio*producto.cantidad}</h5>
                </td>
                <td><button class="borrar-producto btn btn-danger"><i class="fas fa-times-circle"></i></button></td>
            </tr>
        `;
        $('#lista-compra').append(template);
        });
    }

    //modifica el valor del subtotal para que muestre en la tabla el valor correcto de cantidad*precio
    $('#cp').keyup((e)=>{
        let id, cantidad, producto, productos, montos;
        producto=$(this)[0].activeElement.parentElement.parentElement;
        console.log(producto);
        id=$(producto).attr('prodId');
        cantidad=producto.querySelector('input').value;
        //querySelectorAll selecciona todos los subtotales
        montos=document.querySelectorAll('.subtotales');
        productos=recuperarLS();
        productos.forEach(function(prod, indice){
            if(prod.id===id){
                prod.cantidad=cantidad;  
                montos[indice].innerHTML=`<h5>${cantidad*productos[indice].precio}</h5>`;
            }
        });
        localStorage.setItem('productos', JSON.stringify(productos));
    });

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
})