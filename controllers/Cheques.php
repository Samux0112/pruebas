<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

class Cheques extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        if (empty($_SESSION['permisos']) || (!in_array('contabilidad', $_SESSION['permisos']) && !in_array('auxiliar contable', $_SESSION['permisos']) && !in_array('administrador', $_SESSION['permisos']))) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
    }

    public function index()
    {
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $data['title'] = 'Emisión de Cheques';
        $data['script'] = 'cheques.js';
        $data['bancos'] = $this->model->getBancos();
        $data['proveedores'] = $this->model->getProveedores();
        $this->views->getView('cheques', 'index', $data);
    }

    public function listar()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        $data = $this->model->getCheques();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarProveedor()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        $term = strClean($_GET['term']);
        $data = $this->model->buscarProveedor($term);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        if (isset($_POST)) {
            $numero_cheque = strClean($_POST['numero_cheque']);
            $id_banco = intval($_POST['id_banco']);
            $proveedor = strClean($_POST['proveedor']);
            $concepto = strClean($_POST['concepto']);
            $monto = floatval($_POST['monto']);
            $fecha_emision = strClean($_POST['fecha_emision']);
            $detalle = json_decode($_POST['detalle'], true);

            if (empty($numero_cheque) || empty($id_banco)) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else if (empty($detalle) || count($detalle) == 0) {
                $res = array('msg' => 'AGREGUE EL DETALLE CONTABLE', 'type' => 'warning');
            } else {
                // Verificar si el proveedor existe, si no, crearlo
                $idProveedor = $this->model->buscarProveedorPorNombre($proveedor);
                if (!$idProveedor) {
                    // Crear nuevo proveedor
                    $idProveedor = $this->model->crearProveedorSimple($proveedor);
                    if (!$idProveedor) {
                        $res = array('msg' => 'ERROR AL CREAR PROVEEDOR', 'type' => 'error');
                        echo json_encode($res, JSON_UNESCAPED_UNICODE);
                        die();
                    }
                }
                
                $datosCheque = array(
                    $numero_cheque,
                    $id_banco,
                    $proveedor,
                    $concepto,
                    $monto,
                    $fecha_emision,
                    $_SESSION['id_usuario']
                );

                $idCheque = $this->model->registrarCheque($datosCheque);

                if ($idCheque > 0) {
                    foreach ($detalle as $row) {
                        $datosDetalle = array(
                            $idCheque,
                            $row['cuenta'],
                            $row['tipo'],
                            $row['monto'],
                            $row['concepto']
                        );
                        $this->model->registrarDetalleCheque($datosDetalle);
                    }
                    $this->model->actualizarCorrelativoBanco($id_banco);
                    
                    // Verificar si la tabla tiene el campo referencia
                    $sqlCheck = "SHOW COLUMNS FROM fondos_banco LIKE 'referencia'";
                    $hasReferencia = $this->model->select($sqlCheck);
                    
                    // Registrar movimiento de descuento en fondos_banco
                    if ($hasReferencia) {
                        $datosMovimientoCheque = array(
                            $id_banco,
                            $fecha_emision,
                            "CHEQUE No. $numero_cheque - $proveedor - $concepto",
                            $monto,
                            'cheque',
                            $numero_cheque,
                            $_SESSION['id_usuario']
                        );
                    } else {
                        $datosMovimientoCheque = array(
                            $id_banco,
                            $fecha_emision,
                            "CHEQUE No. $numero_cheque - $proveedor - $concepto",
                            $monto,
                            'cheque',
                            $_SESSION['id_usuario']
                        );
                    }
                    $this->model->registrarMovimientoCheque($datosMovimientoCheque);
                    
                    // Descontar del saldo del banco
                    $this->model->actualizarSaldoBanco($id_banco, -$monto);
                    
                    $res = array('msg' => 'CHEQUE REGISTRADO', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL REGISTRAR CHEQUE', 'type' => 'error');
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function anular()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        $id = strClean($_POST['id']);
        $motivo = strClean($_POST['motivo']);
        $anulado_por = $_SESSION['id_usuario'];

        if (empty($id) || empty($motivo)) {
            $res = array('msg' => 'EL MOTIVO ES REQUERIDO', 'type' => 'warning');
        } else {
            $data = $this->model->anularCheque($id, $motivo, $anulado_por);
            if ($data == 1) {
                $res = array('msg' => 'CHEQUE ANULADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ANULAR', 'type' => 'error');
            }
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reporte($idCheque)
    {
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        ob_start();
        $data['title'] = 'Imprimir Cheque';
        $data['empresa'] = $this->model->getEmpresa();
        $data['cheque'] = $this->model->getCheque($idCheque);
        $data['detalle'] = $this->model->getDetalleCheque($idCheque);
        $this->views->getView('cheques', 'imprimir', $data);
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('letter', 'portrait');

        $dompdf->render();

        $dompdf->stream('cheque_' . $data['cheque']['numero_cheque'] . '.pdf', array('Attachment' => false));
    }

    // Fondos de Banco
    public function listarFondos()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        $id_banco = isset($_GET['id_banco']) ? intval($_GET['id_banco']) : null;
        $data = $this->model->getFondosBanco($id_banco);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarFondo()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        if (isset($_POST)) {
            $id_banco = intval($_POST['id_banco']);
            $fecha = strClean($_POST['fecha']);
            $concepto = strClean($_POST['concepto']);
            $monto = floatval($_POST['monto']);

            if (empty($id_banco) || empty($fecha) || empty($concepto) || $monto <= 0) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else {
                $datosFondo = array(
                    $id_banco,
                    $fecha,
                    $concepto,
                    $monto,
                    $_SESSION['id_usuario']
                );

                $idFondo = $this->model->registrarFondo($datosFondo);

                if ($idFondo > 0) {
                    $this->model->actualizarSaldoBanco($id_banco, $monto);
                    $res = array('msg' => 'FONDO REGISTRADO', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL REGISTRAR FONDO', 'type' => 'error');
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSaldosBancos()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        $data = $this->model->getSaldosBancos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
}
