const tblNuevaVenta = document.querySelector('#tblNuevaVenta tbody');
const tblDetalleVenta = document.querySelector('#tblDetalleVenta tbody');

const docuemi = document.querySelector('#docuemi');
const codigoGeneracion = document.querySelector('#codigoGeneracion');
const codigoGen = document.querySelector('#codigoGen');
const ivaVenta = document.querySelector('#ivaVenta');
const subTotalVenta = document.querySelector('#subTotalVenta');
const montoTotalVenta = document.querySelector('#montoTotalVenta');
const fEmi = document.querySelector('#fEmi');
const desGravadas = document.querySelector('#desGravadas');
let clienteVigente;
let identificacionDte;
let idClienteVigente;
let correlativoAct;
var puntoVenta = "";



const numdocu = document.querySelector('#numdocu');
const nitCliente = document.querySelector('#nitCliente');
numdocu.value = "05";

const vende = document.querySelector('#vende');
var ventasGravadas = 0;
var totalPagarSD =0;
var totalIva = 0;
var transmision = "Normal";


const idCliente = document.querySelector('#idCliente');


const telefonoCliente = document.querySelector('#telefonoCliente');
const aplicaciones = document.querySelector('#aplicaciones');
const direccionCliente = document.querySelector('#direccionCliente');
const errorCliente = document.querySelector('#errorCliente'); 

const pago = document.querySelector('#pagar_con');
const cambio = document.querySelector('#cambio');
const ivaRetenido = document.querySelector('#ivaRetenido');
const descuento = document.querySelector('#descuento');
const gravadas = document.querySelector('#gravadas');
const exentas = document.querySelector('#exentas');
descuento.value=0;
const iva = document.querySelector('#iva');
const metodo = document.querySelector('#metodo');
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
            docuemi.focus();
            numdocu.focus();
            vende.focus();        
            
        }
    });
	
	 aplicaciones.addEventListener('click', function () {
		 mostrarProducto();

		  })
		  
		   docuemi.addEventListener('click', function () {
		 mostrarProducto();

		  })
		  
		   codigoGeneracion.addEventListener('change', function () {
		 obtenerRegistro(codigoGeneracion.value);

		  })
		  
		  


    //completar venta
    btnAccion.addEventListener('click', function () {
		 document.getElementById('btnAccion').disabled = true;
		 document.getElementById('btnContingencia').disabled = true;
        let filas = document.querySelectorAll('#tblNuevaVenta tr').length;
        if (filas < 2) {
            alertaPersonalizada('warning', 'CARRITO VACIO');
            return;

        }  else if (docuemi.value == '') {
            alertaPersonalizada('warning', 'COLOCAR EL NUMERO DE DOCUMENTO A EMITIR');
            return;

        }  else if (metodo.value == '') {
            alertaPersonalizada('warning', 'EL METODO ES REQUERIDO');
            return;

      



        } else if(idCliente.value == ''){
			alertaPersonalizada('warning', 'EL CLIENTE ES REQUERIDO');
		}


		else {
			validarCaja();
        }

    })
	
	    btnContingencia.addEventListener('click', function () {
        let filas = document.querySelectorAll('#tblNuevaVenta tr').length;
        if (filas < 2) {
            alertaPersonalizada('warning', 'CARRITO VACIO');
            return;

        } else {
                            Swal.fire({
                                title: 'Desea Generar Contigencia?',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'SI',
                                denyButtonText: 'NO',
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                     transmision = "Contingencia";
									validarCaja();
                                } else if (result.isDenied) {
                                     window.location.reload();
                                }
                            })			
	
        }

    })
	
    //cargar datos con el plugin datatables
    tblHistorial = $('#tblHistorial').DataTable({
        ajax: {
            url: base_url + 'notaCredito/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'fecha' },
            { data: 'hora' },
            { data: 'total' },
            { data: 'descuento'},
            { data: 'nombre'},
            { data: 'metodo' },
            { data: 'docuemi' },  
			{ data: 'numeroControlDte'},
			{ data: 'uuid' },
            { data: 'acciones' }
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[1, 'desc']],
    });

    //Calcular cambio
    pagar_con.addEventListener('keyup', function (e) {
        if (totalPagar.value != '') {
            let totalDescuento = descuento.value != '' ? descuento.value : 0;
            let TotalCambio = parseFloat(e.target.value) - (parseFloat(totalPagarHidden.value) - parseFloat(totalDescuento))
            cambio.value = TotalCambio.toFixed(2); 
        }
        
    })

 
 //Calcular descuento
 descuento.addEventListener('keyup', function (e) {
    if (totalPagar.value != '') {
        let nuevoTotal = parseFloat(totalPagarSD) - parseFloat(e.target.value);
        totalPagar.value = (e.target.value) == "" ? parseFloat(totalPagarSD).toFixed(2) : nuevoTotal.toFixed(2); 
		let totalGravadas =  parseFloat(ventasGravadas);
		gravadas.value = (e.target.value) == "" ? parseFloat(ventasGravadas).toFixed(2) :(totalGravadas - parseFloat(e.target.value)).toFixed(2);
        let nuevoCambio = parseFloat(pagar_con.value) - parseFloat(nuevoTotal)
        cambio.value = (e.target.value) == "" ? (parseFloat(pagar_con.value) - parseFloat(totalPagarSD)).toFixed(2)   :  nuevoCambio.toFixed(2);
		if(docuemi.value=="Nota de credito"){
				
			totalIva = (e.target.value) != "" ? Math.round(((((ventasGravadas - parseFloat(descuento.value)) * 1.13) - (ventasGravadas - parseFloat(descuento.value)))+ Number.EPSILON) * 100) / 100 : Math.round(((((ventasGravadas - 0) * 1.13) - (ventasGravadas - 0))+ Number.EPSILON) * 100) / 100 ;
            iva.value = totalIva;
		let nuevoTotal = parseFloat(totalPagarSD) - (parseFloat(e.target.value)*1.13) + parseFloat(exentas.value) - parseFloat(ivaRetenido.value) ;
        totalPagar.value = (e.target.value) == "" ? parseFloat(totalPagarSD).toFixed(2) : nuevoTotal.toFixed(2); 
		}
    }
    
})


})

function validarCaja(){
	
			 const url = base_url + 'ventas/verificarCaja';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send();
            //verificar estados
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    console.log(this.responseText);
                   
                    if (res.type == 'success') {
						maxCorrelativo(clienteVigente);
                        
                    }else{
						
						 alertaPersonalizada(res.type, res.msg);
					}
                }
            }
	
}

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
					if(aplicaciones.value=="sinAplicaciones"){
					ventasGravadas = docuemi.value == "Nota de credito" ? res.gravadas : (Math.round(((parseFloat(res.gravadas) + parseFloat(res.iva))+ Number.EPSILON) * 100) / 100).toFixed(2);
                    totalPagarSD = res.totalVentaSD;
					tblNuevaVenta.innerHTML = html;
                    totalPagar.value = (parseFloat(res.totalVenta.replaceAll(",","")));
                    totalPagarHidden.value = res.totalVentaSD; 
					gravadas.value = docuemi.value == "Nota de credito" ? res.gravadas : (Math.round(((parseFloat(res.gravadas) + parseFloat(res.iva))+ Number.EPSILON) * 100) / 100).toFixed(2);
					exentas.value = res.exentos;
					ivaRetenido.value = 0;
					iva.value = docuemi.value == "Nota de credito" ? Math.round(((parseFloat(res.iva))+ Number.EPSILON) * 100) / 100 : 0 ;
                    btnEliminarProducto();
                    agregarCantidad();
                    agregarPrecioVenta();
					}else if(aplicaciones.value=="retencion"){
					tblNuevaVenta.innerHTML = html;
                    totalPagar.value = Math.round(((parseFloat(res.totalVenta.replaceAll(",","")) - (parseFloat(res.gravadas.replaceAll(",","")) *0.01))+ Number.EPSILON) * 100) / 100;
                    totalPagarHidden.value = res.totalVentaSD;
					gravadas.value = res.gravadas; 
					exentas.value = res.exentos;
					ivaRetenido.value = Math.round(((res.gravadas *0.01)+ Number.EPSILON) * 100) / 100;
					iva.value = res.iva;
                    btnEliminarProducto();
                    agregarCantidad();
                    agregarPrecioVenta();
						
					}
                } else {
					totalPagar.value = 0.0 ;
                    totalPagarHidden.value = 0.0;
					gravadas.value = 0.0;
					exentas.value = 0.0;
					ivaRetenido.value = 0.0;
					iva.value = 0.0;
                    btnEliminarProducto();
                    agregarCantidad();
                    agregarPrecioVenta();
                    tblNuevaVenta.innerHTML = '';
                }
            }
        }
    } else {
        tblNuevaVenta.innerHTML = `<tr>
            <td colspan="4" class="text-center">CARRITO VACIO</td>
        </tr>`;
    }
}

function verReporte(idVenta) {
    Swal.fire({
        title: 'Desea Generar Reporte?',
        showDenyButton: true,
		showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ticked',
        denyButtonText: `Enviar`,
		cancelButtonText: 'PDF',
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const ruta = base_url + 'notaCredito/reporte/ticked/' + idVenta;
            window.open(ruta, '_blank');
        } else if (result.isDenied) {
            const ruta = base_url + 'notaCredito/reporte/impresion/' + idVenta;
            window.open(ruta, '_blank');
        }else if (result.isDismissed && result.dismiss !="close" ) {
            const ruta = base_url + 'notaCredito/reporte/pdf/' + idVenta;
            window.open(ruta, '_blank');
        }
    })
}

function anularVenta(idVenta) {
    Swal.fire({
        title: 'Esta seguro de anular la venta?',
        text: "El stock de los productos cambiarán!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Anular!'
    }).then((result) => {
        if (result.isConfirmed) {
			   const url = base_url + 'notaCredito/anularDte/' + idVenta;
			    window.open(url, '_blank');

        }
    })
}

function obtenerCliente(idCliente){
	            $.ajax({
                url: base_url + 'clientes/buscarId',
                dataType: "json",
                data: {
                    id: idCliente
                },
                success: function (data) {
					if(data.length==0){
				alertaPersonalizada('warning', 'EL CLIENTE ES REQUERIDO O NO EXISTE REGISTRO');
					}else{
					maxCorrelativo(data);
					}
					
					
  
                }
            });
	
}

function obtenerRegistro(idRegistro){
	let html = '';

	            $.ajax({
                url: base_url + 'notaCredito/buscarRegistro',
                dataType: "json",
                data: {
                    id: idRegistro
                },
                success: function (data) {
					if(data.length==0){
				alertaPersonalizada('warning', 'EL CLIENTE ES REQUERIDO O NO EXISTE REGISTRO');
					}else{
					puntoVenta = data[0].codPuntoVentaMH;
					idClienteVigente =data[0].id_cliente;
					identificacionDte = data[0].identificacion;
				    clienteVigente = data[0].receptor;
					desGravadas.value = data[0].resumen.totalDescu;
					fEmi.value = data[0].identificacion.fecEmi;
					buscarCliente.value = data[0].receptor.nombre;
					nitCliente.value = data[0].emisor.nit;
					codigoGen.value =data[0].uuid;
					let cuerpoDocumento = data[0].cuerpo;
					for(i=0; i<cuerpoDocumento.length; i++){
					html += `<tr><td>`+	cuerpoDocumento[i].descripcion+`</td><td>`+cuerpoDocumento[i].precioUni+`</td><td>`+cuerpoDocumento[i].cantidad+`</td><td>`+cuerpoDocumento[i].ventaGravada+`</td></tr>`;
					}
					tblDetalleVenta.innerHTML = html;
					ivaVenta.value = data[0].resumen.tributos[0].valor;
					subTotalVenta.value = data[0].resumen.subTotal;
					montoTotalVenta.value = data[0].resumen.totalPagar;
					
					
						
					}
					
					
  
                }
            });
	
}

function maxCorrelativo(cliente){
	            $.ajax({
                url: base_url + 'ventas/maxCorrelativo',
                dataType: "json",
                data: {
                    tipoDocumento: docuemi.value,
					codPuntoVentaMH : puntoVenta
                },
                success: function (data) {
					if(docuemi.value=="Nota de credito"){
					crearDte(cliente,data);
					} else if(docuemi.value=="FACTURA"){
						crearDteFactura(cliente,data);
						
					}
  
                }
            });
	
}

function crearDteFactura(dataCliente,correlativo){
	
	var duiReceptor = dataCliente[0].DUI;
	var nombreReceptor = dataCliente[0].nombre;
	var telefonoReceptor = dataCliente[0].telefono;
	var correoReceptor = dataCliente[0].correo;
	var direccionReceptor = dataCliente[0].direccion;
	    direccionReceptor = direccionReceptor.replaceAll("<p>","");
		direccionReceptor = direccionReceptor.replaceAll("</p>","");
    var cuerpodocumento=[];
	var totalGravada = 0;
	var subTotal= 0;
	var totalExenta = 0;
		
		
		 for (i = 0; i < listaCarrito.length; i++) {
	 var cuerpo={
            numItem: i+1,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: listaCarrito[i].descripcion,
            cantidad:  parseFloat(listaCarrito[i].cantidad),
            uniMedida: 59,
            precioUni: (Math.round10(parseFloat(listaCarrito[i].precio),-8)),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta:  listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Exento" ? Math.round(((parseFloat(listaCarrito[i].precio) * listaCarrito[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
            ventaGravada: listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Normal" ? Math.round(((parseFloat(listaCarrito[i].precio) * listaCarrito[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
			tributos : null,
		    psv : 0,
		    noGravado : 0,
			ivaItem : (Math.round10(parseFloat(listaCarrito[i].precio * listaCarrito[i].cantidad ) - parseFloat((listaCarrito[i].precio*listaCarrito[i].cantidad)/1.13) ,-8)) 
} 

cuerpodocumento.push(cuerpo);	
totalGravada = Math.round(((totalGravada + cuerpo.ventaGravada)+ Number.EPSILON) * 100) / 100;
totalExenta = Math.round(((totalExenta + cuerpo.ventaExenta)+ Number.EPSILON) * 100) / 100;
subTotal = subTotal + (parseFloat(listaCarrito[i].precio) * parseFloat(listaCarrito[i].cantidad));
totalIva =  Math.round(((totalGravada - (totalGravada/1.13))+ Number.EPSILON) * 100) / 100;
subTotal =  Math.round(((subTotal)+ Number.EPSILON) * 100) / 100;
} 
totalIva = (totalGravada - parseFloat(descuento.value)) - ((totalGravada - parseFloat(descuento.value))/1.13);
totalIva =  Math.round(((totalIva)+ Number.EPSILON) * 100) / 100;
   var fe = new Date(); 
   var mes = (fe.getMonth() +1);
   var dia = fe.getDate();
   
  if(mes<10){
	  mes = "0"+mes;
  }
  if(dia<10){
	  dia = "0"+dia;
  }
   var fechaFactura = fe.getFullYear()+"-"+mes+"-"+dia;
   var now = fe.toLocaleTimeString('it-IT');
			
	var jsondteObj ={
		nit : nit,
		activo : true,
		passwordPri : passwordPri,
		dteJson : {
		 identificacion : {
			 version : parseInt(versionConsumidor),
			 ambiente : ambiente,
			 tipoDte  : tipoDTEConsumidor,
			 numeroControl : crearCorrelativo(correlativo[0].correlativo),
			 codigoGeneracion : create_UUID(),
			  tipoModelo : transmision=="Contingencia" ? 2 : 1,
			 tipoOperacion : transmision=="Contingencia" ? 2 : 1,
			 tipoContingencia :transmision=="Contingencia" ? 1 : null,
			 motivoContin : null,
			 fecEmi : fechaFactura,
			 horEmi : now,
			 tipoMoneda : tipoMoneda
			 
		 },
		 documentoRelacionado : null,
		 emisor : {
			 nit : nit,
			 nrc : nrc,
			 nombre : nombreEmi,
			 codActividad : codActividad,
			 descActividad : descActividad,
			 nombreComercial : null,
			 tipoEstablecimiento : tipoEstablecimiento,
			 direccion : {
				 departamento : departamentoEmisor,
				 municipio : municipioEmisor,
				 complemento : complemento
			 },
			 telefono : telefonoEmisor,
			 correo : correoEmisor,
			 codEstableMH : null,
			 codEstable : null,
			 codPuntoVentaMH : null,
			 codPuntoVenta : null
			 
		 },
		 receptor : {
			 tipoDocumento: "13",
			 numDocumento: duiReceptor,
             nombre: nombreReceptor,
             nrc: null,
             codActividad: null,
             descActividad: null,
             direccion: null,
             telefono: telefonoReceptor,
             correo: correoReceptor
		 },
		 otrosDocumentos : null,
		 ventaTercero : null,
		 cuerpoDocumento :  cuerpodocumento,
		 resumen : {
			 totalIva : totalIva,
			 totalNoSuj : 0,
			 totalExenta : totalExenta,
			 totalGravada :  totalGravada,
			 subTotalVentas : totalGravada + totalExenta ,
			 descuNoSuj : 0,
			 descuExenta : 0,
			 descuGravada : parseFloat(descuento.value),
			 porcentajeDescuento : 0,
			 totalDescu : parseFloat(descuento.value),
			 tributos : null,
			 subTotal :totalGravada + totalExenta - parseFloat(descuento.value) ,
			 ivaRete1 : parseFloat(ivaRetenido.value),
			 reteRenta : 0,
			 montoTotalOperacion : totalGravada + totalExenta - parseFloat(descuento.value) - parseFloat(ivaRetenido.value),
			 totalNoGravado : 0,
			 totalPagar :totalGravada + totalExenta - parseFloat(ivaRetenido.value) - parseFloat(descuento.value),
			 totalLetras : NumeroALetras(totalGravada + totalExenta - parseFloat(ivaRetenido.value)),
			 saldoFavor : 0,
			 condicionOperacion : metodo.value == "CREDITO" ? 2 : 1,
			 pagos : [
			 {
				 codigo : "01",
				 montoPago :totalGravada + totalExenta - parseFloat(ivaRetenido.value) - parseFloat(descuento.value),
				 plazo :  metodo.value == "CREDITO" ? "02" : null,
				 referencia : "",
				 periodo : metodo.value == "CREDITO" ? 1 : null
				 
			 }
			 ],
			 numPagoElectronico : null	 
		 },
		 extension : {
			 nombEntrega : null,
			 docuEntrega : null,
			 nombRecibe : null,
			 docuRecibe : null,
			 observaciones : null,
			 placaVehiculo : null			 			 
		 },
		 apendice : null,
		}		 
	 };
	 
	 firmador(JSON.stringify(jsondteObj),correlativo);
		
	}



function crearDte(dataCliente,correlativo){
	
	

    var cuerpodocumento=[];
	var totalGravada = 0;
	var subTotal= 0;
	var totalExento = 0;
		
		
		 for (i = 0; i < listaCarrito.length; i++) {
	 var cuerpo={
            numItem: i+1,
            tipoItem: 1,
            numeroDocumento: codigoGen.value,
            codigo: null,
            codTributo: null,
            descripcion: listaCarrito[i].descripcion,
            cantidad:  parseFloat(listaCarrito[i].cantidad),
            uniMedida: 59,
            precioUni: listaCarrito[i].catalogo =="Normal" ? (Math.round10(parseFloat(listaCarrito[i].precio/1.13),-8)) : (Math.round10(parseFloat(listaCarrito[i].precio),-8)),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta: listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Exento" ? Math.round(((parseFloat(listaCarrito[i].precio) * listaCarrito[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
            ventaGravada: listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Normal"? Math.round(((parseFloat(listaCarrito[i].precio/1.13) * listaCarrito[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
			tributos : listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Normal"  ?  [ "20"] : null

} 
cuerpodocumento.push(cuerpo);	
totalExento = Math.round(((totalExento + cuerpo.ventaExenta)+ Number.EPSILON) * 100) / 100;
totalGravada = Math.round(((totalGravada + cuerpo.ventaGravada)+ Number.EPSILON) * 100) / 100;
subTotal = subTotal + (parseFloat(listaCarrito[i].precio) * parseFloat(listaCarrito[i].cantidad));
totalIva = Math.round(((parseFloat(iva.value))+ Number.EPSILON) * 100) / 100;
subTotal =  Math.round(((subTotal)+ Number.EPSILON) * 100) / 100;
}

var MontoFinal = totalGravada + totalExento - parseFloat(ivaRetenido.value) + totalIva -parseFloat(descuento.value) ;
   var fe = new Date();
   var mes = (fe.getMonth() +1);
   var dia = fe.getDate();
   
  if(mes<10){
	  mes = "0"+mes;
  }
  if(dia<10){
	  dia = "0"+dia;
  }
   var fechaFactura = fe.getFullYear()+"-"+mes+"-"+dia;
   var now = fe.toLocaleTimeString('it-IT');
   		
	var jsondteObj ={
		nit : nit,
		activo : true,
		passwordPri : passwordPri,
		dteJson : {
		 identificacion : {
			 version : parseInt(versionNotaCredito),
			 ambiente : ambiente,
			 tipoDte  : tipoDTEnotaCredito,
			 numeroControl : crearCorrelativo(correlativo[0].correlativo),
			 codigoGeneracion : create_UUID(),
			 tipoModelo : transmision=="Contingencia" ? 2 : 1,
			 tipoOperacion : transmision=="Contingencia" ? 2 : 1,
			 tipoContingencia :transmision=="Contingencia" ? 1 : null,
			 motivoContin : null,
			 fecEmi : fechaFactura,
			 horEmi : now,
			 tipoMoneda : tipoMoneda
			 
		 },
		 documentoRelacionado : null,
		 emisor : {
			 nit : nit,
			 nrc : nrc,
			 nombre : nombreEmi,
			 codActividad : codActividad,
			 descActividad : descActividad,
			 nombreComercial : null,
			 tipoEstablecimiento : tipoEstablecimiento,
			 direccion : {
				 departamento : departamentoEmisor,
				 municipio : municipioEmisor,
				 complemento : complemento
			 },
			 telefono : telefonoEmisor,
			 correo : correoEmisor
			 
		 },
		 receptor : {
			 nit :  dataCliente.nit,
			 nrc :  dataCliente.nrc,
			 nombre : dataCliente.nombre,
			 codActividad : dataCliente.codActividad,
			 descActividad : dataCliente.descActividad,
			 nombreComercial : null,
			 direccion : {
				 departamento :dataCliente.direccion.departamento,
				 municipio : dataCliente.direccion.municipio,
				 complemento : dataCliente.direccion.complemento
			 },
			 telefono : dataCliente.telefono,
			 correo : dataCliente.correo
		 },
		 documentoRelacionado : [{
		 tipoDocumento : "03",
		 tipoGeneracion: 2,
		 numeroDocumento : codigoGen.value,
		 fechaEmision : identificacionDte.fecEmi
		 }],
		 ventaTercero : null,
		 cuerpoDocumento :  cuerpodocumento,
		 resumen : {
			 totalNoSuj : 0,
			 totalExenta : totalExento,
			 totalGravada :  totalGravada,
			 subTotalVentas : Math.round(((totalGravada + totalExento)+ Number.EPSILON) * 100) / 100,
			 descuNoSuj : 0,
			 descuExenta : 0,
			 descuGravada : parseFloat(descuento.value),
			 totalDescu : parseFloat(descuento.value),
			 tributos : totalIva > 0 ? [{codigo : "20",descripcion : "Impuesto al Valor Agregado13%",valor : totalIva}] : null,
			 subTotal :Math.round(((totalGravada + totalExento - parseFloat(descuento.value) )+ Number.EPSILON) * 100) / 100,
			 ivaPerci1 : 0,
			 ivaRete1 : parseFloat(ivaRetenido.value),
			 reteRenta : 0,
			 montoTotalOperacion : parseFloat(totalPagar.value.replaceAll(",","")),
			 totalLetras : NumeroALetras(parseFloat(totalPagar.value)),
			 condicionOperacion : metodo.value == "CREDITO" ? 2 : 1 
		 },
		 extension : null,
		 apendice : null
		}		 
	 };
	 
	firmador(JSON.stringify(jsondteObj),correlativo);
		
	}
	

	
	function crearCorrelativo(correlativo){
			var correlativo = correlativo != "" && correlativo != null ? correlativo.toString() : "0" ;
			 var controlDTE="";
			 var str = notaCreditoBase;
			if(correlativo=="9"){
				controlDTE = str.substring(0, 31-(correlativo.length+1));
			}else if(correlativo=="99"){
				controlDTE = str.substring(0, 31-(correlativo.length+1));
			} else if(correlativo=="999"){
				controlDTE = str.substring(0, 31-(correlativo.length+1));
			}else if(correlativo=="9999"){
				controlDTE = str.substring(0, 31-(correlativo.length+1));
			}else{
			
			controlDTE = str.substring(0, 31-correlativo.length);
			}
			
              correlativo = parseInt(correlativo);
              correlativo = correlativo+1;
			  var correlativonuevo = correlativo;
			  correlativoAct = correlativo;
              console.log(correlativo);

             correlativo = correlativo.toString();
              console.log(correlativo);
            controlDTE = controlDTE + correlativo;
			var strCorr = controlDTE.substring(15, 31);
			controlDTE = controlDTE.substring(0, 7) + puntoVenta + strCorr;
			return controlDTE;
}

 function firmador(dte,correlativo){
var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText)
	console.log(dte);
    console.log(jsonResponse);
	 console.log(jsonResponse.body);
	 
	 if(transmision=="Normal"){
	 dte = JSON.parse(dte);
	 dte.passwordPri = null;
	autorizador(JSON.stringify(dte),jsonResponse.body,correlativo);
	 } else if(transmision=="Contingencia"){
		 
	var objdte = JSON.parse(dte);
	objdte.firmaElectronica = jsonResponse.body;
	objdte.selloRecibido = "";
	objdte.fhProcesamiento = objdte.dteJson.identificacion.fecEmi;
	objdte.passwordPri ="";
	guardarDte(objdte,correlativo);
	
	 }
	
  }
};

// Endpoint de la API y método que se va a usar para llamar
xhttp.open("POST",apiFirmador, true);
xhttp.setRequestHeader("Content-type", "application/JSON","User-Agent","PostmanRuntime/7.28.0","Content-Length","<calculated when request is sent>");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send(dte);	
	
}

function autorizador(dte,dteFirmado,correlativo){
var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText)
	
	registroDTE(dte,dteFirmado,jsonResponse.body.token,correlativo);
  }
};

// Endpoint de la API y método que se va a usar para llamar
xhttp.open("POST", apiAutorizador, true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded","User-Agent","PostmanRuntime/7.28.0","Content-Length","<calculated when request is sent>");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send("user="+nit+"&pwd="+pasApiMH);

}

function registroDTE(dte,Dtefirmado,token,correlativo){
	
var req = {
	ambiente : ambiente,
	idEnvio : 1,
	version : versionNotaCredito,
	tipoDte : tipoDTEnotaCredito,
	documento : Dtefirmado	
	}

var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText)
	
   var objdte = JSON.parse(dte);
	objdte.firmaElectronica = Dtefirmado;
	objdte.selloRecibido = jsonResponse.selloRecibido;
	objdte.fhProcesamiento = jsonResponse.fhProcesamiento;
	objdte.passwordPri ="";
	
	//guardarFactura(tablaDatos,nrc,totales,fecha1,descripcion,controlDTE,correlativonuevo,dte,jsonResponse,JSON.stringify(objdte));
	guardarDte(objdte,correlativo);
	

	
	
  }else if(this.status == 400){
	  var jsonResponse = JSON.parse(this.responseText)
	  alert("ERROR: "+jsonResponse.descripcionMsg);
	  
  }else if(this.status == 401){
	  alert("ERROR: "+jsonResponse.descripcionMsg);
	  
  }
};

// Endpoint de la API y método que se va a usar para llamar
xhttp.open("POST", apiRecepcionDTE, true);
xhttp.setRequestHeader("Authorization",token);
xhttp.setRequestHeader("Content-type", "application/JSON");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send(JSON.stringify(req));	

		
}

function guardarDte(objdte,correlativo){
		 const url = base_url + 'notaCredito/registrarVenta';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                productos: listaCarrito,
                idCliente: idClienteVigente,
                docuemi: docuemi.value,
                metodo: metodo.value,
                descuento: descuento.value,
                pago: pagar_con.value,
				correlativo: correlativoAct != "" && correlativoAct != null ?  correlativoAct : 1,
				numeroControlDte : objdte.dteJson.identificacion.numeroControl,
				dte: JSON.stringify(objdte),
				uuid: objdte.dteJson.identificacion.codigoGeneracion,
				tipoTransmision : transmision,
				codPuntoVentaMH : puntoVenta,
				total : objdte.dteJson.resumen.montoTotalOperacion,
				sello: objdte.selloRecibido,
				vExentas:objdte.dteJson.resumen.totalExenta, 
				vIva : objdte.dteJson.resumen.tributos[0].valor,
				vGravadas : objdte.dteJson.resumen.totalGravada,
				numdocu: numdocu.value
				
				
                
               
            }));
            //verificar estados
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    console.log(this.responseText);
                    alertaPersonalizada(res.type, res.msg);
                    if (res.type == 'success') {
		 document.getElementById('btnAccion').disabled = false;
		 document.getElementById('btnContingencia').disabled = false;
                        localStorage.removeItem(nombreKey);
                        setTimeout(() => {
                            Swal.fire({
                                title: 'Desea Generar Reporte?',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Ticked',
                                denyButtonText: `Enviar`,
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    const ruta = base_url + 'notaCredito/reporte/ticked/' + res.idVenta;
                                    window.open(ruta, '_blank');
                                } else if (result.isDenied) {
                                    const ruta = base_url + 'notaCredito/reporte/impresion/' + res.idVenta;
                                    window.open(ruta, '_blank');
                                }
                                window.location.reload();
                            })

                        }, 2000);
                    }
                }
            }
}
	
	   (function() {
  /**
   * Ajuste decimal de un número.
   *
   * @param {String}  tipo  El tipo de ajuste.
   * @param {Number}  valor El numero.
   * @param {Integer} exp   El exponente (el logaritmo 10 del ajuste base).
   * @returns {Number} El valor ajustado.
   */
  function decimalAdjust(type, value, exp) {
    // Si el exp no está definido o es cero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // Si el valor no es un número o el exp no es un entero...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  // Decimal round
  if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
  }
  // Decimal floor
  if (!Math.floor10) {
    Math.floor10 = function(value, exp) {
      return decimalAdjust('floor', value, exp);
    };
  }
  // Decimal ceil
  if (!Math.ceil10) {
    Math.ceil10 = function(value, exp) {
      return decimalAdjust('ceil', value, exp);
    };
  }
})();

function Unidades(num){
 
  switch(num)
  {
    case 1: return "UN";
    case 2: return "DOS";
    case 3: return "TRES";
    case 4: return "CUATRO";
    case 5: return "CINCO";
    case 6: return "SEIS";
    case 7: return "SIETE";
    case 8: return "OCHO";
    case 9: return "NUEVE";
  }
 
  return "";
}
 
function Decenas(num){
 
  decena = Math.floor(num/10);
  unidad = num - (decena * 10);
 
  switch(decena)
  {
    case 1:
      switch(unidad)
      {
        case 0: return "DIEZ";
        case 1: return "ONCE";
        case 2: return "DOCE";
        case 3: return "TRECE";
        case 4: return "CATORCE";
        case 5: return "QUINCE";
        default: return "DIECI" + Unidades(unidad);
      }
    case 2:
      switch(unidad)
      {
        case 0: return "VEINTE";
        default: return "VEINTI" + Unidades(unidad);
      }
    case 3: return DecenasY("TREINTA", unidad);
    case 4: return DecenasY("CUARENTA", unidad);
    case 5: return DecenasY("CINCUENTA", unidad);
    case 6: return DecenasY("SESENTA", unidad);
    case 7: return DecenasY("SETENTA", unidad);
    case 8: return DecenasY("OCHENTA", unidad);
    case 9: return DecenasY("NOVENTA", unidad);
    case 0: return Unidades(unidad);
  }
}//Unidades()
 
function DecenasY(strSin, numUnidades){
  if (numUnidades > 0)
    return strSin + " Y " + Unidades(numUnidades)
 
  return strSin;
}//DecenasY()
 
function Centenas(num){
 
  centenas = Math.floor(num / 100);
  decenas = num - (centenas * 100);
 
  switch(centenas)
  {
    case 1:
      if (decenas > 0)
        return "CIENTO " + Decenas(decenas);
      return "CIEN";
    case 2: return "DOSCIENTOS " + Decenas(decenas);
    case 3: return "TRESCIENTOS " + Decenas(decenas);
    case 4: return "CUATROCIENTOS " + Decenas(decenas);
    case 5: return "QUINIENTOS " + Decenas(decenas);
    case 6: return "SEISCIENTOS " + Decenas(decenas);
    case 7: return "SETECIENTOS " + Decenas(decenas);
    case 8: return "OCHOCIENTOS " + Decenas(decenas);
    case 9: return "NOVECIENTOS " + Decenas(decenas);
  }
 
  return Decenas(decenas);
}//Centenas()
 
function Seccion(num, divisor, strSingular, strPlural){
  cientos = Math.floor(num / divisor)
  resto = num - (cientos * divisor)
 
  letras = "";
 
  if (cientos > 0)
    if (cientos > 1)
      letras = Centenas(cientos) + " " + strPlural;
    else
      letras = strSingular;
 
  if (resto > 0)
    letras += "";
 
  return letras;
}//Seccion()
 
function Miles(num){
  divisor = 1000;
  cientos = Math.floor(num / divisor)
  resto = num - (cientos * divisor)
 
  strMiles = Seccion(num, divisor, "MIL", "MIL");
  strCentenas = Centenas(resto);
 
  if(strMiles == "")
    return strCentenas;
 
  return strMiles + " " + strCentenas;
 
  //return Seccion(num, divisor, "UN MIL", "MIL") + " " + Centenas(resto);
}//Miles()
 
function Millones(num){
  divisor = 1000000;
  cientos = Math.floor(num / divisor)
  resto = num - (cientos * divisor)
 
  strMillones = Seccion(num, divisor, "UN MILLON", "MILLONES");
  strMiles = Miles(resto);
 
  if(strMillones == "")
    return strMiles;
 
  return strMillones + " " + strMiles;
 
  //return Seccion(num, divisor, "UN MILLON", "MILLONES") + " " + Miles(resto);
}//Millones()
 
function NumeroALetras(num,centavos){
  var data = {
    numero: num,
    enteros: Math.floor(num),
    centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
    letrasCentavos: "",
  };
  if(centavos == undefined || centavos==false) {
    data.letrasMonedaPlural="DOLARES";
    data.letrasMonedaSingular="DOLAR";
  }else{
    data.letrasMonedaPlural="CENTAVOS";
    data.letrasMonedaSingular="CENTAVO";
  }
 
  if (data.centavos > 0)
    data.letrasCentavos = "CON " + NumeroALetras(data.centavos,true);
 
  if(data.enteros == 0)
    return "CERO " + data.letrasMonedaPlural + " " + data.letrasCentavos;
  if (data.enteros == 1)
    return Millones(data.enteros) + " " + data.letrasMonedaSingular + " " + data.letrasCentavos;
  else
    return Millones(data.enteros) + " " + data.letrasMonedaPlural + " " + data.letrasCentavos;
}

function create_UUID(){
    var dt = new Date().getTime();
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (dt + Math.random()*16)%16 | 0;
        dt = Math.floor(dt/16);
        return (c=='x' ? r :(r&0x3|0x8)).toString(16);
    });
	
    return uuid.toUpperCase();
}

