<?php
class Requisiciones extends Controller{
    public function comparativoCotizaciones($idRequisicion) {
        $cotizaciones = $this->model->getCotizacionesRequisicion($idRequisicion);
        // Construir comparativo de productos
        $productosComparados = [];
        foreach ($cotizaciones as $cot) {
            $productos = $this->model->getProductosCotizacion($cot['id']);
            foreach ($productos as $prod) {
                $nombre = $prod['nombre'];
                if (!isset($productosComparados[$nombre])) {
                    $productosComparados[$nombre] = [];
                }
                $productosComparados[$nombre][$cot['id']] = [
                    'cantidad' => $prod['cantidad'],
                    'precio' => $prod['precio'],
                    'descuento' => $prod['descuento'],
                    'subtotal' => $prod['subtotal']
                ];
            }
        }
        $data = [
            'cotizaciones' => $cotizaciones,
            'productosComparados' => $productosComparados
        ];
        $this->views->getView('requisiciones', 'comparativoCotizaciones', $data);
    }
    public function guardarCotizacion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $idRequisicion = isset($data['requisicion_id']) ? intval($data['requisicion_id']) : 0;
            $proveedor = isset($data['proveedor']) ? trim($data['proveedor']) : '';
            $monto = isset($data['monto']) ? floatval($data['monto']) : 0;
            $detalle = isset($data['detalle']) ? trim($data['detalle']) : '';
            $productos = isset($data['productos']) && is_array($data['productos']) ? $data['productos'] : [];
            $res = $this->model->guardarCotizacion($idRequisicion, $proveedor, $monto, $detalle, $productos);
            if ($res) {
                $msg = 'Cotización guardada correctamente.';
                echo json_encode(['success' => true, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
            } else {
                $msg = 'Error al guardar la cotización.';
                echo json_encode(['success' => false, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }
    }
    public function cotizacion($id) {
        $requisicion = $this->model->getRequisicion($id);
        $productos = json_decode($requisicion['productos'], true);
    $proveedores = $this->model->getProveedores(1); // estado=1 activos
        $data = [
            'id' => $id,
            'productos' => $productos,
            'requisicion' => $requisicion,
            'proveedores' => $proveedores,
            'script' => 'cotizacionRequisicion.js'
        ];
        $this->views->getView('requisiciones', 'cotizacion', $data);
    }
    public function detalle($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['estado'])) {
            $estado = $_POST['estado'];
            if (in_array($estado, ['Pendiente','Aprobada','Rechazada'])) {
                $ok = $this->model->actualizarEstado($id, $estado);
                $msg = $ok ? 'Estado actualizado correctamente.' : 'Error al actualizar estado.';
            } else {
                $msg = 'Estado inválido.';
            }
        } else {
            $msg = '';
        }
        $requisicion = $this->model->getRequisicion($id);
        $productos = json_decode($requisicion['productos'], true);
        $cotizaciones = $this->model->getCotizacionesRequisicion($id);
        $data = [
            'requisicion' => $requisicion,
            'productos' => $productos,
            'cotizaciones' => $cotizaciones,
            'script' => 'detalleRequisicion.js',
            'msg' => $msg
        ];
        $this->views->getView('requisiciones', 'detalle', $data);
    }
    public function verCotizacion($idCotizacion) {
        $cotizacion = $this->model->getCotizacionById($idCotizacion);
        $productos = $this->model->getProductosCotizacion($idCotizacion);
        $data = [
            'cotizacion' => $cotizacion,
            'productos' => $productos,
            'script' => 'detalleCotizacionRequisicion.js'
        ];
        $this->views->getView('requisiciones', 'detalleCotizacion', $data);
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
        $data['title'] = 'Requisiciones';
        $data['script'] = 'requisiciones.js';
        $data['busqueda'] = 'busqueda.js';
    $data['carrito'] = 'posCotizaciones';
        $this->views->getView('requisiciones', 'index', $data);
    }

    public function registrarRequisicion(){
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $total = 0;
        if (!empty($datos['productos'])) {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $observaciones = isset($datos['observaciones']) ? strClean($datos['observaciones']) : null;

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
            $req = $this->model->registrarRequisicion($datosProductos, $total, $fecha, $hora, $this->id_usuario, $observaciones);
            if ($req > 0) {
                $res = array('msg' => 'REQUISICIÓN CREADA', 'type' => 'success', 'idRequisicion' => $req);
            } else {
                $res = array('msg' => 'ERROR AL CREAR REQUISICIÓN', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'CARRITO VACIO', 'type' => 'warning');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listar(){
        $data = $this->model->getRequisiciones();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function ver($id){
        $data = $this->model->getRequisicion($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function actualizarEstado(){
        // espera POST {id, estado}
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $estado = isset($_POST['estado']) ? strClean($_POST['estado']) : '';
        if ($id > 0 && in_array($estado, ['pendiente','aprobada','rechazada'])) {
            $r = $this->model->actualizarEstado($id, $estado);
            if ($r) {
                $res = array('msg' => 'ESTADO ACTUALIZADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ACTUALIZAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'DATOS INVALIDOS', 'type' => 'warning');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>