<?php
define('BASE_URL', 'https://maquinariaagricola.in.net/postwo/');
define('HOST', 'localhost');
define('USER', 'maquigd8_maquinaria');
define('PASS', 'Gatoperro12');
define('DBNAME', 'maquigd8_maquinaria');
define('CHARSET', 'UTF-8');
define('TITLE', 'POS VENTA');
define('MONEDA', 'S/$ ');
define('HOST_SMTP', 'mail.maquinariaagricola.top');
define('USER_SMTP', 'prueba@maquinariaagricola.top');
define('CLAVE_SMTP', 'Gatoperro12$$');
define('PUERTO_SMTP', '465');
define('nit','02100903931053');

// Configuraciones Facturacion Electronica

define('apiAutorizador','https://apitest.dtes.mh.gob.sv/seguridad/auth');
define('pasApiMH','Julio0903$$$$');
//define('apiFirmador','http://54.88.184.145:8113/firmardocumento/');
define('apiFirmador','https://pnn1jei9yi.execute-api.us-east-1.amazonaws.com/dev/firmador');
//define('apiFirmador','https://zssljx0x3g.execute-api.us-east-1.amazonaws.com/dev/prdfirmador/');

define('apiRecepcionDTE','https://apitest.dtes.mh.gob.sv/fesv/recepciondte');
define('apiAnularMH','https://apitest.dtes.mh.gob.sv/fesv/anulardte');
define('apiSolicitudContingencia','https://apitest.dtes.mh.gob.sv/fesv/contingencia');
define('passwordPri','Belen0809$$$$');

define('nrc','2926189');
define('nombre','INTERACTIVEMENUSV');
define('codActividad','62010');
define('descActividad','Programacion informatica');
define('tipoEstablecimiento','02');
define('nomEstablecimiento','Casa Matriz');
//define('tipoEstablecimiento','01');
//define('nomEstablecimiento','Sucursal');
define('departamento','06');
define('municipio','14');
define('complemento','AV. ALVARADO DIAG. CENTROAMERICA, #4,');
define('telefonoEmisor','79213508');
define('correo','jmarroquin@interactivemenusv.com');
define('correoEmisor','jmarroquin@interactivemenusv.com');
define('nombreCorreo','Software Informaticos');

//CreditoFiscal
define('creditoBase','DTE-03-00000000-110000000000000');
define('version',3);
define('tipoDTECredito','03');

//Objeto identificacion


// objeto identificacion
define('ambiente','00');
define('tipoModelo',1);
define('tipoOperacion',1);
define('tipoMoneda','USD');



//Consumidor Final
define('consumidorBase','DTE-01-00000000-110000000000000');
define('versionConsumidor',1);
define('tipoDTEConsumidor',"01");

//Nota de Credito
define('notaCreditoBase','DTE-05-00000000-00000000000000');
define('versionNotaCredito',3);
define('tipoDTEnotaCredito',"05");

//Sujeto Excluido
define('sujetoExcluidoBase','DTE-14-00000000-00000000000000');
define('versionSujetoExcluido',1);
define('tipoDTEsujetoExcluido',"14");

//Exportacion
define('exportacionBase','DTE-11-00000000-00000000000000');
define('versionExportacion',1);
define('tipoDTEExportacion',"11");

?>