<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
class Creditos2 extends Controller
{
    private $id_usuario;
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if (!verificar('cuentas por pagar')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        $data['script'] = 'creditos2.js';
        $data['title'] = 'Administrar Creditos2';
        $this->views->getView('creditos2', 'index', $data);
    }
    public function listar()
    {
        $data = $this->model->getCreditos2();
        for ($i = 0; $i < count($data); $i++) {
            $credito2 = $this->model->getCredito2($data[$i]['id']);
            $result = $this->model->getAbono2($data[$i]['id']);
            $abonado2 = ($result['total'] == null) ? 0 : $result['total'];
            $restante = $data[$i]['monto'] - $abonado2;
            if ($restante < 0.01 && $credito2['estado'] = 1) {
                $this->model->actualizarCredito2(0, $data[$i]['id']);
            }
            $data[$i]['monto'] = number_format($data[$i]['monto'], 2);
            $data[$i]['abonado2'] = number_format($abonado2, 2, '.', '');
            $data[$i]['restante'] = number_format($restante, 2, '.', '');
            $data[$i]['venta2'] = 'N: ' . $data[$i]['id_venta2'];
            $data[$i]['acciones'] = '<a class="btn btn-danger" href="'.BASE_URL.'creditos2/reporte/'.$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>';
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-warning">PENDIENTE</span>';
            } else if($data[$i]['estado'] == 2){
                $data[$i]['estado'] = '<span class="badge bg-danger">ANULADO</span>';
            }else{
                $data[$i]['estado'] = '<span class="badge bg-success">COMPLETADO</span>';
            }
            
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
            $resultAbono2 = $this->model->getAbono2($row['id']);
            $abonado2 = ($resultAbono2['total'] == null) ? 0 : $resultAbono2['total'];
            //calcular restante  (monto - abono2)
            $restante = $row['monto'] - $abonado2;
            $result['monto'] = $row['monto'];
            $result['abonado2'] = number_format($abonado2, 2, '.', '');
            $result['restante'] = number_format($restante, 2, '.', '');
            $result['fecha'] = $row['fecha'];
            $result['id'] = $row['id'];
            $result['label'] = $row['nombre'];
            $result['telefono'] = $row['telefono'];
            $result['direccion'] = $row['direccion'];
            $result['id_venta2'] = $row['id_venta2'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarAbono2()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        if (!empty($datos)) {
            $idCredito2 = strClean($datos['idCredito2']);
            $monto = strClean($datos['monto_abonar2']);
            $data = $this->model->registrarAbono2($monto, $idCredito2, $this->id_usuario);
            if ($data > 0) {
                $res = array('msg' => 'ABONO2 REGISTRADO', 'type' => 'success');
            }else{
            $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
            }
        }else{
            $res = array('msg' => 'TODO LOS CAMPOS SON REQUERIDO', 'type' => 'warning');
        }
        echo json_encode($res);
        die();
    }

    public function reporte($idCredito2)
    {
        ob_start();
        $data['title'] = 'Reporte';
        $data['empresa'] = $this->model->getEmpresa();
        $data['credito2'] = $this->model->getCredito2($idCredito2);
        $data['abonos2'] = $this->model->getAbonos2($idCredito2);
        if (empty($data['credito2'])) {
            echo 'Pagina no Encontrada';
            exit;
        }
        $this->views->getView('creditos2', 'reporte', $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }

    public function listarAbonos2()
    {
        $data = $this->model->getHistorialAbonos2();
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['credito2'] = 'N째: ' . $data[$i]['id_credito2'];
        }
        echo json_encode($data);
        die();
    }
}
