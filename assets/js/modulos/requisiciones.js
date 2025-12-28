const tblNuevaRequisicion = document.querySelector('#tblNuevaRequisicion tbody');
const observaciones = document.querySelector('#observaciones');
document.addEventListener('DOMContentLoaded', function () {
    if (localStorage.getItem(nombreKey) != null) {
        listaCarrito = JSON.parse(localStorage.getItem(nombreKey));
    }
    mostrarProducto();

    btnAccion.addEventListener('click', function () {
        let filas = document.querySelectorAll('#tblNuevaRequisicion tr').length;
        if (filas < 1) {
            alertaPersonalizada('warning', 'CARRITO VACIO');
            return;
        }
        const url = base_url + 'requisiciones/registrarRequisicion';
        const http = new XMLHttpRequest();
        http.open('POST', url, true);
        http.send(JSON.stringify({
            productos: listaCarrito,
            observaciones: observaciones.value
        }));
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
    });

    // cargar historial
    cargarHistorial();
});

function mostrarProducto() {
    if (localStorage.getItem(nombreKey) != null) {
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
                            <td width="100">
                            <input type="number" class="form-control inputCantidad" data-id="${producto.id}" value="${producto.cantidad}" placeholder="Cantidad">
                            </td>
                            <td><button class="btn btn-danger btnEliminar" data-id="${producto.id}" type="button"><i class="fas fa-trash"></i></button></td>
                        </tr>`;
                    });
                    tblNuevaRequisicion.innerHTML = html;
                    totalPagar.textContent = res.totalCompra || res.total;
                    btnEliminarProducto();
                    agregarCantidad();
                } else {
                    tblNuevaRequisicion.innerHTML = '';
                    totalPagar.textContent = '0';
                }
            }
        }
    } else {
        tblNuevaRequisicion.innerHTML = `<tr><td colspan="5" class="text-center">CARRITO VACIO</td></tr>`;
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

function cargarHistorial() {
    const url = base_url + 'requisiciones/listar';
    const http = new XMLHttpRequest();
    http.open('GET', url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            if (res.length > 0) {
                // Obtener el rol del usuario desde el input oculto en la vista
                var userRol = document.getElementById('rol_usuario') ? document.getElementById('rol_usuario').value : '';
                res.forEach(r => {
                    html += `<tr>
                        <td>${r.id}</td>
                        <td>${r.fecha}</td>
                        <td>${r.solicitante}</td>
                        <td>${r.estado}</td>
                        <td>`;
                    const rolesPermitidos = ['ADMINISTRADOR', 'SUPERVISOR', 'CONTADOR', 'INVENTARIO', 'VENDEDOR ADMINISTRATIVO'];
                    if (rolesPermitidos.includes(userRol)) {
                        html += `<button class="btn btn-sm btn-primary" onclick="verRequisicion(${r.id})">Ver</button>`;
                    }
                    html += `</td></tr>`;
                });
            }
            document.querySelector('#tblHistorialRequisiciones tbody').innerHTML = html;
            // Inicializar DataTable después de cargar los datos
            if (window.jQuery && $('#tblHistorialRequisiciones').length) {
                if ($.fn.DataTable.isDataTable('#tblHistorialRequisiciones')) {
                    $('#tblHistorialRequisiciones').DataTable().destroy();
                }
                $('#tblHistorialRequisiciones').DataTable({
                    language: {
                        url: base_url + 'assets/DataTables/es_es.json'
                    },
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    order: [[0, 'desc']]
                });
            }
        }
    }
}

function verRequisicion(id) {
    window.location.href = base_url + 'requisiciones/detalle/'+ id;
}

// función auxiliar para agregar producto al carrito desde otras vistas
function agregarAlCarritoRequisicion(producto){
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
