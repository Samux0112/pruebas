<?php
require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Dompdf\Dompdf;

class TipoProducto extends Controller
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
        $data['title'] = 'TipoProducto';
        $data['script'] = 'tipoProducto.js';
        $this->views->getView('tipoProducto', 'index', $data);
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
            } else if (empty($_POST['codTipoProducto'])) {
                $res = array('msg' => 'EL CODIGO DE TIPO DE PRODUCTO ES REQUERIDO', 'type' => 'warning');
            } else {
                $descripcion = strClean($_POST['descripcion']);
                $codTipoProducto = strClean($_POST['codTipoProducto']);
                $id = strClean($_POST['id']);
                if ($id == '') {
                    $data = $this->model->registrar(
                        $descripcion,
                        $codTipoProducto,
                    );
                    if ($data > 0) {
                        $res = array('msg' => 'TIPO DE PRODUCTO REGISTRADO', 'type' => 'success');
                    } else {
                        $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                    }
                } else {
                    $data = $this->model->actualizar(
                        $descripcion,
                        $codTipoProducto,
                        $id
                    );
                    if ($data == 1) {
                        $res = array('msg' => 'TIPO DE PRODUCTO MODIFICADO', 'type' => 'success');
                    } else {
                        $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                    }
                }
            }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
        }
    }
}
