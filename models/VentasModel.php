<?php
class VentasModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getProducto($id,$bodega)
{
		$sql = "SELECT p.*, m.medida,m.nombre_corto, c.categoria,s.cantidad as stock FROM productos p INNER JOIN medidas m
		ON p.id_medida = m.id INNER JOIN categorias c ON p.id_categoria = c.id join Stock s on p.id = s.idProducto WHERE p.id = $id and s.idBodega=$bodega";
		return $this->select($sql);
}
	
	public function getProductoTraslado($idProducto)
    {
        $sql = "SELECT p.id, p.descripcion, s.cantidad, p.precio_venta2, p.precio_venta, m.medida,m.nombre_corto FROM productos p join medidas m on p.id_medida=m.id join Stock s on p.id = s.idProducto WHERE p.id = $idProducto";
		
        return $this->select($sql);
    }
	
	public function getPedido($idPedido)
    {
        $sql = "SELECT * from pedidos where id = $idPedido";
		
        return $this->select($sql);
    }
	
	public function getCodPuntoVenta($idUsuario)
    {
        $sql = "select codPuntoVentaMH from usuarios where id = $idUsuario";
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
    
	public function registrarPlan($plan, $monto, $plazo, $interes, $cuotaSeguro, $montoTotalPlan, $cliente, $fecha)
    {
        $sql = "INSERT INTO planPagosPrevios (detalle_plan,monto,plazo,interes,cuotaSeguro,montoTotalPlan,cliente, fecha) VALUES (?,?,?,?,?,?,?,?)";
        $array = array($plan, $monto, $plazo, $interes, $cuotaSeguro, $montoTotalPlan, $cliente, $fecha);
        return $this->insertar($sql, $array);
    }
	 public function buscarPlanes($idCliente)
    {
        $sql = "SELECT * from planPagos where id_cliente = $idCliente";
        return $this->selectAll($sql);
    }
	public function getInteresMora()
    {
        $sql = "SELECT tasa from tasasMora where id = (select max(id) from tasasMora)";
        return $this->select($sql);
    }
	public function getCreditos()
    {
        $sql = "SELECT cr.*, cl.nombre FROM creditos cr INNER JOIN ventas v ON cr.id_venta = v.id INNER JOIN clientes cl ON v.id_cliente = cl.id";
        return $this->selectAll($sql);
    }
	public function getCredito($idCredito)
    {
        $sql = "SELECT cr.*, v.productos, cl.identidad, cl.num_identidad, cl.nombre, cl.telefono, cl.direccion FROM creditos cr INNER JOIN ventas v ON cr.id_venta = v.id INNER JOIN clientes cl ON v.id_cliente = cl.id WHERE cr.id = $idCredito";
        return $this->select($sql);
    }
	public function getAbono($idCredito)
    {
        $sql = "SELECT SUM(abono) AS total FROM abonos WHERE id_credito = $idCredito";
        return $this->select($sql);
    }
	public function actualizarCredito($estado, $idCredito)
    {
        $sql = "UPDATE planPagos SET estado = ? WHERE id_credito = ?";
        $array = array($estado, $idCredito);
        return $this->save($sql, $array);
    }
	public function updatePedido($pedido, $numeroControlDte, $uuid)
    {
        $sql = "UPDATE pedidos SET estadoPedido = ?, numeroControlDte=?, uuid =? WHERE id = ?";
        $array = array('DESPACHO', $numeroControlDte, $uuid, $pedido);
        return $this->save($sql, $array);
    }
	
	public function anularPlan($idVenta)
    {
		$estado = "Anulado";
        $sql = "UPDATE planPagos SET estado = ? WHERE id_venta = ?";
        $array = array($estado, $idVenta);
        return $this->save($sql, $array);
    }
	
	public function registrarDetallePago($idVenta, $idPlan, $cuota)
    {
        $sql = "INSERT INTO detalle_pagoCuota (id_venta, id_plan, cuota) VALUES (?,?,?)";
        $array = array($idVenta, $idPlan, $cuota);
        return $this->insertar($sql, $array);
    }
	public function registrarAbono($monto, $idCredito, $id_usuario)
    {
        $sql = "INSERT INTO abonos (abono, id_credito, id_usuario) VALUES (?,?,?)";
        $array = array($monto, $idCredito, $id_usuario);
        return $this->insertar($sql, $array);
    }
    public function registrarVenta($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $pago, $docuemi, $numdocu, $vende,  $forma, $forma2, $idCliente, $idusuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH, $sello, $vExentas, $vIva, $vGravadas, $claseDoc, $retenIva, $tipo_operacion, $tipo_ingreso)
    {

	if($docuemi == "CREDITO FISCAL"){
			$numdocu = "03";
		}elseif($docuemi == "FACTURA"){
			$numdocu = "01";
		}
		
        $sql = "INSERT INTO ventas (productos, total, fecha, hora, metodo, descuento, serie, pago, docuemi, numdocu, vende, forma, forma2, id_cliente, id_usuario, correlativo, numeroControlDte, uuid, codPuntoVentaMH, selloRecepcion, vExentas, vIva, vGravadas, claseDoc, reteIva,tipo_operacion,tipo_ingreso) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $pago, $docuemi, $numdocu, $vende,  $forma, $forma2, $idCliente, $idusuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH, $sello, $vExentas, $vIva, $vGravadas, $claseDoc, $retenIva, $tipo_operacion, $tipo_ingreso);
        return $this->insertar($sql, $array); 
        
    }

	public function registrarDte($id, $dte)
    {
        $sql = "INSERT INTO dtes (id, dte) VALUES (?,?)";
        $array = array($id, $dte);
        return $this->insertar($sql, $array);
    }
	public function registrarDteCotingencia($id, $dte, $uuid, $totalVenta, $fecha)
    {
        $sql = "INSERT INTO contingencias (id, codigo_generacion, total, fecha, enviada) VALUES (?,?,?,?,?)";
        $array = array($id, $uuid, $totalVenta, $fecha, "false" );
        return $this->insertar($sql, $array);
    }
    public function actualizarStock($cantidad, $idProducto, $idBodega)
    {
        $sql = "UPDATE Stock SET cantidad = ? WHERE idProducto = ? and idBodega = ?"; 
        $array = array($cantidad, $idProducto, $idBodega);
        return $this->save($sql, $array);
    }
    public function registrarCredito($monto, $fecha, $hora, $idVenta)
    {
        $sql = "INSERT INTO creditos (monto, fecha, hora, id_venta) VALUES (?,?,?,?)";
        $array = array($monto, $fecha, $hora, $idVenta);
        return $this->insertar($sql, $array);
    }
	 public function registrarPlanPago($planPagoDetalle, $fecha, $venta, $totalVenta, $interes, $monto, $plazo, $cuotaSeguro, $idCliente, $montoTotalPlan, $credito, $estado)
    {
        $sql = "INSERT INTO planPagos (detalle_plan, fecha, id_venta, prima, interes, monto, plazo, seguro, id_cliente, montoTotalPlan, id_credito, estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($planPagoDetalle, $fecha, $venta, $totalVenta, $interes, $monto, $plazo, $cuotaSeguro, $idCliente, $montoTotalPlan, $credito, $estado);
        return $this->insertar($sql, $array);
    }	
	public function getPlanDetalle($id)
    {
        $sql = "SELECT detalle_plan FROM planPagos WHERE id_venta = $id";
        return $this->select($sql);
    }
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }

    public function getVenta($idVenta)
    {
        $sql = "SELECT v.*, c.identidad, c.num_identidad, c.nombre, c.telefono, c.direccion, c.departamento, c.municipio, c.actividad, c.correo, c.id as id_cliente FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.id = $idVenta";
        return $this->select($sql);
    } 

     public function getVentas()
    {
        $sql = "SELECT v.*, c.nombre, c.identidad, u.nombre as nomUsuario FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id join usuarios u on v.id_usuario=u.id;";
        return $this->selectAll($sql);
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
	
	public function deleteAbono($idCredito)
    {
        $sql = "delete from abonos where id =(SELECT max(id) from abonos where id_credito =?)";
        $array = array($idCredito);
        return $this->save($sql, $array);
    }
	
    public function anular($idVenta)
    {
        $sql = "UPDATE ventas SET estado = ? WHERE id = ?";
        $array = array(0, $idVenta);
        return $this->save($sql, $array);
    }
	public function updatePlanPagos($idPlan,$planDetalle)
    {
		$estado = "Activo";
        $sql = "UPDATE planPagos SET detalle_plan = ?, estado = ? WHERE id_plan = ?";
        $array = array($planDetalle, $estado, $idPlan);
        return $this->save($sql, $array);
    }
    public function anularCredito($idVenta)
    {
        $sql = "UPDATE creditos SET estado = ? WHERE id_venta = ?";
        $array = array(2, $idVenta);
        return $this->save($sql, $array);
    }

    public function getSerie()
    {
        $sql = "SELECT MAX(id) AS total FROM ventas";
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
	
	public function getMaxCorrelativo($tipoFactura, $codPuntoVenta)
    {
		date_default_timezone_set('America/El_Salvador');
		$anio = date("Y");
        $sql = "SELECT MAX(correlativo) AS correlativo FROM ventas where docuemi = '$tipoFactura' AND codPuntoVentaMH = '$codPuntoVenta' AND fecha like '%$anio%' ";
        return $this->select($sql);
    }
	
		public function getDTE($id)
    {
        $sql = "select * from dtes where id = $id";
        return $this->select($sql);
    }
	
		public function getClienteVenta($id)
    {
        $sql = "select * from clientes where id = $id";
        return $this->select($sql);
    }
	
}


?>