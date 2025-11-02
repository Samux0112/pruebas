<?php
class RequisicionesModel extends Query{
    public function getCotizacionesRequisicion($idRequisicion)
    {
        $sql = "SELECT * FROM cotizaciones_requisicion WHERE id_requisicion = ? ORDER BY fecha DESC";
        return $this->select($sql, [$idRequisicion]);
    }

    public function getCotizacionById($idCotizacion)
    {
        $sql = "SELECT * FROM cotizaciones_requisicion WHERE id = ?";
        return $this->select($sql, [$idCotizacion]);
    }

    public function getProductosCotizacion($idCotizacion)
    {
        $sql = "SELECT * FROM cotizaciones_productos WHERE id_cotizacion = ?";
        return $this->selectAll($sql, [$idCotizacion]);
    }
    public function guardarCotizacion($idRequisicion, $proveedor, $monto, $detalle, $productos)
    {
        // Guardar cotización principal
        $sql = "INSERT INTO cotizaciones_requisicion (id_requisicion, proveedor, monto, detalle) VALUES (?,?,?,?)";
        $array = array($idRequisicion, $proveedor, $monto, $detalle);
        $idCotizacion = $this->insertar($sql, $array);
        if ($idCotizacion > 0) {
            // Guardar productos/ofertas asociadas
            foreach ($productos as $prod) {
                $sqlProd = "INSERT INTO cotizaciones_productos (id_cotizacion, nombre, cantidad, descripcion, precio, descuento, subtotal) VALUES (?,?,?,?,?,?,?)";
                $arrProd = array($idCotizacion, $prod['nombre'], $prod['cantidad'], $prod['descripcion'], $prod['precio'], $prod['descuento'], $prod['subtotal']);
                $this->insertar($sqlProd, $arrProd);
            }
            return true;
        }
        return false;
    }
    public function getProveedores($estado = 1)
    {
        $sql = "SELECT * FROM clientes2 WHERE estado = $estado";
        return $this->selectAll($sql);
    }
    public function __construct() {
        parent::__construct();
    }
    public function getProducto($idProducto)
    {
        $sql = "SELECT * FROM productos WHERE id = $idProducto";
        return $this->select($sql);
    }
    public function registrarRequisicion($productos, $total, $fecha, $hora, $idUsuario, $observaciones = null)
    {
        $sql = "INSERT INTO requisiciones (productos, total, fecha, hora, id_usuario, observaciones) VALUES (?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $idUsuario, $observaciones);
        return $this->insertar($sql, $array);
    }
    public function getRequisicion($id)
    {
        $sql = "SELECT r.*, u.nombre as solicitante FROM requisiciones r LEFT JOIN usuarios u ON r.id_usuario = u.id WHERE r.id = $id";
        return $this->select($sql);
    }
    public function getRequisiciones()
    {
        $sql = "SELECT r.*, u.nombre as solicitante FROM requisiciones r LEFT JOIN usuarios u ON r.id_usuario = u.id ORDER BY r.created_at DESC";
        return $this->selectAll($sql);
    }
    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE requisiciones SET estado = ? WHERE id = ?";
        $array = array($estado, $id);
        return $this->save($sql, $array);
    }
}
?>