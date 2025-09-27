<?php
require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Dompdf\Dompdf;

class LibrosIva extends Controller
{
    private $id_usuario;
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        if (!verificar('librosIva')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        $data['title'] = 'Ventas';
		 $data['script'] = 'librosIva.js';
        $data['carrito'] = 'posVenta';
        $resultSerie = $this->model->getSerie();
        $serie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;
        $data['serie'] = $this->generate_numbers($serie, 1, 8);
        $this->views->getView('librosIva', 'index', $data);
    }
	
	 public function csv()
    {
 $arreglo[0] = array("Nombre","Apellido","Animal","Fruto");
$arreglo[1] = array("Juan","Juarez","Jirafa","Jicama");
$arreglo[2] = array("Maria","Martinez","Mono","Mandarina");
$arreglo[3] = array("Esperanza","Escobedo","Elefante","Elote");

$ruta ="mi_archivo.csv";
generarCSV($arreglo, $ruta, $delimitador = ';', $encapsulador = '"');

$ruta = 'mi_archivo.csv';
$archivo = basename($ruta);

header("Content-type: application/octet-stream");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"$archivo\"");
readfile($ruta);
    }
	
	function generarCSV($arreglo, $ruta, $delimitador, $encapsulador){
  $file_handle = fopen($ruta, 'w');
  foreach ($arreglo as $linea) {
    fputcsv($file_handle, $linea, $delimitador, $encapsulador);
  }
  rewind($file_handle);
  fclose($file_handle);
}
	
	 public function buscarRegistro(){
	 $array = array();
		$idVenta = strClean($_GET['id']);
        $data = $this->model->buscarRegistroVenta($idVenta);
		$datajson = $this->model->buscarRegistroDte($idVenta);
		$resultSeriePdv = $this->model->getCodPuntoVenta($_SESSION['id_usuario']);
		$result['codPuntoVentaMH'] = $resultSeriePdv['codPuntoVentaMH'];
		 foreach ($datajson as $row) {
			$datajson = $row['dte'];
			 
		 }
		$dte = json_decode($datajson,true);
		$result['emisor'] = $dte["dteJson"]["emisor"];
		$result['cuerpo'] = $dte["dteJson"]["cuerpoDocumento"];
		$result['resumen'] = $dte["dteJson"]["resumen"];
		$result['identificacion'] = $dte["dteJson"]["identificacion"];
		$result['receptor'] = $dte["dteJson"]["receptor"];
        foreach ($data as $row) {
            $result['id_cliente'] = $row['id_cliente'];
            $result['uuid'] = $row['uuid'];
            $result['total'] = $row['total'];
            $result['nombre'] = $row['nombre'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
	 }

    public function registrarVenta()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $total = 0;
        if (!empty($datos['productos'])) {
            $fecha = date('Y-m-d');
            $hora = date('h:i:s');
            $metodo = $datos['metodo'];
            $docuemi = $datos['docuemi'];
			$correlativo = $datos['correlativo'];
			$numeroControlDte = $datos['numeroControlDte'];
			$dte = $datos['dte'];
			$uuid = $datos['uuid'];
			$tipoTransmision = $datos['tipoTransmision'];
			$totalVenta = $datos['total'];
			$codPuntoVentaMH = $datos['codPuntoVentaMH'];


            $resultSerie = $this->model->getSerie();
            $numSerie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;

            $serie = $this->generate_numbers($numSerie, 1, 8);
            $descuento = (!empty($datos['descuento'])) ? $datos['descuento'] : 0;
            $idCliente = $datos['idCliente'];
            if (empty($idCliente)) {
                $res = array('msg' => 'EL CLIENTE ES REQUERIDO', 'type' => 'warning');
            }else {
					
                    foreach ($datos['productos'] as $producto) {
                        $result = $this->model->getProducto($producto['id']);
                        $data['id'] = $result['id'];
                        $data['nombre'] = $result['descripcion'];
                        $data['precio'] = $producto['precio'];
                        $data['cantidad'] = $producto['cantidad'];
                        $subTotal = $producto['precio'] * $producto['cantidad'];
                        array_push($array['productos'], $data);
                        $total += $subTotal;
                    }
                    $datosProductos = json_encode($array['productos']);
                    $pago = (!empty($datos['pago'])) ? $datos['pago'] : $totalVenta;
                    $venta = $this->model->registrarVenta($datosProductos, $totalVenta, $fecha, $hora, $metodo, $descuento, $serie[0], $pago, $docuemi, 0, " ", " ", " ", $idCliente, $this->id_usuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH);
					
                    
				   if ($venta > 0) {
					    foreach ($datos['productos'] as $producto) {
                            $result = $this->model->getProducto($producto['id']);
                            //actualizar stock
                            $nuevaCantidad = $result['cantidad'] + $producto['cantidad'];
                            $totalVentas2 = $result['ventas2'] + $producto['cantidad'];
                            $this->model->actualizarStock($nuevaCantidad, $totalVentas2, $result['id']);

                            $movimiento = 'Nota de credito N°: ' . $venta;
                            $cantidad = $producto['cantidad'];
                            $this->model->registrarMovimiento($movimiento, 'entrada', $cantidad, $nuevaCantidad, $producto['id'], $this->id_usuario);
                        }
					   
					   
					    $dteJson = $this->model->registrarDte($venta, $dte);
						if($tipoTransmision == "Contingencia"){
						$this->model->registrarDteCotingencia($venta, $dte, $uuid, $totalVenta, $fecha);	
						}
                        $res = array('msg' => 'NOTA DE CREDITO GENERADA', 'type' => 'success', 'idVenta' => $venta);
                    } else {
                        $res = array('msg' => 'ERROR AL GENERAR VENTA', 'type' => 'error');
                    }
                
            }
        } else {
            $res = array('msg' => 'CARRITO VACIO', 'type' => 'warning');
        }
        echo json_encode($res);
        die();
    }
	
	public function verificarCaja(){
		
		 $verifcarCaja = $this->model->getCaja($this->id_usuario);
                if (empty($verifcarCaja['monto_inicial'])) {
                    $res = array('msg' => 'La CAJA ESTA CERRADA', 'type' => 'warning');
                }else{
					 $res = array('msg' => 'Caja Abierta', 'type' => 'success');
				}
				
				echo json_encode($res);
				
	}

        public function reporte($datos)
    {
        ob_start();
        $array = explode(',', $datos);
        $mesLetras = $array[1];
		$mesNum = $array[2];
		$anio = $array[3];
        $documento = $array[4];
		$finicio = $array[5];
		$ffin = $array[6];

        $data['title'] = 'Reporte';
        $data['empresa'] = $this->model->getEmpresa();
        $data['mesLetras'] = $mesLetras;
		$data['mesNum'] = $mesNum;
		$data['anio'] = $anio;
		$data['documento'] = $documento;
		$data['finicio'] = $finicio;
		$data['ffin'] = $ffin;
		$this->views->getView('librosIva', 'libroPdf', $data);	
        
    }
	
	public function anularDte($datos){
		 ob_start();
        $array = explode(',', $datos);
        $id = $array[0];	
	$data['id'] = $id;
	$this->views->getView('notaCredito', 'anular', $data);
	}

    public function listar()
    {
        $data = $this->model->getVentas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['acciones'] = '<div>
                <a class="btn btn-warning" href="#" onclick="anularVenta(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></a>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
            } else {
                $data[$i]['acciones'] = '<div>
                <span class="badge bg-info">Anulado</span>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
            }
        }
        echo json_encode($data);
        die();
    }
	
	public function listarClientes()
    {
        $data = $this->model->getClientes();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['acciones'] = '<div>
                <a class="btn btn-warning" href="#" onclick="anularVenta(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></a>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
            } else {
                $data[$i]['acciones'] = '<div>
                <span class="badge bg-info">Anulado</span>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
            }
        }
        echo json_encode($data);
        die();
    }

    public function anular($idVenta)
    {
        if (isset($_GET) && is_numeric($idVenta)) {
            $data = $this->model->anular($idVenta);
            if ($data == 1) {
                $resultVenta = $this->model->getVenta($idVenta);
                $ventaProducto = json_decode($resultVenta['productos'], true);
                foreach ($ventaProducto as $producto) {
                    $result = $this->model->getProducto($producto['id']);
                    $nuevaCantidad = $result['cantidad'] - $producto['cantidad'];
                    $totalVentas2 = $result['ventas2'] - $producto['cantidad'];
                    $this->model->actualizarStock($nuevaCantidad, $totalVentas2, $producto['id']);

                    //movimientos
                    $movimiento = 'Anulacion Nota de Credito N°: ' . $idVenta2;
                    $this->model->registrarMovimiento($movimiento, 'salida', $producto['cantidad'], $nuevaCantidad, $producto['id'], $this->id_usuario);
                }
                if ($resultVenta['metodo'] == 'CREDITO') {
                    $this->model->anularCredito($idVenta);
                }
                $res = array('msg' => 'NOTA DE CREDITO ANULADA', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ANULAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function impresionDirecta($idVenta)
    {
        $empresa = $this->model->getEmpresa();
        $venta = $this->model->getVenta($idVenta);
        $nombre_impresora = "AON Printer";
        $connector = new WindowsPrintConnector($nombre_impresora);
        $printer = new Printer($connector);

        # Vamos a alinear al centro lo próximo que imprimamos
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        /*
            Intentaremos cargar e imprimir
            el logo
        */
        try {
            $logo = EscposImage::load("assets/images/logo.png", false);
            $printer->bitImage($logo);
        } catch (Exception $e) {/*No hacemos nada si hay error*/
        }

        /*
            Ahora vamos a imprimir un encabezado
        */

        $printer->text($empresa['nombre'] . "\n");
        $printer->text('Nit: ' . $empresa['ruc'] . "\n");
        $printer->text('Telefono: ' . $empresa['telefono'] . "\n");
        $printer->text('Dirección: ' . $empresa['direccion'] . "\n");
        $printer->text('Giro: ' . $empresa['giro'] . "\n");
        #La fecha también
        $printer->text(date("Y-m-d H:i:s") . "\n\n");


         #Datos del documento
         $printer->text('Documento Emitido' . "\n");
         $printer->text('--------------------' . "\n");
         /*Alinear a la izquierda para la cantidad y el nombre*/
         $printer->setJustification(Printer::JUSTIFY_LEFT);
         
         $printer->text('Id: ' . $venta['serie'] . "\n");
         $printer->text('Emitido: ' . $venta['docuemi'] . "\n");
         $printer->text('Numero: ' . $venta['numdocu'] . "\n");
         $printer->text('Forma de pago: ' . $venta['metodo'] . "\n\n");
 

        #Datos del cliente
        $printer->text('Datos del Cliente' . "\n");
        $printer->text('--------------------' . "\n");
        /*Alinear a la izquierda para la cantidad y el nombre*/
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        
        $printer->text('Nombre: ' . $venta['nombre'] . "\n");
        $printer->text('Telefono: ' . $venta['telefono'] . "\n");
        $printer->text('Dirección: ' . $venta['direccion'] . "\n\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text('Detalles del Producto' . "\n");
        $printer->text('--------------------' . "\n");
        $productos = json_decode($venta['productos'], true);
        foreach ($productos as $producto) {
            /*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($producto['cantidad'] . "x" . $producto['nombre'] . "\n");

            /*Y a la derecha para el importe*/
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text(MONEDA . number_format($producto['cantidad'] = $venta['total'], 2) . "\n\n");
        }

        /*
            Terminamos de imprimir
            los productos, ahora va el total
        */
        $printer->text("--------\n");
        $printer->text("Descuento: " . MONEDA . number_format($venta['descuento'], 2) . "\n");
        $printer->text("--------\n");
        $printer->text("Paga con: " . MONEDA . number_format($venta['pago'], 2) . "\n");
        $printer->text("--------\n");
        $printer->text("Cambio: " . MONEDA . number_format($venta['pago'] - $venta['total'] + $venta['descuento'], 2) . "\n\n");
        $printer->text("--------\n");
        $printer->text("TOTAL: " . MONEDA . number_format($venta['total'] - $venta['descuento'], 2) . "\n\n");


        /*
            Podemos poner también un pie de página
        */
        $printer->text($empresa['mensaje']);



        /*Alimentamos el papel 3 veces*/
        $printer->feed(3);

        /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
        */
        $printer->cut();

        /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
        */
        $printer->pulse();

        /*
            Para imprimir realmente, tenemos que "cerrar"
            la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
        $printer->close();
    }

    public function verificarStock($idProducto)
    {
        $data = $this->model->getProducto($idProducto);
        echo json_encode($data);
        die();
    }

    function generate_numbers($start, $count, $digits)
    {
        $result = array();
        for ($n = $start; $n < $start + $count; $n++) {
            $result[] = str_pad($n, $digits, "0", STR_PAD_LEFT);
        }
        return $result;
    }
	
		 public function maxCorrelativo()
    {
        $array = array();
		$tipoDocumento = strClean($_GET['tipoDocumento']);
        $data = $this->model->getMaxCorrelativo($tipoDocumento);
       
            $resultado['correlativo'] = $data['correlativo'];
            array_push($array, $resultado);
        
        echo json_encode($array);
        die();
    }
}
