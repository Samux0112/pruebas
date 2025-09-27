const tblNuevaCotizacion = document.querySelector('#tblNuevaCotizacion tbody');

const idCliente = document.querySelector('#idCliente');
const telefonoCliente = document.querySelector('#telefonoCliente');
const direccionCliente = document.querySelector('#direccionCliente');

const descuento = document.querySelector('#descuento');
const metodo = document.querySelector('#metodo');
const validez = document.querySelector('#validez');
const idCotizacion = document.querySelector('#idCotizacion');
var contribuyente = "";

const errorCliente = document.querySelector('#errorCliente');
const docuemi = document.querySelector('#docuemi');
const iva = document.querySelector('#iva');
const gravadas = document.querySelector('#gravadas');
descuento.value = "0"
const ivaRete = document.querySelector('#ivaRete');
ivaRete.value = "0.0"



document.addEventListener('DOMContentLoaded', function () {
    //cargar productos de localStorage
    mostrarProducto();
	
docuemi.addEventListener('click', function () {
		 mostrarProducto();

		  })
		  
		   descuento.addEventListener('keyup', function (e) {
mostrarProducto();
			   
		   })

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
			contribuyente = ui.item.contribuyente;
			mostrarProducto();
			
        }
		
		
    });

    //completar cotizacion
    btnAccion.addEventListener('click', function () {
        let filas = document.querySelectorAll('#tblNuevaCotizacion tr').length;
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
            document.getElementById('btnAccion').disabled = true;
            const url = base_url + 'cotizaciones/registrarCotizacion';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                productos: listaCarrito,
                idCliente: idCliente.value,
                metodo: metodo.value,
                descuento: descuento.value,
                validez: validez.value,
				id : idCotizacion.value,
				documento : docuemi.value,
				vGravadas : gravadas.value,
				vIva : iva.value,
				vTotal : totalPagar.value,
				vIvaRete : ivaRete.value
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
                                denyButtonText: `Impresion`,
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    const ruta = base_url + 'cotizaciones/reporte/ticked/' + res.idCotizacion;
                                    window.open(ruta, '_blank');
                                } else if (result.isDenied) {
                                    const ruta = base_url + 'cotizaciones/reporte/impresion/' + res.idCotizacion;
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
    tblHistorial = $('#tblHistorial').DataTable({
        ajax: {
            url: base_url + 'cotizaciones/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'fecha' },
            { data: 'hora' },
			{ data: 'id' },
            { data: 'total' },
            { data: 'nombre' },
            { data: 'validez' },
            { data: 'metodo' },
            { data: 'acciones' }
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
                            <td width="200">
                            <input type="number" class="form-control inputPrecio" data-id="${producto.id}" value="${producto.precio_venta}">
                            </td>
                            <td width="150">
                            <input type="number" class="form-control inputCantidad" data-id="${producto.id}" value="${producto.cantidad}">
                            </td>
                            <td>${producto.subTotalVenta}</td>
                            <td><button class="btn btn-danger btnEliminar" data-id="${producto.id}" type="button"><i class="fas fa-trash"></i></button></td>
                        </tr>`;
                    });
                    tblNuevaCotizacion.innerHTML = html;
					var calculoDescuento = 0;
					if(descuento.value!=''){
						calculoDescuento = parseFloat(descuento.value);
					}
					var calculoTotalVenta = res.totalVenta - calculoDescuento ;
                    totalPagar.value = calculoTotalVenta;
					
					if(docuemi.value=='CREDITO FISCAL'){
					var calculoIva = parseFloat(calculoTotalVenta) - (parseFloat(calculoTotalVenta) / 1.13);
					iva.value = Math.round((calculoIva+ Number.EPSILON) * 100) / 100;
					var calculoGravadas = (parseFloat(calculoTotalVenta) / 1.13);
					gravadas.value = Math.round((calculoGravadas+ Number.EPSILON) * 100) / 100;
					if(contribuyente=='Grande'){
					ivaRete.value = Math.round((calculoGravadas *0.01+ Number.EPSILON) * 100) / 100;
					calculoTotalVenta = calculoTotalVenta - parseFloat(ivaRete.value);
					totalPagar.value = calculoTotalVenta;
					}else{
					ivaRete.value ="0.0"	
					}
					}else{
					totalPagar.value = calculoTotalVenta; 	
					gravadas.value = "0.0";
					iva.value="0.0";
					}
					
                    btnEliminarProducto();
                    agregarCantidad();
                    agregarPrecioVenta();
                } else {
                    tblNuevaCotizacion.innerHTML = '';
					
					
                }
            }
        }
    } else {
        tblNuevaCotizacion.innerHTML = `<tr>
            <td colspan="4" class="text-center">CARRITO VACIO</td>
        </tr>`;
    }
}

function verReporte(id) {
    Swal.fire({
        title: 'Desea Generar Reporte?',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ticked',
        denyButtonText: `Impresion`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const ruta = base_url + 'cotizaciones/reporte/ticked/' + id;
            window.open(ruta, '_blank');
        } else if (result.isDenied) {
            const ruta = base_url + 'cotizaciones/reporte/impresion/' + id;
            window.open(ruta, '_blank');
        }
    })
}

function detalle(idCot){
	idCotizacion.value = idCot;
	localStorage.removeItem('posCotizaciones');
listaCarrito = [];
	const url = base_url + 'cotizaciones/editar/' + idCot;
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
			const productos = JSON.parse(res.productos);
			idCliente.value = res.idCliente;
			telefonoCliente.value = res.telefono;
			direccionCliente.innerHTML = res.direccion;
			buscarCliente.value = res.nombre;
			descuento.value = res.descuento;
			docuemi.value = res.documento;
			contribuyente = res.contribuyente;
            for (i=0; i<productos.length; i++){
				
		listaCarrito.push({
        id: productos[i].id,
        cantidad: productos[i].cantidad, 
        
        precio: productos[i].precio, 
		descripcion : productos[i].nombre,
		catalogo : "Normal"
        
    })	
			}
        }
	localStorage.setItem('posCotizaciones', JSON.stringify(listaCarrito));
	mostrarProducto();
	firstTab.show();
	
    }
	
	
}

function venta(idCot){
	idCotizacion.value = idCot;
	localStorage.removeItem('posVenta');
	
listaCarrito = [];
	const url = base_url + 'cotizaciones/editar/' + idCot;
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
			const productos = JSON.parse(res.productos);
			idCliente.value = res.idCliente;
			telefonoCliente.value = res.telefono;
			direccionCliente.innerHTML = res.direccion;
			buscarCliente.value = res.nombre;
            for (i=0; i<productos.length; i++){
				
		listaCarrito.push({
        id: productos[i].id,
        cantidad: productos[i].cantidad, 
        
        precio: productos[i].precio, 
		descripcion : productos[i].nombre,
		catalogo : "Normal"
        
    })	
			}
        }
	localStorage.setItem('posVenta', JSON.stringify(listaCarrito));
	window.location.href = base_url + 'ventas/ventasCotizacion/'+idCot+'/'+idCliente.value
	
    }
	
	
}


	

