<?php include_once 'views/templates/header.php'; ?>

<?php 

$idFactura = $data['id'];
 
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
$sql = "select * from dtesSujetoExcluido where id=(".$idFactura.")";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
		$dteStr = $row["dte"];  
  }
} 
else {
  echo "0";
}
$conn->close();
$dteStr = json_decode($dteStr,true);
if($dteStr["dteJson"]["identificacion"]["tipoDte"]!="14"){
	$array = $dteStr["dteJson"]["resumen"]["tributos"];
foreach ($array as $value) {
$iva = $value['valor'];
}

}


?>


<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-ventas-tab" data-bs-toggle="tab" data-bs-target="#nav-ventas" type="button" role="tab" aria-controls="nav-ventas" aria-selected="true">AnularDTE</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-ventas" role="tabpanel" aria-labelledby="nav-ventas-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-cash-register"></i>Anular DTE</h5>
                <hr>


                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="editMotivo">Motivo</label>
                        <select id="editMotivo" class="form-control">
                      <option value="1">Error en la Información del Documento Tributario Electrónico</option>
					  <option value="2">Rescindir de la operación realizada</option>
					  <option value="3">Error en la identificación del cliente</option>
					  <option value="3">Error en el producto o servicio facturado</option>
					  <option value="3">Error en la fecha de la operación</option>
					  <option value="3">Error en el precio de los bienes o servicios</option>
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label>Nombre Responsable</label>
                        <div class="input-group mb-2">

                            <input class="form-control" type="text" id="nombreR" placeholder="Nombre responsable">
                        </div>
                    </div>
					
					<div class="col-md-4">
                        <label>Dui Responsable</label>
                        <div class="input-group mb-2">

                            <input class="form-control" type="text" id="duiR" placeholder="DUI responsable">
                        </div>
                    </div>
					
					<div class="col-md-4">
                        <label>Nombre solicitante</label>
                        <div class="input-group mb-2">

                            <input class="form-control" type="text" id="nombreS" placeholder="DUI solicitante">
                        </div>
                    </div>
					
					<div class="col-md-4">
                        <label>Dui Solicitante</label>
                        <div class="input-group mb-2">

                            <input class="form-control" type="text" id="duiS" placeholder="DUI solicitante">
                        </div>
												<div class="d-grid">
                            <button class="btn btn-danger" type="button" onclick="anular()" id="btnAnular">Anular</button>
                        </div>
                    </div>
					
					<div class="col-md-4">
                        <label>Nuevo Documento</label>
                        <div class="input-group mb-2">

                            <input class="form-control" type="text" id="documento" placeholder="Nuevo Documento">
                        </div>
						

                    </div>
					
					


        
                </div>

                

               

     
        </div>
    </div>
	</div>
</div>
<script>
   var fe = new Date();
   var mes = (fe.getMonth() +1);
   var dia = fe.getDate();
   
   var now = fe.toLocaleTimeString('it-IT');
   var iva = 0;
   var tipo = "Sujeto Excluido";
   iva = parseFloat(iva);
    if(mes<10){
	  mes = "0"+mes;
  }
  if(dia<10){
	  dia = "0"+dia;
  }
  var fechaFactura = fe.getFullYear()+"-"+mes+"-"+dia;
 
  
  function anular(){
  
  var nombreR =  document.getElementById("nombreR").value;
  var duiR =  document.getElementById("duiR").value;
  var nombreS =  document.getElementById("nombreS").value;
  var duiS =  document.getElementById("duiS").value;
  var motivo =  document.getElementById("editMotivo").value;
  var combo = document.getElementById("editMotivo");
  var motivoDescripcion = combo.options[combo.selectedIndex].text;
   var documentoR =  document.getElementById("documento").value;
    var numdocumento = 0;
 var numdocumento = "<?php if($dteStr["dteJson"]["identificacion"]["tipoDte"]=="14")  {echo ($dteStr["dteJson"]["sujetoExcluido"]["numDocumento"]);}else{ echo $dteStr["dteJson"]["receptor"]["nit"];} ?>";

   
  if(nombreR!="" && duiR !="" && nombreS != "" && duiS!="" && motivo != ""  ){
	  if(motivo!= "1" && motivo!= "3"){
		  $('#load').modal('show');
  var jsondteObj ={
   nit: nit, 
   activo: true,
   passwordPri: passwordPri,
   dteJson: {
      identificacion: {
         version : 2,
		 ambiente : ambiente,
         codigoGeneracion: "<?php echo  $dteStr["dteJson"]["identificacion"]["codigoGeneracion"] ?>",
         fecAnula : fechaFactura,
		 horAnula : now
      },
      emisor: {
         nit: nit,
         nombre: nombreEmi,
         tipoEstablecimiento: tipoEstablecimiento,
         nomEstablecimiento: nomEstablecimientoEmisor,
         telefono: telefonoEmisor,
         correo: correoEmisor,
         codEstable: null,
         codPuntoVentaMH: null,
         codPuntoVenta: null
      },
      documento: {
         tipoDte: "<?php echo  $dteStr["dteJson"]["identificacion"]["tipoDte"] ?>",
         codigoGeneracion: "<?php echo  $dteStr["dteJson"]["identificacion"]["codigoGeneracion"] ?>",
         selloRecibido: "<?php echo  $dteStr["selloRecibido"] ?>",
         numeroControl: "<?php echo  $dteStr["dteJson"]["identificacion"]["numeroControl"] ?>",
         fecEmi: "<?php echo  $dteStr["dteJson"]["identificacion"]["fecEmi"] ?>",
         montoIva: iva!="" ? iva : 0,
         codigoGeneracionR: null,
         tipoDocumento: numdocumento > 10 ? "36" : "13",
         numDocumento: numdocumento ,
         nombre: "<?php echo  $dteStr["dteJson"]["sujetoExcluido"]["nombre"] ?>"
      },
      motivo: {
         tipoAnulacion: parseInt(motivo),
         motivoAnulacion: motivoDescripcion,
         nombreResponsable: nombreR,
         tipDocResponsable: "13",
         numDocResponsable: duiR,
         nombreSolicita: nombreS,
         tipDocSolicita: "02",
         numDocSolicita: duiS
      }
   }
}

firmador(JSON.stringify(jsondteObj));
	  }else{
		  if(documentoR != ""){
			  
			  		  $('#load').modal('show');
  var jsondteObj ={
   nit: nit, 
   activo: true,
   passwordPri: passwordPri,
   dteJson: {
      identificacion: {
         version : 2,
		 ambiente : ambiente,
         codigoGeneracion: "<?php echo  $dteStr["dteJson"]["identificacion"]["codigoGeneracion"] ?>",
         fecAnula : fechaFactura,
		 horAnula : now
      },
      emisor: {
         nit: "<?php echo  $dteStr["dteJson"]["emisor"]["nit"] ?>",
         nombre: "<?php echo  $dteStr["dteJson"]["emisor"]["nombre"] ?>",
         tipoEstablecimiento: "<?php echo  $dteStr["dteJson"]["emisor"]["tipoEstablecimiento"] ?>",
         nomEstablecimiento: null,
         telefono:"<?php echo  $dteStr["dteJson"]["emisor"]["telefono"] ?>",
         correo: "<?php echo  $dteStr["dteJson"]["emisor"]["correo"] ?>",
         codEstable: null,
         codPuntoVentaMH: null,
         codPuntoVenta: null
      },
      documento: {
         tipoDte: "<?php echo  $dteStr["dteJson"]["identificacion"]["tipoDte"] ?>",
         codigoGeneracion: "<?php echo  $dteStr["dteJson"]["identificacion"]["codigoGeneracion"] ?>",
         selloRecibido: "<?php echo  $dteStr["selloRecibido"] ?>",
         numeroControl: "<?php echo  $dteStr["dteJson"]["identificacion"]["numeroControl"] ?>",
         fecEmi: "<?php echo  $dteStr["dteJson"]["identificacion"]["fecEmi"] ?>",
         montoIva: iva!="" ? iva : 0,
         codigoGeneracionR: documentoR,
         tipoDocumento: numdocumento > 10 ? "36" : "13",
         numDocumento: numdocumento ,
         nombre: "<?php echo  $dteStr["dteJson"]["sujetoExcluido"]["nombre"] ?>"
      },
      motivo: {
         tipoAnulacion: parseInt(motivo),
         motivoAnulacion: motivoDescripcion,
         nombreResponsable: nombreR,
         tipDocResponsable: "13",
         numDocResponsable: duiR,
         nombreSolicita: nombreS,
         tipDocSolicita: "02",
         numDocSolicita: duiS
      }
   }
}

firmador(JSON.stringify(jsondteObj));
			  
			  
			  
		  }else{
			alert("Para el motivo seleccionado es necesario ingresar el documento que lo reemplaza"); 
			  
			  
		  }
		  
		  
	  }
  }else{
	  
	  alert("Todos los campos son requeridos");
	   $('#load').modal('hide'); 
  }

  }
  
  
   function firmador(dte){
	console.log("llega a firmador");
	console.log(dte);
var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText)
	
    console.log(jsonResponse);
	console.log(jsonResponse.body);
	autorizador(jsonResponse.body);
  }
};

// Endpoint de la API y método que se va a usar para llamar
xhttp.open("POST", apiFirmador, true);
xhttp.setRequestHeader("Content-type", "application/JSON","User-Agent","PostmanRuntime/7.28.0","Content-Length","<calculated when request is sent>");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send(dte);	
	
}


function autorizador(dte){
var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText)
	console.log("token");
    console.log(jsonResponse.body.token);
	AnularDTE(jsonResponse.body.token,dte);
  } 
};

// Endpoint de la API y método que se va a usar para llamar
xhttp.open("POST", apiAutorizador, true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded","User-Agent","PostmanRuntime/7.28.0","Content-Length","<calculated when request is sent>");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send("user="+nit+"&pwd="+pasApiMH);

}


function AnularDTE(token,Dtefirmado){
console.log("llega a registro");
var req = {
	ambiente : ambiente,
	idEnvio : 1,
	version : 2,
	tipoDte : "<?php echo  $dteStr["dteJson"]["identificacion"]["tipoDte"] ?>",
	documento : Dtefirmado	
}


var xhttp = new XMLHttpRequest();

// Esta es la función que se ejecutará al finalizar la llamada
xhttp.onreadystatechange = function() {
  // Si nada da error
  if (this.readyState == 4 && this.status == 200) {
    // La respuesta, aunque sea JSON, viene en formato texto, por lo que tendremos que hace run parse
	var jsonResponse = JSON.parse(this.responseText)
	alert(jsonResponse.descripcionMsg);
	 $('#load').modal('hide'); 
	         const url = base_url + 'ventas2/anular/' + <?php echo $idFactura; ?>;
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
					location.href="<?php echo BASE_URL . 'ventas2'; ?>";	
                    }
                }
            }
  }else if(this.status == 400){
	  var jsonResponse = JSON.parse(this.responseText)
	  alert("ERROR: "+jsonResponse.descripcionMsg);
	  $('#load').modal('hide');
	  
	  
  }else if(this.status == 401){
	  alert ("Error al anular DTE");
	   $('#load').modal('hide');
  }
};

// Endpoint de la API y método que se va a usar para llamar
xhttp.open("POST", apiAnularMH, true);
xhttp.setRequestHeader("Authorization",token);
xhttp.setRequestHeader("Content-type", "application/JSON");
// Si quisieramos mandar parámetros a nuestra API, podríamos hacerlo desde el método send()
xhttp.send(JSON.stringify(req));	

		
}
  
 </script>

<?php include_once 'views/templates/footer.php'; ?>