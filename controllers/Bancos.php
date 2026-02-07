<?php
class Bancos extends Controller {
    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        if (empty($_SESSION['permisos']) || (!in_array('contabilidad', $_SESSION['permisos']) && !in_array('contador', $_SESSION['permisos']) && !in_array('auxiliar contable', $_SESSION['permisos']) && !in_array('administrador', $_SESSION['permisos']))) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
    }

    public function index()
    {
        $data['title'] = 'Bancos';
        $data['partidas'] = $this->model->getPartidasContables();
        $data['cuentas'] = $this->model->getCuentasContables();
        $data['bancos'] = $this->model->getAllBancos();
        $this->views->getView('bancos', 'index', $data);
    }

    // Tipos de Transacción
    public function listarTransaccion()
    {
        $data = $this->model->getAllTransaccion();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarTransaccion()
    {
        if (isset($_POST)) {
            $nombre = strClean($_POST['nombre']);
            $tipo_partida = $_POST['tipo_partida'];
            $tipo_transaccion = $_POST['tipo_transaccion'];

            if (empty($nombre) || empty($tipo_partida) || empty($tipo_transaccion)) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $result = $this->model->insertarTransaccion($nombre, $tipo_partida, $tipo_transaccion);
                if ($result > 0) {
                    $res = array('msg' => 'TIPO DE TRANSACCIÓN REGISTRADO', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                }
            }
            echo json_encode($res);
            die();
        }
    }

    public function modificarTransaccion()
    {
        if (isset($_POST)) {
            $id = intval($_POST['id']);
            $nombre = strClean($_POST['nombre']);
            $tipo_partida = $_POST['tipo_partida'];
            $tipo_transaccion = $_POST['tipo_transaccion'];

            if (empty($nombre) || empty($tipo_partida) || empty($tipo_transaccion) || $id == 0) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $result = $this->model->actualizarTransaccion($id, $nombre, $tipo_partida, $tipo_transaccion);
                if ($result > 0) {
                    $res = array('msg' => 'TIPO DE TRANSACCIÓN MODIFICADO', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                }
            }
            echo json_encode($res);
            die();
        }
    }

    public function eliminarTransaccion($id)
    {
        if (isset($id)) {
            $result = $this->model->eliminarTransaccion($id);
            if ($result == 1) {
                $res = array('msg' => 'TIPO DE TRANSACCIÓN ELIMINADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
            }
            echo json_encode($res);
            die();
        }
    }

    public function getTransaccion($id)
    {
        $data = $this->model->getTransaccionById($id);
        echo json_encode($data);
        die();
    }

    // Bancos
    public function listarBancos()
    {
        $data = $this->model->getAllBancos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarBanco()
    {
        if (isset($_POST)) {
            $nombre = strClean($_POST['nombre']);
            $numero_cuenta = strClean($_POST['numero_cuenta']);
            $cuenta_contable = strClean($_POST['cuenta_contable']);
            $cuenta_pos = isset($_POST['cuenta_pos']) ? intval($_POST['cuenta_pos']) : 0;
            $correlativo_cheque = isset($_POST['correlativo_cheque']) ? intval($_POST['correlativo_cheque']) : 1;

            if (empty($nombre) || empty($numero_cuenta) || empty($cuenta_contable)) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $result = $this->model->insertarBanco($nombre, $numero_cuenta, $cuenta_contable, $cuenta_pos, $correlativo_cheque);
                if ($result > 0) {
                    $res = array('msg' => 'BANCO REGISTRADO', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                }
            }
            echo json_encode($res);
            die();
        }
    }

    public function modificarBanco()
    {
        if (isset($_POST)) {
            $id = intval($_POST['id']);
            $nombre = strClean($_POST['nombre']);
            $numero_cuenta = strClean($_POST['numero_cuenta']);
            $cuenta_contable = strClean($_POST['cuenta_contable']);
            $cuenta_pos = isset($_POST['cuenta_pos']) ? intval($_POST['cuenta_pos']) : 0;
            $correlativo_cheque = isset($_POST['correlativo_cheque']) ? intval($_POST['correlativo_cheque']) : 1;

            if (empty($nombre) || empty($numero_cuenta) || empty($cuenta_contable) || $id == 0) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $result = $this->model->actualizarBanco($id, $nombre, $numero_cuenta, $cuenta_contable, $cuenta_pos, $correlativo_cheque);
                if ($result > 0) {
                    $res = array('msg' => 'BANCO MODIFICADO', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                }
            }
            echo json_encode($res);
            die();
        }
    }

    public function eliminarBanco($id)
    {
        if (isset($id)) {
            $result = $this->model->eliminarBanco($id);
            if ($result == 1) {
                $res = array('msg' => 'BANCO ELIMINADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
            }
            echo json_encode($res);
            die();
        }
    }

    public function getBanco($id)
    {
        $data = $this->model->getBancoById($id);
        echo json_encode($data);
        die();
    }

    // Fondos de Chqueras
    public function fondos()
    {
        $data['title'] = 'Fondos de Chqueras';
        $data['bancos'] = $this->model->getAllBancos();
        $this->views->getView('bancos', 'fondos', $data);
    }
}
