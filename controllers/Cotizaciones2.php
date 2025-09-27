<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
class Cotizaciones2 extends Controller{
    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if (!verificar('formulario medico')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
    }
    public function index()
    {
        $data['title'] = 'Cotizaciones2';
        $data['script'] = 'cotizaciones2.js';
        $data['busqueda'] = 'busqueda.js';
        $data['carrito'] = 'posCotizaciones2';
        $this->views->getView('cotizaciones2', 'index', $data);
    }
    public function registrarCotizacion2()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $total = 0;
        if (!empty($datos['productos'])) {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $metodo = $datos['metodo'];
            $numfac = $datos['numfac'];
            $numorden = $datos['numorden'];
            $sucursal = $datos['sucursal'];
            $opticli = $datos['opticli'];
            $fecha1 = $datos['fecha1'];
            $fechaentre = $datos['fechaentre'];
            $validez = $datos['validez'];
            $descuento = (!empty($datos['descuento'])) ? $datos['descuento'] : 0;
            $idCliente = $datos['idCliente'];
            if (empty($idCliente)) {
                $res = array('msg' => 'EL CLIENTE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($metodo)) {
                $res = array('msg' => 'EL METODO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($numfac)) {
                $res = array('msg' => 'NUMFAC ES REQUERIDO', 'type' => 'warning');
            } else if (empty($numorden)) {
                $res = array('msg' => 'NUMorden ES REQUERIDO', 'type' => 'warning');
            } else if (empty($sucursal)) {
                $res = array('msg' => 'Sucursal ES REQUERIDO', 'type' => 'warning');
            } else if (empty($opticli)) {
                $res = array('msg' => 'OPTICLI ES REQUERIDO', 'type' => 'warning');
            } else if (empty($fecha1)) {
                $res = array('msg' => 'FECHA1 ES REQUERIDO', 'type' => 'warning');
            } else if (empty($fechaentre)) {
                $res = array('msg' => 'FECHAENTRE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($validez)) {
                $res = array('msg' => 'LA VALIDEZ ES REQUERIDO', 'type' => 'warning');
            } else {
                foreach ($datos['productos'] as $producto) {
                    $result = $this->model->getProducto($producto['id']);
                    $data['id'] = $result['id'];
                    $data['nombre'] = $result['descripcion'];
                    $data['precio'] = $result['precio_venta'];
                    $data['cantidad'] = $producto['cantidad'];
                    $subTotal = $result['precio_venta'] * $producto['cantidad'];
                    array_push($array['productos'], $data);
                    $total += $subTotal;
                }
                $datosProductos = json_encode($array['productos']);
                $cotizacion2 = $this->model->registrarCotizacion2($datosProductos, $total, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $numfac, $numorden, $sucursal, $opticli, $fecha1, $fechaentre);
                if ($cotizacion2 > 0) {
                    $res = array('msg' => 'COTIZACIÓN GENERADA', 'type' => 'success', 'idCotizacion2' => $cotizacion2);
                } else {
                    $res = array('msg' => 'ERROR AL GENERAR LA COTIZACIÓN', 'type' => 'error');
                }
            }
        } else {
            $res = array('msg' => 'CARRITO VACIO', 'type' => 'warning');
        }
        echo json_encode($res);
        die();
    }

    public function reporte($datos)
    {
        ob_start();
        $array = explode(',', $datos);
        $tipo = $array[0];
        $idCotizacion2 = $array[1];

        $data['title'] = 'Reporte';
        $data['empresa'] = $this->model->getEmpresa();
        $data['cotizacion2'] = $this->model->getCotizacion2($idCotizacion2);
        if (empty($data['cotizacion2'])) {
            echo 'Pagina no Encontrada';
            exit;
        }
        $this->views->getView('cotizaciones2', $tipo, $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        if ($tipo == 'ticked') {
            $dompdf->setPaper(array(0, 0, 130, 841), 'portrait');
        } else {
            $dompdf->setPaper('A4', 'horizontal');
        }

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }

    public function listar()
    {
        $data = $this->model->getCotizaciones2();
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['acciones'] = '<a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a>';   
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>