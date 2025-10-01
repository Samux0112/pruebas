<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
class Cotizaciones extends Controller{
    public function verComentarios($id_cotizacion)
    {
        if (!verificar('cotizaciones')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Comentarios de Cotización';
        $data['id_cotizacion'] = $id_cotizacion;
        $this->views->getView('cotizaciones', 'comentarios', $data);
    }

    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        if (!verificar('cotizaciones')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
    }
        public function listarComentarios()
        {
            if (!verificar('cotizaciones')) {
                header('Location: ' . BASE_URL . 'admin/permisos');
                exit;
            }
            $id_cotizacion = isset($_REQUEST['id_cotizacion']) ? intval($_REQUEST['id_cotizacion']) : 0;
            $data = [];
            if ($id_cotizacion > 0) {
                $data = $this->model->listarComentarios($id_cotizacion);
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    public function registrarComentario()
    {
        if (!verificar('cotizaciones')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $comentario = isset($_POST['comentario']) ? strClean($_POST['comentario']) : '';
        $fecha = isset($_POST['fecha']) ? strClean($_POST['fecha']) : date('Y-m-d');
        $id_cotizacion = isset($_POST['id_cotizacion']) ? intval($_POST['id_cotizacion']) : 0;
        if (empty($comentario) || $id_cotizacion == 0) {
            $res = array('msg' => 'El comentario y la cotización son requeridos', 'type' => 'warning');
        } else {
            $data = $this->model->registrarComentario($comentario, $fecha, $id_cotizacion);
            if ($data > 0) {
                $res = array('msg' => 'Comentario registrado', 'type' => 'success');
            } else {
                $res = array('msg' => 'Error al registrar', 'type' => 'error');
            }
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function index()
    {
        $data['title'] = 'Cotizaciones';
        $data['script'] = 'cotizaciones.js';
        $data['busqueda'] = 'busqueda.js';
        $data['carrito'] = 'posCotizaciones';
        $this->views->getView('cotizaciones', 'index', $data);
    }
    public function registrarCotizacion()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $total = 0;
			$vGravadas = $datos['vGravadas'];
			$vTotal = $datos['vTotal'];
			$vIva = $datos['vIva'];
			$vIvaRete = $datos['vIvaRete'];
        if (!empty($datos['productos'])) {
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            $metodo = $datos['metodo'];
            $validez = $datos['validez'];
            $descuento = (!empty($datos['descuento'])) ? $datos['descuento'] : 0;
            $idCliente = $datos['idCliente'];
			$id = $datos['id'];
			$documento = $datos['documento'];

            if (empty($idCliente)) {
                $res = array('msg' => 'EL CLIENTE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($metodo)) {
                $res = array('msg' => 'EL METODO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($validez)) {
                $res = array('msg' => 'LA VALIDEZ ES REQUERIDO', 'type' => 'warning');
            } else {
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
				if($id==""){
				$cotizacion = $this->model->registrarCotizacion($datosProductos, $vTotal, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $documento, $vGravadas, $vIva, $vIvaRete);	
				}else{
				$cotizacion = $this->model->updateCotizacion($datosProductos, $vTotal, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $id, $documento, $vGravadas, $vIva, $vIvaRete);
				$cotizacion = $id;
				}
                
                if ($cotizacion > 0) {
                    $res = array('msg' => 'COTIZACIÓN GENERADA', 'type' => 'success', 'idCotizacion' => $cotizacion);
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
	
	public function editar($id)
    {
        
		$data = $this->model->getProductoCotizacion($id);
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reporte($datos)
    {
        ob_start();
        $array = explode(',', $datos);
        $tipo = $array[0];
        $idCotizacion = $array[1];

        $data['title'] = 'Reporte';
        $data['empresa'] = $this->model->getEmpresa();
        $data['cotizacion'] = $this->model->getCotizacion($idCotizacion);
        if (empty($data['cotizacion'])) {
            echo 'Pagina no Encontrada';
            exit;
        }
        $this->views->getView('cotizaciones', $tipo, $data);
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
            $dompdf->setPaper('A4', 'vertical');
        }

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }

    public function listar()
    {
        $data = $this->model->getCotizaciones();
        for ($i=0; $i < count($data); $i++) { 
            //$data[$i]['acciones'] = '<a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a> <a class="btn btn-warning" href="#" onclick="detalle(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a> <a class="btn btn-success" href="#" onclick="venta(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a>';
$data[$i]['acciones'] = '<a class="btn btn-danger" href="#" onclick="verReporte(' . $data[$i]['id'] . ')"><i class="fas fa-file-pdf"></i></a> <a class="btn btn-warning" href="#" onclick="detalle(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a><a class="btn btn-success" href="#" onclick="verComentarios(' . $data[$i]['id'] . ')"><i class="fas fa-file-edit"></i></a>';			
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>