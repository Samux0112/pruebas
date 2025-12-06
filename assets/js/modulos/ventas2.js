const tblNuevaVenta2 = document.querySelector('#tblNuevaVenta2 tbody');
const numc = document.querySelector('#numc');
const bodegas = document.querySelector('#bodegas');
const docuemi = document.querySelector('#docuemi');
const rRenta = document.querySelector('#rRenta');
const subTotal = document.querySelector('#subTotal');
const cIva = document.querySelector('#cIva');
const cGravadas = document.querySelector('#cGravadas');
const claseDoc = document.querySelector('#claseDoc');
const tipoDoc = document.querySelector('#tipoDoc');
const cExentas = document.querySelector('#cExentas');
const aplicaciones = document.querySelector('#aplicaciones');
const cPercepcion = document.querySelector('#cPercepcion');
const clasificacion = document.querySelector('#clasificacion');
const sector = document.querySelector('#sector');
const tipoGasto = document.querySelector('#tipoGasto');
const tipoOperacionCompra = document.querySelector('#tipoOperacionCompra');
var transmision = "Normal";
var puntoVenta = "";
cFovial.value = 0;
cCotrans.value=0;
cPercepcion.value = 0;



const idCliente2 = document.querySelector('#idCliente2');
const telefonoCliente2 = document.querySelector('#telefonoCliente2');
const direccionCliente2 = document.querySelector('#direccionCliente2');
const errorCliente2 = document.querySelector('#errorCliente2');

const descuento = document.querySelector('#descuento');
const metodo = document.querySelector('#metodo');
const impresion_directa = document.querySelector('#impresion_directa');
const fechaCompra = document.querySelector('#fechaFactura');

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
  var descripcion="";
  fechaCompra.value = fechaFactura; 


document.addEventListener('DOMContentLoaded', function () {
    //cargar productos de localStorage
    mostrarProducto();

    //autocomplete clientes2
    $("#buscarCliente2").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'clientes2/buscar',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                    if (data.length > 0) {
                        errorCliente2.textContent = '';
						puntoVenta = data[0].codPuntoVentaMH;
                    } else {
                        errorCliente2.textContent = 'NO HAY CLIENTE2 CON ESE NOMBRE';
                    }
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            telefonoCliente2.value = ui.item.telefono;
            direccionCliente2.innerHTML = ui.item.direccion;
            idCliente2.value = ui.item.id;
            numc.focus();
           
            
        }
    });
	
			   docuemi.addEventListener('click', function () {
		 mostrarProducto();
		 

		  })


    //completar venta2
    btnAccion.addEventListener('click', function () {
        let filas = document.querySelectorAll('#tblNuevaVenta2 tr').length;
        if (filas < 2) {
            alertaPersonalizada('warning', 'CARRITO VACIO');
            return;
        } else if (metodo.value == '') {
            alertaPersonalizada('warning', 'EL METODO ES REQUERIDO');
            return;
        } else if(idCliente2.value == '' ){
			alertaPersonalizada('warning', 'EL CLIENTE ES REQUERIDO');
		}else if (numc.value == '') {
                alertaPersonalizada('warning', 'numc ES REQUERIDO');
                return;
            }
         else {
		 document.getElementById('btnAccion').disabled = true;
		 document.getElementById('btnContingencia').disabled = true;
			 validarCaja();
			 

        }

    })
	
	 aplicaciones.addEventListener('click', function () {
		 mostrarProducto();

		  })
	
	    //completar venta2
    btnContingencia.addEventListener('click', function () {
        let filas = document.querySelectorAll('#tblNuevaVenta2 tr').length;
        if (filas < 2) {
            alertaPersonalizada('warning', 'CARRITO VACIO');
            return;
        } else if (metodo.value == '') {
            alertaPersonalizada('warning', 'EL METODO ES REQUERIDO');
            return;
        }  else if (numc.value == '') {
                alertaPersonalizada('warning', 'numc ES REQUERIDO');
                return;
            }
         else {
			  Swal.fire({
                                title: 'Desea Generar Contigencia?',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'SI',
                                denyButtonText: 'NO',
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
									document.getElementById('btnAccion').disabled = true;
									document.getElementById('btnContingencia').disabled = true;
                                     transmision = "Contingencia";
									validarCaja();
                                } else if (result.isDenied) {
                                     window.location.reload();
                                }
                            })			
			 

        }

    })
	
	function validarCaja(){
	
			 const url = base_url + 'ventas2/verificarCaja';
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
						if(docuemi.value=="SUJETO EXCLUIDO"){
						obtenerCliente(idCliente2.value);
						}else{
						            const url = base_url + 'ventas2/registrarVenta2';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
              productos: listaCarrito,
              idCliente2: idCliente2.value,
              metodo: metodo.value,
              numc: numc.value,
              descuento: descuento.value,
              impresion: impresion_directa.checked,
               correlativo: "",
               numeroControlDte : "",
               dte:"",
               uuid:"",
               tipoTransmision : transmision,
               total : totalPagar.value,
               gravadas : cGravadas.value,
               exentas : cExentas.value,
               iva : cIva.value,
               claseDocumento : claseDoc.value,
               tipoDocumento : tipoDoc.value,
               fechaC : fechaCompra.value,
               fovial : cFovial.value,
               cotrans : cCotrans.value,
               docuemision : docuemi.value,
               percepcion1 : aplicaciones.value == "sinAplicaciones" ? 0.00 : aplicaciones.value == "Percepcion1" ? cPercepcion.value : 0.00,
               percepcion2 : aplicaciones.value == "sinAplicaciones" ? 0.00 : aplicaciones.value == "Percepcion1" ? 0.00 : cPercepcion.value,
               tipoOperacionCompra  : tipoOperacionCompra.value, 
               clasificacion : clasificacion.value,
               sector : sector.value,
               tipoGasto : tipoGasto.value,
               bodega : bodegas.value,
               idPedido: document.getElementById('idPedido').value
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
                                denyButtonText: `Reporte`,
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    const ruta = base_url + 'ventas2/reporte/ticked/' + res.idVenta2;
                                    window.open(ruta, '_blank');
                                } else if (result.isDenied) {
                                    const ruta = base_url + 'ventas2/reporte/impresion/' + res.idVenta2;
                                    window.open(ruta, '_blank');
                                }
                                window.location.reload();
                            })

                        }, 2000);
                    }
                }
            }	
							
						}
                        
                    }else{
						
						 alertaPersonalizada(res.type, res.msg);
					}
                }
            }
	
}
	

    //cargar datos con el plugin datatables
    tblHistorial2 = $('#tblHistorial2').DataTable({
        ajax: {
            url: base_url + 'ventas2/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'fecha' },
            { data: 'hora' },
            { data: 'total' },
            { data: 'nombre' },
            { data: 'serie' },
            { data: 'numc' },
            { data: 'metodo' },
			{ data: 'docuemi' },
			{ data: 'numeroControlDte' },
			{ data: 'uuid' },
            { data: 'acciones' }
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[4, 'desc']],
    });
	
	 //Calcular descuento
 descuento.addEventListener('keyup', function (e) {
    if (totalPagar.value != '') {
        mostrarProducto();
    }
    
})
	

})

function obtenerCliente(idCliente){
	            $.ajax({
                url: base_url + 'clientes2/buscarId',
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

function maxCorrelativo(cliente){
	            $.ajax({
                url: base_url + 'ventas2/maxCorrelativo',
                dataType: "json",
                data: {
                    tipoDocumento: docuemi.value,
					codPuntoVentaMH : puntoVenta
                },
                success: function (data) {
					if(docuemi.value=="SUJETO EXCLUIDO"){
					crearDte(cliente,data);
					} else if(docuemi.value=="COMPRA"){
						
					}
  
                }
            });
	
}

function crearDte(dataCliente,correlativo){
	
	var nitReceptor = dataCliente[0].identidad;
	var numDocumento = dataCliente[0].DUI;
	var nombreReceptor = dataCliente[0].nombre;
	var telefonoReceptor = dataCliente[0].telefono;
	var correoReceptor = dataCliente[0].correo;
	var direccionReceptor = dataCliente[0].direccion;
	    direccionReceptor = direccionReceptor.replaceAll("<p>","");
		direccionReceptor = direccionReceptor.replaceAll("</p>","");
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
	var desc = descuento.value != "" ? parseFloat(descuento.value) : 0;
		
		
		 for (i = 0; i < listaCarrito.length; i++) {
	 var cuerpo={
            numItem: i+1,
            tipoItem: 1,
            codigo: null,
            descripcion: listaCarrito[i].descripcion,
            cantidad:  parseFloat(listaCarrito[i].cantidad),
            uniMedida: 59,
            precioUni: parseFloat(listaCarrito[i].precio),
            montoDescu: 0,
            compra: Math.round(((parseFloat(listaCarrito[i].precio) * listaCarrito[i].cantidad )+ Number.EPSILON) * 100) / 100,
} 
cuerpodocumento.push(cuerpo);	

totalGravada = Math.round(((totalGravada + cuerpo.compra)+ Number.EPSILON) * 100) / 100;
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
			 version : parseInt(versionSujetoExcluido),
			 ambiente : ambiente,
			 tipoDte  : tipoDTEsujetoExcluido,
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
		 emisor : {
			 nit : nit,
			 nrc : nrc,
			 nombre : nombreEmi,
			 codActividad : codActividad,
			 descActividad : descActividad,
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
		 sujetoExcluido : {
			 tipoDocumento :  "13",
			 numDocumento :  numDocumento,
			 nombre : nombreReceptor,
			 codActividad: null,
             descActividad: null,
			 direccion : {
				 departamento : idDepartamentoReceptor,
				 municipio : idMunicipioReceptor,
				 complemento : direccionReceptor
			 },
			 telefono : telefonoReceptor,
			 correo : correoReceptor
		 },
		 cuerpoDocumento :  cuerpodocumento,
		 resumen : {
			 totalCompra : totalGravada,
			 descu: desc,
			 totalDescu : 0,
			 subTotal : totalGravada - desc,
			 ivaRete1 : 0,
			 reteRenta : parseFloat(rRenta.value),
			 totalPagar : parseFloat(totalPagar.value),
			 totalLetras : NumeroALetras(parseFloat(totalPagar.value)),
			 condicionOperacion : metodo.value == "CREDITO" ? 2 : 1,
			 pagos : [
			 {
				 codigo : "01",
				 montoPago : parseFloat(totalPagar.value),
				 plazo :  metodo.value == "CREDITO" ? "02" : null,
				 referencia : "",
				 periodo : metodo.value == "CREDITO" ? 1 : null
				 
			 }
			 ],
			 observaciones: ""
		 },
		 apendice : null,
		}		 
	 };
	 
	 firmador(JSON.stringify(jsondteObj),correlativo);
		
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
	version : parseInt(versionSujetoExcluido),
	tipoDte : "14",
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
		    const url = base_url + 'ventas2/registrarVenta2'; 
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                productos: listaCarrito,
                idCliente2: idCliente2.value,
                metodo: metodo.value,
                numc: numc.value,
                descuento: descuento.value,
                impresion: impresion_directa.checked,
				correlativo: correlativo[0].correlativo != "" && correlativo[0].correlativo != null ?  parseInt(correlativo[0].correlativo) +1 : 1,
				numeroControlDte : objdte.dteJson.identificacion.numeroControl,
				dte: JSON.stringify(objdte),
				uuid: objdte.dteJson.identificacion.codigoGeneracion,
				tipoTransmision : transmision,
				total : objdte.dteJson.resumen.totalPagar,
				renta : rRenta.value,
				codPuntoVentaMH : puntoVenta,
				sello : objdte.selloRecibido,
				fechaC : fechaCompra.value,
				fovial : cFovial.value,
				cotrans : cCotrans.value,
				percepcion1 : aplicaciones.value == "sinAplicaciones" ? 0.00 : aplicaciones.value == "Percepcion1" ? cPercepcion.value : 0.00,
				percepcion2 : aplicaciones.value == "sinAplicaciones" ? 0.00 : aplicaciones.value == "Percepcion1" ? 0.00 : cPercepcion.value,
				tipoOperacionCompra  : tipoOperacionCompra.value, 
				clasificacion : clasificacion.value,
				sector : sector.value,
				tipoGasto : tipoGasto.value
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
                                denyButtonText: `Enviar`,
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    const ruta = base_url + 'ventas2/reporte/ticked/' + res.idVenta2;
                                    window.open(ruta, '_blank');
                                } else if (result.isDenied) {
                                    const ruta = base_url + 'ventas2/reporte/impresion/' + res.idVenta2;
                                    window.open(ruta, '_blank');
                                }
                                window.location.reload();
                            })

                        }, 2000);
                    }
                }
            }
	}
	
		function crearCorrelativo(correlativo){
			var correlativo = correlativo != "" && correlativo != null ? correlativo.toString() : "0" ;
			 var controlDTE="";
			 var str = sujetoExcluidoBase;
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

//cargar productos
function mostrarProducto() {	
	if(docuemi.value=="COMPRA"){
	document.getElementById('btnContingencia').disabled = true; 
		 }else{
			document.getElementById('btnContingencia').disabled = false; 
		 }
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
						if(producto.id==0){
							cGravadas.disabled = false;
							cFovial.disabled = false;
							cCotrans.disabled = false;
							cIva.disabled = false;
							cPercepcion.disabled = false;
							totalPagar.disabled = false;
						}else{
							cGravadas.disabled = true;
							cFovial.disabled = true;
							cCotrans.disabled = true;
							cIva.disabled = true;
							totalPagar.disabled = true;
						}
                        html += `<tr>
                            <td>${producto.nombre}</td>
                            <td width="200">
                            <input type="number" class="form-control inputPrecio" data-id="${producto.id}" value="${producto.precio_venta2}">
                            </td>
                            <td width="100">
                            <input type="number" class="form-control inputCantidad" data-id="${producto.id}" value="${producto.cantidad}">
                            </td>
                            <td>${producto.subTotalVenta2}</td>
                            <td><button class="btn btn-danger btnEliminar" data-id="${producto.id}" type="button"><i class="fas fa-trash"></i></button></td>
                        </tr>`;
                    });
					if(docuemi.value=="COMPRA" || docuemi.value=="NOTA DE CREDITO"){
                    tblNuevaVenta2.innerHTML = html;
					var desc = descuento.value != "" ? parseFloat(descuento.value) : 0;
					subTotal.value = (parseFloat(res.cSubtotalventas2.replace(",",'')) - (desc/1.13)).toFixed(2);   
					rRenta.value = 0;
					cGravadas.value = (res.cGravadas - (desc/1.13)).toFixed(2);
					cExentas.value = res.exentos;
					cIva.value = (res.cIva-(desc - (desc/1.13))).toFixed(2);
                    btnEliminarProducto();
                    agregarCantidad();
                    agregarPrecioVenta2();
					if(aplicaciones.value=="sinAplicaciones"){
						cPercepcion.value = 0;
					} else if(aplicaciones.value=="Percepcion1"){
						cPercepcion.value = res.cGravadas *0.01;
					}else if(aplicaciones.value=="Percepcion2"){
						cPercepcion.value = res.cGravadas *0.02;
					}
					totalPagar.value = (parseFloat(res.totalVenta2) + parseFloat(cPercepcion.value) - (desc)).toFixed(2) ;
					}else{
						debugger;
				
					tblNuevaVenta2.innerHTML = html;
					var desc = descuento.value != "" ? parseFloat(descuento.value) : 0;
					subTotal.value = ((parseFloat(res.totalVenta2.replace(",",''))/1.13) - desc).toFixed(2);
					cIva.value = 0.0;
                    totalPagar.value = (parseFloat(subTotal.value) *0.9).toFixed(2);
					rRenta.value = (parseFloat(subTotal.value) * 0.1).toFixed(2);
                    btnEliminarProducto();
                    agregarCantidad();
                    agregarPrecioVenta2()
					}
					
                } else {
                    tblNuevaVenta2.innerHTML = '';
					subTotal.value = 0;
                    totalPagar.value = 0;
					rRenta.value = 0;
					cIva.value =0;
					cGravadas.value = 0;
					cExentas.value =0;
					cFovial.value = 0;
					cCotrans.value =0;
					cPercepcion.value=0;
                }
            }
        }
    } else {
        tblNuevaVenta2.innerHTML = `<tr>
            <td colspan="4" class="text-center">CARRITO VACIO</td>
        </tr>`;
    }
}

function verReporte(idVenta2) {
    Swal.fire({
        title: 'Desea Generar Reporte?',
        showDenyButton: true,
		showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ticked',
        denyButtonText: `Reporte`,
		cancelButtonText: 'PDF',
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const ruta = base_url + 'ventas2/reporte/ticked/' + idVenta2;
            window.open(ruta, '_blank');
        } else if (result.isDenied) {
            const ruta = base_url + 'ventas2/reporte/impresion/' + idVenta2;
            window.open(ruta, '_blank');
        }else if (result.isDismissed && result.dismiss !="close" ) {
            const ruta = base_url + 'ventas2/reporte/pdf/' + idVenta2;
            window.open(ruta, '_blank');
        }
    })
}

function anularVenta2(idVenta2) {
    Swal.fire({
        title: 'Esta seguro de anular la venta2?',
        text: "El stock de los productos cambiarán!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Anular!'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + 'ventas2/anular/' + idVenta2;
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
                    alertaPersonalizada(res.type, res.msg);
                    if (res.type == 'success') {
                        tblHistorial2.ajax.reload();
                    }
                }
            }
        }
    })
}

function anularDte(idVenta) {
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
			   const url = base_url + 'ventas2/anularDte/' + idVenta;
			    window.open(url, '_blank');

        }
    })
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