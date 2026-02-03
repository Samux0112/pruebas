<?php
class SalidasModel extends Query{
    public function getNombreBodega($idBodega)
    {
        $sql = "SELECT nombre FROM bodegas WHERE id = $idBodega LIMIT 1";
        return $this->select($sql);

    }
    public function __construct() {
        parent::__construct();
    }
    public function getProducto($id,$bodega)
{
		$sql = "SELECT p.*, m.medida,m.nombre_corto, c.categoria,s.cantidad as stock FROM productos p INNER JOIN medidas m
		ON p.id_medida = m.id INNER JOIN categorias c ON p.id_categoria = c.id join Stock s on p.id = s.idProducto WHERE p.id = $id and s.idBodega=$bodega";
		return $this->select($sql);
}

public function getProductoBodega($idProducto,$bodega)
    {
        $sql = "SELECT count(id) as total FROM Stock WHERE idProducto = $idProducto and idBodega = $bodega";
        return $this->select($sql);
    }
	
	public function actualizarStock($cantidad, $idProducto, $idBodega)
    {
        $sql = "UPDATE Stock SET cantidad = ? WHERE idProducto = ? and idBodega = ?"; 
        $array = array($cantidad, $idProducto, $idBodega);
        return $this->save($sql, $array);
    }
	
	public function registrarStock($cantidad, $idProducto, $idBodega)
    {
        $sql = "INSERT INTO Stock (cantidad, idProducto, idBodega) VALUES (?,?,?)"; 
        $array = array($cantidad, $idProducto, $idBodega);
        return $this->insertar($sql, $array);
    }
	
    public function registrarTraslado($productos, $fecha_create, $fecha_traslado, $total, $idCliente, $bodega1, $tipoTraslado, $id_usuario)
    {
        $sql = "INSERT INTO traslados (productos, fecha_create, fecha_traslado, total, id_bodega, id_bodega2, traslado, id_usuario) VALUES (?,?,?,?,?,?,?,?)";
        $array = array($productos, $fecha_create, $fecha_traslado, $total, $idCliente, $bodega1, $tipoTraslado, $id_usuario);
        return $this->insertar($sql, $array);
    }

    public function registrarDetalle($monto, $idApartado, $id_usuario)
    {
        $sql = "INSERT INTO detalle_apartado (monto, id_apartado, id_usuario) VALUES (?,?,?)";
        $array = array($monto, $idApartado, $id_usuario);
        return $this->insertar($sql, $array);
    }

    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }
    public function getApartado($idApartado)
    {
        $sql = "SELECT ap.*, (SELECT nombre from bodegas where id = ap.id_bodega2) as salida, cl.nombre as entrada FROM traslados ap INNER JOIN bodegas cl ON ap.id_bodega = cl.id WHERE ap.id = $idApartado";
        return $this->select($sql);
    }

    public function getCotizaciones()
    {
        $sql = "SELECT ap.*, (SELECT nombre from bodegas where id = ap.id_bodega2) as salida, cl.nombre as entrada FROM traslados ap INNER JOIN bodegas cl ON ap.id_bodega = cl.id where ap.traslado='SALIDA';"; 
        return $this->selectAll($sql);
    }

    public function procesarEntrega($abono, $estado, $idApartado)
    {
        $sql = "UPDATE apartados SET abono = ?, estado = ? WHERE id = ?";
        $array = array($abono, $estado, $idApartado);
        return $this->save($sql, $array);
    }
    //actualizar detalle
    public function actualizarDetalle($abono, $idApartado)
    {
        $sql = "UPDATE detalle_apartado SET monto = ? WHERE id_apartado = ?";
        $array = array($abono, $idApartado);
        return $this->save($sql, $array);
    }
    //actualizar stock}

    //movimiento
    public function registrarMovimiento($movimiento, $accion, $cantidad, $stockActual, $idProducto, $id_usuario)
    {
        $sql = "INSERT INTO inventario (movimiento, accion, cantidad, stock_actual, id_producto, id_usuario) VALUES (?,?,?,?,?,?)";
        $array = array($movimiento, $accion, $cantidad, $stockActual, $idProducto, $id_usuario);
        return $this->insertar($sql, $array);
    }
	public function buscarPorNombre($valor)
    {
        $sql = "SELECT id, nombre, telefono, direccion FROM bodegas WHERE nombre LIKE '%".$valor."%' AND estado = 1 LIMIT 10";
        return $this->selectAll($sql);
    }
	
		public function getDatos($table) 
    {
        $sql = "SELECT * FROM $table WHERE estado = 1";
        return $this->selectAll($sql);
    }
	
}

?>