<?php
require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Dompdf\Dompdf;

class Pedidos extends Controller
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
        if ( !verificar('pedidos') && !verificar('FacturarPedidos') && !verificar('despacho') ) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        $data['title'] = 'Pedidos';
        $data['script'] = 'pedidos.js';
        $data['busqueda'] = 'busqueda.js';
        $data['carrito'] = 'posVenta';
        $resultSerie = $this->model->getSerie();
		$data['bodegas'] = $this->model->getBodegas(1);
        $serie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;
        $data['serie'] = $this->generate_numbers($serie, 1, 8);
        $data['formasPago'] = $this->model->getFormasPago();
        $this->views->getView('pedidos', 'index', $data);
    }
	
	public function facturacion()
    {
        $data['title'] = 'PedidosFacturacion';
        $data['script'] = 'pedidosFacturacion.js';
        $data['busqueda'] = 'busqueda.js';
        $data['carrito'] = 'posVenta';
        $resultSerie = $this->model->getSerie();
		$data['bodegas'] = $this->model->getBodegas(1);
        $serie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;
        $data['serie'] = $this->generate_numbers($serie, 1, 8);
        $data['formasPago'] = $this->model->getFormasPago();
        $this->views->getView('pedidos', 'facturacion', $data);
    }
	
	public function despacho()
    {
        $data['title'] = 'despacho';
        $data['script'] = 'despacho.js';
        $data['busqueda'] = 'busqueda.js';
        $data['carrito'] = 'posVenta';
        $resultSerie = $this->model->getSerie();
		$data['bodegas'] = $this->model->getBodegas(1);
        $serie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;
        $data['serie'] = $this->generate_numbers($serie, 1, 8);
        $data['formasPago'] = $this->model->getFormasPago();
        $this->views->getView('pedidos', 'despacho', $data);
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
            $numdocu = $datos['numdocu'];
            $vende = $datos['vende'];
            $forma = $datos['forma'];
            $forma2 = $datos['forma2'];
			$correlativo = $datos['correlativo'];
			$numeroControlDte = $datos['numeroControlDte'];
			$dte = $datos['dte'];
			$uuid = $datos['uuid'];
			$tipoTransmision = $datos['tipoTransmision'];
			$totalVenta = $datos['total'];
			$codPuntoVentaMH = $datos['codPuntoVentaMH'];
			$sello = $datos['sello'];
			$vExentas = $datos['vExentas'];
			$vIva = $datos['vIva'];
			$vGravadas = $datos['vGravadas'];
			$claseDoc = 4;
			$retenIva = $datos['retenIva'];
			$tipo_operacion = $datos['tipoOp'];
	        $tipo_ingreso = $datos['tipoVen'];
            $observaciones = $datos['obser'];
            $id = $datos['id'];

			


            $resultSerie = $this->model->getSerie();
            $numSerie = ($resultSerie['total'] == null) ? 1 : $resultSerie['total'] + 1;

            $serie = $this->generate_numbers($numSerie, 1, 8);
            $descuento = (!empty($datos['descuento'])) ? $datos['descuento'] : 0;
            $idCliente = $datos['idCliente'];
            if (empty($idCliente)) {
                $res = array('msg' => 'EL CLIENTE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($metodo)) {
                $res = array('msg' => 'EL METODO ES REQUERIDO', 'type' => 'warning');
            } else {
					
                    foreach ($datos['productos'] as $producto) {
                        $result = $this->model->getProducto($producto['id']);
                        $data['id'] = $result['id'];
                        $data['nombre'] = $producto['descripcion'];
                        $data['precio'] = $producto['precio'];
                        $data['cantidad'] = $producto['cantidad'];
						$data['mediaMh'] = $producto['mediaMh'];
                        $subTotal = $producto['precio'] * $producto['cantidad'];
                        array_push($array['productos'], $data);
                        $total += $subTotal;
                    }
                    $datosProductos = json_encode($array['productos']);
                    $pago = (!empty($datos['pago'])) ? $datos['pago'] : $totalVenta;
					$estadoPedido = "GENERADO";

                    if($id==""){
				//$venta = $this->model->registrarCotizacion($datosProductos, $vTotal, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $documento, $vGravadas, $vIva, $vIvaRete);	
                $venta = $this->model->registrarVenta($datosProductos, $totalVenta, $fecha, $hora, $metodo, $descuento, $serie[0], $pago, $docuemi, $numdocu, $vende, $forma, $forma2, $idCliente, $this->id_usuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH, $sello, $vExentas, $vIva, $vGravadas, $claseDoc, $retenIva, $tipo_operacion, $tipo_ingreso,$estadoPedido,$observaciones);
				}else{
				$venta = $this->model->updateVenta($datosProductos, $totalVenta, $fecha, $hora, $metodo, $descuento, $serie[0], $pago, $docuemi, $numdocu, $vende, $forma, $forma2, $idCliente, $this->id_usuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH, $sello, $vExentas, $vIva, $vGravadas, $claseDoc, $retenIva, $tipo_operacion, $tipo_ingreso,$estadoPedido,$observaciones,$id);
				$venta = $id;
				}

                    
					$res = array('msg' => 'PEDIDO GENERADO', 'type' => 'success', 'idVenta' => $venta);
                    
				   if ($venta > 0) {
					   $dteJson = $this->model->registrarDte($venta, $dte);
					    $res = array('msg' => 'PEDIDO GENERADO', 'type' => 'success', 'idVenta' => $venta);
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
	
	

    public function reporte($datos)
{
ob_start();
$array = explode(',', $datos);
$tipo = $array[0];
$idVenta = $array[1];
$data['title'] = 'Reporte';
$data['empresa'] = $this->model->getEmpresa();
$data['venta'] = $this->model->getVenta($idVenta);
$data['correlativo'] = $this->model->getMaxCorrelativo($data['venta']['docuemi'],$data['venta']['codPuntoVentaMH']);
$data['idVenta'] = $idVenta;
$data['medidas'] =$this->model->getMedidas();
if (empty($data['venta'])) {
echo 'Pagina no Encontrada';
exit;
}
if ($tipo == 'ticked') {
$this->views->getView('pedidos', $tipo, $data);
$html = ob_get_clean();
$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set('isJavascriptEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf->setOptions($options);
 $dompdf->loadHtml($html);
$productos = json_decode($data['venta']['productos'], true);
foreach ($productos as $producto) {
$c+=1;
}
$largo = ($c*50)+500;
$dompdf->setPaper(array(0, 0, 150, $largo), 'portrait');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream('reporte.pdf', array('Attachment' => false));
}else if($tipo == 'pdf'){
if($data['venta']['docuemi']=='CREDITO FISCAL'){
$this->views->getView('pedidos', 'creditopdf', $data);
}else if($data['venta']['docuemi']=='FACTURA'){
$this->views->getView('pedidos', 'facturapdf', $data);
}else if($data['venta']['docuemi']=='NOTA DE REMISION'){
$this->views->getView('pedidos', 'remisionpdf', $data);
}else if($data['venta']['docuemi']=='Nota de credito'){
$this->views->getView('notaCredito', 'creditopdf', $data);
}else if($data['venta']['docuemi']=='EXPORTACION'){
$this->views->getView('pedidos', 'exportacionpdf', $data);
}
}else{
if($data['venta']['docuemi']=='CREDITO FISCAL'){
$this->views->getView('ventas', 'credito', $data);
}else if($data['venta']['docuemi']=='FACTURA'){
$this->views->getView('ventas', 'factura', $data);
}else if($data['venta']['docuemi']=='Nota de credito'){
$this->views->getView('notaCredito', 'credito', $data);
}else if($data['venta']['docuemi']=='EXPORTACION'){
$this->views->getView('ventas', 'exportacion', $data);
}
}
}
    
	public function anularDte($datos){
		 ob_start();
        $array = explode(',', $datos);
        $id = $array[0];	
	$data['id'] = $id;
	$this->views->getView('ventas', 'anular', $data);
	}
	

    public function listar()
    {
        $data = $this->model->getVentas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
				if($data[$i]['metodo']=="PAGO CUOTA"){
               $data[$i]['acciones'] = '<div>
                <button class="btn btn-warning" href="#" onclick="anularPedido(' . $data[$i]['id'] . ')" disabled><i class="fas fa-trash"></i></button>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
				}else{
				 $data[$i]['acciones'] = '<div>
                <a class="btn btn-warning" href="#" onclick="anularPedido(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></a>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                <a class="btn btn-warning" href="#" onclick="detalle(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a>
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
	
	public function editar($id)
    {
        
		$data = $this->model->getProductoCotizacion($id);
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	public function listarGenerados()
    {
        $data = $this->model->getVentasGenerados();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
				if($data[$i]['metodo']=="PAGO CUOTA"){
               $data[$i]['acciones'] = '<div>
                <button class="btn btn-warning" href="#" onclick="anularVenta(' . $data[$i]['id'] . ')" disabled><i class="fas fa-trash"></i></button>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
				}else{
				 $data[$i]['acciones'] = '<div>
                <a class="btn btn-warning" href="#" onclick="anularVenta(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></a>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
				<a class="btn btn-success" href="#" onclick="venta(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a>
                </div>';	
				}
            } else {
                $data[$i]['acciones'] = '<div>
                <span class="badge bg-info">Anulado</span>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
				<a class="btn btn-success" href="#" onclick="venta(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a>
                </div>';
            }
        }
        echo json_encode($data);
        die();
    }
	
		public function listarDespacho()
    {
        $data = $this->model->getVentasDespacho();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
		if($data[$i]['estadoPedido']=='DESPACHO'){
		$data[$i]['estadoPedido']='<span class="badge bg-danger">DESAPACHO</span';	
		}	
		if($data[$i]['estadoPedido']=='EN PROCESO'){
		$data[$i]['estadoPedido']='<span class="badge bg-warning">EN PROCESO</span';	
		}
		if($data[$i]['estadoPedido']=='COMPLETADO'){
		$data[$i]['estadoPedido']='<span class="badge bg-success">COMPLETADO</span';	
		}
		if($data[$i]['estadoPedido']=='CANCELADO'){
		$data[$i]['estadoPedido']='<span class="badge bg-primary">CANCELADO</span';	
		}
				if($data[$i]['metodo']=="PAGO CUOTA"){
               $data[$i]['acciones'] = '<div>
               
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
                </div>';
				}else{
				 $data[$i]['acciones'] = '<div>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
				<a class="btn btn-success" href="#" onclick="venta(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a>
                </div>';	
				}
            } else {
                $data[$i]['acciones'] = '<div>
                <span class="badge bg-info">Anulado</span>
                <a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>
				<a class="btn btn-success" href="#" onclick="venta(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a>
                </div>';
            }
        }
        echo json_encode($data);
        die();
    }
	
	public function verDetalle($datos)
    {
		ob_start();
        $array = explode(',', $datos);
        $idPedido = $array[0];
		$data['idPedido'] = $idPedido;
		$data['empresa'] = $this->model->getEmpresa();		
		$data['cotizacion'] = $this->model->getCotizacion($idPedido);		
        $data['title'] = 'Bodegas';
        $data['script'] = 'detallePedido.js';
        $this->views->getView('pedidos', 'detallePedido', $data);
    }

    public function historialFacturacion()
    {		
        $data['title'] = 'HistorialFacturacion';
        $data['script'] = 'historialFacturacion.js';
        $this->views->getView('pedidos', 'historialFacturacion', $data);
    }
	
	public function updatePedido()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
            $metodo = $datos['metodo'];
            $id = $datos['id'];
			$comentarios = $datos['comentarios'];
			
			 $data = $this->model->actualizar(
                                $metodo,
                                $comentarios,
                                $id
                            );
                            if ($data > 0) {
                                $res = array('msg' => 'PEDIDO ACTUALIZADO', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL ACTUALIZAR', 'type' => 'error');
                            }
							echo json_encode($res, JSON_UNESCAPED_UNICODE);
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

     public function anularPedido($idVenta)
    {
        $data = $this->model->anular($idVenta);
        if ($data == 1) {
            $res = array('msg' => 'PEDIDO ANULADO', 'type' => 'success');
        }else{
            $res = array('msg' => 'ERROR AL ANULAR', 'type' => 'error');
        }
        echo json_encode($res);
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
                    $nuevaCantidad = $result['cantidad'] + $producto['cantidad'];
                    $totalVentas = $result['ventas'] - $producto['cantidad'];
					if($producto['id']!=0){
					$this->model->actualizarStock($nuevaCantidad, $totalVentas, $producto['id']);	
					//movimientos
                    $movimiento = 'Devolución Venta N°: ' . $idVenta;
                    $this->model->registrarMovimiento($movimiento, 'entrada', $producto['cantidad'], $nuevaCantidad, $producto['id'], $this->id_usuario);
					}else{
					 $movimiento = 'Devolución Venta N°: ' . $idVenta;
                    $this->model->registrarMovimiento($movimiento, 'entrada', 0 , 0, $producto['id'], $this->id_usuario);	
					}
                    
                    
                }
                if ($resultVenta['metodo'] == 'CREDITO' || $resultVenta['metodo'] == 'PLAZO') {
                    $this->model->anularCredito($idVenta);
                }
				if($resultVenta['metodo'] == 'PLAZO'){
				$this->model->anularPlan($idVenta);
					
				}
                $res = array('msg' => 'VENTA ANULADO', 'type' => 'success');
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
	
	public function verificarStockTraslado($idProducto)
    {
        $data = $this->model->getProductoTraslado($idProducto);
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
		$codPuntoVenta = strClean($_GET['codPuntoVentaMH']);
        $data = $this->model->getMaxCorrelativo($tipoDocumento,$codPuntoVenta);
       
            $resultado['correlativo'] = $data['correlativo'];
            array_push($array, $resultado);
        
        echo json_encode($array);
        die();
    }
	
}
