<?php
class OrdenesCompraModel extends Query{
        public function getEmpresa()
        {
            $sql = "SELECT * FROM configuracion LIMIT 1";
            return $this->select($sql);
        }
    public function __construct() {
        parent::__construct();
    }
    public function getProducto($idProducto)
    {
        $sql = "SELECT * FROM productos WHERE id = $idProducto";
        return $this->select($sql);
    }
    public function registrarOrden($productos, $total, $fecha, $hora, $idUsuario, $idProveedor = null, $proveedor, $cotizacion = null, $observaciones = null, $requisicion_id = null)
    {
        // Agregar campo estado con valor 'generada' por defecto
        $sql = "INSERT INTO ordenes_compra (productos, total, fecha, hora, id_proveedor, nombreProveedor, id_usuario, cotizacion, observaciones, requisicion_id, estado) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $idProveedor, $proveedor, $idUsuario, $cotizacion, $observaciones, $requisicion_id, 'generada');
        return $this->insertar($sql, $array);
    }
    public function getOrden($idOrden)
    {
        $sql = "SELECT o.*, p.nombre as proveedor, u.nombre as usuario FROM ordenes_compra o LEFT JOIN proveedor p ON o.id_proveedor = p.id LEFT JOIN usuarios u ON o.id_usuario = u.id WHERE o.id = $idOrden";
        return $this->select($sql);
    }
    public function getOrdenes()
    {
        $sql = "SELECT o.*, p.nombre as proveedor, u.nombre as usuario FROM ordenes_compra o LEFT JOIN proveedor p ON o.id_proveedor = p.id LEFT JOIN usuarios u ON o.id_usuario = u.id ORDER BY o.created_at DESC";
        return $this->selectAll($sql);
    }
    public function actualizarEstadoOrden($id, $estado)
    {
        $sql = "UPDATE ordenes_compra SET estado = ? WHERE id = ?";
        $array = array($estado, $id);
        return $this->save($sql, $array);
    }
}
?>