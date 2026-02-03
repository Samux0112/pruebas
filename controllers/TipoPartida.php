<?php
class TipoPartida extends Controller {
    public function __construct() {
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
        $data['title'] = 'Tipos de Partida';
        $data['script'] = 'tipoPartida.js';
        $this->views->getView('contabilidad/tipoPartida', 'index', $data);
    }

    public function listar()
    {
        $tipoPartidaModel = new TipoPartidaModel();
        $data = $tipoPartidaModel->getAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function guardar()
    {
        if (isset($_POST)) {
            $id = strClean($_POST['id']);
            $nombre = strClean($_POST['nombre']);
            $idOriginal = strClean($_POST['id_original'] ?? '');

            if (empty($id) || empty($nombre)) {
                $res = array('msg' => 'TODOS LOS CAMPOS SON REQUERIDOS', 'type' => 'warning');
                } else {
                    $tipoPartidaModel = new TipoPartidaModel();
                    
                    if (empty($idOriginal)) {
                        $verificar = $tipoPartidaModel->getById($id);
                        if (!empty($verificar)) {
                            $res = array('msg' => 'EL ID YA EXISTE', 'type' => 'warning');
                            echo json_encode($res, JSON_UNESCAPED_UNICODE);
                            die();
                        }
                        $result = $tipoPartidaModel->insertar($id, $nombre);
                        error_log("DEBUG insertar result: " . print_r($result, true));
                        if ($result > 0) {
                            $res = array('msg' => 'TIPO DE PARTIDA REGISTRADO', 'type' => 'success');
                        } else {
                            $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                        }
                    } else {
                    $result = $tipoPartidaModel->actualizar($id, $nombre, $idOriginal);
                    if ($result > 0) {
                        $res = array('msg' => 'TIPO DE PARTIDA ACTUALIZADO', 'type' => 'success');
                    } else {
                        $res = array('msg' => 'ERROR AL ACTUALIZAR', 'type' => 'error');
                    }
                }
            }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function eliminar($id)
    {
        $tipoPartidaModel = new TipoPartidaModel();
        $verificar = $tipoPartidaModel->verificarRelaciones($id);
        if ($verificar['total'] > 0) {
            $res = array('msg' => 'NO SE PUEDE ELIMINAR - TIENE PARTIDAS ASOCIADAS', 'type' => 'warning');
        } else {
            $result = $tipoPartidaModel->eliminar($id);
            if ($result > 0) {
                $res = array('msg' => 'TIPO DE PARTIDA ELIMINADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
            }
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getById($id)
    {
        $tipoPartidaModel = new TipoPartidaModel();
        $data = $tipoPartidaModel->getById($id);
        echo json_encode($data);
        die();
    }
}
