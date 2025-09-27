<?php include_once 'views/templates/header.php'; ?>
<form name="form" id="form1" action="librosIva" method="post">
<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-ventas-tab" data-bs-toggle="tab" data-bs-target="#nav-ventas" type="button" role="tab" aria-controls="nav-ventas" aria-selected="true">Libros de IVA</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-ventas" role="tabpanel" aria-labelledby="nav-ventas-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-cash-register"></i>Libros de IVA</h5>
                <hr>


 <div class="row">
 <input class="form-control" type="text" id="tipoDocumento" value= "<?php if(isset($_POST["documento"])){echo $_POST["documento"];}?>" hidden>
     <div class="col">
      <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Detalle documento</span>
<select class="form-select" aria-label="Default select example" name = "documento" id="documento" value = "<?php if(isset($_POST["documento"])){echo $_POST["documento"];}?>">
<?php  if(isset($_POST["documento"])) {  echo '<option value = "'.$_POST["documento"].'">'.$_POST["documento"].'</option>'; } ?> 
  <option value="contribuyentes">contribuyentes</option>
  <option value="consumidor">consumidor</option>
  <option value="compras">compras</option>
  <option value="Sujeto Excluido">Sujeto Excluido</option>
  <option value="Anulaciones">Anulaciones</option>
  
</select>
    </div>
  </div>
 
     <div class="col">
        <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Fecha inicio</span>
  <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" name="finicio" id = "finicio" value = "<?php if(isset($_POST["finicio"])){echo $_POST["finicio"];}?>" >
</div>
    </div>
	 <div class="col">
        <div class="input-group input-group-sm mb-3">
  <span class="input-group-text" id="inputGroup-sizing-sm">Fecha Fin</span>
  <input type="date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" name="ffin" id = "ffin" value ="<?php if(isset($_POST["finicio"])){echo $_POST["ffin"];}?>">
</div>
    </div>
	<div class="col">
     <button type="submit" class="btn btn-success solid" value="Enviar">Generar</button>
    </div>
	</div>
</form>
                <br>
 	<div class="card text-dark text-center">
  <h5 class="card-header"></h5>
  <div class="card-body"> 
 <div class="table-responsive">
 
 <table class="table">
	  <?php
	  $fini = "";
	  $ffin="";
	  $mes="";

if(isset($_POST["finicio"])){
	
$fini = $_POST["finicio"];
$ffin = $_POST["ffin"];

	   }

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

if(strlen($fini)>1 && strlen($ffin)>1 ) {
	$documento = $_POST["documento"];
	if(substr($fini,5,2)==substr($ffin,5,2)){
		$mes = substr($fini,5,2);
		$anio = substr($fini,0,4);
	if($documento=="contribuyentes"){
	
	
echo '  <thead>
    <tr>
	 <th scope="col">Fecha</th>
      <th scope="col">Clase documento</th>
      <th scope="col">Tipo documento</th>
	  <th scope="col">Numero resolucion</th>
	  <th scope="col">Numero de serie</th>
	  <th scope="col">Numero de documento</th>
	  <th scope="col">Numero de control interno</th>
	  <th scope="col">Nit</th>
	  <th scope="col">Nombre</th>
	  <th scope="col">Exentas</th>
	  <th scope="col">No sujetas</th>
	  <th scope="col">Gravadas</th>
	  <th scope="col">Debito fiscal</th>
	  <th scope="col">Venta no domiciliados</th>
	  <th scope="col">Debito fiscal cuenta terceros</th>
	  <th scope="col">Total ventas</th>
	  <th scope="col">Dui</th>
	  <th scope="col">Tipo Operacion</th>
	  <th scope="col">Tipo Ingreso</th>
	  <th scope="col">Anexo</th>

    </tr>
  </thead>
  <tbody>';
$sql = "SELECT v.*, c.nombre, c.identidad FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.fecha BETWEEN '".$_POST["finicio"]."' AND '".$_POST["ffin"]."' AND ((docuemi = 'Nota de credito' OR docuemi = 'CREDITO FISCAL') AND v.estado !=0) ORDER BY v.fecha ";
$result = $conn->query($sql);
$totalDinero=0;
$totalProducto =0;

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
	  $fecha =explode("-", $row["fecha"]);
	  $fechaf =$fecha[2]."/".$fecha[1]."/".$fecha[0] ;
	  $exentas ="";
	  if($row["vExentas"]>0){
$valBase="0000000000.00";
$valorLength =strlen($row["vExentas"]);
$valBase = substr($valBase,0,13-$valorLength);
$valBase = $valBase.$row["vExentas"];	
	  }else{
		  $valBase="0000000000.00";
	  }
	  
	  	  if($row["vGravadas"]>0){
$valBaseGravadas="0000000000.00";
$valorLength =strlen($row["vGravadas"]);
$valBaseGravadas = substr($valBaseGravadas,0,13-$valorLength);
$valBaseGravadas = $valBaseGravadas.$row["vGravadas"];	
	  }else{
		  $valBaseGravadas="0000000000.00";
	  }
	  
	  	  	  if($row["vIva"]>0){
$valBasevIva="0000000000.00";
$valorLength =strlen($row["vIva"]);
$valBasevIva = substr($valBasevIva,0,13-$valorLength);
$valBasevIva = $valBasevIva.$row["vIva"];	
	  }else{
		  $valBasevIva="0000000000.00";
	  }
	  
	  $ventasGravadas = $row["total"] + $row["reteIva"];
	  $ventasGravadas = (number_format($ventasGravadas,2,'.',''));
	  
	  	  	  	  if($ventasGravadas>0){
$valBasetotal="0000000000.00";
$valorLength =strlen($ventasGravadas);
$valBasetotal = substr($valBasetotal,0,13-$valorLength);
$valBasetotal = $valBasetotal.$ventasGravadas;	
	  }else{
		  $valBasetotal="0000000000.00";
	  }
	  
     echo " <tr><td>" . $fechaf. " </td><td>" . $row["claseDoc"]. " </td><td>".str_replace("-","",$row["numdocu"])."</td><td> ".str_replace("-","",$row["numeroControlDte"])."</td><td>".$row["selloRecepcion"]."</td><td>".str_replace("-","",$row["uuid"])."</td><td></td><td>".$row["identidad"]."</td><td>".$row["nombre"]."</td><td>".$valBase."</td><td>0000000000.00</td><td>".$valBaseGravadas."</td><td>".$valBasevIva."</td><td>0000000000.00</td><td>0000000000.00</td><td>".$valBasetotal."</td><td></td><td>" . $row["tipo_operacion"]. " </td><td>" . $row["tipo_ingreso"]. " </td><td>1</td></tr>";
  }
} else {
  echo "0 results";
}
 $conn->close();

	}elseif ($documento=="consumidor"){
		echo '  <thead>
    <tr>
	 <th scope="col">Fecha</th>
      <th scope="col">Clase documento</th>
      <th scope="col">Tipo documento</th>
	  <th scope="col">Numero resolucion</th>
	  <th scope="col">Serie de documento</th>
	  <th scope="col">Numero de control interno(Del)</th>
	  <th scope="col">Numero de control interno(Al)</th>
	  <th scope="col">Numero de documento(Del)</th>
	  <th scope="col">Numero de documento(Al)</th>
	  <th scope="col">N de Maquina registradora</th>
	  <th scope="col">Exentas</th>
	  <th scope="col">No sujetas</th>
	  <th scope="col">Internas No sujetas</th>
	  <th scope="col">Ventas gravadas</th>
	  <th scope="col">Exportaciones dentro de C.A.</th>
	  <th scope="col">Exportaciones fuera de C.A.</th>
	  <th scope="col">Exportaciones de servicios</th>
	  <th scope="col">Ventas a Zonas francas</th>
	  <th scope="col">Venta a cuenta de terceros no domiciliados</th>
	  <th scope="col">Total ventas</th>
	  <th scope="col">Tipo Operacion</th>
	  <th scope="col">Tipo Ingreso</th>
	  <th scope="col">Numero de anexo</th>

    </tr>
  </thead>
  <tbody>';
  
  $sql = "SELECT v.*, c.nombre, c.identidad FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.fecha BETWEEN '".$_POST["finicio"]."' AND '".$_POST["ffin"]."' AND (docuemi = 'FACTURA' OR docuemi = 'EXPORTACION')  AND (v.estado !=0) ORDER BY v.fecha ";
$result = $conn->query($sql);
$totalDinero=0;
$totalProducto =0; 
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) { 
   $fecha =explode("-", $row["fecha"]);
   $fechaf =$fecha[2]."/".$fecha[1]."/".$fecha[0] ;
   if($row["docuemi"]=="FACTURA"){
echo " <tr><td>" . $fechaf. " </td><td>" . $row["claseDoc"]. " </td><td>".str_replace("-","",$row["numdocu"])."</td><td> ".str_replace("-","",$row["numeroControlDte"])."</td><td>".$row["selloRecepcion"]."</td><td></td><td></td><td>".str_replace("-","",$row["uuid"])."</td><td>".str_replace("-","",$row["uuid"])."</td><td></td><td>".$row["vExentas"]."</td><td>0.00</td><td>0.00</td><td>".$row["vGravadas"]."</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>".$row["vGravadas"]."</td><td>" . $row["tipo_operacion"]. " </td><td>" . $row["tipo_ingreso"]. " </td><td>2</td></tr>";	   
   }else{
echo " <tr><td>" . $fechaf. " </td><td>" . $row["claseDoc"]. " </td><td>".str_replace("-","",$row["numdocu"])."</td><td> ".str_replace("-","",$row["numeroControlDte"])."</td><td>".$row["selloRecepcion"]."</td><td></td><td></td><td>".str_replace("-","",$row["uuid"])."</td><td>".str_replace("-","",$row["uuid"])."</td><td></td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>".$row["vGravadas"]."</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>".$row["vGravadas"]."</td><td>" . $row["tipo_operacion"]. " </td><td>" . $row["tipo_ingreso"]. " </td><td>2</td></tr>";
	   
   }
  
   
  
  }
}
		
	}elseif ($documento=="compras"){
		echo '  <thead>
    <tr>
	 <th scope="col">Fecha</th>
      <th scope="col">Clase documento</th>
      <th scope="col">Tipo documento</th>
	  <th scope="col">Numero de documento</th>
	  <th scope="col">NIT</th>
	  <th scope="col">Nombre proveedor</th>
	  <th scope="col">Compras internas exentas no sujetas</th>
	  <th scope="col">Internacionales Exentas no sujetas</th>
	  	  <th scope="col">Importaciones Exentas</th>
	  <th scope="col">Compras internas gravadas</th>
	  <th scope="col">Internacionales gravadas de bienes</th>
	  <th scope="col">Importaciones gravadas de bienes</th>
	  <th scope="col">importaciones gravadas de servicios</th>
	  <th scope="col">Credito fiscal</th>
	  <th scope="col">Total compras</th>
	  <th scope="col">Dui del proveedor</th>
	  <th scope="col">Tipo de operacion</th>
	  <th scope="col">Clasificacion</th>
	  <th scope="col">Sector</th>
	  <th scope="col">Tipo Gasto</th>
	  <th scope="col">Numero de Anexo</th> 
    </tr> 
  </thead>
  <tbody>';
  
   $sql = "SELECT v.*, c.nombre, c.identidad FROM ventas2 v INNER JOIN clientes2 c ON v.id_cliente2 = c.id WHERE v.fecha BETWEEN '".$_POST["finicio"]."' AND '".$_POST["ffin"]."' AND ((docuemi = 'COMPRA' or docuemi = 'NOTA DE CREDITO')  AND v.estado !=0) ORDER BY v.fecha ";
$result = $conn->query($sql);
$totalDinero=0;
$totalProducto =0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) { 
   $fecha =explode("-", $row["fecha"]);
   $fechaf =$fecha[2]."/".$fecha[1]."/".$fecha[0] ;
   
   	  if($row["vExentas"]>0){
$valBaseExt="0000000000.00";
$valorLength =strlen($row["vExentas"]);
$valBaseExt = substr($valBaseExt,0,13-$valorLength);
$valBaseExt = $valBaseExt.$row["vExentas"];	
	  }else{
		  $valBaseExt="0000000000.00";
	  }
	  
	  	  	  if($row["vGravadas"]>0){
$valBaseGravadas="0000000000.00";
$valorLength =strlen($row["vGravadas"]);
$valBaseGravadas = substr($valBaseGravadas,0,13-$valorLength);
$valBaseGravadas = $valBaseGravadas.$row["vGravadas"];	
	  }else{
		  $valBaseGravadas="0000000000.00";
	  }
	  
	  	  	  	  if($row["vIva"]>0){
$valBasevIva="0000000000.00";
$valorLength =strlen($row["vIva"]);
$valBasevIva = substr($valBasevIva,0,13-$valorLength);
$valBasevIva = $valBasevIva.$row["vIva"];	
	  }else{
		  $valBasevIva="0000000000.00";
	  }
	  
	  	  	  	  	  if($row["total"]>0){
$valBasetotal="0000000000.00";
$valorLength =strlen($row["total"]);
$valBasetotal = substr($valBasetotal,0,13-$valorLength);
$valBasetotal = $valBasetotal.$row["total"];	
	  }else{
		  $valBasetotal="0000000000.00";
	  }
  
   echo " <tr><td>" . $fechaf. " </td><td>" . $row["claseDoc"]. " </td><td>".$row["tipoDoc"]."</td><td> ".str_replace("-","",$row["numc"])."</td><td>".$row["identidad"]."</td><td>".$row["nombre"]."</td><td>".$valBaseExt."</td><td>0000000000.00</td><td>0000000000.00</td><td>".$valBaseGravadas."</td><td>0000000000.00</td><td>0000000000.00</td><td>0000000000.00</td><td>".$valBasevIva."</td><td>".$valBasetotal."</td><td></td><td>".$row["tipoOperacion"]."</td><td>".$row["clasificacion"]."</td><td>".$row["sector"]."</td><td>".$row["tipoGasto"]."</td><td>3</td></tr>";

  
  }
}
		
		
	}
	elseif ($documento=="Sujeto Excluido"){
		echo '  <thead>
    <tr>
	 <th scope="col">Tipo Documento</th>
      <th scope="col">NIT/DUI</th>
      <th scope="col">Nombre</th>
	  <th scope="col">Fecha</th>
	  <th scope="col">Numero de Serie</th>
	  <th scope="col">Numero de documento</th>
	  <th scope="col">Monto de operacion</th>
	  <th scope="col">Monto de la retencion IVA 13%</th>
	  <th scope="col">Tipo de operacion</th>
	  <th scope="col">Clasificacion</th>
	  <th scope="col">Sector</th>
	  <th scope="col">Tipo Gasto</th>
	  <th scope="col">Anexo</th>
    </tr>
  </thead>
  <tbody>';
  
  $sql = "SELECT v.*, c.identidad, c.DUI, c.num_identidad, c.nombre, c.telefono, c.direccion FROM ventas2 v INNER JOIN clientes2 c ON v.id_cliente2 = c.id WHERE v.fecha BETWEEN '".$_POST["finicio"]."' AND '".$_POST["ffin"]."' AND (v.docuemi = 'SUJETO EXCLUIDO' AND v.estado !=0) ORDER BY v.fecha ";
$result = $conn->query($sql);
$totalDinero=0;
$totalProducto =0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) { 
   $fecha =explode("-", $row["fecha"]);
   $fechaf =$fecha[2]."/".$fecha[1]."/".$fecha[0] ;
  
    echo " <tr><td>2</td><td>".$row["DUI"]." </td><td>".$row["nombre"]." </td><td>".$fechaf."</td><td> ".str_replace("-","",$row["selloRecepcion"])."</td><td>".str_replace("-","",$row["uuid"])."</td><td>".(number_format(($row["total"]/90)*100,2,'.',''))."</td><td>0.00</td><td>".$row["tipoOperacion"]."</td><td>".$row["clasificacion"]."</td><td>".$row["sector"]."</td><td>".$row["tipoGasto"]."</td><td>5</td></tr>";

  
  }
  
  
	}
  
		}
		
	elseif ($documento=="Anulaciones"){
		echo '  <thead>
    <tr>
	 <th scope="col">Numero resolucion</th>
      <th scope="col">Clase documento</th>
      <th scope="col">Desde</th>
	  <th scope="col">Hasta</th>
	  <th scope="col">Tipo de documento</th>
	  <th scope="col">Tipo de detalle</th>
	  <th scope="col">Serie</th>
	  <th scope="col">Desde</th>
	  <th scope="col">Hasta</th>
	  <th scope="col">Codigo de generaci¨®n</th>
    </tr>
  </thead>
  <tbody>';
  
  $sql = "SELECT v.*, c.identidad, c.DUI, c.num_identidad, c.nombre, c.telefono, c.direccion FROM ventas2 v INNER JOIN clientes2 c ON v.id_cliente2 = c.id WHERE v.fecha BETWEEN '".$_POST["finicio"]."' AND '".$_POST["ffin"]."' AND (v.docuemi = 'SUJETO EXCLUIDO' AND v.estado = 0) ORDER BY v.fecha ";
$result = $conn->query($sql);
$totalDinero=0;
$totalProducto =0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) { 
   $fecha =explode("-", $row["fecha"]);
   $fechaf =$fecha[2]."/".$fecha[1]."/".$fecha[0] ;
  
   echo " <tr><td>".str_replace("-","",$row["numeroControlDte"])."</td><td>4</td><td>0 </td><td>0</td><td>14</td><td>D</td><td>".$row["selloRecepcion"]."</td><td>0</td><td>0</td><td>".str_replace("-","",$row["uuid"])."</td></tr>";

  
  }
  
  
	}
	

  $sql = "SELECT v.*, c.identidad, c.DUI, c.num_identidad, c.nombre, c.telefono, c.direccion FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.fecha BETWEEN '".$_POST["finicio"]."' AND '".$_POST["ffin"]."' AND v.estado = 0 ";
$result = $conn->query($sql);
$totalDinero=0;
$totalProducto =0;
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) { 
   $fecha =explode("-", $row["fecha"]);
   $fechaf =$fecha[2]."/".$fecha[1]."/".$fecha[0] ;
  
   echo " <tr><td>".str_replace("-","",$row["numeroControlDte"])."</td><td>4</td><td>0 </td><td>0</td><td> ".$row["numdocu"]."</td><td>D</td><td>".$row["selloRecepcion"]."</td><td>0</td><td>0</td><td>".str_replace("-","",$row["uuid"])."</td></tr>";

  
  }
  
  
	}	
  
  
  
  
	}	
	
}else{
	echo "Rango de fechas no validos";
}
}




?> 
 
</tbody>
</table>

</div>
						<div class="col-md-12">
                            <button class="btn btn-primary" type="button" id="btnAccion">Exportar CSV</button>
							
							<button class="btn btn-danger" type="button" id="btnpdf" onclick ="generarPdf(<?php echo $mes.",".$anio.",'".$documento."','".$_POST["finicio"]."','".$_POST["ffin"]."'"; ?>)">Exportar PDF</button>
						 </div>




	  
   
     <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?php if(isset($_POST["finicio"])){echo $_POST["finicio"]; }   ?>" id="finicioValue" hidden >
	  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?php if(isset($_POST["ffin"])){  echo $_POST["ffin"];} ?>" id="ffinValue" hidden >
	  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" value="<?php echo $documento[0]; ?>" id="tipo" hidden >
	
	

	
	 
	 </div>
	</div>

                <!-- input para buscar codigo -->


                <!-- input para buscar nombre -->


               

                <!-- table productos -->

        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>