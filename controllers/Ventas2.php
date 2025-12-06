<?php
require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Dompdf\Dompdf;

class Ventas2 extends Controller
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
        if (!verificar('compras')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        $data['title'] = 'Ventas2';
        $data['script'] = 'ventas2.js';
        $data['busqueda'] = 'busquedaVentas2.js';
        $data['carrito'] = 'posVenta2';
		$data['bodegas'] = $this->model->getDatos('bodegas');
        $resultSerie = $this->model->getSerie();
        $serie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;
        $data['serie'] = $this->generate_numbers($serie, 1, 8);
        $this->views->getView('ventas2', 'index', $data);
        
    }

    	public function comprasCotizacion($datos)
    {
		ob_start();
        $array = explode(',', $datos);
        $idCotizacion = $array[0];
		$idCliente = $array[1];
		$data['cliente'] = $this->model->getCliente($idCliente);
		$data['cotizacion'] = $idCotizacion;
        $data['title'] = 'Ventas2';
        $data['script'] = 'ventas2.js';
        $data['busqueda'] = 'busquedaVentas2.js';
        $data['carrito'] = 'posVenta2';
		$data['bodegas'] = $this->model->getDatos('bodegas');
        $resultSerie = $this->model->getSerie();
        $serie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;
        $data['serie'] = $this->generate_numbers($serie, 1, 8);
        $this->views->getView('ventas2', 'index', $data);
    }
	
	public function maxCorrelativo()
    {
        $array = array();
		$tipoDocumento = strClean($_GET['tipoDocumento']);	
		$codPuntoVenta = strClean($_GET['codPuntoVentaMH']);
        $data = $this->model->getMaxCorrelativo($tipoDocumento,$codPuntoVenta);
       
            $resultado['correlativo'] = $data['correlativo'];
            array_push($array, $resultado);
        
        echo json_encode($array);
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

    public function registrarVenta2()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $total = 0;
        if (!empty($datos['productos'])) {
            // Si la compra proviene de una orden de compra, actualizar el estado
            if (isset($datos['idPedido']) && intval($datos['idPedido']) > 0) {
                $idPedido = intval($datos['idPedido']);
                // Actualizar estado en la tabla ordenes_compra
                $this->model->actualizarEstadoOrdenCompra($idPedido, 'completado');
            }
            $fecha =$datos['fechaC']; 
            $hora = date('H:i:s');
            $metodo = $datos['metodo'];
            $numc = $datos['numc'];
            $resultSerie = $this->model->getSerie();
            $numSerie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;
			$correlativo = $datos['correlativo'];
			$numeroControlDte = $datos['numeroControlDte'];
			$dte = $datos['dte'];
			$uuid = $datos['uuid'];
			$tipoTransmision = $datos['tipoTransmision'];
			$totalVenta = $datos['total'];
			$renta = $datos['renta'];
			$codPuntoVentaMH = $datos['codPuntoVentaMH'];
			$cGravadas = $datos['gravadas'];
			$cExentas = $datos['exentas'];
			$cIva = $datos['iva'];
			$claseDoc = $datos['claseDocumento'];
			$tipoDoc = $datos['tipoDocumento'];
			$sello = $datos['sello'];
			$fovial = $datos['fovial'];
			$cotrans = $datos['cotrans'];
			$percepcion1 = $datos['percepcion1'];
			$percepcion2 = $datos['percepcion2'];
			$docuemision = $datos['docuemision'];
			$tipoOperacionCompra = $datos['tipoOperacionCompra'];
			$clasificacion = $datos['clasificacion'];
			$sector = $datos['sector'];
			$tipoGasto = $datos['tipoGasto'];
			$bodega = $datos['bodega'];

            $serie = $this->generate_numbers($numSerie, 1, 8);
            $descuento = (!empty($datos['descuento'])) ? $datos['descuento'] : 0;
            $idCliente2 = $datos['idCliente2'];
            if (empty($idCliente2)) {
                $res = array('msg' => 'EL CLIENTE2 ES REQUERIDO', 'type' => 'warning');
            } else if (empty($metodo)) {
                $res = array('msg' => 'EL METODO ES REQUERIDO', 'type' => 'warning');
            } else 


                $verifcarCaja = $this->model->getCaja($this->id_usuario);
                if (empty($verifcarCaja['monto_inicial'])) {
                    $res = array('msg' => 'La CAJA ESTA CERRADA', 'type' => 'warning');
                } else {
                    foreach ($datos['productos'] as $producto) {
                        $result = $this->model->getProducto($producto['id'],$bodega);
                        $data['id'] = $producto['id'];
                        $data['nombre'] = $producto['descripcion'];
                        $data['precio'] = $producto['precio'];
                        $data['cantidad'] = $producto['cantidad'];
						$data['catalogo'] = $producto['catalogo'];
                        $subTotal = $producto['precio'] * $producto['cantidad'];
                        array_push($array['productos'], $data);
                        $total += $subTotal;
                    }
                    $datosProductos = json_encode($array['productos']);
					
                    $venta2 = $this->model->registrarVenta2($datosProductos, $totalVenta, $fecha, $hora, $metodo, $descuento, $serie[0], $numc,  $idCliente2, $this->id_usuario, $correlativo,$numeroControlDte,$uuid,$renta, $codPuntoVentaMH, $cGravadas, $cExentas, $cIva, $claseDoc, $tipoDoc, $sello, $fovial, $cotrans, $percepcion1, $percepcion2, $docuemision, $tipoOperacionCompra, $clasificacion, $sector, $tipoGasto); 
                    if ($venta2 > 0) {
						if($correlativo!=""){
						 $dteJson = $this->model->registrarDte($venta2, $dte);
						if($tipoTransmision == "Contingencia"){
						$this->model->registrarDteCotingencia($venta2, $dte, $uuid, $totalVenta, $fecha);						
						}
						}
                        foreach ($datos['productos'] as $producto) {
                            $result = $this->model->getProducto($producto['id'],$bodega);
                            //actualizar stock
							if($docuemision=="NOTA DE CREDITO"){
							$nuevaCantidad = $result['stock'] - $producto['cantidad'];
                            $totalVentas2 = $result['ventas2'] - $producto['cantidad'];	
							}else{
								$dataValidar = $this->model->getProductoBodega($producto['id'],$bodega);
								if($dataValidar['total']>0){
									$nuevaCantidad = $result['stock'] + $producto['cantidad'];
									$totalVentas2 = $result['ventas2'] + $producto['cantidad'];
								}else{
									$nuevaCantidad = 0 + $producto['cantidad'];
									$totalVentas2 = 0 + $producto['cantidad'];
								}
							
							}
                            
							if($producto['catalogo']=="Servicio"){
							$nuevaCantidad =1;
							$cantidad =1;
							}
							if($producto['catalogo']!="Servicio"){
							$dataValidar = $this->model->getProductoBodega($producto['id'],$bodega);
							if($dataValidar['total']>0){
                            $this->model->actualizarStock($nuevaCantidad, $producto['id'], $bodega);
							}else{
							$this->model->registrarStock($nuevaCantidad, $producto['id'], $bodega);	
							}
							}

                            $movimiento = 'Compra N°: ' . $venta2;
                            $cantidad = $producto['cantidad'];
							if($producto['catalogo']=="Servicio"){
							$cantidad = 1;
							if($docuemision=="NOTA DE CREDITO"){
							 $this->model->registrarMovimiento($movimiento, 'Salida Nota de credito', $cantidad, $result['cantidad'], $producto['id'], $this->id_usuario);	
							}else{
							 $this->model->registrarMovimiento($movimiento, 'entrada', $cantidad, $result['cantidad'], $producto['id'], $this->id_usuario);	
							}
                           
							}else{
								if($docuemision=="NOTA DE CREDITO"){
								 $this->model->registrarMovimiento($movimiento, 'Salida Nota de Credito', $cantidad, $nuevaCantidad, $producto['id'], $this->id_usuario);	
								}else{
								 $this->model->registrarMovimiento($movimiento, 'entrada', $cantidad, $nuevaCantidad, $producto['id'], $this->id_usuario);	
								}
								
                           
							}
						}
                        if ($metodo == 'CREDITO') {
                            $monto = $total - $descuento;
							$monto = $monto *1.13;
                            $this->model->registrarCredito2($monto, $fecha, $hora, $venta2);
                        }
                        if ($datos['impresion']) {
                            $this->impresionDirecta($venta2);
                        }
						if($docuemision=="NOTA DE CREDITO"){
						 $res = array('msg' => 'NOTA DE CREDITO GENERADA', 'type' => 'success', 'idVenta2' => $venta2);	
						}else{
						 $res = array('msg' => 'COMPRA GENERADA', 'type' => 'success', 'idVenta2' => $venta2);	
						}
                       
                    } else {
                        $res = array('msg' => 'ERROR AL GENERAR COMPRA', 'type' => 'error');
                    }
                }
            
        } else {
            $res = array('msg' => 'CARRITO VACIO', 'type' => 'warning');
        }
        echo json_encode($res);
        die();
    }

    public function reporte($datos)
    {
        ob_start();
        $array = explode(',', $datos);
        $tipo = $array[0];
        $idVenta2 = $array[1];

        $data['title'] = 'Reporte';
        $data['empresa'] = $this->model->getEmpresa();
        $data['venta2'] = $this->model->getVenta2($idVenta2);
		$data['idVenta']=$idVenta2;
        if (empty($data['venta2'])) {
            echo 'Pagina no Encontrada';
            exit;
        }
        if ($tipo == 'ticked') {
		$this->views->getView('ventas2', $tipo, $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper(array(0, 0, 130, 841), 'portrait');
			 // Render the HTML as PDF
         $dompdf->render();

        // Output the generated PDF to Browser
         $dompdf->stream('reporte.pdf', array('Attachment' => false));
		 if($data['venta2']['docuemi']=='SUJETO EXCLUIDO'){
		$this->views->getView('ventas2', 'factura', $data);	
		}
		 
        }else if($tipo == 'pdf'){
		if($data['venta2']['docuemi']=='SUJETO EXCLUIDO'){
		$this->views->getView('ventas2', 'facturapdf', $data);	
		}	
		}else{	
		if($data['venta2']['docuemi']=='SUJETO EXCLUIDO'){
		$this->views->getView('ventas2', 'factura', $data);	
		}elseif($data['venta2']['docuemi']=='COMPRA'){
			$this->views->getView('ventas2', $tipo, $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'vertical');
			 // Render the HTML as PDF
         $dompdf->render();

        // Output the generated PDF to Browser
         $dompdf->stream('reporte.pdf', array('Attachment' => false));
		}
		}
    }

    public function listar()
    {
        $data = $this->model->getVentas2();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
				if($data[$i]['docuemi'] == "COMPRA"){
                $data[$i]['acciones'] = '<div>
                <a class="btn btn-warning" href="#" onclick="anularVenta2(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></a>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
				}else{
				 $data[$i]['acciones'] = '<div>
                <a class="btn btn-warning" href="#" onclick="anularDte(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></a>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';	
				}
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
	
	public function anularDte($datos){
		 ob_start();
        $array = explode(',', $datos);
        $id = $array[0];	
	$data['id'] = $id;
	$this->views->getView('ventas2', 'anular', $data);
	}

    public function anular($idVenta2)
    {
        if (isset($_GET) && is_numeric($idVenta2)) {
            $data = $this->model->anular($idVenta2);
            if ($data == 1) {
                $resultVenta2 = $this->model->getVenta2($idVenta2);
                $venta2Producto = json_decode($resultVenta2['productos'], true);
                foreach ($venta2Producto as $producto) {
                    $result = $this->model->getProducto($producto['id']);
                    $nuevaCantidad = $result['cantidad'] - $producto['cantidad'];
                    $totalVentas2 = $result['ventas2'] - $producto['cantidad'];
					if($producto['catalogo']!="Servicio"){
					$this->model->actualizarStock($nuevaCantidad, $totalVentas2, $producto['id']);	
					}
                    //movimientos
                    $movimiento = 'Devolución Venta2 N°: ' . $idVenta2;
                    $this->model->registrarMovimiento($movimiento, 'salida', $producto['cantidad'], $nuevaCantidad, $producto['id'], $this->id_usuario);
                }
                if ($resultVenta2['metodo'] == 'CREDITO') {
                    $this->model->anularCredito2($idVenta2);
                }
                $res = array('msg' => 'VENTA2 ANULADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ANULAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }
	
	

    public function impresionDirecta($idVenta2)
    {
        $empresa = $this->model->getEmpresa();
        $venta2 = $this->model->getVenta2($idVenta2);
        $nombre_impresora = "POS-58-Series";
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
        $printer->text('RUC: ' . $empresa['ruc'] . "\n");
        $printer->text('Telefono: ' . $empresa['telefono'] . "\n");
        $printer->text('Dirección: ' . $empresa['direccion'] . "\n");
        $printer->text('numc: ' . $empresa['numc'] . "\n");
        #La fecha también
        $printer->text(date("Y-m-d H:i:s") . "\n\n");

        #Datos del cliente2
        $printer->text('Datos del Cliente2' . "\n");
        $printer->text('--------------------' . "\n");
        /*Alinear a la izquierda para la cantidad y el nombre*/
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text($venta2['identidad'] . ': ' . $venta2['num_identidad'] . "\n");
        $printer->text('Nombre: ' . $venta2['nombre'] . "\n");
        $printer->text('Telefono: ' . $venta2['telefono'] . "\n");
        $printer->text('Dirección: ' . $venta2['direccion'] . "\n\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text('Detalles del Producto' . "\n");
        $printer->text('--------------------' . "\n");
        $productos = json_decode($venta2['productos'], true);
        foreach ($productos as $producto) {
            /*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($producto['cantidad'] . "x" . $producto['nombre'] . "\n");

            /*Y a la derecha para el importe*/
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text(MONEDA . number_format($producto['precio'], 2) . "\n");
        }

        /*
            Terminamos de imprimir
            los productos, ahora va el total
        */
        $printer->text("--------\n");
        $printer->text("Descuento: " . MONEDA . number_format($venta2['descuento'], 2) . "\n");
        $printer->text("--------\n");
        $printer->text("TOTAL: " . MONEDA . number_format($venta2['total'] - $venta2['descuento'], 2) . "\n\n");


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
}
