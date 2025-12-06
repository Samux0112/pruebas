<?php
class Ventas2Model extends Query{
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

    	public function getCliente($idCliente)
    {
        $sql = "select * from clientes2 where id=$idCliente";
        return $this->select($sql);
    }
	
	
	public function getMaxCorrelativo($tipoFactura, $codPuntoVentaMH)
    {
		date_default_timezone_set('America/El_Salvador');
		$anio = date("Y");
        $sql = "SELECT MAX(correlativo) AS correlativo FROM ventas2 where docuemi = '$tipoFactura' and codPuntoVentaMH = '$codPuntoVentaMH' AND fecha like '%$anio%'";
        return $this->select($sql);
    }
    public function registrarVenta2($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $numc, $idCliente2, $idusuario, $correlativo, $numeroControlDte, $uuid, $renta, $codPuntoVentaMH, $cGravadas, $cExentas, $cIva, $claseDoc, $tipoDoc, $sello, $fovial, $cotrans, $percepcion1, $percepcion2, $docuemision, $tipoOperacion, $clasificacion, $sector, $tipoGasto)
    {
			$docuemi = "COMPRA";
		if($numeroControlDte!=""){
			$docuemi = "SUJETO EXCLUIDO";
		}if($docuemision=="NOTA DE CREDITO"){
			$docuemi = "NOTA DE CREDITO";
			$tipoDoc = "05";
		}
        $sql = "INSERT INTO ventas2 (productos, total, fecha, hora, metodo, descuento, serie, numc, id_cliente2, id_usuario,correlativo,numeroControlDte,uuid,docuemi,renta, codPuntoVentaMH, vGravadas, vExentas, vIva, claseDoc, tipoDoc, selloRecepcion, fovial, cotrans, percepcion1, percepcion2, tipoOperacion, clasificacion, sector, tipoGasto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $numc, $idCliente2, $idusuario, $correlativo, $numeroControlDte, $uuid, $docuemi, $renta, $codPuntoVentaMH, $cGravadas, $cExentas, $cIva, $claseDoc, $tipoDoc, $sello, $fovial, $cotrans, $percepcion1, $percepcion2, $tipoOperacion, $clasificacion, $sector, $tipoGasto);
        return $this->insertar($sql, $array);
    }
	
	public function registrarDte($id, $dte)
    {
        $sql = "INSERT INTO dtesSujetoExcluido (id, dte) VALUES (?,?)"; 
        $array = array($id, $dte);
        return $this->insertar($sql, $array);
    }
	public function registrarDteCotingencia($id, $dte, $uuid, $totalVenta, $fecha)
    {
        $sql = "INSERT INTO contingenciasSujeto (id, codigo_generacion, total, fecha, enviada) VALUES (?,?,?,?,?)";
        $array = array($id, $uuid, $totalVenta, $fecha, "false" );
        return $this->insertar($sql, $array);
    }
	
	public function registrarStock($cantidad, $idProducto, $idBodega)
    {
        $sql = "INSERT INTO Stock (cantidad, idProducto, idBodega) VALUES (?,?,?)"; 
        $array = array($cantidad, $idProducto, $idBodega);
        return $this->insertar($sql, $array);
    }
		
    public function actualizarStock($cantidad, $idProducto, $idBodega)
    {
        $sql = "UPDATE Stock SET cantidad = ? WHERE idProducto = ? and idBodega = ?"; 
        $array = array($cantidad, $idProducto, $idBodega);
        return $this->save($sql, $array);
    }
    public function registrarCredito2($monto, $fecha, $hora, $idVenta2)
    {
        $sql = "INSERT INTO creditos2 (monto, fecha, hora, id_venta2) VALUES (?,?,?,?)";
        $array = array($monto, $fecha, $hora, $idVenta2);
        return $this->insertar($sql, $array);
    }
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }

    public function getVenta2($idVenta2)
    {
        $sql = "SELECT v.*, c.identidad, c.num_identidad, c.nombre, c.telefono, c.direccion FROM ventas2 v INNER JOIN clientes2 c ON v.id_cliente2 = c.id WHERE v.id = $idVenta2";
        return $this->select($sql);
    }

    public function getVentas2()
    {
        $sql = "SELECT v.*, c.nombre FROM ventas2 v INNER JOIN clientes2 c ON v.id_cliente2 = c.id";
        return $this->selectAll($sql);
    }

    public function anular($idVenta2)
    {
        $sql = "UPDATE ventas2 SET estado = ? WHERE id = ?";
        $array = array(0, $idVenta2);
        return $this->save($sql, $array);
    }
    public function anularCredito2($idVenta2)
    {
        $sql = "UPDATE creditos2 SET estado = ? WHERE id_venta2 = ?";
        $array = array(2, $idVenta2);
        return $this->save($sql, $array);
    }

    public function getSerie()
    {
        $sql = "SELECT MAX(id) AS total FROM ventas2";
        return $this->select($sql);
    }

    //movimiento
    public function registrarMovimiento($movimiento, $accion, $cantidad, $stockActual, $idProducto, $id_usuario)
    {
        $sql = "INSERT INTO inventario (movimiento, accion, cantidad, stock_actual, id_producto, id_usuario) VALUES (?,?,?,?,?,?)";
        $array = array($movimiento, $accion, $cantidad, $stockActual, $idProducto, $id_usuario);
        return $this->insertar($sql, $array);
    }

    public function getCaja($id_usuario)
    {
        $sql = "SELECT * FROM cajas WHERE estado = 1 AND id_usuario = $id_usuario";
        return $this->select($sql);
    }
	
	public function getDatos($table) 
    {
        $sql = "SELECT * FROM $table WHERE estado = 1";
        return $this->selectAll($sql);
    }
}


?>