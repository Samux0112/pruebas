<?php
class Clientes2Model extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getClientes2($estado)
    {
        $sql = "SELECT * FROM clientes2 WHERE estado = $estado";
        return $this->selectAll($sql);
    }
	public function buscarPorId($id)
    {
        $sql = "select * from clientes2 where id = $id";
        return $this->selectAll($sql);
    }
	
    public function registrar($identidad, $num_identidad, $DUI, $nombre,
    $telefono, $correo, $direccion, $departamentoCliente, $municipioCliente, $actividad, $chekExento, $contribuyente)
    {
        $sql = "INSERT INTO clientes2 (identidad, num_identidad, DUI, nombre, telefono, correo, direccion, departamento, municipio, actividad, exento, contribuyente) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($identidad, $num_identidad, $DUI, $nombre,
        $telefono, $correo, $direccion, $departamentoCliente, $municipioCliente, $actividad, $chekExento, $contribuyente);
        return $this->insertar($sql, $array);
    }
	
	public function getCuentaContable($id) 
    {
        $sql = "SELECT id FROM cuentas_contables where codigo = $id";
        return $this->select($sql);
    }
	
	public function registrarCuenta_Cliente2($cuentaContable, $id_cliente)
    {
        $sql = "INSERT INTO detalle_cuentas_clientes2 (id_cuenta, id_cliente) VALUES (?,?)";
        $array = array($cuentaContable, $id_cliente);
        return $this->insertar($sql, $array);
    }
	
	public function validarCuentaContable($idCliente)
    {
        $sql = "SELECT count(id) as total from detalle_cuentas_clientes2 where id_cliente = $idCliente";
        return $this->select($sql);
    }
	
	public function actualizar_Cuenta_Cliente($cuentaContable, $id_cliente)
    {
        $sql = "UPDATE detalle_cuentas_clientes2 SET id_cuenta=? WHERE id_cliente=?";
        $array = array($cuentaContable, $id_cliente);
        return $this->save($sql, $array);
    }
	
	public function eliminar_Cuenta_Cliente($id)
    {
        $sql = "DELETE from detalle_cuentas_clientes2 WHERE id_cliente = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }

    public function getValidar($campo, $valor, $accion, $id)
    {
        if ($accion == 'registrar' && $id == 0) {
            $sql = "SELECT id FROM clientes2 WHERE $campo = '$valor'";
        }else{
            $sql = "SELECT id FROM clientes2 WHERE $campo = '$valor' AND id != $id";
        }
        return $this->select($sql);
    }

    public function eliminar($estado, $idCliente2)
    {
        $sql = "UPDATE clientes2 SET estado = ? WHERE id = ?";
        $array = array($estado, $idCliente2);
        return $this->save($sql, $array);
    }
   public function editar($idCliente,$total)
    {
		if($total>0){
		$sql = "SELECT cl.*,cu.codigo,cu.nombre_cuenta FROM detalle_cuentas_clientes2 dt join clientes2 cl on dt.id_cliente = cl.id join cuentas_contables cu on dt.id_cuenta = cu.id  WHERE cl.id = $idCliente";	
		}else{
			$sql = "SELECT *from clientes2 where id = $idCliente";
		}
        
        return $this->select($sql);
    }

    public function actualizar($identidad, $num_identidad, $DUI, $nombre,
    $telefono, $correo, $direccion, $id, $departamentoCliente, $municipioCliente, $actividad, $chekExento, $contribuyente)
    {
        $sql = "UPDATE clientes2 SET identidad=?, num_identidad=?, DUI=?, nombre=?, telefono=?, correo=?, direccion=?, departamento=?, municipio=?, actividad=?, exento=?, contribuyente=?  WHERE id=?";
        $array = array($identidad, $num_identidad, $DUI, $nombre,
        $telefono, $correo, $direccion, $departamentoCliente, $municipioCliente, $actividad, $chekExento, $contribuyente, $id);
        return $this->save($sql, $array);
    }

    public function buscarPorNombre($valor)
    {
        $sql = "SELECT id, nombre, telefono, direccion FROM clientes2 WHERE nombre LIKE '%".$valor."%' AND estado = 1 LIMIT 10";
        return $this->selectAll($sql);
    }
	
	public function getCodPuntoVenta($idUsuario)
    {
        $sql = "select codPuntoVentaMH from usuarios where id = $idUsuario";
        return $this->select($sql);
    }
}

?>