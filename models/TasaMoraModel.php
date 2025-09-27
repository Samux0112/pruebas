<?php
class TasaMoraModel extends Query{
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
        $sql = "select * from tasasMora;";
        return $this->selectAll($sql);
    }
	
	public function editar($id)
    {
        $sql = "SELECT id, tasa from tasasMora where id = $id";
        return $this->select($sql);
    }
	
	    public function actualizar($descripcion, $id)
    {
        $sql = "UPDATE tasasMora SET tasa=? WHERE id=?";
        $array = array($descripcion, $id);
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