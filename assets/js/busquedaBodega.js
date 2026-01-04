const inputBuscarCodigo = document.querySelector('#buscarProductoCodigo');
const inputBuscarNombre = document.querySelector('#buscarProductoNombre');
const chekExento = document.querySelector('#chekExento');
const chekServicio = document.querySelector('#chekServicio');
const inputBuscarGasto = document.querySelector('#buscarGasto');
const bodegaSalida = document.querySelector('#bodegaSalida');

const barcode = document.querySelector('#barcode');
const nombre = document.querySelector('#nombre');
const containerCodigo = document.querySelector('#containerCodigo');
const containerNombre = document.querySelector('#containerNombre');
const containerGasto = document.querySelector('#containerGasto');
const gasto = document.querySelector('#gasto');

const errorBusqueda = document.querySelector('#errorBusqueda');

const btnAccion = document.querySelector('#btnAccion');
const totalPagar = document.querySelector('#totalPagar');


//para filtro por rango de fechas
const desde = document.querySelector('#desde');
const hasta = document.querySelector('#hasta');

let listaCarrito, tblHistorial;

document.addEventListener('DOMContentLoaded', function () {
    //comprobar productos en localStorage
    if (localStorage.getItem(nombreKey) != null) {
        listaCarrito = JSON.parse(localStorage.getItem(nombreKey));
    }
    //mostrar input para la busqueda por nombre
    nombre.addEventListener('click', function () {
        containerCodigo.classList.add('d-none');
        containerNombre.classList.remove('d-none');
		containerGasto.classList.add('d-none');
        inputBuscarNombre.value = '';
        errorBusqueda.textContent = '';
        inputBuscarNombre.focus();
    })
    //mostrar input para la busqueda por codigo
    barcode.addEventListener('click', function () {
        containerNombre.classList.add('d-none');
        containerCodigo.classList.remove('d-none');
		containerGasto.classList.add('d-none');
        inputBuscarCodigo.value = '';
        errorBusqueda.textContent = '';
        inputBuscarCodigo.focus();
    })
	
	 gasto.addEventListener('click', function () {
        containerNombre.classList.add('d-none');
		containerCodigo.classList.add('d-none')
        containerGasto.classList.remove('d-none');
        inputBuscarGasto.value = '';
        errorBusqueda.textContent = '';
        inputBuscarGasto.focus();
    })

    inputBuscarCodigo.addEventListener('keyup', function (e) {
        if (e.keyCode === 13) {
            buscarProducto(e.target.value);
        }
        return;
    })
	
	inputBuscarGasto.addEventListener('keyup', function (e) {
  if (e.keyCode === 13) {
        agregarProducto(inputBuscarGasto.value,0,0,1,0,'Normal','Unidad',59);
  }
        return;
    })
    //autocomplete productos
    $("#buscarProductoNombre").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'productos/buscarPorNombreTraslado',
                dataType: "json",
                data: {
                    term: request.term,
                    bodegaSalida: bodegaSalida.value
                },
                success: function (data) {
                    response(data);
                    if (data.length > 0) {
                        errorBusqueda.textContent = '';
                    } else {
                        errorBusqueda.textContent = 'NO HAY PRODUCTO CON ESE NOMBRE';
                    }
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            let precio = nombreKey == 'posventa2' ? ui.item.precio_venta2 : ui.item.precio_venta;
            agregarProducto(ui.item.label, ui.item.id, 1, ui.item.stock, precio, ui.item.catalogo, ui.item.nombre_corto, ui.item.medida);
            inputBuscarNombre.value = '';
            inputBuscarNombre.focus();
            return false;
        }
    });


    //filtro rango de fechas
    desde.addEventListener('change', function () {
        tblHistorial.draw();
    })
    hasta.addEventListener('change', function () {
        tblHistorial.draw();
    })

    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var FilterStart = desde.value;
            var FilterEnd = hasta.value;
            var DataTableStart = data[0].trim();
            var DataTableEnd = data[0].trim();
            if (FilterStart == '' || FilterEnd == '') {
                return true;
            }
            if (DataTableStart >= FilterStart && DataTableEnd <= FilterEnd) {
                return true;
            } else {
                return false;
            }

        });

})

function buscarProducto(valor) {
    const url = base_url + 'productos/buscarPorCodigo/' + valor +'/'+ bodegaSalida.value;
    //hacer una instancia del objeto XMLHttpRequest 
    const http = new XMLHttpRequest();
    //Abrir una Conexion - POST - GET
    http.open('GET', url, true);
    //Enviar Datos
    http.send();
    //verificar estados
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            errorBusqueda.textContent = '';
            if (res.estado) {
                let precio = nombreKey == 'posventa2' ? res.datos.precio_venta2 : res.datos.precio_venta;
                agregarProducto(res.datos.descripcion, res.datos.id, 1, res.datos.cantidad, precio,res.datos.catalogo,res.datos.nombre_corto,res.datos.medida);                
            }else{
                errorBusqueda.textContent = 'CODIGO NO EXISTE';
                //alertaPersonalizada('warning', 'CODIGO NO EXISTE');
            }
            inputBuscarCodigo.value = '';
            inputBuscarCodigo.focus();
        }
    }
}

//agregar productos a localStorage
function agregarProducto(descripcion, idProducto, cantidad, stockActual, precio, catalogo,nomMedida,medida) {
    if (localStorage.getItem(nombreKey) == null) {
        listaCarrito = [];
    } else {
        if (nombreKey === 'posVenta' || nombreKey === 'posApartados') {
            let cantidadAgregado = 0;
            for (let i = 0; i < listaCarrito.length; i++) {
                if (listaCarrito[i]['id'] == idProducto) {
                    cantidadAgregado = parseInt(listaCarrito[i]['cantidad']) + parseInt(cantidad);
                }
            }
            if(bodegaSalida.options[bodegaSalida.selectedIndex].text !="BODEGA VIRTUAL"){  
                if (cantidadAgregado > stockActual || stockActual == 0) {
                alertaPersonalizada('warning', 'STOCK NO DISPONIBLE');
                return;
            }
            }
            
        }
        for (let i = 0; i < listaCarrito.length; i++) {
            if (listaCarrito[i]['id'] == idProducto) {
                listaCarrito[i]['cantidad'] = parseInt(listaCarrito[i]['cantidad']) + 1;
                localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
                alertaPersonalizada('success', 'PRODUCTO AGREGADO');
                mostrarProducto();
                return;
            }

        }
    }

    //si lista carrito no existe
    if(bodegaSalida.options[bodegaSalida.selectedIndex].text !="BODEGA VIRTUAL"){
            if (nombreKey === 'posVenta' || nombreKey === 'posApartados') {
    if (stockActual <= 0) {
        alertaPersonalizada('warning', 'STOCK NO DISPONIBLE');
        return;
    }
}

    }


if (nombreKey === 'posVenta2'){
	        listaCarrito.push({
        id: idProducto,
        cantidad: cantidad, 
        
        precio: precio, 
		descripcion : descripcion,
		catalogo : chekServicio.checked ? "Servicio" :  chekExento.checked ? "Exento" :"Normal"
        
    })
	
}else{
        listaCarrito.push({
        id: idProducto,
        cantidad: cantidad, 
        
        precio: chekExento.checked ? Math.round(((precio/1.13)+ Number.EPSILON) * 100) / 100 : precio, 
		descripcion : descripcion,
		catalogo : chekExento.checked ? "Exento" : "Normal",
		nombreMedida : nomMedida,
		mediaMh : medida
        
    })	
}




    
    localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
    alertaPersonalizada('success', 'PRODUCTO AGREGADO');
    mostrarProducto();
}

//agregar evento click para eliminar
function btnEliminarProducto() {
    let lista = document.querySelectorAll('.btnEliminar');
    for (let i = 0; i < lista.length; i++) {
        lista[i].addEventListener('click', function () {
            let idProducto = lista[i].getAttribute('data-id');
            console.log(idProducto);
            eliminarProducto(idProducto);
			 mostrarProducto();
        });
    }
}
//eliminar productos del table
function eliminarProducto(idProducto) {
    for (let i = 0; i < listaCarrito.length; i++) {
        if (listaCarrito[i]['id'] == idProducto) {
            listaCarrito.splice(i, 1);
        }
    }
    localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
    alertaPersonalizada('success', 'PRODUCTO ELIMINADO');
    mostrarProducto();
}

//agregar eventa change para cambiar la cantidad
function agregarCantidad() {
    let lista = document.querySelectorAll('.inputCantidad');
    for (let i = 0; i < lista.length; i++) {
        lista[i].addEventListener('change', function () {
            let idProducto = lista[i].getAttribute('data-id');
            let cantidad = lista[i].value;
            cambiarCantidad(idProducto, cantidad);
			
        });
    }
}

function cambiarCantidad(idProducto, cantidad) {
	debugger;
    if ((nombreKey === 'posVenta' || nombreKey === 'posApartados') && idProducto !="0" ) {
        const url = base_url + 'ventas/verificarStockTraslado/' + idProducto+'/'+bodegaSalida.value;
        //hacer una instancia del objeto XMLHttpRequest 
        const http = new XMLHttpRequest();
        //Abrir una Conexion - POST - GET
        http.open('GET', url, true);
        //Enviar Datos
        http.send();
        //verificar estados
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if (parseFloat(res.cantidad) >= parseFloat(cantidad)) {
                    for (let i = 0; i < listaCarrito.length; i++) {
                        if (listaCarrito[i]['id'] == idProducto) {
                            listaCarrito[i]['cantidad'] = cantidad;
                        }
                    }
                    localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
                }else if(bodegaSalida.options[bodegaSalida.selectedIndex].text =="BODEGA VIRTUAL"){
                            for (let i = 0; i < listaCarrito.length; i++) {
                            if (listaCarrito[i]['id'] == idProducto) {
                                listaCarrito[i]['cantidad'] = cantidad;
                            }
                        }
                    localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
                    mostrarProducto();
                } else {
                    alertaPersonalizada('warning', 'STOCK NO DISPONIBLE');
                }
                mostrarProducto();
                return;
            }
        }
    } else {
        for (let i = 0; i < listaCarrito.length; i++) {
            if (listaCarrito[i]['id'] == idProducto) {
                listaCarrito[i]['cantidad'] = cantidad;
            }
        }
        localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
        mostrarProducto();
    }

}



//Precio editable Para cambiar el precio

function agregarPrecioVenta() {
    let lista = document.querySelectorAll('.inputPrecio');
    for (let i = 0; i < lista.length; i++) {
        lista[i].addEventListener('change', function () {
            let idProducto = lista[i].getAttribute('data-id');
            let precio = lista[i].value;
            cambiarPrecio(idProducto, precio);
        });
    }
}



//Precio editable Para precio_venta2

function agregarPrecioVenta2() {
    let lista = document.querySelectorAll('.inputPrecio');
    for (let i = 0; i < lista.length; i++) {
        lista[i].addEventListener('change', function () {
            let idProducto = lista[i].getAttribute('data-id');
            let precio = lista[i].value;
            cambiarPrecio(idProducto, precio);
        });
    }
}



function cambiarPrecio(idProducto, precio) {
    for (let i = 0; i < listaCarrito.length; i++) {
        if (listaCarrito[i]['id'] == idProducto) {
            listaCarrito[i]['precio'] = precio;
        }
    }
    localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
    mostrarProducto();

}