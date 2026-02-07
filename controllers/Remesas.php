<?php
/**
 * Controlador para Remesas y Transferencias
 */
class Remesas extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        // Verificar permisos para remesas
        if (empty($_SESSION['permisos']) || 
            (!in_array('contabilidad', $_SESSION['permisos']) && 
             !in_array('auxiliar contable', $_SESSION['permisos']) && 
             !in_array('auxiliar', $_SESSION['permisos']) && 
             !in_array('administrador', $_SESSION['permisos']))) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
    }

    /**
     * Carga la vista principal de remesas
     */
    public function index()
    {
        $data['title'] = 'Remesas y Transferencias';
        $data['script'] = 'remesas.js';
        $data['bancos'] = $this->model->getBancos();
        $this->views->getView('remesas', 'index', $data);
    }

    /**
     * Obtiene lista de remesas para DataTable
     */
    public function listar()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        $data = $this->model->getRemesas();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /**
     * Obtiene una remesa por ID
     */
    public function ver($id)
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        $remesa = $this->model->getRemesa($id);
        if ($remesa) {
            $detalle = $this->model->getDetalleRemesa($id);
            $empresa = $this->model->getEmpresa();
            echo json_encode([
                'remesa' => $remesa,
                'detalle' => $detalle,
                'empresa' => $empresa
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'Remesa no encontrada']);
        }
        die();
    }

    /**
     * Obtiene datos para nuevo registro
     */
    public function nuevo()
    {
        if (empty($_SESSION['id_usuario'])) {
            echo json_encode(['error' => 'SESION EXPIRADA']);
            die();
        }
        $data = [
            'bancos' => $this->model->getBancos(),
            'correlativo' => $this->model->getCorrelativo()
        ];
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /**
     * Registra una nueva remesa
     */
    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_SESSION['id_usuario'])) {
                $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                die();
            }

            $idBanco = $_POST['id_banco'] ?? '';
            $tipoTransaccion = $_POST['tipo_transaccion'] ?? 'remesa';
            $concepto = $_POST['concepto'] ?? '';
            $tipoPartidaRemesa = $_POST['tipo_partida_remesa'] ?? '';
            $monto = floatval($_POST['monto'] ?? 0);
            $detalle = json_decode($_POST['detalle'] ?? '[]', true);

            if (empty($idBanco) || empty($detalle)) {
                $res = array('msg' => 'FALTAN DATOS REQUERIDOS', 'type' => 'error');
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                die();
            }

            // Insertar remesa
            $datosRemesa = array(
                $idBanco,
                $tipoTransaccion,
                $concepto,
                $tipoPartidaRemesa,
                $monto,
                $_SESSION['id_usuario']
            );

            $idRemesa = $this->model->registrarRemesa($datosRemesa);

            if ($idRemesa > 0) {
                // Insertar detalle
                foreach ($detalle as $item) {
                    $datosDetalle = array(
                        $idRemesa,
                        $item['cuenta'],
                        $item['tipo'],
                        floatval($item['monto']),
                        $item['concepto'] ?? ''
                    );
                    $this->model->registrarDetalleRemesa($datosDetalle);
                }

                $res = array('msg' => 'REMSA REGISTRADA', 'type' => 'success', 'id' => $idRemesa);
            } else {
                $res = array('msg' => 'ERROR AL REGISTRAR REMESA', 'type' => 'error');
            }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /**
     * Anula una remesa
     */
    public function anular()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_SESSION['id_usuario'])) {
                $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                die();
            }

            $id = $_POST['id'] ?? '';
            $motivo = $_POST['motivo'] ?? '';

            if (empty($id) || empty($motivo)) {
                $res = array('msg' => 'FALTAN DATOS', 'type' => 'error');
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                die();
            }

            $result = $this->model->anularRemesa($id, $motivo, $_SESSION['id_usuario']);
            if ($result) {
                $res = array('msg' => 'REMSA ANULADA', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ANULAR', 'type' => 'error');
            }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /**
     * Genera PDF de remesa
     */
    public function pdf($id)
    {
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $remesa = $this->model->getRemesa($id);
        if (!$remesa) {
            echo "Remesa no encontrada";
            exit;
        }

        $detalle = $this->model->getDetalleRemesa($id);
        $empresa = $this->model->getEmpresa();

        $this->views->getView('remesas', 'imprimir', [
            'remesa' => $remesa,
            'detalle' => $detalle,
            'empresa' => $empresa
        ]);
    }

    /**
     * Obtiene cuentas contables para autocomplete
     */
    public function cuentas()
    {
        if (empty($_SESSION['id_usuario'])) {
            echo json_encode([]);
            die();
        }
        $search = $_GET['search'] ?? '';
        $data = $this->model->getCuentasContables($search);
        echo json_encode($data);
        die();
    }
}
