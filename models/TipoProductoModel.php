<?php
class TipoProductoModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function registrar($descripcion, $codTipoProducto)
    {
        $sql = "INSERT INTO tipoProducto (descripcion, codTipoProductoMH) VALUES (?,?)";
        $array = array($descripcion, $codTipoProducto);
        return $this->insertar($sql, $array);
    }
    public function getTipoProducto() 
    {
        $sql = "select * from tipoProducto;";
        return $this->selectAll($sql);
    }
    public function editar($id)
    {
        $sql = "SELECT id_tipoProducto, descripcion, codTipoProductoMH FROM tipoProducto WHERE id_tipoProducto = $id";
        return $this->select($sql);
    }
    public function actualizar($descripcion, $codTipoProducto, $id)
    {
        $sql = "UPDATE tipoProducto SET descripcion=?, codTipoProductoMH=? WHERE id_tipoProducto=?";
        $array = array($descripcion, $codTipoProducto, $id);
        return $this->save($sql, $array);
    }
    public function eliminar($id)
    {
        $sql = "DELETE from tipoProducto WHERE id_tipoProducto = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }
}
?>
