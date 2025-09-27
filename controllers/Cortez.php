<?php
require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Dompdf\Dompdf;

class Cortez extends Controller
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
        if (!verificar('Cortez')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        $data['title'] = 'Cortez';
        $data['script'] = 'cortez.js';
        $resultSerie = $this->model->getSerie();
        $this->views->getView('cortez', 'index', $data);
    }


    public function reporte($datos)
    {
        ob_start();
        $array = explode(',', $datos);
        $tipo = $array[0];
        $fInicio = $array[1];
		$fFin = $array[2];
        $data['title'] = 'Reporte';
        $data['empresa'] = $this->model->getEmpresa();
        $data['factura'] = $this->model->getDatosFactura($fInicio, $fFin, 'FACTURA');
		$data['credito'] = $this->model->getDatosFactura($fInicio, $fFin, 'CREDITO FISCAL');
		$data['fInicio'] = $fInicio;
		$data['fFin'] = $fFin;
		if ($tipo == 'ticked') {
		$this->views->getView('cortez', $tipo, $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
		$c+=1;
		 $largo = ($c*50)+500;
            $dompdf->setPaper(array(0, 0, 150, $largo), 'portrait');
			 // Render the HTML as PDF
            $dompdf->render();

        // Output the generated PDF to Browser
            $dompdf->stream('reporte.pdf', array('Attachment' => false));	
        }    
    }
	
	
}
