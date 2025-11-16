<?php
class OrdenesCompra extends Controller{
            public function crear() {
                $json = file_get_contents('php://input');
                $datos = json_decode($json, true);
                $productos = $datos['productos'];
                $proveedor = $datos['proveedor'];
                $cotizacion = $datos['cotizacion'];
                $id_usuario = $this->id_usuario;
                $array['productos'] = array();
                $total = 0;
                if (!empty($productos)) {
                    foreach ($productos as $idProd) {
                        $result = $this->model->getProducto($idProd);
                        $data['id'] = $result['id'];
                        $data['nombre'] = $result['descripcion'];
                        $data['precio'] = $result['precio'];
                        $data['cantidad'] = $result['cantidad'];
                        $subTotal = $result['precio'] * $result['cantidad'];
                        array_push($array['productos'], $data);
                        $total += $subTotal;
                    }
                    $datosProductos = json_encode($array['productos']);
                    $orden = $this->model->registrarOrden($datosProductos, $total, date('Y-m-d'), date('H:i:s'), $id_usuario, $proveedor, $cotizacion, null);
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
            // Aquí se genera el PDF usando Dompdf
            require_once 'vendor/autoload.php';
            $dompdf = new Dompdf\Dompdf();
            $data = $this->model->getOrden($idOrden);
            $productos = json_decode($data['productos'], true);
            ob_start();
            include 'views/ordenesCompra/reporte.php';
            $html = ob_get_clean();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('orden_compra_' . $idOrden . '.pdf', ['Attachment' => false]);
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
}
?>