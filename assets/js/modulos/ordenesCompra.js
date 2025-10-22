const tblNuevaOrden = document.querySelector('#tblNuevaOrden tbody');
const idProveedor = document.querySelector('#idProveedor');
const proveedorDireccion = document.querySelector('#proveedorDireccion');
const btnAccion = document.querySelector('#btnAccion');
const totalPagar = document.querySelector('#totalPagar');

const nombreKey = 'posOrdenCompra';
let listaCarrito = [];

document.addEventListener('DOMContentLoaded', function () {
    //cargar carrito desde localStorage
    if (localStorage.getItem(nombreKey) != null) {
        listaCarrito = JSON.parse(localStorage.getItem(nombreKey));
    }

    //autocomplete proveedores
    $("#buscarProveedor").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'proveedor/buscar',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                    if (data.length > 0) {
                        //limpiar errores
                    } else {
                        // mostrar mensaje
                    }
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            proveedorDireccion.innerHTML = ui.item.direccion;
            idProveedor.value = ui.item.id;
        }
    });

    mostrarProducto();

    btnAccion.addEventListener('click', function () {
        let filas = document.querySelectorAll('#tblNuevaOrden tr').length;
        if (filas < 1) {
            alertaPersonalizada('warning', 'CARRITO VACIO');
            return;
        } else {
            const url = base_url + 'ordenesCompra/registrarOrden';
            const http = new XMLHttpRequest();
            http.open('POST', url, true);
            // construir payload; idProveedor es opcional
            const payload = { productos: listaCarrito };
            if (idProveedor && idProveedor.value && idProveedor.value.trim() !== '') {
                payload.idProveedor = idProveedor.value;
            }
            http.send(JSON.stringify(payload));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertaPersonalizada(res.type, res.msg);
                    if (res.type == 'success') {
                        localStorage.removeItem(nombreKey);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                }
            }
        }
    });
});

function mostrarProducto() {
    if (listaCarrito.length > 0) {
        const url = base_url + 'productos/mostrarDatos';
        const http = new XMLHttpRequest();
        http.open('POST', url, true);
        http.send(JSON.stringify(listaCarrito));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                let html = '';
                if (res.productos.length > 0) {
                    res.productos.forEach(producto => {
                        html += `<tr>
                            <td>${producto.nombre}</td>
                            <td>${producto.precio}</td>
                            <td width="100">
                            <input type="number" class="form-control inputCantidad" data-id="${producto.id}" value="${producto.cantidad}" placeholder="Cantidad">
                            </td>
                            <td>${producto.subTotalCompra || producto.subTotal}</td>
                            <td><button class="btn btn-danger btnEliminar" data-id="${producto.id}" type="button"><i class="fas fa-trash"></i></button></td>
                        </tr>`;
                    });
                    tblNuevaOrden.innerHTML = html;
                    totalPagar.textContent = res.totalCompra || res.total;
                    btnEliminarProducto();
                    agregarCantidad();
                } else {
                    tblNuevaOrden.innerHTML = '';
                    totalPagar.textContent = '0';
                }
            }
        }
    } else {
        tblNuevaOrden.innerHTML = `<tr><td colspan="5" class="text-center">CARRITO VACIO</td></tr>`;
        totalPagar.textContent = '0';
    }
}

function btnEliminarProducto() {
    const btns = document.querySelectorAll('.btnEliminar');
    btns.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            listaCarrito = listaCarrito.filter(p => p.id != id);
            localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
            mostrarProducto();
        });
    });
}

function agregarCantidad() {
    const inputs = document.querySelectorAll('.inputCantidad');
    inputs.forEach(input => {
        input.addEventListener('change', function () {
            const id = this.getAttribute('data-id');
            const valor = parseInt(this.value) || 0;
            listaCarrito = listaCarrito.map(p => {
                if (p.id == id) p.cantidad = valor;
                return p;
            });
            localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
            mostrarProducto();
        });
    });
}

// función auxiliar para agregar producto al carrito desde otras vistas
function agregarAlCarritoOrden(producto){
    // producto = {id, cantidad, precio}
    const existe = listaCarrito.find(p => p.id == producto.id);
    if(existe){
        listaCarrito = listaCarrito.map(p => {
            if(p.id == producto.id) p.cantidad = parseInt(p.cantidad) + parseInt(producto.cantidad);
            return p;
        });
    } else {
        listaCarrito.push(producto);
    }
    localStorage.setItem(nombreKey, JSON.stringify(listaCarrito));
}
