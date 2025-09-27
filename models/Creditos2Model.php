<?php
class Creditos2Model extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getCreditos2()
    {
        $sql = "SELECT cr.*, cl.nombre FROM creditos2 cr INNER JOIN ventas2 v ON cr.id_venta2 = v.id INNER JOIN clientes2 cl ON v.id_cliente2 = cl.id";
        return $this->selectAll($sql);
    }
    public function getAbono2($idCredito2)
    {
        $sql = "SELECT SUM(abono2) AS total FROM abonos2 WHERE id_credito2 = $idCredito2";
        return $this->select($sql);
    }

    public function buscarPorNombre($valor)
    {
        $sql = "SELECT cr.*, cl.nombre, cl.telefono, cl.direccion FROM creditos2 cr INNER JOIN ventas2 v ON cr.id_venta2 = v.id INNER JOIN clientes2 cl ON v.id_cliente2 = cl.id WHERE cl.nombre LIKE '%".$valor."%' AND cr.estado = 1 LIMIT 10";
        return $this->selectAll($sql);
    }

    public function registrarAbono2($monto, $idCredito2, $id_usuario)
    {
        $sql = "INSERT INTO abonos2 (abono2, id_credito2, id_usuario) VALUES (?,?,?)";
        $array = array($monto, $idCredito2, $id_usuario);
        return $this->insertar($sql, $array);
    }
    public function getCredito2($idCredito2)
    {
        $sql = "SELECT cr.*, v.productos, cl.identidad, cl.num_identidad, cl.nombre, cl.telefono, cl.direccion FROM creditos2 cr INNER JOIN ventas2 v ON cr.id_venta2 = v.id INNER JOIN clientes2 cl ON v.id_cliente2 = cl.id WHERE cr.id = $idCredito2";
        return $this->select($sql);
    }

    public function actualizarCredito2($estado, $idCredito2)
    {
        $sql = "UPDATE creditos2 SET estado = ? WHERE id = ?";
        $array = array($estado, $idCredito2);
        return $this->save($sql, $array);
    }

    public function getAbonos2($idCredito2)
    {
        $sql = "SELECT * FROM abonos2 WHERE id_credito2 = $idCredito2";
        return $this->selectAll($sql);
    }

    public function getHistorialAbonos2()
    {
        $sql = "SELECT * FROM abonos2";
        return $this->selectAll($sql);
    }

    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }
}

?>