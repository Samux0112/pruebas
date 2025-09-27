const tblNuevaVenta = document.querySelector('#tblNuevaVenta tbody');
const docuemi = document.querySelector('#docuemi');
const numdocu = document.querySelector('#numdocu');
const vende = document.querySelector('#vende');
const forma = document.querySelector('#forma');
const forma2 = document.querySelector('#forma2');
const tblPlanes = document.querySelector('#tblPlanes');
const idPlan = document.querySelector('#idPlan');

var ventasGravadas = 0;
var totalPagarSD =0;
var totalIva = 0;
var transmision = "Normal";
var puntoVenta = "";
var planActual=[];
let planPago=[];
var montoTotal=0;
var mora = 0;
var cuerpodocumentoCredito=[];
const buscarClientePlan = document.querySelector('#buscarClientePlan');
const idClientePlan = document.querySelector('#idClientePlan');
const docuemiPlan = document.querySelector('#docuemiPlan');
const pdv = document.querySelector('#pdv');
var numeroCuota=0;
var totalCuota = 0;

const tipo_operacion = document.querySelector('#tipo_operacion');
const tipo_ingreso = document.querySelector('#tipo_ingreso');


const plazo = document.querySelector('#plazo');
const monto = document.querySelector('#monto');
const interes = document.querySelector('#interes');
const btnPlanPago = document.querySelector('#btnPlanPago');
const plazoSeguro = document.querySelector('#plazoSeguro');
const tblPlan = document.querySelector('#tblPlan');



const idCliente = document.querySelector('#idCliente');


const telefonoCliente = document.querySelector('#telefonoCliente');
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
   // mostrarProducto();

    //autocomplete clientes
	
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
	
		  



})

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
				document.getElementById("tblPlanes").insertRow(-1).innerHTML = '<td>'+data[i].id_plan+'</td><td>'+data[i].fecha+'</td><td>$'+data[i].montoTotalPlan+'</td><td>Activo</td><td><a class="btn btn-success" href="#" onclick="cuotas('+data[i].id_venta+')"><i class="fas fa-file-pdf"></i></a></td>';
				
				}
				
					}
                }
            });
	
}


function validarCajaPlan(){
		                        setTimeout(() => {
                            Swal.fire({
                                title: 'Generando Factura',
								showConfirmButton: false,
								timerProgressBar: true,
                            })

                        }, 2000);
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


function pagarCuota(numCuota,cuota){
	const idVentaPlan = document.querySelector('#idVentaPlan');
	numeroCuota = numCuota;
	totalCuota = cuota;
	
	 mora = document.querySelector('#mora_'+numeroCuota);
	 if(mora.value!=""){
	mora = parseFloat(mora.value);	 
	 }else{
	mora = 0;
	 }
	 
	var MontoTotal = parseFloat(cuota) + mora
							
		  Swal.fire({
		title: 'Genera factura por un monto de $'+MontoTotal.toFixed(2),
		showCancelButton: true,
		confirmButtonText: 'Aceptar',
	}).then((result) => {
		/* Read more about isConfirmed, isDenied below */
		if (result.isConfirmed) {
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

	})
}

function pagarCuotaContingencia(numCuota,cuota){
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
									 const idVentaPlan = document.querySelector('#idVentaPlan');
	numeroCuota = numCuota;
	totalCuota = cuota;
	
	 mora = document.querySelector('#mora_'+numeroCuota);
	 if(mora.value!=""){
	mora = parseFloat(mora.value);	 
	 }else{
	mora = 0;
	 }
	 
	var MontoTotal = parseFloat(cuota) + mora
							
		  Swal.fire({
		title: 'Genera factura por un monto de $'+MontoTotal.toFixed(2),
		showCancelButton: true,
		confirmButtonText: 'Aceptar',
	}).then((result) => {
		/* Read more about isConfirmed, isDenied below */
		if (result.isConfirmed) {
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

	})
									
                                } else if (result.isDenied) {
                                     
                                }
                            })
	
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

function cuotas(idVenta) {
window.location.href = base_url + 'ventas/cuotas/' + idVenta + '/'+ puntoVenta;

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
	var productos = "";	
	var cuotaEnCurso = 	planActual[0].producto;
	 mora = document.querySelector('#mora_'+numeroCuota);
	 mora = parseFloat(mora.value);
		 for (i = 0; i < cuotaEnCurso.length; i++) {
			 if (i==0){
			productos = cuotaEnCurso[i].descripcion;
			 }
			 if(i>0){
			 productos = productos + ' , '+cuotaEnCurso[i].descripcion	 
			 }
			 }
	
	 var cuerpo={
            numItem: 1,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: 'Referencia plan de pagos #'+ idPlan.value+ ' Cuota N. ' +numeroCuota+ " producto : "+ productos,
            cantidad:  1,
            uniMedida: 59,
            precioUni: (Math.round10((parseFloat(totalCuota)/1.13),-8)),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta: 0,
            ventaGravada: Math.round(((parseFloat(totalCuota/1.13) * 1 )+ Number.EPSILON) * 100) / 100,
			tributos :[ "20"],
		    psv : 0,
		    noGravado : 0
}
var cuerpoCredito={
            numItem: 1,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: 'Referencia plan de pagos #'+ idPlan.value+ ' Cuota N. ' +numeroCuota+ " producto : "+ productos,
            cantidad:  1,
            uniMedida: 59,
            precioUni: (Math.round10((parseFloat(totalCuota)),-8)),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta: 0,
            ventaGravada: Math.round(((parseFloat(totalCuota) * 1 )+ Number.EPSILON) * 100) / 100,
			tributos :[ "20"],
		    psv : 0,
		    noGravado : 0
}  
cuerpodocumentoCredito.push(cuerpoCredito);
cuerpodocumento.push(cuerpo);
if(mora>0){
 var cuerpo={
            numItem: 2,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: 'Mora por pago tardio',
            cantidad:  1,
            uniMedida: 59,
            precioUni: (Math.round10((parseFloat(mora)/1.13),-8)),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta: 0,
            ventaGravada: Math.round(((parseFloat(mora/1.13) * 1 )+ Number.EPSILON) * 100) / 100,
			tributos :[ "20"],
		    psv : 0,
		    noGravado : 0
}
var cuerpoCredito={
            numItem: 2,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: 'Mora por pago tardio',
            cantidad:  1,
            uniMedida: 59,
            precioUni: (Math.round10((parseFloat(mora)),-8)),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta: 0,
            ventaGravada: Math.round(((parseFloat(mora) * 1 )+ Number.EPSILON) * 100) / 100,
			tributos :[ "20"],
		    psv : 0,
		    noGravado : 0
}
cuerpodocumentoCredito.push(cuerpoCredito);
cuerpodocumento.push(cuerpo);	
}	
for (var i=0 ; i<cuerpodocumento.length; i++){
totalExento = Math.round(((totalExento + cuerpodocumento[i].ventaExenta)+ Number.EPSILON) * 100) / 100;
totalGravada = Math.round(((totalGravada + cuerpodocumento[i].ventaGravada)+ Number.EPSILON) * 100) / 100;	
}

subTotal = subTotal + (parseFloat(totalCuota) * 1);
totalIva =  parseFloat((totalCuota + mora) - ((totalCuota+mora)/1.13) );
subTotal =  Math.round(((subTotal)+ Number.EPSILON) * 100) / 100;
totalIva = Math.round(((totalIva)+ Number.EPSILON) * 100) / 100;

var MontoFinal = totalGravada + totalExento + totalIva ;
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
			 descuGravada : 0 ,
			 porcentajeDescuento : 0,
			 totalDescu : 0,
			 tributos : totalIva > 0 ? [{codigo : "20",descripcion : "Impuesto al Valor Agregado13%",valor : parseFloat(totalIva.toFixed(2))}] : null,
			 subTotal :Math.round(((totalGravada + totalExento)+ Number.EPSILON) * 100) / 100,
			 ivaPerci1 : 0,
			 ivaRete1 : 0,
			 reteRenta : 0,
			 montoTotalOperacion : Math.round(((parseFloat(MontoFinal))+ Number.EPSILON) * 100) / 100,
			 totalNoGravado : 0,
			 totalPagar :Math.round(((parseFloat(MontoFinal) * 1 )+ Number.EPSILON) * 100) / 100,
			 totalLetras : NumeroALetras(Math.round(((parseFloat(MontoFinal) * 1 )+ Number.EPSILON) * 100) / 100),
			 saldoFavor : 0,
			 condicionOperacion :1,
			 pagos : [
			 {
				 codigo : "01",
				 montoPago : Math.round(((parseFloat(MontoFinal) * 1 )+ Number.EPSILON) * 100) / 100,
				 plazo : null,
				 referencia : "",
				 periodo :null
				 
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
	var productos = "";	
	 mora = document.querySelector('#mora_'+numeroCuota);
	 mora = parseFloat(mora.value);
		 for (i = 0; i < cuotaEnCurso.length; i++) {
			 if (i==0){
			productos = cuotaEnCurso[i].descripcion;
			 }
			 if(i>0){
			 productos = productos + ' , '+cuotaEnCurso[i].descripcion	 
			 }
			 } 
	 var cuerpo={
            numItem: 1,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: 'Referencia plan de pagos #'+ idPlan.value+ ' Cuota N. ' +numeroCuota+ " producto : "+ productos,
            cantidad:  1,
            uniMedida: 59,
            precioUni: parseFloat(totalCuota),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta: 0,
            ventaGravada: Math.round(((parseFloat(totalCuota) * 1 )+ Number.EPSILON) * 100) / 100,
			tributos : null,
		    psv : 0,
		    noGravado : 0,
			ivaItem : (Math.round10(parseFloat(totalCuota * 1 ) - parseFloat((totalCuota*1)/1.13) ,-8)) 
} 

cuerpodocumento.push(cuerpo);
if(mora>0){
 var cuerpo={
            numItem: 2,
            tipoItem: 1,
            numeroDocumento: null,
            codigo: null,
            codTributo: null,
            descripcion: 'Mora por pago tardio',
            cantidad:  1,
            uniMedida: 59,
            precioUni: parseFloat(mora),
            montoDescu: 0,
            ventaNoSuj: 0,
            ventaExenta: 0,
            ventaGravada: Math.round(((parseFloat(mora) * 1 )+ Number.EPSILON) * 100) / 100,
			tributos : null,
		    psv : 0,
		    noGravado : 0,
			ivaItem : (Math.round10(parseFloat(mora * 1 ) - parseFloat((mora*1)/1.13) ,-8)) 
} 
cuerpodocumento.push(cuerpo);	
}	
totalGravada = Math.round(((totalGravada + cuerpodocumento[0].ventaGravada + mora )+ Number.EPSILON) * 100) / 100;
totalExenta = Math.round(((totalExenta + cuerpodocumento[0].ventaExenta)+ Number.EPSILON) * 100) / 100;
subTotal = subTotal + (parseFloat(totalCuota) * 1) + mora;
totalIva =  Math.round(((totalGravada + mora - ((totalGravada + mora)/1.13))+ Number.EPSILON) * 100) / 100;
subTotal =  Math.round(((subTotal)+ Number.EPSILON) * 100) / 100;

totalIva = (totalGravada) - ((totalGravada )/1.13);
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
			 descuGravada : 0,
			 porcentajeDescuento : 0,
			 totalDescu : 0,
			 tributos : null,
			 subTotal :totalGravada,
			 ivaRete1 : 0,
			 reteRenta : 0,
			 montoTotalOperacion : totalGravada,
			 totalNoGravado : 0,
			 totalPagar :totalGravada ,
			 totalLetras : NumeroALetras(totalGravada),
			 saldoFavor : 0,
			 condicionOperacion : 1,
			 pagos : [
			 {
				 codigo : "01",
				 montoPago :totalGravada,
				 plazo :  null,
				 referencia : "",
				 periodo : null
				 
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

	
	function crearCorrelativo(correlativo){
			var correlativo = correlativo != "" && correlativo != null ? correlativo.toString() : "0" ;
			 var controlDTE="";
			 var str = docuemiPlan.value =="CREDITO FISCAL" ? creditoBase : docuemiPlan.value =="FACTURA"  ? consumidorBase : exportacionBase;
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
			controlDTE = controlDTE.substring(0, 7) + pdv.value + strCorr ;
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

	if(docuemiPlan.value=='CREDITO FISCAL'){
var req = {
	ambiente : ambiente,
	idEnvio : 1,
	version : 3,
	tipoDte : "03",
	documento : Dtefirmado	
	}
	}else if(docuemiPlan.value=='FACTURA'){

var req = {
	ambiente : ambiente,
	idEnvio : 1,
	version : versionConsumidor,
	tipoDte : tipoDTEConsumidor,
	documento : Dtefirmado	
}
	}else if(docuemiPlan.value=='EXPORTACION'){

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

function anularVenta(idVenta,idPlan,cuota) {
	var indiceCuota = cuota -1;
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
			   window.location.href = base_url + 'ventas/anularDteCuota/' + idVenta +'/'+ idPlan+'/'+indiceCuota;
			    

        }
    })
}

function guardarDte(objdte,correlativo){
	planActual[numeroCuota-1].Pago = "Pagado";
	var cuotaPagada = numeroCuota-1;
		 const url = base_url + 'ventas/registrarVentaCuota';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                productos: docuemiPlan.value == "FACTURA" ? objdte.dteJson.cuerpoDocumento : cuerpodocumentoCredito ,
                idCliente: idClientePlan.value,
                forma: forma.value,
                forma2: "",
                docuemi: docuemiPlan.value,
                numdocu: docuemiPlan.value == "FACTURA" ? '1' : '3',
                vende: vende.value,
                metodo: 'PAGO CUOTA',
                descuento: 0,
                pago: objdte.dteJson.resumen.totalPagar,
                impresion: "",
				correlativo: correlativo[0].correlativo != "" && correlativo[0].correlativo != null ?  parseInt(correlativo[0].correlativo) +1 : 1,
				numeroControlDte : objdte.dteJson.identificacion.numeroControl,
				dte: JSON.stringify(objdte),
				uuid: objdte.dteJson.identificacion.codigoGeneracion,
				tipoTransmision : transmision,
				codPuntoVentaMH : pdv.value,
				total : objdte.dteJson.resumen.totalPagar,
				sello: objdte.selloRecibido,
				vExentas:objdte.dteJson.resumen.totalExenta,
				vIva : docuemiPlan.value=="CREDITO FISCAL" ? objdte.dteJson.resumen.tributos[0].valor : 0 ,
				vGravadas : objdte.dteJson.resumen.totalGravada,
				retenIva : docuemiPlan.value=="CREDITO FISCAL" ? objdte.dteJson.resumen.ivaRete1 : 0.00,
				monto : totalCuota,
				plazo : "",
				interes : "",
				cuotaSeguro :"",
				planPagoDetalle : JSON.stringify(planActual),
				montoTotalPlan : "",
				idPlanPago : idPlan.value,
				credito : idCredito.value,
				indicePagado : cuotaPagada,
				tipoOp : tipo_operacion.value,
                tipoVen : tipo_ingreso.value

				
				
				
                
               
            }));
            //verificar estados
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    console.log(this.responseText);
                    alertaPersonalizada(res.type, res.msg);
                    if (res.type == 'success') {
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

