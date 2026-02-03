<?php
require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Dompdf\Dompdf;

class Contingencias extends Controller
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
        if (!verificar('ventas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {
        $this->views->getView('contingencias', 'index', $data);
    }
	
	public function obtenerDte()
    {
        $array = array();
		$id = strClean($_GET['id']);
        $data = $this->model->getDte($id);
       
            $resultado['dte'] = $data['dte'];
            array_push($array, $resultado);
        
        echo json_encode($array);
        die();
    }
	
		public function obtenerDteSujeto()
    {
        $array = array();
		$id = strClean($_GET['id']);
        $data = $this->model->getDteSujeto($id);
       
            $resultado['dte'] = $data['dte'];
            array_push($array, $resultado);
        
        echo json_encode($array);
        die();
    }
	
	    public function contingenciaUpdate()
    {
		 $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
		$dte = $datos['dte'];
		$id = $datos['id'];
		$this->model->updateContingencia($id);
		$this->model->updateDte($dte, $id);
		
		
		
	}
	
	 public function contingenciaUpdateSujeto()
    {
		 $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
		$dte = $datos['dte'];
		$id = $datos['id'];
		$this->model->updateContingenciaSujeto($id);
		$this->model->updateDteSujeto($dte, $id);
		
		
		
	}

    

}
