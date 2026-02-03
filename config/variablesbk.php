<?php

$db="u526756767_posman";
$userbd="u526756767_posman";
$passwordbd="Posman27$";
$url="127.0.0.1:3306";


//Variables de ambiente

$apiFirmador = "http://54.88.184.145:8113/firmardocumento/";
$apiAutorizador = "https://apitest.dtes.mh.gob.sv/seguridad/auth";
$apiRecepcionDTE = "https://apitest.dtes.mh.gob.sv/fesv/recepciondte";
$pasApiMH = "Julio0903$$$$";
$apiAnularMH = "https://apitest.dtes.mh.gob.sv/fesv/anulardte";
$apiSolicitudContingencia = "https://apitest.dtes.mh.gob.sv/fesv/contingencia";


// objeto identificacion
$ambiente = "00";
$tipoModelo = 1;
$tipoOperacion = 1;
$tipoContingencia = null;
$motivoContingencia = null;
$motivoContin = null;
$tipoMoneda = "USD";

// objeto emisor

$nit = "02100903931053";
$passwordPri = "Belen0809$$$$";
$nrc = "2926189";
$nombre = "INTERACTIVEMENUSV";
$codActividad = "62010";
$descActividad = "Programacion informatica";
$nombreComercial = null;
$tipoEstablecimiento = "02";
$departamento = "06";
$municipio = "14";
$complemento = "AV. ALVARADO DIAG. CENTROAMERICA, #4,";
$telefono = "79213508";
$correo = "salvador@ggg.com";
$codEstableMH = null;
$codEstable = null;
$codPuntoVentaMH = null;
$codPuntoVenta = null;
$correoEmisor = "jmarroquin@interactivemenusv.com";
$nombreCorreo = "Software Informaticos";

//factura credito fiscal
$creditoBase = "DTE-03-00000000-500000000000000";
$version = 3;
$tipoDTECredito = "03";

//Consumidor Final
$consumidorBase = "DTE-01-00000000-300000000000000";
$versionConsumidor = 1;
$tipoDTEConsumidor = "01";

//Sujeto Excluido
$sujetoExcluidoBase = "DTE-14-00000000-300000000000000";
$versionSujetoExcluido = 1;
$tipoDTESujetoExcluido = "14";

//Sujeto Excluido
$exportacionBase = "DTE-11-00000000-300000000000000";
$versionExportacion = 1;
$tipoDTEExportacion = "11";




?>

