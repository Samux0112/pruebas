<?php
class Cotizaciones2Model extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getProducto($idProducto)
    {
        $sql = "SELECT * FROM productos WHERE id = $idProducto";
        return $this->select($sql);
    }
    public function registrarCotizacion2($productos, $total, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $numfac, $numorden, $sucursal, $opticli, $fecha1, $fechaentre)
    {
        $sql = "INSERT INTO cotizaciones2 (productos, total, fecha, hora, metodo, validez, descuento, id_cliente, numfac, numorden, sucursal, opticli, fecha1, fechaentre) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $array = array($productos, $total, $fecha, $hora, $metodo, $validez, $descuento, $idCliente, $numfac, $numorden, $sucursal,$opticli, $fecha1,  $fechaentre);
        return $this->insertar($sql, $array);
    }
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }
    public function getCotizacion2($idCotizacion2)
    {
        $sql = "SELECT ct.*, cl.identidad, cl.num_identidad, cl.nombre, cl.telefono, cl.direccion FROM cotizaciones2 ct INNER JOIN clientes cl ON ct.id_cliente = cl.id WHERE ct.id = $idCotizacion2";
        return $this->select($sql);
    }

    public function getCotizaciones2()
    {
        $sql = "SELECT ct.*, cl.nombre FROM cotizaciones2 ct INNER JOIN clientes cl ON ct.id_cliente = cl.id";
        return $this->selectAll($sql);
    }
}

?>