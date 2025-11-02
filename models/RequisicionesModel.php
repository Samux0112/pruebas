<?php
class RequisicionesModel extends Query{
    public function getProveedores($estado = 1)
    {
        $sql = "SELECT * FROM clientes2 WHERE estado = $estado";
        return $this->selectAll($sql);
    }
    public function __construct() {
        parent::__construct();
    }
    public function getProducto($idProducto)
    {
        $sql = "SELECT * FROM productos WHERE id = $idProducto";
        return $this->select($sql);
    }
    public function registrarRequisicion($productos, $total, $fecha, $hora, $idUsuario, $observaciones = null)
    {
        $sql = "INSERT INTO requisiciones (productos, total, fecha, hora, id_usuario, observaciones) VALUES (?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $idUsuario, $observaciones);
        return $this->insertar($sql, $array);
    }
    public function getRequisicion($id)
    {
        $sql = "SELECT r.*, u.nombre as solicitante FROM requisiciones r LEFT JOIN usuarios u ON r.id_usuario = u.id WHERE r.id = $id";
        return $this->select($sql);
    }
    public function getRequisiciones()
    {
        $sql = "SELECT r.*, u.nombre as solicitante FROM requisiciones r LEFT JOIN usuarios u ON r.id_usuario = u.id ORDER BY r.created_at DESC";
        return $this->selectAll($sql);
    }
    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE requisiciones SET estado = ? WHERE id = ?";
        $array = array($estado, $id);
        return $this->save($sql, $array);
    }
}
?>