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
        if (empty($_SESSION['permisos']) || !in_array('contabilidad', $_SESSION['permisos'])) {
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
        // Temporal: verificar que hay sesión activa
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

    public function getCorrelativo($idBanco)
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        $data = $this->model->getSiguienteCorrelativo($idBanco);
        echo json_encode($data);
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
            $id_banco = strClean($_POST['id_banco']);
            $id_proveedor = strClean($_POST['id_proveedor']);
            $concepto = strClean($_POST['concepto']);
            $monto = strClean($_POST['monto']);
            $fecha_emision = strClean($_POST['fecha_emision']);
            $detalle = json_decode($_POST['detalle'], true);

            // Si numero_cheque está vacío, generarlo automáticamente
            if (empty($numero_cheque) && !empty($id_banco)) {
                $correlativo = $this->model->getSiguienteCorrelativo($id_banco);
                if (!empty($correlativo) && isset($correlativo['correlativo'])) {
                    $numero_cheque = $correlativo['prefijo'] . str_pad($correlativo['correlativo'], $correlativo['longitud'], '0', STR_PAD_LEFT);
                } else {
                    // Fallback: generar correlativo simple
                    $numero_cheque = $id_banco . date('YmdHis');
                }
            }

            if (empty($numero_cheque) || empty($id_banco) || $id_proveedor === '' || $id_proveedor === null) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
            } else if (empty($detalle) || count($detalle) == 0) {
                $res = array('msg' => 'AGREGUE EL DETALLE CONTABLE', 'type' => 'warning');
            } else {
                $datosCheque = array(
                    $numero_cheque,
                    $id_banco,
                    $id_proveedor,
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
                    $this->model->actualizarCorrelativo($id_banco);
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

    public function getCuentasBancarias()
    {
        if (empty($_SESSION['id_usuario'])) {
            $res = array('msg' => 'SESION EXPIRADA', 'type' => 'warning');
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
        
        $proveedor_id = intval($_GET['proveedor_id']);
        
        $data = $this->model->getCuentasBancariasProveedor($proveedor_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
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

        $dompdf->setPaper('letter', 'landscape');

        $dompdf->render();

        $dompdf->stream('cheque_' . $data['cheque']['numero_cheque'] . '.pdf', array('Attachment' => false));
    }
}
