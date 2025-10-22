<?php
class Requisiciones extends Controller{
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