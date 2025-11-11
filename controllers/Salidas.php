<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
class Salidas extends Controller{
	private $id_usuario;
    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if (!verificar('salidas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
		$this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        $data['title'] = 'Salidas';
        $data['script'] = 'salidas.js';
        $data['busqueda'] = 'busquedaBodega.js';
        $data['carrito'] = 'posVenta';
		$data['bodegas'] = $this->model->getDatos('bodegas');
        $this->views->getView('salidas', 'index', $data);
    }
    public function registrar()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $total = 0;
        if (!empty($datos['productos'])) {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $fecha_traslado = $datos['fecha_traslado'];
            $idCliente = $datos['bEntrada'];
			$bodega = $datos['bEntrada'];
			$bodega1 = $datos['bSalida'];
            if (empty($idCliente)) {
                $res = array('msg' => 'LA BODEGA ES REQUERIDA', 'type' => 'warning');
            } elseif(empty($fecha_traslado)){
			$res = array('msg' => 'LA FECHA ES REQUERIDA', 'type' => 'warning');	
			} else {
                // Obtener el nombre de la bodega de salida usando el modelo
                $nombreBodegaSalida = $this->model->getNombreBodega($bodega1);
                foreach ($datos['productos'] as $producto) {
                   // $result = $this->model->getProducto($producto['id'],$bodega1);
                    //$data['id'] = $result['id'];
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
				$tipoTraslado= "SALIDA";
                $traslado = $this->model->registrarTraslado($datosProductos, $fecha, $fecha_traslado,  $total, $idCliente , $bodega1, $tipoTraslado, $this->id_usuario);
                if ($traslado > 0) {
					foreach ($datos['productos'] as $producto) {
                        $result = $this->model->getProducto($producto['id'],$bodega);
                        if($nombreBodegaSalida['nombre']!='BODEGA VIRTUAL'){
                            $result1 = $this->model->getProducto($producto['id'],$bodega1);
                        }
						
                        //actualizar stock
                        $dataValidar = $this->model->getProductoBodega($producto['id'],$bodega);
							if($dataValidar['total']>0){
								$nuevaCantidad = $result['stock'] + $producto['cantidad'];
								$totalVentas2 = $result['ventas2'] + $producto['cantidad'];
                                 if($nombreBodegaSalida['nombre']!='BODEGA VIRTUAL'){
                                $nuevaCantida1 = $result1['stock'] - $producto['cantidad'];
								$totalVentas1 = $result1['ventas2'] - $producto['cantidad'];
                                 }
								
							}else{
								$nuevaCantidad = 0 + $producto['cantidad'];
								$totalVentas2 = 0 + $producto['cantidad'];
                                if($nombreBodegaSalida['nombre']!='BODEGA VIRTUAL'){
                                    $nuevaCantida1 = $result1['stock'] - $producto['cantidad'];
								$totalVentas1 = $result1['ventas2'] - $producto['cantidad'];

                                }
								
							}
                      $dataValidar = $this->model->getProductoBodega($producto['id'],$bodega);
							if($dataValidar['total']>0){
                            $this->model->actualizarStock($nuevaCantidad, $producto['id'], $bodega);
                            if($nombreBodegaSalida['nombre']!='BODEGA VIRTUAL'){
                                $this->model->actualizarStock($nuevaCantida1, $producto['id'], $bodega1);
                            }
							
							}else{
							$this->model->registrarStock($nuevaCantidad, $producto['id'], $bodega);	
							 if($nombreBodegaSalida['nombre']!='BODEGA VIRTUAL'){
                                $this->model->actualizarStock($nuevaCantida1, $producto['id'], $bodega1);
                            }
							}                       
                        //movimientos
                        $movimiento = 'Apartado N°: ';
                        $this->model->registrarMovimiento($movimiento, 'Salida por traslado', $producto['cantidad'], $nuevaCantidad, $producto['id'], $this->id_usuario);
                    }
                    $res = array('msg' => 'TRASLADO GENERADO', 'type' => 'success', 'idCotizacion' => $traslado);
                } else {
                    $res = array('msg' => 'ERROR AL GENERAR TRASLADO', 'type' => 'error');
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
        $idCotizacion = $array[1];

        $data['title'] = 'Reporte';
        $data['empresa'] = $this->model->getEmpresa();
        $data['cotizacion'] = $this->model->getApartado($idCotizacion);
        if (empty($data['cotizacion'])) {
            echo 'Pagina no Encontrada';
            exit;
        }
        $this->views->getView('salidas', $tipo, $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        if ($tipo == 'ticked') {
            $dompdf->setPaper(array(0, 0, 130, 841), 'portrait');
        } else {
            $dompdf->setPaper('A4', 'vertical');
        }

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }

    public function listar()
    {
        $data = $this->model->getCotizaciones();
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['acciones'] = '<a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>';   
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
	public function buscar()
    {
        $array = array();
        $valor = strClean($_GET['term']);
        $data = $this->model->buscarPorNombre($valor);
        foreach ($data as $row) {
            $result['id'] = $row['id'];
            $result['label'] = $row['nombre'];
            $result['telefono'] = $row['telefono'];
            $result['direccion'] = $row['direccion'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>