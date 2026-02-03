<?php
class LibrosIvaModel extends Query{
    public function __construct() {
        parent::__construct();
    }
	public function buscarRegistroVenta($idRegistro){
		$sql = "select v.id_cliente,v.uuid,v.total,c.nombre from ventas v join clientes c on v.id_cliente=c.id where v.numeroControlDte = '$idRegistro'";
		 return $this->selectAll($sql);
	}
	
	public function buscarRegistroDte($idRegistro){
		$sql = "select dte from dtes where id = (select id from ventas where numeroControlDte = '$idRegistro')"; 
		 return $this->selectAll($sql);
	}
	
	
	
    public function getProducto($idProducto)
    {
        $sql = "SELECT * FROM productos WHERE id = $idProducto";
        return $this->select($sql);
    }
    public function registrarVenta($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $pago, $docuemi, $numdocu, $vende,  $forma, $forma2, $idCliente, $idusuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH)
    {
        $sql = "INSERT INTO ventas (productos, total, fecha, hora, metodo, descuento, serie, pago, docuemi, numdocu, vende, forma, forma2, id_cliente, id_usuario, correlativo, numeroControlDte, uuid, codPuntoVentaMH) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $metodo, $descuento, $serie, $pago, $docuemi, $numdocu, $vende,  $forma, $forma2, $idCliente, $idusuario, $correlativo, $numeroControlDte, $uuid, $codPuntoVentaMH);
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
    public function actualizarStock($cantidad, $ventas, $idProducto)
    {
        $sql = "UPDATE productos SET cantidad = ?, ventas=? WHERE id = ?";
        $array = array($cantidad, $ventas, $idProducto);
        return $this->save($sql, $array);
    }
    public function registrarCredito($monto, $fecha, $hora, $idVenta)
    {
        $sql = "INSERT INTO creditos (monto, fecha, hora, id_venta) VALUES (?,?,?,?)";
        $array = array($monto, $fecha, $hora, $idVenta);
        return $this->insertar($sql, $array);
    }
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }

    public function getVenta($idVenta)
    {
        $sql = "SELECT v.*, c.identidad, c.num_identidad, c.nombre, c.telefono, c.direccion, c.departamento, c.municipio, c.actividad, c.correo FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.id = $idVenta";
        return $this->select($sql);
    } 

    public function getVentas()
    {
        $sql = "SELECT v.*, c.nombre FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id where v.docuemi = 'Nota de credito'";
        return $this->selectAll($sql);
    }
	 public function getClientes()
    {
        $sql = "select * from clientes";
        return $this->selectAll($sql);
    }

    public function anular($idVenta)
    {
        $sql = "UPDATE ventas SET estado = ? WHERE id = ?";
        $array = array(0, $idVenta);
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
	
	public function getMaxCorrelativo($tipoFactura)
    {
        $sql = "SELECT MAX(correlativo) AS correlativo FROM ventas where docuemi = '$tipoFactura'";
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
	
	 public function getCodPuntoVenta($idUsuario)
    {
        $sql = "select codPuntoVentaMH from usuarios where id = $idUsuario";
        return $this->select($sql);
    }
	
}


?>