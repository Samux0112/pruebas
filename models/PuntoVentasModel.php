<?php
class PuntoVentasModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function registrar($descripcion, $codPuntoVenta)
    {
        $sql = "INSERT INTO puntoVentas (descripcion, codPuntoVentaMH) VALUES (?,?)";
        $array = array($descripcion, $codPuntoVenta);
        return $this->insertar($sql, $array);
    }
	
	public function getPuntoVentas() 
    {
        $sql = "select * from puntoVentas;";
        return $this->selectAll($sql);
    }
	
	public function editar($id)
    {
        $sql = "SELECT id_puntoVenta, descripcion, codPuntoVentaMH FROM puntoVentas WHERE id_puntoVenta = $id";
        return $this->select($sql);
    }
	
	    public function actualizar($descripcion, $codPuntoVenta, $id)
    {
        $sql = "UPDATE puntoVentas SET descripcion=?, codPuntoVentaMH=? WHERE id_puntoVenta=?";
        $array = array($descripcion, $codPuntoVenta, $id);
        return $this->save($sql, $array);
    }
	
	 public function eliminar($id)
    {
        $sql = "DELETE from puntoVentas WHERE id_puntoVenta = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }
	
}


?>