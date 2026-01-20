<?php
class PedidosModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getProducto($idProducto)
    {
        $sql = "SELECT * FROM productos WHERE id = $idProducto";
        return $this->select($sql);
    }
	
	public function getBodegas($estado)
    {
        $sql = "SELECT * FROM bodegas WHERE estado = $estado";
        return $this->selectAll($sql);
    }
	
	public function registrarDte($id, $dte)
    {
        $sql = "INSERT INTO dtesPedidos (id, dte) VALUES (?,?)";
        $array = array($id, $dte);
        return $this->insertar($sql, $array);
    }
	
	public function getProductoTraslado($idProducto)
    {
        $sql = "SELECT p.id, p.descripcion, s.cantidad, p.precio_venta2, p.precio_venta, m.medida,m.nombre_corto FROM productos p join medidas m on p.id_medida=m.id join Stock s on p.id = s.idProducto WHERE p.id = $idProducto";
		
        return $this->select($sql);
    }
    
    public function getMedidas()
{
$sql = "SELECT * FROM medidas;";
return $this->selectAll($sql);
}

public function getFormasPago()

{
$sql = "SELECT * FROM formasPago;";

return $this->selectAll($sql);

}
    public function registrarVenta($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $pago, $docuemi, $numdocu, $vende,  $forma, $forma2, $idCliente, $idusuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH, $sello, $vExentas, $vIva, $vGravadas, $claseDoc, $retenIva, $tipo_operacion, $tipo_ingreso, $estadoPedido, $observaciones)
    {

	if($docuemi == "CREDITO FISCAL"){
			$numdocu = "03";
		}elseif($docuemi == "FACTURA"){
			$numdocu = "01";
		}
		
        $sql = "INSERT INTO pedidos (productos, total, fecha, hora, metodo, descuento, serie, pago, docuemi, numdocu, vende, forma, forma2, id_cliente, id_usuario, correlativo, numeroControlDte, uuid, codPuntoVentaMH, selloRecepcion, vExentas, vIva, vGravadas, claseDoc, reteIva,tipo_operacion,tipo_ingreso,estadoPedido, observaciones) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $pago, $docuemi, $numdocu, $vende,  $forma, $forma2, $idCliente, $idusuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH, $sello, $vExentas, $vIva, $vGravadas, $claseDoc, $retenIva, $tipo_operacion, $tipo_ingreso,$estadoPedido,$observaciones);
        return $this->insertar($sql, $array); 
        
    }

    public function updateVenta($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $pago, $docuemi, $numdocu, $vende,  $forma, $forma2, $idCliente, $idusuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH, $sello, $vExentas, $vIva, $vGravadas, $claseDoc, $retenIva, $tipo_operacion, $tipo_ingreso, $estadoPedido, $observaciones,$id)
    {

	if($docuemi == "CREDITO FISCAL"){
			$numdocu = "03";
		}elseif($docuemi == "FACTURA"){
			$numdocu = "01";
		}
		
        $sql = "UPDATE pedidos SET productos = ?, total = ?, fecha = ?, hora = ?, metodo = ?, descuento = ?, serie = ?, pago = ?, docuemi = ?, numdocu = ?, vende = ?, forma = ?, forma2 = ?, id_cliente = ?, id_usuario = ?, correlativo = ?, numeroControlDte = ?, uuid = ?, codPuntoVentaMH = ?, selloRecepcion = ?, vExentas = ?, vIva = ?, vGravadas = ?, claseDoc = ?, reteIva = ?, tipo_operacion = ?, tipo_ingreso = ?, estadoPedido = ?, observaciones = ? WHERE id = ?";
        $array = array($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $pago, $docuemi, $numdocu, $vende,  $forma, $forma2, $idCliente, $idusuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH, $sello, $vExentas, $vIva, $vGravadas, $claseDoc, $retenIva, $tipo_operacion, $tipo_ingreso,$estadoPedido,$observaciones,$id);
        return $this->save($sql, $array); 
        
    }

	
	
    
    
	 	
	
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }

    public function getVenta($idVenta)
    {
        $sql = "SELECT v.*, c.identidad, c.num_identidad, c.nombre, c.telefono, c.direccion, c.departamento, c.municipio, c.actividad, c.correo, c.id as id_cliente FROM pedidos v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.id = $idVenta";
        return $this->select($sql);
    } 

     public function getVentas()
    {
        $sql = "SELECT v.*, c.nombre, c.identidad, u.nombre as nomUsuario FROM pedidos v INNER JOIN clientes c ON v.id_cliente = c.id join usuarios u on v.id_usuario=u.id where v.estadoPedido = 'GENERADO' OR v.estadoPedido = 'ANULADO';";
        return $this->selectAll($sql);
    }
	public function getProductoCotizacion($id)
	{
	$sql = "Select cot.*, c.nombre, c.contribuyente, c.direccion, c.telefono,c.id as idCliente from pedidos cot join clientes c on cot.id_cliente = c.id where cot.id = $id;";
	return $this->select($sql);
	}
	
	public function getVentasGenerados()
    {
        $sql = "SELECT v.*, c.nombre, c.identidad, u.nombre as nomUsuario FROM pedidos v INNER JOIN clientes c ON v.id_cliente = c.id join usuarios u on v.id_usuario=u.id where v.estadoPedido = 'GENERADO';";
        return $this->selectAll($sql);
    }
		public function getVentasDespacho()
    {
        $sql = "SELECT v.*, c.nombre, c.identidad, u.nombre as nomUsuario FROM pedidos v INNER JOIN clientes c ON v.id_cliente = c.id join usuarios u on v.id_usuario=u.id where v.estadoPedido != 'GENERADO';";
        return $this->selectAll($sql);
    }
	
	public function getCotizacion($idCotizacion)
    {
        $sql = "SELECT *from pedidos where id = $idCotizacion";
        return $this->select($sql);
    }
	
	public function actualizar($metodo, $comentarios, $id)
    {
        $sql = "UPDATE pedidos SET estadoPedido=?, comentarios=? WHERE id=?";
        $array = array($metodo, $comentarios, $id);
        return $this->save($sql, $array);
    }

	
	
	 public function getClientes()
    {
        $sql = "select * from clientes";
        return $this->selectAll($sql);
    }
	public function getCliente($idCliente)
    {
        $sql = "select * from clientes where id=$idCliente";
        return $this->select($sql);
    }
	
	
	
    public function anular($idVenta)
    {
        $estado = "ANULADO";
        $sql = "UPDATE pedidos SET estado = ?, estadoPedido = ? WHERE id = ?";
        $array = array(0, $estado, $idVenta);
        return $this->save($sql, $array);
    }
	
    

    public function getSerie()
    {
        $sql = "SELECT MAX(id) AS total FROM pedidos";
        return $this->select($sql);
    }

    //movimiento
    public function registrarMovimiento($movimiento, $accion, $cantidad, $stockActual, $idProducto, $id_usuario)
    {
        $sql = "INSERT INTO inventario (movimiento, accion, cantidad, stock_actual, id_producto, id_usuario) VALUES (?,?,?,?,?,?)";
        $array = array($movimiento, $accion, $cantidad, $stockActual, $idProducto, $id_usuario);
        return $this->insertar($sql, $array);
    }
	public function getClienteVenta($id)
    {
        $sql = "select * from clientes where id = $id";
        return $this->select($sql);
    }
	
	public function getMaxCorrelativo($tipoFactura, $codPuntoVenta)
    {
		date_default_timezone_set('America/El_Salvador');
		$anio = date("Y");
        $sql = "SELECT MAX(correlativo) AS correlativo FROM pedidos where docuemi = '$tipoFactura' AND codPuntoVentaMH = '$codPuntoVenta' AND fecha like '%$anio%' ";
        return $this->select($sql);
    }
	
}


?>