<?php
class Contabilidad extends Controller {
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
        $data['title'] = 'Contabilidad';
        $this->views->getView('contabilidad', 'index', $data);
    }

    public function tipoPartida()
    {
        $data['title'] = 'Tipos de Partida';
        $data['script'] = 'tipoPartida.js';
        $this->views->getView('contabilidad', 'tipoPartida', $data);
    }
}
