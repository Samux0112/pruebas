<?php

use FontLib\Table\Type\post;

class Bodegas extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        if (!verificar('bodegas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
    }
    public function index()
    {
        $data['title'] = 'Bodegas';
        $data['script'] = 'bodegas.js';
        $data['permisos'] = $this->model->getPermisos();
        $this->views->getView('bodegas', 'index', $data);
    }
    public function listar()
    {
        $data = $this->model->getBodegas(1);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="eliminarRol(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
            <button class="btn btn-info" type="button" onclick="editarRol(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
			<button class="btn btn-success" type="button" onclick="productos(' . $data[$i]['id'] . ')"><i class="fa-solid fa-list"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $nombre = strClean($_POST['nombre']);
		$telefono = strClean($_POST['telefono']);
		$nombreContacto = strClean($_POST['nombreContacto']);
		$direccion = strClean($_POST['direccion']);
        $id = strClean($_POST['id']);
        if (empty($nombre)) {
            $res = array('msg' => 'EL NOMBRE ES REQUERIDO', 'type' => 'warning');
        }  else {

            if ($id == '') {
                $verificar = $this->model->getValidar('nombre', $nombre, 'registrar', 0);
                if (empty($verificar)) {
                    $data = $this->model->registrar($nombre, $telefono, $nombreContacto, $direccion);
                    if ($data > 0) {
                        $res = array('msg' => 'BODEGA REGISTRADA', 'type' => 'success');
                    } else {
                        $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                    }
                } else {
                    $res = array('msg' => 'BODEGA YA EXISTE', 'type' => 'warning');
                }
            } else {
                $verificar = $this->model->getValidar('nombre', $nombre, 'actualizar', $id);
                if (empty($verificar)) {
                    $data = $this->model->actualizar($nombre, $telefono, $nombreContacto, $direccion, $id);
                    if ($data == 1) {
                        $res = array('msg' => 'BODEGA MODIFICADA', 'type' => 'success');
                    } else {
                        $res = array('msg' => 'ERROR AL MODICAR', 'type' => 'error');
                    }
                } else {
                    $res = array('msg' => 'LA BODEGA YA EXISTE', 'type' => 'warning');
                }
            }
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar($idRol)
    {
        if (isset($_GET)) {
            if (is_numeric($idRol)) {
                $data = $this->model->eliminar(0, $idRol);
                if ($data == 1) {
                    $res = array('msg' => 'BODEGA DADA DE BAJA', 'type' => 'success');
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

    public function editar($idRol)
    {
        $data['rol'] = $this->model->editar($idRol);
        $permisos = [];
        if ($data['rol']['permisos'] != null) {
            $permisos = json_decode($data['rol']['permisos'], true);
        }
        $data['permisos'] = $permisos;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	public function verstock($datos)
    {
		ob_start();
        $array = explode(',', $datos);
        $idBodega = $array[0];
		$data['idBodega'] = $idBodega;		
        $data['title'] = 'Bodegas';
        $data['script'] = 'stockBodegas.js';
        $this->views->getView('bodegas', 'stockBodegas', $data);
    }
}
