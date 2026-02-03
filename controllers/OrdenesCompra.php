<?php
require 'vendor/autoload.php';
class OrdenesCompra extends Controller{
            public function crear() {
                $json = file_get_contents('php://input');
                $datos = json_decode($json, true);
                $productos = $datos['productos'];
                $proveedor = $datos['proveedor'];
                $proveedor_id = isset($datos['proveedor_id']) ? $datos['proveedor_id'] : null;
                $cotizacion = $datos['cotizacion'];
                $requisicion_id = $datos['requisicion_id'];
                $id_usuario = $this->id_usuario;
                $array['productos'] = array();
                $total = 0;
                if (!empty($productos)) {
                    foreach ($productos as $prod) {
                        $data['id'] = $prod['id'];
                        $data['nombre'] = $prod['nombre'];
                        $data['cantidad'] = $prod['cantidad'];
                        $data['descripcion'] = $prod['descripcion'];
                        $data['precio'] = $prod['precio'];
                        $data['descuento'] = $prod['descuento'];
                        $data['subtotal'] = $prod['subtotal'];
                        $subTotal = floatval($prod['subtotal']);
                        array_push($array['productos'], $data);
                        $total += $subTotal;
                    }
                    $iva = $total * 0.13;
                    $totalConIva = $total + $iva;
                    $datosProductos = json_encode($array['productos']);
                    $orden = $this->model->registrarOrden($datosProductos, $totalConIva, date('Y-m-d'), date('H:i:s'), $id_usuario, $proveedor_id, $proveedor, $cotizacion, null, $requisicion_id);
                    if ($orden > 0) {
                        echo json_encode(['success' => true, 'idOrden' => $orden]);
                    } else {
                        echo json_encode(['success' => false]);
                    }
                } else {
                    echo json_encode(['success' => false]);
                }
                die();
            }
        public function generarPDF($idOrden) {
        // Generar PDF con formato profesional
        ob_start();
        $data['title'] = 'Orden de Compra';
        $data['empresa'] = $this->model->getEmpresa(); // Debes tener este método en el modelo
        $orden = $this->model->getOrden($idOrden);
        $data = array_merge($data, $orden);
        $this->views->getView('ordenesCompra', 'reporte', $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf\Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'vertical');
        $dompdf->render();
        $dompdf->stream('orden_compra_' . $idOrden . '.pdf', array('Attachment' => false));
        exit;
        }
    private $id_usuario;
    public function __construct(){
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }

    public function index(){
        $data['title'] = 'Órdenes de Compra';
        $data['script'] = 'ordenesCompra.js';
        $data['busqueda'] = 'busqueda.js';
        $data['carrito'] = 'posOrdenCompra';
        $this->views->getView('ordenesCompra', 'index', $data);
    }

        public function completar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';
            // Aquí puedes actualizar el estado de la orden y guardar las observaciones
            // Ejemplo: $this->model->completarOrden($id, $observaciones);
            // Redirigir al listado tras completar
            header('Location: ' . BASE_URL . 'ordenesCompra/listado');
            exit;
        }
        $orden = $this->model->getOrden($id);
        $productos = json_decode($orden['productos'], true);
        $data = [
            'orden' => $orden,
            'productos' => $productos,
            'title' => 'Completar Compra'
        ];
        $this->views->getView('ordenesCompra', 'completar', $data);
    }

    public function registrarOrden(){
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $total = 0;
        if (!empty($datos['productos'])) {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $proveedor = isset($datos['idProveedor']) && !empty($datos['idProveedor']) ? $datos['idProveedor'] : null;
            $requisicion_id = isset($datos['requisicion_id']) && !empty($datos['requisicion_id']) ? intval($datos['requisicion_id']) : null;
            $observaciones = isset($datos['observaciones']) ? strClean($datos['observaciones']) : null;
            // proveedor y requisicion_id son opcionales
            {
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
                $orden = $this->model->registrarOrden($datosProductos, $total, $fecha, $hora, $this->id_usuario, $proveedor, $requisicion_id, $observaciones);
                if ($orden > 0) {
                    $res = array('msg' => 'ORDEN REGISTRADA', 'type' => 'success', 'idOrden' => $orden);
                } else {
                    $res = array('msg' => 'ERROR AL CREAR ORDEN', 'type' => 'error');
                }
            }
        } else {
            $res = array('msg' => 'CARRITO VACIO', 'type' => 'warning');
        }
        echo json_encode($res);
        die();
    }

    public function listado(){
        $data['title'] = 'Listado de Órdenes de Compra';
        $data['script'] = 'listadoOrdenesCompra.js';
       $data['ordenes'] = $this->model->getOrdenes();
        $this->views->getView('ordenesCompra', 'listadoOrdenes', $data);
    }

    public function listar(){
        $data = $this->model->getOrdenes();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div><a class="btn btn-danger" href="#" onclick="verReporteOrden(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a></div>';
        }
        echo json_encode($data);
        die();
    }

    public function editar($id){
        $data = $this->model->getOrden($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function detalle($id) {
         $data['title'] = 'Listado de Órdenes de Compra';
        $data['script'] = 'detalleOrdenCompra.js';
        $orden = $this->model->getOrden($id);
         $data['orden'] = $orden;
        
        
        $this->views->getView('ordenesCompra', 'detalle', $data);
    }
    public function autorizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
            if ($id > 0 && in_array($estado, ['aprobado', 'rechazado'])) {
                $ok = $this->model->actualizarEstadoOrden($id, $estado,$this->id_usuario);
                if ($ok) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
            } else {
                echo json_encode(['success' => false]);
            }
            die();
        }
    }
}