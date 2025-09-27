<?php
require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Dompdf\Dompdf;

class TasaMora extends Controller
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
    {	$data['title'] = 'TasaMora';
		$data['script'] = 'tasaMora.js';
        $this->views->getView('tasaMora', 'index', $data);
    }
	
	    //metodo para registrar y modificar
    public function registrar()
    {
        if (!verificar('usuarios')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_POST)) {
            if (empty($_POST['descripcion'])) {
                $res = array('msg' => 'LA DESCRIPCION ES REQUERIDA', 'type' => 'warning');
            }  else {
                $descripcion = strClean($_POST['descripcion']);
                $id = strClean($_POST['id']);

                if ($id == '') {
					
			$data = $this->model->registrar(
                                $descripcion                
                            );
                            if ($data > 0) {
                                $res = array('msg' => 'PUNTO DE VENTA REGISTRADO', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                            }
                } else {
                            $data = $this->model->actualizar(
                                $descripcion,
                                $id
                            );
                            if ($data > 0) {
                                $res = array('msg' => 'TASA DE INTERES ACTUALIZADA', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL ACTUALIZAR', 'type' => 'error');
                            }
                       
                     
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	 public function listar()
    {
        if (!verificar('usuarios')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getPuntoVentas();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-info" type="button" onclick="editarPuntoVenta(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
            </div>';
        $data[$i]['tasa'] = '<span class="badge bg-success">'.$data[$i]['tasa'].'%</span>';
    }
	
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
	}
	
	 public function editar($id)
    {
        if (!verificar('usuarios')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->editar($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	    public function eliminar($id)
    {
        if (!verificar('usuarios')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET)) {
            if (is_numeric($id)) {
                $data = $this->model->eliminar($id);
                if ($data == 1) {
                    $res = array('msg' => 'PUNTO DE VENTA DADO DE BAJA', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
                }
            } else {
                $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }
	

	


    

}
