const tblNuevaVenta = document.querySelector('#tblNuevaVenta tbody');
const docuemi = document.querySelector('#docuemi');
const numdocu = document.querySelector('#numdocu');
const vende = document.querySelector('#vende');
const forma = document.querySelector('#forma');
const forma2 = document.querySelector('#forma2');
const formaPago = document.querySelector('#formaPago');
const tblPlanes = document.querySelector('#tblPlanes');
const tipo_operacion = document.querySelector('#tipo_operacion');
const tipo_ingreso = document.querySelector('#tipo_ingreso');
const pdv = document.querySelector('#pdv');
var prueba = Julio;




var ventasGravadas = 0;
var totalPagarSD =0;
var totalIva = 0;
var transmision = "Normal";
var puntoVenta = "";
numdocu.value = "03";
var planActual=[];
let planPago=[];
var montoTotal=0;
const buscarClientePlan = document.querySelector('#buscarClientePlan');
const idClientePlan = document.querySelector('#idClientePlan');


const plazo = document.querySelector('#plazo');
const monto = document.querySelector('#monto');
const interes = document.querySelector('#interes');
const btnPlanPago = document.querySelector('#btnPlanPago');
const btnPlanPagoPDF = document.querySelector('#btnPlanPagoPDF');
const plazoSeguro = document.querySelector('#plazoSeguro');
const tblPlan = document.querySelector('#tblPlan');



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
const iva = document.querySelector('#iva');
const metodo = document.querySelector('#metodo');
const impresion_directa = document.querySelector('#impresion_directa');
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
						puntoVenta = data[0].codPuntoVentaMH;
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
            forma.focus();
            forma2.focus();
            docuemi.focus();
            numdocu.focus();
            vende.focus();        
            
        }
    });
	
	    $("#buscarClientePlan").autocomplete({
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
						puntoVenta = data[0].codPuntoVentaMH;
                    } else {
                        errorCliente.textContent = 'NO HAY CLIENTE CON ESE NOMBRE';
                    }
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            idClientePlan.value = ui.item.id;  
			obtenerPlanesCliente(ui.item.id);
            
        }
    });
	
	 aplicaciones.addEventListener('click', function () {
		 mostrarProducto();

		  })
		  
		   docuemi.addEventListener('click', function () {
		if(docuemi.value == "FACTURA"){	   
		numdocu.value = "01";
		}else if(docuemi.value == "CREDITO FISCAL"){
			numdocu.value = "03"
		}else if(docuemi.value == "EXPORTACION"){
			numdocu.value = "11"
		}
		 mostrarProducto();

		  })
		   monto.addEventListener('click', function () {
			 var table = document.getElementById("tblPlan");
  var rowCount = table.rows.length;
  //console.log(rowCount);
  if (rowCount>2){
  for (var i=0; i < rowCount-1; i++){
  if(rowCount <= 1)
    alert('No se puede eliminar el encabezado');
  else
    table.deleteRow(rowCount - [i] -1);	  
  }
  }   
			   
		   })
		  
		 metodo.addEventListener('click', function () {
		if(metodo.value == "PLAZO"){	   
			document.getElementById('plazo').disabled = false;
			document.getElementById('monto').disabled = false;
			document.getElementById('interes').disabled = false;
			document.getElementById('plazoSeguro').disabled = false;
			document.getElementById('btnPlanPago').disabled = false;
			document.getElementById('btnPlanPagoPDF').disabled = false;
			
			
		}else{
		document.getElementById('plazo').disabled = true;
		document.getElementById('monto').disabled = true;
		document.getElementById('interes').disabled = true;
		document.getElementById('plazoSeguro').disabled = true;
		document.getElementById('btnPlanPago').disabled = true;	
		document.getElementById('btnPlanPagoPDF').disabled = true;		
		plazo.value = "";
		monto.value = "";
		interes.value = "";
		plazoSeguro.value = "";
 var table = document.getElementById("tblPlan");
  var rowCount = table.rows.length;
  //console.log(rowCount);
  if (rowCount>2){
  for (var i=0; i < rowCount-1; i++){
  if(rowCount <= 1)
    alert('No se puede eliminar el encabezado');
  else
    table.deleteRow(rowCount - [i] -1);	  
  }
  }
  

		}

		  })
		  
		   btnPlanPago.addEventListener('click', function () {			
			generarPlan();  	   
		   })
		   
		   btnPlanPagoPDF.addEventListener('click', function () {			
			verPlanPago();  	   
		   })


    //completar venta
    btnAccion.addEventListener('click', function () {
		 document.getElementById('btnAccion').disabled = true;
		 document.getElementById('btnContingencia').disabled = true;
        let filas = document.querySelectorAll('#tblNuevaVenta tr').length;
        if (filas < 2) {
            alertaPersonalizada('warning', 'CARRITO VACIO');
            return;

        }  else if (forma.value == '') {
            alertaPersonalizada('warning', 'LA FORMA DE PAGO ES REQUERIDO');
            return;   
            
        } else if (numdocu.value == '') {
            alertaPersonalizada('warning', 'EL NUMERO DE DOCUMENTO ES REQUERIDO');
            return;

        }  else if (docuemi.value == '') {
            alertaPersonalizada('warning', 'COLOCAR EL NUMERO DE DOCUMENTO A EMITIR');
            return;

        }  else if (vende.value == '') {
            alertaPersonalizada('warning', 'EL VENDEDOR ES REQUERIDO');
            return;
        } else if (metodo.value == '') {
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

        }  else if (forma.value == '') {
            alertaPersonalizada('warning', 'LA FORMA DE PAGO ES REQUERIDO');
            return;   
            
        }   else if (numdocu.value == '') {
            alertaPersonalizada('warning', 'EL NUMERO DE DOCUMENTO ES REQUERIDO');
            return;

        }  else if (docuemi.value == '') {
            alertaPersonalizada('warning', 'COLOCAR EL NUMERO DE DOCUMENTO A EMITIR');
            return;

        }  else if (vende.value == '') {
            alertaPersonalizada('warning', 'EL VENDEDOR ES REQUERIDO');
            return;
        } else if (metodo.value == '') {
            alertaPersonalizada('warning', 'EL METODO ES REQUERIDO');
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
            url: base_url + 'ventas/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'fecha' },
            { data: 'hora' },
            { data: 'total' },
            { data: 'descuento'},
            { data: 'nombre'},
            { data: 'forma' },
            { data: 'forma2' },
            { data: 'metodo' },
            { data: 'docuemi' },
            { data: 'numdocu' },
            { data: 'vende' }, 
            { data: 'nomUsuario'},
            { data: 'serie' },
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
            let TotalCambio = parseFloat(e.target.value) - (parseFloat(totalPagar.value.replaceAll(",","")));
            cambio.value = pagar_con.value!='' ? TotalCambio.toFixed(2):0; 
        }
        
    })


 //Calcular descuento
 descuento.addEventListener('keyup', function (e) {
	 
    if (totalPagar.value != '') {
		var ivaReten =0;
        let nuevoTotal = (e.target.value) != "" ? parseFloat(totalPagarSD) - parseFloat(e.target.value) : 0;
        totalPagar.value = (e.target.value) == "" ? parseFloat(totalPagarSD).toFixed(2) : nuevoTotal.toFixed(2); 
		let totalGravadas =  parseFloat(ventasGravadas);
		gravadas.value = (e.target.value) == "" ? parseFloat(ventasGravadas).toFixed(2) :(totalGravadas - parseFloat(e.target.value)).toFixed(2);
        let nuevoCambio =  parseFloat(pagar_con.value) - parseFloat(nuevoTotal)
        cambio.value = (e.target.value) == "" ? (parseFloat(pagar_con.value) - parseFloat(totalPagarSD)).toFixed(2)   :  nuevoCambio.toFixed(2);
		cambio.value = cambio.value == "NaN" ? "" : cambio.value;
		if(docuemi.value=="CREDITO FISCAL"){
			let gr = (e.target.value) == "" ? parseFloat(ventasGravadas).toFixed(2) :(totalGravadas - (parseFloat(e.target.value))/1.13).toFixed(2);
			gravadas.value = (e.target.value) == "" ? parseFloat(ventasGravadas).toFixed(2) :(totalGravadas - (parseFloat(e.target.value))/1.13).toFixed(2);
			if(aplicaciones.value!="sinAplicaciones"){
			ivaReten = parseFloat(gravadas.value)*0.01;
			 ivaRetenido.value = ivaReten.toFixed(2);
			
		}
				
		totalIva = (e.target.value) != "" ? Math.round((((parseFloat(gravadas.value)*1.13) - parseFloat(gravadas.value) )+ Number.EPSILON) * 100) / 100 : Math.round(((((ventasGravadas - 0) * 1.13) - (ventasGravadas - 0))+ Number.EPSILON) * 100) / 100 ;
        iva.value = totalIva;
		let nuevoTotal = parseFloat(totalPagarSD) - (parseFloat(e.target.value)) + parseFloat(exentas.value) - parseFloat(ivaRetenido.value) ;
        totalPagar.value = (e.target.value) == "" ? parseFloat(totalPagarSD).toFixed(2) - ivaReten : nuevoTotal.toFixed(2); 
		var vuelto = pagar_con.value !='' ? parseFloat(pagar_con.value) : (parseFloat(totalPagar.value.replaceAll(",","")));
		cambio.value = (vuelto - (parseFloat(totalPagar.value.replaceAll(",","")))).toFixed(2); 
		
		}
    }
	totalPagar.value = parseFloat(totalPagar.value).toFixed(2);
    
})


})

function generarPlan(){
var table = document.getElementById("tblPlan");
  var rowCount = table.rows.length;
  //console.log(rowCount);
  if (rowCount>2){
  for (var i=0; i < rowCount-1; i++){
  if(rowCount <= 1)
    alert('No se puede eliminar el encabezado');
  else
    table.deleteRow(rowCount - [i] -1);	  
  }
  }
	if(metodo.value == "PLAZO"){
		if(totalPagar.value > 0){
			
		}
	if(plazo.value !="" && monto.value!="" && interes.value!="" && plazoSeguro.value!="" && totalPagar.value > 0){
		///////
		var tasaInteres = parseFloat(interes.value) / 100;
		
		////
			var capital = parseFloat(monto.value);	
			var totalInteres = 0; 
			var totalPlan = 0;
			var plazoMeses = parseFloat(plazo.value);
			var cuotaBase = capital / plazoMeses ;
			var intereses =  parseFloat(monto.value) * (parseFloat(interes.value) / 100);
			var interesMensual = intereses / plazoMeses; 
			var totalSeguro = parseFloat(plazoSeguro.value) * parseFloat(plazo.value);
			montoTotal = parseFloat(monto.value) +  intereses + totalSeguro ;
			var cuotaSeguro = parseFloat(plazoSeguro.value);
			var montoMensualCapital = parseFloat(monto.value) / plazoMeses;
			var cuotaMensual = montoMensualCapital + interesMensual+   parseFloat(plazoSeguro.value);
			var meses = ["Enero", "febrero","Marzo","Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre","Diciembre"];
			 var fe = new Date();
             var mes = (fe.getMonth());
			 planPago=[];
			 var planDetalle = "";
			 var contadorMes = 0;
			 var numCuota = 0;
			 listaCarrito;
			 var sumCuota = 0;
			 var sumInteres = 0;
			 var sumCapital = 0;
			  var dias = 0; 
			  var cuota = capital * (tasaInteres/ (1-(Math.pow((1+tasaInteres),-plazoMeses))));
			  var abonoCapital = 0;
			  var totalAbonoCapital = 0;
			 for(var i=0; i<plazoMeses ; i++){
				 numCuota ++;
				contadorMes = mes + 1 + i;
				dias = dias +30;
					var fe = new Date(); // 2020-04-12T22:45:54.194Z
					fe.setDate(fe.getDate() + dias)
					var mes1 = (fe.getMonth() +1);
					var dia = fe.getDate();
					 if(mes1<10){
						  mes1 = "0"+mes1;
					  }
					  if(dia<10){
						  dia = "0"+dia;
					  }
					var fechaFactura = fe.getFullYear()+"-"+mes1+"-"+dia;

				if(i==plazoMeses-1){
				   cuotaMensual = montoTotal - sumCuota; 
				   montoMensualCapital	= 	capital - sumCapital;
				   interesMensual	= 	intereses - sumInteres;		   
				}else{
				sumCuota = sumCuota + parseFloat(cuotaMensual.toFixed(2));
				sumCapital = sumCapital + parseFloat(montoMensualCapital.toFixed(2));
				sumInteres =  sumInteres + parseFloat(interesMensual.toFixed(2))	
				}
				
				while (contadorMes > 11) {
				contadorMes = contadorMes - 12;
				}
				 if(i==0){
				
				 
				 var intMensual  = capital * tasaInteres;
				 capital = capital - (cuota - intMensual );
				 abonoCapital = cuota - intMensual;
				 totalAbonoCapital = totalAbonoCapital + abonoCapital;
				 //var cuota = intMensual + montoMensualCapital + cuotaSeguro ;
				 totalInteres = totalInteres + intMensual;
				 totalPlan = totalPlan + cuota;
					planDetalle = {
				"mes":meses[mes + 1],
				"capital" : abonoCapital.toFixed(2),
				"interes" : intMensual.toFixed(2),
				"Seguro" : parseFloat(plazoSeguro.value),
				"Cuota" : parseFloat(cuota.toFixed(2)) + parseFloat(plazoSeguro.value),
				"Pago" : "Pendiente",
				"numCuota" : numCuota,
				"producto" : listaCarrito,
				"fechaPago" : fechaFactura,
				"abonoCapital" : capital.toFixed(2)
				
			} ;
				 }else{
			 var intMensual  = capital * tasaInteres;
			      abonoCapital = cuota - intMensual;
				  totalAbonoCapital = totalAbonoCapital + abonoCapital;
				 capital = capital - (cuota - intMensual );
			totalInteres = totalInteres + intMensual;
			 totalPlan = totalPlan + cuota;
				planDetalle = {
				"mes":meses[contadorMes],
				"capital" : abonoCapital.toFixed(2),
				"interes" : intMensual.toFixed(2),
				"Seguro" : parseFloat(plazoSeguro.value),
				"Cuota" : parseFloat(cuota.toFixed(2)) + parseFloat(plazoSeguro.value),
				"Pago" : "Pendiente",
				"numCuota" : numCuota,
				"producto" : listaCarrito,
				"fechaPago" : fechaFactura,
				"abonoCapital" : capital.toFixed(2)
			} ; 
					  
					 
				 }
				 	 
				planPago.push(planDetalle); 
				document.getElementById("tblPlan").insertRow(-1).innerHTML = '<td>'+planPago[i].fechaPago+'</td><td>$'+planPago[i].capital+'</td><td>$'+planPago[i].interes+'</td><td>$'+planPago[i].Seguro.toFixed(2)+'</td><td>$'+planPago[i].Cuota+'</td><td>$'+capital.toFixed(2)+'</td>';
				
			 }
			 document.getElementById("tblPlan").insertRow(-1).innerHTML = '<td>TOTALES</td><td>$'+totalAbonoCapital.toFixed(2)+'</td><td>$'+totalInteres.toFixed(2)+'</td><td>$'+totalSeguro.toFixed(2)+'</td><td>$'+(parseFloat(totalPlan.toFixed(2)) + parseFloat(totalSeguro))+'</td><td>$'+totalAbonoCapital.toFixed(2)+'</td>';		 
			console.log(JSON.stringify(planPago));	
			montoTotal = totalPlan.toFixed(2);
			}else{
			alertaPersonalizada('warning', 'TODOS LOS VALORES SON REQUERIDOS PARA PLAN DE PAGO');
			
			}
	}
}

function obtenerPlanesCliente(idCliente){
	            $.ajax({
                url: base_url + 'ventas/buscarPlanes',
                dataType: "json",
                data: {
                    id: idCliente
                },
                success: function (data) {
					if(data.length==0){
				alertaPersonalizada('warning', 'EL CLIENTE SELECCIONADO NO TIENE PLANES ACTIVOS');
					}else{
				for(var i = 0; i<data.length; i++){
					if(data[i].estado!='Anulado'){
					document.getElementById("tblPlanes").insertRow(-1).innerHTML = '<td>'+data[i].id_plan+'</td><td>'+data[i].fecha+'</td><td>$'+data[i].montoTotalPlan+'</td><td>'+data[i].estado+'</td><td><a class="btn btn-success" href="#" onclick="cuotas('+data[i].id_venta+','+data[i].id_plan+','+data[i].id_credito+')"><i class="fas fa-dollar-sign"></i></a><a class="btn btn-danger" href="#" onclick="planPdf(' + data[i].id_plan + ')"><i class="fas fa-file-pdf"></i></a></td>';	
					}
				
				}
					}
                }
            });
	
}

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
						obtenerCliente(idCliente.value);
                        
                    }else{
						
						 alertaPersonalizada(res.type, res.msg);
					}
                }
            }
	
}

function validarCajaPlan(){
	const idClientePlan = document.querySelector('#idClientePlan');
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
						obtenerClientePlan(idClientePlan.value);
                        
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
					if(idPedido.value>0){
						
					}else{
					descuento.value= 0;	
					}
					
					pagar_con.value ='';
					cambio.value = '';
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
					ventasGravadas = docuemi.value == "CREDITO FISCAL" ? res.gravadas : (Math.round(((parseFloat(res.gravadas) + parseFloat(res.iva))+ Number.EPSILON) * 100) / 100).toFixed(2);
                    totalPagarSD = res.totalVentaSD;
					tblNuevaVenta.innerHTML = html;
                    totalPagar.value = res.totalVenta;
                    totalPagarHidden.value = res.totalVentaSD; 
					gravadas.value = docuemi.value == "CREDITO FISCAL" ? res.gravadas : (Math.round(((parseFloat(res.gravadas) + parseFloat(res.iva))+ Number.EPSILON) * 100) / 100).toFixed(2);
					exentas.value = res.exentos;
					ivaRetenido.value = 0;
					iva.value = docuemi.value == "CREDITO FISCAL" ? Math.round(((parseFloat(res.iva))+ Number.EPSILON) * 100) / 100 : 0 ;
                    btnEliminarProducto();
                    agregarCantidad();
                    agregarPrecioVenta();
					}else if(aplicaciones.value=="retencion"){
					ventasGravadas = docuemi.value == "CREDITO FISCAL" ? res.gravadas : (Math.round(((parseFloat(res.gravadas) + parseFloat(res.iva))+ Number.EPSILON) * 100) / 100).toFixed(2);	
					tblNuevaVenta.innerHTML = html;
					totalPagarSD = res.totalVentaSD;
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
					pagar_con.value = '';
					cambio.value = '';
					descuento.value = 0;
                }
            }
        }
    } else {
        tblNuevaVenta.innerHTML = `<tr>
            <td colspan="4" class="text-center">CARRITO VACIO</td>
        </tr>`;
    }
			generarPlan();
}

function pagarCuota(numCuota,cuota){
	const idVentaPlan = document.querySelector('#idVentaPlan');
	
	
	            $.ajax({
                url: base_url + 'ventas/getPlanDePago',
                dataType: "json",
                data: {
                    id: idVentaPlan.value
                },
                success: function (data) {
planActual = JSON.parse(data.detalle_plan);
validarCajaPlan();
                }
            });	
	
	
	
}

function planPdf(idPlan) {
	
 const ruta = base_url + 'ventas/planPdf/ticked/' + idPlan;
  window.open(ruta, '_blank');	
  
}

function verPlanPago(){
	var planP = JSON.stringify(planPago)
	const url = base_url +'ventas/registrarPlanPrevio';
	 const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
				 monto : monto.value,
				plazo : plazo.value,
				interes : interes.value,
				cuotaSeguro : plazoSeguro.value,
				plan : planP,
				montoTotalPlan : montoTotal,
				cliente : buscarCliente.value
			}));
			
			            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    console.log(this.responseText);
                    alertaPersonalizada(res.type, res.msg);
                    if (res.type == 'success') {
                 const ruta = base_url + 'ventas/planPdfPrevio/ticked/' + res.idPlan;
                window.open(ruta, '_blank');	
                    }
                }
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
            const ruta = base_url + 'ventas/reporte/ticked/' + idVenta;
            window.open(ruta, '_blank');
        } else if (result.isDenied) {
            const ruta = base_url + 'ventas/reporte/impresion/' + idVenta;
            window.open(ruta, '_blank');
        }else if (result.isDismissed && result.dismiss !="close" ) {
            const ruta = base_url + 'ventas/reporte/pdf/' + idVenta;
            window.open(ruta, '_blank');
        }
		
    })
}

function cuotas(idVenta,idPlan,idCredito) {
window.location.href = base_url + 'ventas/cuotas/' + idVenta + '/'+ puntoVenta+'/'+idPlan+'/'+idCredito;

}

function prueba() {
alert("0000");

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
			   window.location.href = base_url + 'ventas/anularDte/' + idVenta;
			    

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

function obtenerClientePlan(idCliente){
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
					maxCorrelativoPlan(data);
					}
					
					
  
                }
            });
	
}

function maxCorrelativoPlan(cliente){
const docuemiPlan = document.querySelector('#docuemiPlan');
const pdv = document.querySelector('#pdv');
	
	            $.ajax({
                url: base_url + 'ventas/maxCorrelativo',
                dataType: "json",
                data: {
                    tipoDocumento: docuemiPlan.value,
					codPuntoVentaMH : pdv.value
                },
                success: function (data) {
					if(docuemiPlan.value=="CREDITO FISCAL"){
					crearDte(cliente,data);
					} else if(docuemiPlan.value=="FACTURA"){
						crearDteFacturaPlan(cliente,data);
						
					}else if(docuemiPlan.value=="EXPORTACION"){
						crearDteFacturaExp(cliente,data);
						
					}
  
                }
            });
	
}

function maxCorrelativo(cliente){
	if(puntoVenta ==""){
	puntoVenta = pdv.value;	
	}
	            $.ajax({
                url: base_url + 'ventas/maxCorrelativo',
                dataType: "json",
                data: {
                    tipoDocumento: docuemi.value,
					codPuntoVentaMH : puntoVenta
                },
                success: function (data) {
					if(docuemi.value=="CREDITO FISCAL"){
					crearDte(cliente,data);
					} else if(docuemi.value=="FACTURA"){
						crearDteFactura(cliente,data);
						
					}else if(docuemi.value=="EXPORTACION"){
						crearDteFacturaExp(cliente,data);
						
					}
  
                }
            });
	
}

function crearDteFacturaExp(dataCliente,correlativo){
	
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
	var actividadExportacion = dataCliente[0].actividadExportacion;
	var paisReceptor = dataCliente[0].pais;
	var idPaisReceptor = paisReceptor.split("-");
	paisReceptor = idPaisReceptor[1];
	idPaisReceptor = idPaisReceptor[0];
		
		
		 for (i = 0; i < listaCarrito.length; i++) {
	 var cuerpo={
            numItem: i+1,
            codigo: null,
            descripcion: listaCarrito[i].descripcion,
            cantidad:  parseFloat(listaCarrito[i].cantidad),
			uniMedida: parseInt(listaCarrito[i].mediaMh),
            precioUni: (Math.round10(parseFloat(listaCarrito[i].precio),-8)),
            montoDescu: 0,
            ventaGravada: listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Normal" ? Math.round(((parseFloat(listaCarrito[i].precio) * listaCarrito[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
			tributos : null,
			noGravado : 0
} 

cuerpodocumento.push(cuerpo);	
totalGravada = Math.round(((totalGravada + cuerpo.ventaGravada)+ Number.EPSILON) * 100) / 100;
subTotal = subTotal + (parseFloat(listaCarrito[i].precio) * parseFloat(listaCarrito[i].cantidad));
subTotal =  Math.round(((subTotal)+ Number.EPSILON) * 100) / 100;
} 

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
			 version : parseInt(versionExportacion),
			 ambiente : ambiente,
			 tipoDte  : tipoDTEExportacion,
			 numeroControl : crearCorrelativo(correlativo[0].correlativo),
			 codigoGeneracion : create_UUID(),
			  tipoModelo : transmision=="Contingencia" ? 2 : 1,
			 tipoOperacion : transmision=="Contingencia" ? 2 : 1,
			 tipoContingencia :transmision=="Contingencia" ? 1 : null,
			 motivoContigencia: null,
			 fecEmi : fechaFactura,
			 horEmi : now,
			 tipoMoneda : tipoMoneda
			 
		 },
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
			 codPuntoVenta : null,
			 tipoItemExpor: 2,
             recintoFiscal: null,
             regimen: null
			 
		 },
		 receptor : {
			 tipoDocumento: "13",
			 numDocumento: duiReceptor,
             nombre: nombreReceptor,
			 nombreComercial: null,
             codPais: idPaisReceptor,
             nombrePais: paisReceptor,
             complemento: direccionReceptor,
             tipoPersona: 1,
			 descActividad: actividadExportacion,
             telefono: telefonoReceptor,
             correo: correoReceptor
		 },
		 cuerpoDocumento :  cuerpodocumento,
		 resumen : {
			 totalGravada :  totalGravada,
			 descuento: parseFloat(descuento.value),
			 porcentajeDescuento : 0,
			 totalDescu : parseFloat(descuento.value),
			 seguro: 0,
             flete: 0,
			 montoTotalOperacion : totalGravada + totalExenta - parseFloat(descuento.value),
			 totalNoGravado : 0,
			 totalPagar :totalGravada + totalExenta - parseFloat(ivaRetenido.value) - parseFloat(descuento.value),
			 totalLetras : NumeroALetras(totalGravada + totalExenta - parseFloat(ivaRetenido.value)),
			 condicionOperacion : metodo.value == "CREDITO" ? 2 : 1,
			 pagos : [
			 {
				 codigo : formaPago.value,
				 montoPago :totalGravada + totalExenta - parseFloat(ivaRetenido.value) - parseFloat(descuento.value),
				 plazo :  metodo.value == "CREDITO" ? "02" : null,
				 referencia : "",
				 periodo : metodo.value == "CREDITO" ? 1 : null
				 
			 }
			 ],
			  codIncoterms: null,
              descIncoterms: null,
              numPagoElectronico: null,
              observaciones: ""
		 },
		      otrosDocumentos: null,
              ventaTercero: null,
              apendice: null

		}		 
	 };
	 
	 firmador(JSON.stringify(jsondteObj),correlativo);
		
	}

function crearDteFactura(dataCliente,correlativo){
	
	var duiReceptor = dataCliente[0].DUI;
	var tipoDoc = "13";
	if(duiReceptor.length>10){
	var tipoDoc = "36";	
	}
	var nombreReceptor = dataCliente[0].nombre;
	var telefonoReceptor = dataCliente[0].telefono;
	var correoReceptor = dataCliente[0].correo;
	var municipioReceptor = dataCliente[0].municipio;
	var idMunicipioReceptor = municipioReceptor.split("-");
		municipioReceptor = idMunicipioReceptor[1];
		idMunicipioReceptor = idMunicipioReceptor[0];
		var departamentoReceptor = dataCliente[0].departamento;
	var idDepartamentoReceptor = departamentoReceptor.split("-");
		departamentoReceptor = idDepartamentoReceptor[1];
		idDepartamentoReceptor = idDepartamentoReceptor[0];
	var direccionReceptor = dataCliente[0].direccion + ', '+municipioReceptor +', '+municipioReceptor;
	    direccionReceptor = direccionReceptor.replaceAll("<p>","");
		direccionReceptor = direccionReceptor.replaceAll("</p>","");	
	if(idMunicipioReceptor==""){
	direccionReceptor=null;
	idMunicipioReceptor =null;
	idDepartamentoReceptor =null;
	}
	
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
            uniMedida: parseInt(listaCarrito[i].mediaMh),
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
			 tipoDocumento: tipoDoc,
			 numDocumento: duiReceptor,
             nombre: nombreReceptor,
             nrc: null,
             codActividad: null,
             descActividad: null,
             direccion: null,
             telefono: telefonoReceptor,
             correo: correoReceptor,
			  direccion : direccionReceptor == null ? null : {
				 departamento : departamentoEmisor,
				 municipio : municipioEmisor,
				 complemento : direccionReceptor
			 }
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
			 montoTotalOperacion : totalGravada + totalExenta - parseFloat(descuento.value),
			 totalNoGravado : 0,
			 totalPagar : Math.round(((totalGravada + totalExenta - parseFloat(ivaRetenido.value) - parseFloat(descuento.value))+ Number.EPSILON) * 100) / 100,
			 totalLetras : NumeroALetras(Math.round(((totalGravada + totalExenta - parseFloat(ivaRetenido.value) - parseFloat(descuento.value))+ Number.EPSILON) * 100) / 100),
			 saldoFavor : 0,
			 condicionOperacion : metodo.value == "CREDITO" ? 2 : 1,
			 pagos : [
			 {
				 codigo : formaPago.value,
				 montoPago :  Math.round(((totalGravada + totalExenta - parseFloat(ivaRetenido.value) - parseFloat(descuento.value))+ Number.EPSILON) * 100) / 100,
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
			 observaciones : metodo.value == "PLAZO" ? "Pago se efectuo en concepto de prima" : null,
			 placaVehiculo : null			 			 
		 },
		 apendice : null,
		}		 
	 };
	 
	 firmador(JSON.stringify(jsondteObj),correlativo);
	 
		
	}

	
	function crearDteFacturaPlan(dataCliente,correlativo){
	
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
	var cuotaEnCurso = 	planActual[0].producto;
		
		 for (i = 0; i < cuotaEnCurso.length; i++) {
	 var cuerpo={
            numItem: i+1,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: cuotaEnCurso[i].descripcion,
            cantidad:  parseFloat(cuotaEnCurso[i].cantidad),
            uniMedida: parseInt(listaCarrito[i].mediaMh),
            precioUni: parseFloat(cuotaEnCurso[i].precio),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta:  cuotaEnCurso[i].cantidad != "" && cuotaEnCurso[i].catalogo =="Exento" ? Math.round(((parseFloat(cuotaEnCurso[i].precio) * cuotaEnCurso[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
            ventaGravada: cuotaEnCurso[i].cantidad != "" && cuotaEnCurso[i].catalogo =="Normal" ? Math.round(((parseFloat(cuotaEnCurso[i].precio) * cuotaEnCurso[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
			tributos : null,
		    psv : 0,
		    noGravado : 0,
			ivaItem : (Math.round10(parseFloat(cuotaEnCurso[i].precio * cuotaEnCurso[i].cantidad ) - parseFloat((cuotaEnCurso[i].precio*cuotaEnCurso[i].cantidad)/1.13) ,-8)) 
} 

cuerpodocumento.push(cuerpo);	
totalGravada = Math.round(((totalGravada + cuerpo.ventaGravada)+ Number.EPSILON) * 100) / 100;
totalExenta = Math.round(((totalExenta + cuerpo.ventaExenta)+ Number.EPSILON) * 100) / 100;
subTotal = subTotal + (parseFloat(cuotaEnCurso[i].precio) * parseFloat(cuotaEnCurso[i].cantidad));
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
			 montoTotalOperacion : totalGravada + totalExenta - parseFloat(descuento.value),
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
	
	var nitReceptor = dataCliente[0].identidad;
	var nrcReceptor = dataCliente[0].num_identidad;
	var nombreReceptor = dataCliente[0].nombre;
	var telefonoReceptor = dataCliente[0].telefono;
	var correoReceptor = dataCliente[0].correo;
	var direccionReceptor = dataCliente[0].direccion;
	    direccionReceptor = direccionReceptor.replaceAll("<p>","");
		direccionReceptor = direccionReceptor.replaceAll("</p>","");
	var actividadReceptor = dataCliente[0].actividad;
	var idActividadReceptor = actividadReceptor.split("-");
		actividadReceptor = idActividadReceptor[1];
		idActividadReceptor =idActividadReceptor[0];
	var municipioReceptor = dataCliente[0].municipio;
	var idMunicipioReceptor = municipioReceptor.split("-");
		municipioReceptor = idMunicipioReceptor[1];
		idMunicipioReceptor = idMunicipioReceptor[0];
	var departamentoReceptor = dataCliente[0].departamento;
	var idDepartamentoReceptor = departamentoReceptor.split("-");
		departamentoReceptor = idDepartamentoReceptor[1];
		idDepartamentoReceptor = idDepartamentoReceptor[0];
    var cuerpodocumento=[];
	var totalGravada = 0;
	var subTotal= 0;
	var totalExento = 0;
		
		
		 for (i = 0; i < listaCarrito.length; i++) {
	 var cuerpo={
            numItem: i+1,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: listaCarrito[i].descripcion,
            cantidad:  parseFloat(listaCarrito[i].cantidad),
             uniMedida: parseInt(listaCarrito[i].mediaMh),
            precioUni: listaCarrito[i].catalogo =="Normal" ? (Math.round10(parseFloat(listaCarrito[i].precio/1.13),-8)) : (Math.round10(parseFloat(listaCarrito[i].precio),-8)),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta: listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Exento" ? Math.round(((parseFloat(listaCarrito[i].precio) * listaCarrito[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
            ventaGravada: listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Normal"? Math.round(((parseFloat(listaCarrito[i].precio/1.13) * listaCarrito[i].cantidad )+ Number.EPSILON) * 100) / 100 :0,
			tributos : listaCarrito[i].cantidad != "" && listaCarrito[i].catalogo =="Normal"  ?  [ "20"] : null,
		    psv : 0,
		    noGravado : 0
} 
cuerpodocumento.push(cuerpo);	
totalExento = Math.round(((totalExento + cuerpo.ventaExenta)+ Number.EPSILON) * 100) / 100;
totalGravada = Math.round(((totalGravada + cuerpo.ventaGravada)+ Number.EPSILON) * 100) / 100;
subTotal = subTotal + (parseFloat(listaCarrito[i].precio) * parseFloat(listaCarrito[i].cantidad));
totalIva =  parseFloat(iva.value);
subTotal =  Math.round(((subTotal)+ Number.EPSILON) * 100) / 100;
}

var MontoFinal = totalGravada + totalExento - parseFloat(ivaRetenido.value) + totalIva ;
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
			 version : parseInt(version),
			 ambiente : ambiente,
			 tipoDte  : tipoDTECredito,
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
			 nit :  nitReceptor,
			 nrc :  nrcReceptor,
			 nombre : nombreReceptor,
			 codActividad : idActividadReceptor,
			 descActividad : actividadReceptor,
			 nombreComercial : null,
			 direccion : {
				 departamento : idDepartamentoReceptor,
				 municipio : idMunicipioReceptor,
				 complemento : direccionReceptor
			 },
			 telefono : telefonoReceptor,
			 correo : correoReceptor
		 },
		 otrosDocumentos : null,
		 ventaTercero : null,
		 cuerpoDocumento :  cuerpodocumento,
		 resumen : {
			 totalNoSuj : 0,
			 totalExenta : totalExento,
			 totalGravada :  totalGravada,
			 subTotalVentas : Math.round(((totalGravada + totalExento)+ Number.EPSILON) * 100) / 100,
			 descuNoSuj : 0,
			 descuExenta : 0,
			 descuGravada : descuento.value=="0" ? parseFloat(descuento.value) :  parseFloat((parseFloat(descuento.value)/1.13).toFixed(2)) ,
			 porcentajeDescuento : 0,
			 totalDescu : descuento.value=="0" ? parseFloat(descuento.value) : parseFloat((parseFloat(descuento.value)/1.13).toFixed(2)),
			 tributos : totalIva > 0 ? [{codigo : "20",descripcion : "Impuesto al Valor Agregado13%",valor : parseFloat(totalIva.toFixed(2))}] : null,
			 subTotal :Math.round(((totalGravada + totalExento - (parseFloat(descuento.value)/1.13) )+ Number.EPSILON) * 100) / 100,
			 ivaPerci1 : 0,
			 ivaRete1 : parseFloat(ivaRetenido.value),
			 reteRenta : 0,
			 montoTotalOperacion : Math.round(((parseFloat(totalPagar.value.replaceAll(",","")) + parseFloat(ivaRetenido.value) )+ Number.EPSILON) * 100) / 100,
			 totalNoGravado : 0,
			 totalPagar :parseFloat(totalPagar.value.replaceAll(",","")),
			 totalLetras : NumeroALetras(parseFloat(totalPagar.value.replaceAll(",",""))),
			 saldoFavor : 0,
			 condicionOperacion : metodo.value == "CREDITO" ? 2 : 1,
			 pagos : [
			 {
				 codigo : formaPago.value,
				 montoPago : parseFloat(totalPagar.value.replaceAll(",","")),
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
			 observaciones : metodo.value == "PLAZO" ? "Pago se efectuo en concepto de prima" : null,
			 placaVehiculo : null			 			 
		 },
		 apendice : null,
		}		 
	 };
	 
	 firmador(JSON.stringify(jsondteObj),correlativo);
	
		
	}
	

	
	function crearCorrelativo(correlativo){
			var correlativo = correlativo != "" && correlativo != null ? correlativo.toString() : "0" ;
			 var controlDTE="";
			 var str = docuemi.value =="CREDITO FISCAL" ? creditoBase : docuemi.value =="FACTURA"  ? consumidorBase : exportacionBase;
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
              console.log(correlativo);

             correlativo = correlativo.toString();
              console.log(correlativo);
            controlDTE = controlDTE + correlativo;
			var strCorr = controlDTE.substring(15, 31);
			controlDTE = controlDTE.substring(0, 7) + puntoVenta + strCorr ;
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

	if(docuemi.value=='CREDITO FISCAL'){
var req = {
	ambiente : ambiente,
	idEnvio : 1,
	version : 3,
	tipoDte : "03",
	documento : Dtefirmado	
	}
	}else if(docuemi.value=='FACTURA'){

var req = {
	ambiente : ambiente,
	idEnvio : 1,
	version : versionConsumidor,
	tipoDte : tipoDTEConsumidor,
	documento : Dtefirmado	
}
	}else if(docuemi.value=='EXPORTACION'){

var req = {
	ambiente : ambiente,
	idEnvio : 1,
	version : versionExportacion,
	tipoDte : tipoDTEExportacion,
	documento : Dtefirmado	
}
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
		 const url = base_url + 'ventas/registrarVenta';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                productos: listaCarrito,
                idCliente: idCliente.value,
                forma: formaPago.options[formaPago.selectedIndex].text,
                forma2: forma2.value,
                docuemi: docuemi.value,
                numdocu: numdocu.value,
                vende: vende.value,
                metodo: metodo.value,
                descuento: descuento.value,
                pago: pagar_con.value,
                impresion: impresion_directa.checked,
				correlativo: correlativo[0].correlativo != "" && correlativo[0].correlativo != null ?  parseInt(correlativo[0].correlativo) +1 : 1,
				numeroControlDte : objdte.dteJson.identificacion.numeroControl,
				dte: JSON.stringify(objdte),
				uuid: objdte.dteJson.identificacion.codigoGeneracion,
				tipoTransmision : transmision,
				codPuntoVentaMH : puntoVenta,
				total : objdte.dteJson.resumen.totalPagar,
				sello: objdte.selloRecibido,
				vExentas:objdte.dteJson.resumen.totalExenta,
				vIva : docuemi.value=="CREDITO FISCAL" ? objdte.dteJson.resumen.totalExenta > 0 ? 0 : objdte.dteJson.resumen.tributos[0].valor : 0 ,
				vGravadas : objdte.dteJson.resumen.totalGravada,
				retenIva : docuemi.value=="CREDITO FISCAL" ? objdte.dteJson.resumen.ivaRete1 : objdte.dteJson.resumen.ivaRete1,
				monto : monto.value,
				plazo : plazo.value,
				interes : interes.value,
				cuotaSeguro : plazoSeguro.value,
				planPagoDetalle : JSON.stringify(planPago),
				montoTotalPlan : montoTotal,
				tipoOp : tipo_operacion.value,
	            tipoVen : tipo_ingreso.value,
				pedido : idPedido.value

				
				
				
                
               
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
                                    const ruta = base_url + 'ventas/reporte/ticked/' + res.idVenta;
                                    window.open(ruta, '_blank');
                                } else if (result.isDenied) {
                                    const ruta = base_url + 'ventas/reporte/impresion/' + res.idVenta;
                                    window.open(ruta, '_blank');
                                }
                                 window.location.href = base_url + 'ventas';
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

