<?php
class TipoPartidaModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM tipo_partida ORDER BY id";
        return $this->selectAll($sql);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM tipo_partida WHERE id = '$id'";
        return $this->select($sql);
    }

    public function insertar($id, $nombre)
    {
        $sql = "INSERT INTO tipo_partida (id, nombre) VALUES (?, ?)";
        $array = array($id, $nombre);
        $result = parent::insertar($sql, $array);
        return $result > 0 ? 1 : $result;
    }

    public function actualizar($id, $nombre, $idOriginal)
    {
        $sql = "UPDATE tipo_partida SET id = ?, nombre = ? WHERE id = ?";
        $array = array($id, $nombre, $idOriginal);
        return $this->save($sql, $array);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM tipo_partida WHERE id = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }

    public function verificarRelaciones($id)
    {
        $sql = "SELECT COUNT(*) as total FROM partidas WHERE tipo_partida = '$id'";
        return $this->select($sql);
    }
}