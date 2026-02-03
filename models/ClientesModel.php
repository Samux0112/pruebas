<?php
class ClientesModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getClientes($estado)
    {
        $sql = "SELECT * FROM clientes WHERE estado = $estado";
        return $this->selectAll($sql);
    }
	
	public function getCuentaContable($id) 
    {
        $sql = "SELECT id FROM cuentas_contables where codigo = $id";
        return $this->select($sql);
    }
	
	public function getCuentas()
    {
        $sql = "SELECT * FROM cuentas_contables";
        return $this->selectAll($sql);
    }
	
	public function registrarCuenta_Cliente($cuentaContable, $id_cliente)
    {
        $sql = "INSERT INTO detalle_cuentas_clientes (id_cuenta, id_cliente) VALUES (?,?)";
        $array = array($cuentaContable, $id_cliente);
        return $this->insertar($sql, $array);
    }
	
	public function eliminar_Cuenta_Cliente($id)
    {
        $sql = "DELETE from detalle_cuentas_clientes WHERE id_cliente = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }
	
    public function registrar($identidad, $num_identidad, $DUI, $nombre, $telefono, $correo, $direccion, $departamento, $municipio, $actividad, $pais, $actividadExportacion, $chekExento, $contribuyente)
    {
        $sql = "INSERT INTO clientes (identidad, num_identidad, DUI, nombre, telefono, correo, direccion, departamento, municipio, actividad, pais, actividadExportacion, exento, contribuyente) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($identidad, $num_identidad, $DUI, $nombre,$telefono, $correo, $direccion, $departamento, $municipio, $actividad, $pais, $actividadExportacion, $chekExento, $contribuyente);
        return $this->insertar($sql, $array);
    }

    public function getValidar($campo, $valor, $accion, $id)
    {
        if ($accion == 'registrar' && $id == 0) {
            $sql = "SELECT id FROM clientes WHERE $campo = '$valor'";
        }else{
            $sql = "SELECT id FROM clientes WHERE $campo = '$valor' AND id != $id";
        }
        return $this->select($sql);
    }

    public function eliminar($estado, $idCliente)
    {
        $sql = "UPDATE clientes SET estado = ? WHERE id = ?";
        $array = array($estado, $idCliente);
        return $this->save($sql, $array);
    }
	public function validarCuentaContable($idCliente)
    {
        $sql = "SELECT count(id) as total from detalle_cuentas_clientes where id_cliente = $idCliente";
        return $this->select($sql);
    }
	
    public function editar($idCliente,$total)
    {
		if($total>0){
		$sql = "SELECT cl.*,cu.codigo,cu.nombre_cuenta FROM detalle_cuentas_clientes dt join clientes cl on dt.id_cliente = cl.id join cuentas_contables cu on dt.id_cuenta = cu.id  WHERE cl.id = $idCliente";	
		}else{
			$sql = "SELECT *from clientes where id = $idCliente";
		}
        
        return $this->select($sql);
    }

    public function actualizar($identidad, $num_identidad, $DUI, $nombre,
    $telefono, $correo, $direccion, $id, $departamento, $municipio, $actividad, $pais, $actividadExportacion, $chekExento, $contribuyente)
    {
        $sql = "UPDATE clientes SET identidad=?, num_identidad=?, DUI=?, nombre=?, telefono=?, correo=?, direccion=?, departamento=?, municipio=?, actividad=?, pais=?, actividadExportacion=?, exento=?, contribuyente=? WHERE id=?";
        $array = array($identidad, $num_identidad, $DUI, $nombre,
        $telefono, $correo, $direccion, $departamento, $municipio, $actividad, $pais, $actividadExportacion, $chekExento, $contribuyente, $id);
        return $this->save($sql, $array);
    }
	
	public function actualizar_Cuenta_Cliente($cuentaContable, $id_cliente)
    {
        $sql = "UPDATE detalle_cuentas_clientes SET id_cuenta=? WHERE id_cliente=?";
        $array = array($cuentaContable, $id_cliente);
        return $this->save($sql, $array);
    }

    public function buscarPorId($id)
    {
        $sql = "select * from clientes where id = $id";
        return $this->selectAll($sql);
    }
	    public function buscarPorNombre($valor)
    {
        $sql = "SELECT id, nombre, telefono, direccion, contribuyente FROM clientes WHERE nombre LIKE '%".$valor."%' AND estado = 1 LIMIT 10";
        return $this->selectAll($sql);
    }
	
	public function getMaxCorrelativo($tipoFactura)
    {
        $sql = "SELECT numeroControlDte FROM ventas where id = 77";
        return $this->select($sql);
    }
	
	    public function getCodPuntoVenta($idUsuario)
    {
        $sql = "select codPuntoVentaMH from usuarios where id = $idUsuario";
        return $this->select($sql);
    }
	
}

?>