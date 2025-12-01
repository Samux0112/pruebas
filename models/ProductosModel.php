<?php
class ProductosModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getProductos($estado)
{
$sql = "SELECT p.*, m.medida,m.nombre_corto, c.categoria,s.cantidad as stock FROM productos p INNER JOIN medidas m
ON p.id_medida = m.id INNER JOIN categorias c ON p.id_categoria = c.id join Stock s on p.id = s.idProducto WHERE p.estado = $estado and s.idBodega=1";
return $this->selectAll($sql);
}

	public function getProductosPorBodega($estado,$bodega)
	{
	$sql = "SELECT p.*, m.medida,m.nombre_corto, c.categoria,s.cantidad as stock FROM productos p INNER JOIN medidas m
	ON p.id_medida = m.id INNER JOIN categorias c ON p.id_categoria = c.id join Stock s on p.id = s.idProducto WHERE p.estado = $estado and s.idBodega=$bodega";
	return $this->selectAll($sql);
	}

        public function getTipoProducto()
    {
        $sql = "SELECT * FROM tipoProducto";
        return $this->selectAll($sql);
    }
    
    public function getProductosUni($id)
    {
        $sql = "SELECT p.*, m.medida, c.categoria FROM productos p INNER JOIN medidas m ON p.id_medida = m.id INNER JOIN categorias c ON p.id_categoria = c.id WHERE p.estado = 1 AND p.id = $id";
        return $this->selectAll($sql);
    }

    public function getDatos($table)
    {
        $sql = "SELECT * FROM $table WHERE estado = 1";
        return $this->selectAll($sql);
    }

    public function registrar($codigo, $nombre, $precio_venta2, $precio_venta, 
    $id_medida, $id_categoria, $ubi, $foto, $id_tipoProducto)
    {
        $sql = "INSERT INTO productos (codigo, descripcion, precio_venta2, precio_venta, id_medida, id_categoria, ubi, foto, tipoProducto) VALUES (?,?,?,?,?,?,?,?,?)";
        $array = array($codigo, $nombre, $precio_venta2, $precio_venta, 
        $id_medida, $id_categoria, $ubi, $foto, $id_tipoProducto);
        return $this->insertar($sql, $array);
    }
	
	public function getCuentaContable($id) 
    {
        $sql = "SELECT id FROM cuentas_contables where codigo = $id";
        return $this->select($sql);
    }
	
	public function registrarBodega_producto($id_producto)
    {
        $sql = "INSERT INTO Stock (idProducto,idBodega,cantidad) VALUES (?,?,?)";
        $array = array($id_producto, 1,0);
        return $this->insertar($sql, $array);
    }
	
	public function registrarCuenta_producto($cuentaContable, $id_producto)
    {
        $sql = "INSERT INTO detalle_cuentas_productos  (id_cuenta, id_producto) VALUES (?,?)";
        $array = array($cuentaContable, $id_producto);
        return $this->insertar($sql, $array);
    }
	
	public function validarCuentaContable($idProducto)
    {
        $sql = "SELECT count(id) as total from detalle_cuentas_productos where id_producto = $idProducto";
        return $this->select($sql);
    }
	
	public function actualizar_Cuenta_Productos($cuentaContable, $id_producto)
    {
        $sql = "UPDATE detalle_cuentas_productos SET id_cuenta=? WHERE id_producto=?";
        $array = array($cuentaContable, $id_producto);
        return $this->save($sql, $array);
    }
	
	public function eliminar_Cuenta_Producto($id)
    {
        $sql = "DELETE from detalle_cuentas_productos WHERE id_producto = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }

    public function getValidar($campo, $valor, $accion, $id)
    {
        if ($accion == 'registrar' && $id == 0) {
            $sql = "SELECT id FROM productos WHERE $campo = '$valor'";
        }else{
            $sql = "SELECT id FROM productos WHERE $campo = '$valor' AND id != $id";
        }
        return $this->select($sql);
    }

    public function eliminar($estado, $idProducto)
    {
        $sql = "UPDATE productos SET estado = ? WHERE id = ?";
        $array = array($estado, $idProducto);
        return $this->save($sql, $array);
    }

    public function editar($idProducto)
    {
        $sql = "SELECT * FROM productos WHERE id = $idProducto";
        return $this->select($sql);
    }

    public function actualizar($codigo, $nombre, $precio_venta2, $precio_venta, 
    $id_medida, $id_categoria, $ubi, $foto, $id_tipoProducto, $id)
    {
        $sql = "UPDATE productos SET codigo=?, descripcion=?, precio_venta2=?, precio_venta=?, id_medida=?, id_categoria=?, foto=?, ubi=?, tipoProducto=? WHERE id=?";
        $array = array($codigo, $nombre, $precio_venta2, $precio_venta, 
        $id_medida, $id_categoria, $foto, $ubi, $id_tipoProducto, $id);
        return $this->save($sql, $array);
    }

    public function buscarPorCodigo($valor)
{
//$sql = "SELECT id, descripcion, cantidad, precio_venta2, precio_venta FROM productos WHERE codigo ='$valor'AND estado = 1";
$sql = "SELECT p.id, p.descripcion, p.cantidad, p.precio_venta2, p.precio_venta, m.medida,m.nombre_corto
FROM productos p join medidas m on p.id_medida=m.id WHERE p.codigo = '$valor' AND p.estado = 1";
return $this->select($sql);
}

    public function buscarPorNombre($valor)
{
//$sql = "SELECT id, descripcion, cantidad, precio_venta2, precio_venta FROM productos WHERE descripcion LIKE '%".$valor."%' AND estado = 1 LIMIT 10";
$sql = "SELECT p.id, p.descripcion, p.cantidad, p.precio_venta2, p.precio_venta, m.medida,m.nombre_corto
FROM productos p join medidas m on p.id_medida=m.id WHERE p.descripcion LIKE '%".$valor."%' AND
p.estado = 1 LIMIT 10";
return $this->selectAll($sql);
}

 public function buscarPorNombreTraslado($valor)
{
//$sql = "SELECT id, descripcion, cantidad, precio_venta2, precio_venta FROM productos WHERE descripcion LIKE '%".$valor."%' AND estado = 1 LIMIT 10";
$sql = "SELECT p.id, p.descripcion, p.cantidad, p.precio_venta2, p.precio_venta, m.medida,m.nombre_corto
FROM productos p join medidas m on p.id_medida=m.id WHERE p.descripcion LIKE '%".$valor."%' AND
p.estado = 1 LIMIT 10";
return $this->selectAll($sql);
}
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }
    public function getStock($id, $bodega)
{
    if($bodega == "" || $bodega == null){ 
        $bodega = 1;
    }
//$sql = "SELECT id, descripcion, cantidad, precio_venta2, precio_venta FROM productos WHERE codigo ='$valor'AND estado = 1";
$sql = "SELECT cantidad from Stock WHERE idProducto = $id AND idBodega = $bodega";
return $this->select($sql);
}

}
