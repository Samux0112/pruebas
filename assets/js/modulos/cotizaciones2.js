const tblNuevaCotizacion2 = document.querySelector('#tblNuevaCotizacion2 tbody');

const idCliente = document.querySelector('#idCliente');
const telefonoCliente = document.querySelector('#telefonoCliente');
const direccionCliente = document.querySelector('#direccionCliente');

const descuento = document.querySelector('#descuento');
const metodo = document.querySelector('#metodo');
const numfac = document.querySelector('#numfac');
const numorden = document.querySelector('#numorden');
const sucursal = document.querySelector('#sucursal');
const opticli = document.querySelector('#opticli');
const fecha1 = document.querySelector('#fecha1');
const fechaentre = document.querySelector('#fechaentre');
const validez = document.querySelector('#validez');

const errorCliente = document.querySelector('#errorCliente');

document.addEventListener('DOMContentLoaded', function () {
    //cargar productos de localStorage
    mostrarProducto();

    //autocomplete clientes
    $("#buscarCliente").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'clientes/buscar',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                    if (data.length > 0) {
                        errorCliente.textContent = '';
                    } else {
                        errorCliente.textContent = 'NO HAY CLIENTE CON ESE NOMBRE';
                    }
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            telefonoCliente.value = ui.item.telefono;
            direccionCliente.innerHTML = ui.item.direccion;
            idCliente.value = ui.item.id;
        }
    });

    //completar cotizacion
    btnAccion.addEventListener('click', function () {
        let filas = document.querySelectorAll('#tblNuevaCotizacion2 tr').length;
        if (filas < 2) {
            alertaPersonalizada('warning', 'CARRITO VACIO');
            return;
        } else if (idCliente.value == ''
            && telefonoCliente.value == '') {
            alertaPersonalizada('warning', 'EL CLIENTE ES REQUERIDO');
            return;
        } else if (metodo.value == '') {
            alertaPersonalizada('warning', 'EL METODO ES REQUERIDO');
            return;
        } else if (validez.value == '') {
            alertaPersonalizada('warning', 'LA VALIDEZ ES REQUERIDO');
            return;
        } else {
          
            const url = base_url + 'cotizaciones2/registrarCotizacion2';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                productos: listaCarrito,
                idCliente: idCliente.value,
                metodo: metodo.value,
                numfac: numfac.value,
                numorden: numorden.value,
                sucursal: sucursal.value,
                opticli: opticli.value,
                fecha1: fecha1.value,
                fechaentre: fechaentre.value,
                descuento: descuento.value,
                validez: validez.value
            }));
            //verificar estados
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    console.log(this.responseText);
                    alertaPersonalizada(res.type, res.msg);
                    if (res.type == 'success') {
                        localStorage.removeItem(nombreKey);
                        setTimeout(() => {
                            Swal.fire({
                                title: 'Desea Generar Reporte?',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Ticked',
                                denyButtonText: `Factura`,
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    const ruta = base_url + 'cotizaciones2/reporte/ticked/' + res.idCotizacion2;
                                    window.open(ruta, '_blank');
                                } else if (result.isDenied) {
                                    const ruta = base_url + 'cotizaciones2/reporte/factura/' + res.idCotizacion2;
                                    window.open(ruta, '_blank');
                                }
                                window.location.reload();
                            })

                        }, 2000);
                    }
                }
            }
        }

    })

    //cargar datos con el plugin datatables
    tblHistorial3 = $('#tblHistorial3').DataTable({
        ajax: {
            url: base_url + 'cotizaciones2/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'fecha' },
            { data: 'hora' },
            { data: 'total' },
            { data: 'nombre' },
            { data: 'validez' },
            { data: 'metodo' },
            { data: 'numfac'},
            { data: 'numorden'},
            { data: 'sucursal'},
            { data: 'opticli'},
            { data: 'fecha1'},
            { data: 'fechaentre'},
            { data: 'acciones'}
           
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[0, 'desc']],
    });

})

//cargar productos
function mostrarProducto() {
    if (localStorage.getItem(nombreKey) != null) {
        const url = base_url + 'productos/mostrarDatos';
        //hacer una instancia del objeto XMLHttpRequest 
        const http = new XMLHttpRequest();
        //Abrir una Conexion - POST - GET
        http.open('POST', url, true);
        //Enviar Datos
        http.send(JSON.stringify(listaCarrito));
        //verificar estados
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                let html = '';
                if (res.productos.length > 0) {
                    res.productos.forEach(producto => {
                        html += `<tr>
                            <td>${producto.nombre}</td>
                            <td>${producto.precio_venta}</td>
                            <td width="100">
                            <input type="number" class="form-control inputCantidad" data-id="${producto.id}" value="${producto.cantidad}">
                            </td>
                            <td>${producto.subTotalVenta}</td>
                            <td><button class="btn btn-danger btnEliminar" data-id="${producto.id}" type="button"><i class="fas fa-trash"></i></button></td>
                        </tr>`;
                    });
                    tblNuevaCotizacion2.innerHTML = html;
                    totalPagar.value = res.totalVenta;
                    btnEliminarProducto();
                    agregarCantidad();
                } else {
                    tblNuevaCotizacion2.innerHTML = '';
                }
            }
        }
    } else {
        tblNuevaCotizacion2.innerHTML = `<tr>
            <td colspan="4" class="text-center">CARRITO VACIO</td>
        </tr>`;
    }
}

function verReporte(idCotizacion2) {
    Swal.fire({
        title: 'Desea Generar Reporte?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ticked',
        denyButtonText: `Factura`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const ruta = base_url + 'cotizaciones2/reporte/ticked/' + idCotizacion2;
            window.open(ruta, '_blank');
        } else if (result.isDenied) {
            const ruta = base_url + 'cotizaciones2/reporte/factura/' + idCotizacion2;
            window.open(ruta, '_blank');
        }
    })
}