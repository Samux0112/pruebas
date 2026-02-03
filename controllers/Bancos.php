<?php
class Bancos extends Controller {
    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        if (empty($_SESSION['permisos']) || !in_array('contabilidad', $_SESSION['permisos'])) {
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
        $data['proveedores'] = $this->model->getProveedores();
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

            if (empty($nombre) || empty($numero_cuenta) || empty($cuenta_contable)) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $result = $this->model->insertarBanco($nombre, $numero_cuenta, $cuenta_contable, $cuenta_pos);
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

            if (empty($nombre) || empty($numero_cuenta) || empty($cuenta_contable) || $id == 0) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $result = $this->model->actualizarBanco($id, $nombre, $numero_cuenta, $cuenta_contable, $cuenta_pos);
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
    
    // Cuentas Bancarias
    public function listarCuentasBancarias()
    {
        $data = $this->model->getAllCuentasBancarias();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function getCuentaBancaria($id)
    {
        $data = $this->model->getCuentaBancariaById($id);
        echo json_encode($data);
        die();
    }
    
    public function registrarCuentaBancaria()
    {
        if (isset($_POST)) {
            $banco_id = intval($_POST['banco_id']);
            $numero_cuenta = strClean($_POST['numero_cuenta']);
            $cuenta_contable = strClean($_POST['cuenta_contable']);
            $propietario_id = intval($_POST['propietario_id']);
            $tipo_cuenta = $_POST['tipo_cuenta'];
            
            if (empty($banco_id) || empty($numero_cuenta) || empty($cuenta_contable) || 
                empty($propietario_id) || empty($tipo_cuenta)) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $datos = array(
                    'banco_id' => $banco_id,
                    'numero_cuenta' => $numero_cuenta,
                    'cuenta_contable' => $cuenta_contable,
                    'propietario_id' => $propietario_id,
                    'tipo_cuenta' => $tipo_cuenta
                );
                
                $result = $this->model->insertarCuentaBancaria($datos);
                if ($result > 0) {
                    $res = array('msg' => 'CUENTA BANCARIA REGISTRADA', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                }
            }
            echo json_encode($res);
            die();
        }
    }
    
    public function modificarCuentaBancaria()
    {
        if (isset($_POST)) {
            $id = intval($_POST['id']);
            $banco_id = intval($_POST['banco_id']);
            $numero_cuenta = strClean($_POST['numero_cuenta']);
            $cuenta_contable = strClean($_POST['cuenta_contable']);
            $propietario_id = intval($_POST['propietario_id']);
            $tipo_cuenta = $_POST['tipo_cuenta'];
            
            if (empty($banco_id) || empty($numero_cuenta) || empty($cuenta_contable) || 
                empty($propietario_id) || empty($tipo_cuenta) || $id == 0) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $datos = array(
                    'banco_id' => $banco_id,
                    'numero_cuenta' => $numero_cuenta,
                    'cuenta_contable' => $cuenta_contable,
                    'propietario_id' => $propietario_id,
                    'tipo_cuenta' => $tipo_cuenta
                );
                
                $result = $this->model->actualizarCuentaBancaria($id, $datos);
                if ($result > 0) {
                    $res = array('msg' => 'CUENTA BANCARIA MODIFICADA', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                }
            }
            echo json_encode($res);
            die();
        }
    }
    
    public function eliminarCuentaBancaria($id)
    {
        if (isset($id)) {
            $result = $this->model->eliminarCuentaBancaria($id);
            if ($result == 1) {
                $res = array('msg' => 'CUENTA BANCARIA ELIMINADA', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
            }
            echo json_encode($res);
            die();
        }
    }
}
