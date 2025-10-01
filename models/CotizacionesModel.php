<?php
class CotizacionesModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getProducto($idProducto)
    {
        $sql = "SELECT * FROM productos WHERE id = $idProducto";
        return $this->select($sql);
    }
        public function registrarComentario($comentario, $fecha, $id_cotizacion)
        {
            $sql = "INSERT INTO comentarios_cotizacion (comentario, fecha, id_cotizacion) VALUES (?,?,?)";
            $array = array($comentario, $fecha, $id_cotizacion);
            return $this->insertar($sql, $array);
        }
        public function listarComentarios($id_cotizacion)
        {
            $sql = "SELECT fecha, comentario FROM comentarios_cotizacion WHERE id_cotizacion = $id_cotizacion ORDER BY fecha DESC";
            return $this->selectAll($sql);
        }
	public function getProductoCotizacion($id)
	{
	$sql = "Select cot.*, c.nombre, c.contribuyente, c.direccion, c.telefono,c.id as idCliente from cotizaciones cot join clientes c on cot.id_cliente = c.id where cot.id = $id;";
	return $this->select($sql);
	}
    public function registrarCotizacion($productos, $total, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $documento, $vGravadas, $vIva, $vIvaRete)
    {
        $sql = "INSERT INTO cotizaciones (productos, total, fecha, hora, metodo, validez, descuento, id_cliente, documento, vGravadas, iva, ivaRete) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $documento, $vGravadas, $vIva, $vIvaRete);
        return $this->insertar($sql, $array);
    }
	public function updateCotizacion($productos, $total, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $idCotizacion , $documento, $vGravadas, $vIva, $vIvaRete)
    {
        $sql = "update cotizaciones set productos = ?, total =?, fecha =?, hora =?, metodo =?, validez =?, descuento = ?, id_cliente = ?, documento=?, vGravadas=?, iva=?, ivaRete=?  where id = ?";
        $array = array($productos, $total, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $documento, $vGravadas, $vIva, $vIvaRete, $idCotizacion);
        return $this->save($sql, $array);
    }
	
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }
    public function getCotizacion($idCotizacion)
    {
        $sql = "SELECT ct.*, cl.identidad, cl.num_identidad, cl.nombre, cl.telefono, cl.direccion FROM cotizaciones ct INNER JOIN clientes cl ON ct.id_cliente = cl.id WHERE ct.id = $idCotizacion";
        return $this->select($sql);
    }

    public function getCotizaciones()
    {
        $sql = "SELECT ct.*, cl.nombre FROM cotizaciones ct INNER JOIN clientes cl ON ct.id_cliente = cl.id";
        return $this->selectAll($sql);
    }
}

?>