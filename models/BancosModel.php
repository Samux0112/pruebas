<?php
class BancosModel extends Query {
    public function __construct() {
        parent::__construct();
    }

    // Partidas contables para dropdown
    public function getPartidasContables()
    {
        $sql = "SELECT id, nombre FROM tipo_partida ORDER BY id";
        return $this->selectAll($sql);
    }

    // Cuentas contables del catálogo
    public function getCuentasContables()
    {
        $sql = "SELECT codigo, nombre_cuenta FROM cuentas_contables ORDER BY codigo";
        return $this->selectAll($sql);
    }

    // Tipos de Transacción
    public function getAllTransaccion()
    {
        $sql = "SELECT t.*, tp.nombre as nombre_partida 
                FROM tipo_transaccion t 
                LEFT JOIN tipo_partida tp ON t.tipo_partida_contable = tp.id 
                WHERE t.estado = 1 
                ORDER BY t.id DESC";
        return $this->selectAll($sql);
    }

    public function getTransaccionById($id)
    {
        $sql = "SELECT * FROM tipo_transaccion WHERE id = $id";
        return $this->select($sql);
    }

    public function insertarTransaccion($nombre, $tipo_partida, $tipo_transaccion)
    {
        $sql = "INSERT INTO tipo_transaccion (nombre, tipo_partida_contable, tipo_transaccion) VALUES (?,?,?)";
        $array = array($nombre, $tipo_partida, $tipo_transaccion);
        return $this->insertar($sql, $array);
    }

    public function actualizarTransaccion($id, $nombre, $tipo_partida, $tipo_transaccion)
    {
        $sql = "UPDATE tipo_transaccion SET nombre=?, tipo_partida_contable=?, tipo_transaccion=? WHERE id=?";
        $array = array($nombre, $tipo_partida, $tipo_transaccion, $id);
        return $this->save($sql, $array);
    }

    public function eliminarTransaccion($id)
    {
        $sql = "UPDATE tipo_transaccion SET estado=0 WHERE id=?";
        $array = array($id);
        return $this->save($sql, $array);
    }

    // Bancos
    public function getAllBancos()
    {
        $sql = "SELECT b.*, c.nombre_cuenta 
                FROM bancos b 
                LEFT JOIN cuentas_contables c ON b.cuenta_contable = c.codigo 
                WHERE b.estado = 1 
                ORDER BY b.id DESC";
        return $this->selectAll($sql);
    }

    public function getBancoById($id)
    {
        $sql = "SELECT b.*, c.nombre_cuenta 
                FROM bancos b 
                LEFT JOIN cuentas_contables c ON b.cuenta_contable = c.codigo 
                WHERE b.id = $id";
        return $this->select($sql);
    }

    public function insertarBanco($nombre, $numero_cuenta, $cuenta_contable, $pos, $correlativo_cheque)
    {
        $sql = "INSERT INTO bancos (nombre, numero_cuenta, cuenta_contable, pos, correlativo_cheque) VALUES (?,?,?,?,?)";
        $array = array($nombre, $numero_cuenta, $cuenta_contable, $pos, $correlativo_cheque);
        return $this->insertar($sql, $array);
    }

    public function actualizarBanco($id, $nombre, $numero_cuenta, $cuenta_contable, $pos, $correlativo_cheque)
    {
        $sql = "UPDATE bancos SET nombre=?, numero_cuenta=?, cuenta_contable=?, pos=?, correlativo_cheque=? WHERE id=?";
        $array = array($nombre, $numero_cuenta, $cuenta_contable, $pos, $correlativo_cheque, $id);
        return $this->save($sql, $array);
    }

    public function eliminarBanco($id)
    {
        $sql = "UPDATE bancos SET estado=0 WHERE id=?";
        $array = array($id);
        return $this->save($sql, $array);
    }
}
