<?php include_once 'views/templates/header.php'; 
?>

<div class="card text-dark text-center">
  <h5 class="card-header">Solicitar Evento de Contingencia</h5>
  <div class="card-body">
 <div class="row">
     <div class="col">
        <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Nombre Responsable</span>
  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="nombreR">
</div>
    </div>
	 <div class="col">
        <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Dui responsable</span>
  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="duiR">
</div>
    </div>
	 <div class="col">
	  <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Motivo Contingencia</span>
      <select class="form-select" aria-label="Default select example" id="motivo">
  <option value="1">No disponibilidad de sistema del MH</option>
  <option value="2">No disponibilidad de sistema del emisor</option>
  <option value="3">Falla en el suministro de servicio de Internet del Emisor</option>
  <option value="3">Falla en energia electrica</option>
</select>
    </div>
	</div>
	
	<div class="row">
     <div class="col-3">
        <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Fecha de inicio del evento</span>
  <input type="text" class="form-control" placeholder = "yyyy-mm-dd" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="fechaiE">
</div>
    </div>
	 <div class="col-3">
        <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Hora de inicio del evento</span>
  <input type="text" class="form-control" placeholder = "hh:mm:ss" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="horaiE">
</div>
    </div>
	 <div class="col-3">
        <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Fecha final del evento</span>
  <input type="text" class="form-control" aria-label="Sizing example input"  placeholder = "yyyy-mm-dd" aria-describedby="inputGroup-sizing-sm" id="fechafE">
</div>
    </div>
	 <div class="col-3">
	 <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Hora de Final del evento</span>
  <input type="text" class="form-control" placeholder = "hh:mm:ss" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="horafE">
</div>
</div>
	</div>
		
	 <div class="d-grid gap-2">
     <button type="button" class="btn btn-warning solid" onclick="solicitar()">Solicitar evento de contingencia</button>
    </div>
	
	
</div>

<!-- Button trigger modal -->
</div>
<div class="card text-dark text-center">
  <h5 class="card-header">Documentos emitidos en evento de contingencia</h5>
  <div class="card-body">
   <table class="table" id="tablaClientes">
      <thead class="thead-dark">
        <tr>
          <th>Codigo de Generación</th>
		  <th>Numero de Control</th>
		  <th>Documento</th>
          <th>Total</th>
		  <th>Fecha generacion</th>
		  <th>Accion</th>
        </tr>
      </thead>
      <tbody>
	  <?php


$servername = HOST;
$username = USER;
$password = PASS;
$dbname = DBNAME;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "select c.id_contingencia, c.id, c.codigo_generacion, c.total,v.docuemi,c.fecha,v.numeroControlDte from contingencias c join ventas v on c.id=v.id where c.enviada='false';";
$result = $conn->query($sql);
$tablaDatos = "";
if ($result->num_rows > 0) {
  // output data of each row

  while($row = $result->fetch_assoc()) {
    echo " <tr><td>" .$row["codigo_generacion"]. " </td><td>" .$row["numeroControlDte"]. " </td><td>" .$row["docuemi"]. " </td><td>$".$row["total"]."</td><td> ".$row["fecha"]."</td><td><button type='button' class='btn btn-success' onclick='Enviar(".$row["id"].")'>Enviar</button></td></tr>";
  $tablaDatos = $row["codigo_generacion"]."|".$tablaDatos;
  $tablaDatosTipo = $row["docuemi"]."|".$tablaDatosTipo;
  }
} else {
  echo "0 results";
}

$sql = "select c.id_contingencia, c.id, c.codigo_generacion, c.total,v.docuemi,c.fecha,v.numeroControlDte from contingenciasSujeto c join ventas2 v on c.id=v.id where c.enviada='false';";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo " <tr><td>" .$row["codigo_generacion"]. " </td><td>" .$row["numeroControlDte"]. " </td><td>" .$row["docuemi"]. " </td><td>$".$row["total"]."</td><td> ".$row["fecha"]."</td><td><button type='button' class='btn btn-success' onclick='EnviarSujeto(".$row["id"].")'>Enviar</button></td></tr>";
  $tablaDatos = $row["codigo_generacion"]."|".$tablaDatos;
  $tablaDatosTipo = $row["docuemi"]."|".$tablaDatosTipo;
  }
} else {
  echo "0 results";
}


$conn->close();


echo"</tbody></table>";

$myuuid = guidv4();



function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

?>

	  
  </div>
  </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>

<script>
  function solicitar(){
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
  var idFacturaEncurso = "";
  var tDocumento = "otro";
	  
if (document.getElementById("nombreR").value != "" &&  document.getElementById("duiR").value != "" &&  document.getElementById("fechaiE").value != "" &&  document.getElementById("horaiE").value != "" &&  document.getElementById("fechafE").value != "" &&  document.getElementById("horafE").value != ""){

var tablaDatos = "<?php echo $tablaDatos ?>";	
var tablaDatosTipo = "<?php echo $tablaDatosTipo ?>";	
tablaDatos = 	tablaDatos.split("|"); 
tablaDatosTipo = tablaDatosTipo.split("|");
var cuerpodocumento=[];
for (var i = 0; i < tablaDatos.length-1; i++) {
	 var cuerpo={
		 noItem : i+1,
		 codigoGeneracion: tablaDatos[i],
		 tipoDoc : tablaDatosTipo[i] =="FACTURA" ? "01" :tablaDatosTipo[i] =="CREDITO FISCAL" ? "03" : tablaDatosTipo[i] =="Nota de credito" ? "05" : tablaDatosTipo[i] =="SUJETO EXCLUIDO" ? "14" : "11",
	 }
cuerpodocumento.push(cuerpo);	 
}


			 
var jsondteObj = {
	nit : nit,
	activo : true,
	passwordPri : passwordPri,
	dteJson :{
		identificacion :{
			version: 3,
			ambiente : ambiente,
			codigoGeneracion : "<?php echo  strtoupper($myuuid);?>",
			fTransmision : fechaFactura,
			hTransmision : now
		},
		emisor : {
			 nit : nit,
			 nombre : nombreEmi,
			 nombreResponsable : document.getElementById("nombreR").value,
			 tipoDocResponsable: "13",
			 numeroDocResponsable : document.getElementById("duiR").value,
			 tipoEstablecimiento : "02",
			 codEstableMH: null,
			 codPuntoVenta: null,
			 telefono : telefonoEmisor,
			 correo : correoEmisor	
		},
		detalleDTE : cuerpodocumento,
		motivo: {
			fInicio : document.getElementById("fechaiE").value,
			fFin : document.getElementById("fechafE").value,
			hInicio : document.getElementById("horaiE").value,
			hFin : document.getElementById("horafE").value,
			tipoContingencia :  parseInt(document.getElementById("motivo").value),
			motivoContingencia : null
			
		}
	 }
}

console.log(jsondteObj);

 $('#load').modal('show');
firmador(JSON.stringify(jsondteObj));
 
}else{
	
	alert("todos los campos son necesarios");
}
	  
  }
  
  
  function firmador(obj){
	console.log("llega a firmador");
var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText)
		var objFirmado = (jsonResponse.body);
		objFirmado = objFirmado;
		autorizador(objFirmado,"contingencia","");
  }
};
// Endpoint de la API y método que se va a usar para llamar
xhttp.open("POST", apiFirmador, true);
xhttp.setRequestHeader("Content-type", "application/JSON","User-Agent","PostmanRuntime/7.28.0","Content-Length","<calculated when request is sent>");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send(obj);	
	
}

function autorizador(objFirmado,evento,dte){
var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText)
    console.log(jsonResponse.body.token);
	registroDTE(objFirmado,jsonResponse.body.token,evento,dte);
  }else if (this.status == 400 || this.status == 401 ){
	   $('#load').modal('hide');
	  }
};

// Endpoint de la API y método que se va a usar para llamar
xhttp.open("POST", apiAutorizador, true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded","User-Agent","PostmanRuntime/7.28.0","Content-Length","<calculated when request is sent>");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send("user="+nit+"&pwd="+pasApiMH);

}

function registroDTE(objFirmado,token,evento,dte){
console.log("llega a registro");
if(evento == "contingencia"){
	
var req = {
	nit : nit,
	documento : objFirmado	
}
} else if(evento == "envio"){
		
	var req = {
	ambiente : ambiente,
	idEnvio : 1,
	version : dte.dteJson.identificacion.version,
	tipoDte : dte.dteJson.identificacion.tipoDte,
	documento : objFirmado	
}
	
}


var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText);
	
	if(evento == "envio"){
		alert("Estado : "+jsonResponse.estado+" Observaciones : "+ jsonResponse.observaciones+" Sello recibido : "+jsonResponse.selloRecibido);
		dte.selloRecibido = jsonResponse.selloRecibido;
		dte.passwordPri = "";
		salirContingencia(dte);
	} else if(evento == "contingencia"){
	alert("Estado : "+jsonResponse.estado+" Observaciones : "+ jsonResponse.observaciones+" Sello recibido : "+jsonResponse.selloRecibido);
	$('#load').modal('hide');
	}
	
  }else if(this.status == 400){
	  var jsonResponse = JSON.parse(this.responseText)
	  alert("ERROR: "+jsonResponse.descripcionMsg);
	  $('#load').modal('hide');
	  
  }
};

// Endpoint de la API y método que se va a usar para llamar
if(evento == "contingencia"){
xhttp.open("POST", apiSolicitudContingencia, true);
}else{
xhttp.open("POST", apiRecepcionDTE, true);	
}
xhttp.setRequestHeader("Authorization",token);
xhttp.setRequestHeader("Content-type", "application/JSON");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send(JSON.stringify(req));	

		
}


function Enviar(idFactura){
	idFacturaEncurso = idFactura;
	            $.ajax({
                url: base_url + 'contingencias/obtenerDte',
                dataType: "json",
                data: {
                    id: idFactura
                },
                success: function (data) {
					tDocumento = "Otro";
					dte = JSON.parse(data[0].dte);
					console.log(dte);
					Dtefirmado = dte.firmaElectronica;
					autorizador(Dtefirmado,"envio",dte);
  
                }
            });
	
}

function EnviarSujeto(idFactura){
	idFacturaEncurso = idFactura;
	            $.ajax({
                url: base_url + 'contingencias/obtenerDteSujeto',
                dataType: "json",
                data: {
                    id: idFactura
                },
                success: function (data) {
					tDocumento = "Sujeto Excluido";
					dte = JSON.parse(data[0].dte);
					console.log(dte);
					Dtefirmado = dte.firmaElectronica;
					autorizador(Dtefirmado,"envio",dte);
  
                }
            });
	
}


function salirContingencia(dte){
	var jsonDte = JSON.stringify(dte);
	var url ="";
	    if(tDocumento=="Sujeto Excluido"){
		url = base_url + 'contingencias/contingenciaUpdateSujeto';	
		}else{
		 url = base_url + 'contingencias/contingenciaUpdate';
		}
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                dte: jsonDte,
                id: idFacturaEncurso
             
            }));
            //verificar estados
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
               $('#load').modal('hide');
					location.reload();
                }
            }
}
  </script>
   <script type='text/javascript'>
	$(document).ready(function() {
  $('#tablaClientes').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
    }
  });
});
</script>