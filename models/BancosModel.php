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

    public function insertarBanco($nombre, $numero_cuenta, $cuenta_contable, $pos)
    {
        $sql = "INSERT INTO bancos (nombre, numero_cuenta, cuenta_contable, pos) VALUES (?,?,?,?)";
        $array = array($nombre, $numero_cuenta, $cuenta_contable, $pos);
        return $this->insertar($sql, $array);
    }

    public function actualizarBanco($id, $nombre, $numero_cuenta, $cuenta_contable, $pos)
    {
        $sql = "UPDATE bancos SET nombre=?, numero_cuenta=?, cuenta_contable=?, pos=? WHERE id=?";
        $array = array($nombre, $numero_cuenta, $cuenta_contable, $pos, $id);
        return $this->save($sql, $array);
    }

    public function eliminarBanco($id)
    {
        $sql = "UPDATE bancos SET estado=0 WHERE id=?";
        $array = array($id);
        return $this->save($sql, $array);
    }
    
    // Proveedores
    public function getProveedores()
    {
        $sql = "SELECT id, ruc, nombre FROM proveedor WHERE estado = 1 ORDER BY nombre";
        return $this->selectAll($sql);
    }
    
    // Cuentas Bancarias
    public function getAllCuentasBancarias()
    {
        $sql = "SELECT cb.*, b.nombre as nombre_banco, cc.nombre_cuenta, p.nombre as nombre_propietario, p.ruc
                FROM cuentas_bancarias cb 
                LEFT JOIN bancos b ON cb.banco_id = b.id 
                LEFT JOIN cuentas_contables cc ON cb.cuenta_contable = cc.codigo 
                LEFT JOIN proveedor p ON cb.`proveedor_id` = p.id
                WHERE cb.estado = 1 
                ORDER BY cb.id DESC";
        return $this->selectAll($sql);
    }
    
    public function getCuentaBancariaById($id)
    {
        $sql = "SELECT cb.*, b.nombre as nombre_banco, cc.nombre_cuenta, p.nombre as nombre_propietario, p.ruc
                FROM cuentas_bancarias cb 
                LEFT JOIN bancos b ON cb.banco_id = b.id 
                LEFT JOIN cuentas_contables cc ON cb.cuenta_contable = cc.codigo 
                LEFT JOIN proveedor p ON cb.`proveedor_id` = p.id
                WHERE cb.id = $id";
        return $this->select($sql);
    }
    
    public function insertarCuentaBancaria($datos)
    {
        $sql = "INSERT INTO cuentas_bancarias 
                (banco_id, numero_cuenta, cuenta_contable, `proveedor_id`, tipo_cuenta) 
                VALUES (?, ?, ?, ?, ?)";
        $array = array(
            $datos['banco_id'],
            $datos['numero_cuenta'],
            $datos['cuenta_contable'],
            $datos['propietario_id'],
            $datos['tipo_cuenta']
        );
        return $this->insertar($sql, $array);
    }
    
    public function actualizarCuentaBancaria($id, $datos)
    {
        $sql = "UPDATE cuentas_bancarias SET 
                banco_id=?, numero_cuenta=?, cuenta_contable=?, `proveedor_id`=?, tipo_cuenta=? 
                WHERE id=?";
        $array = array(
            $datos['banco_id'],
            $datos['numero_cuenta'],
            $datos['cuenta_contable'],
            $datos['propietario_id'],
            $datos['tipo_cuenta'],
            $id
        );
        return $this->save($sql, $array);
    }
    
    public function eliminarCuentaBancaria($id)
    {
        $sql = "UPDATE cuentas_bancarias SET estado=0 WHERE id=?";
        $array = array($id);
        return $this->save($sql, $array);
    }
}
