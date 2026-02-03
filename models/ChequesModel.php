<?php
class ChequesModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBancos()
    {
        $sql = "SELECT * FROM bancos WHERE estado = 1 ORDER BY nombre";
        return $this->selectAll($sql);
    }

    public function getProveedores()
    {
        $sql = "SELECT id, ruc, nombre, telefono, correo FROM proveedor WHERE estado = 1 ORDER BY nombre";
        return $this->selectAll($sql);
    }

    public function buscarProveedor($term)
    {
        $term = strClean($term);
        $sql = "SELECT id, ruc, nombre, telefono, correo, direccion FROM proveedor WHERE estado = 1 AND (nombre LIKE '%$term%' OR ruc LIKE '%$term%') LIMIT 10";
        return $this->selectAll($sql);
    }

    public function getCheques()
    {
        $sql = "SELECT ch.*, b.nombre as banco, cb.numero_cuenta as numero_cuenta_bancaria, p.nombre as proveedor, p.ruc, 
                       u.nombre as usuario, ua.nombre as anulado_por_nombre
                FROM cheques ch 
                INNER JOIN bancos b ON ch.id_banco = b.id 
                INNER JOIN proveedor p ON ch.id_proveedor = p.id 
                INNER JOIN usuarios u ON ch.id_usuario = u.id
                LEFT JOIN usuarios ua ON ch.anulado_por = ua.id
                LEFT JOIN cuentas_bancarias cb ON cb.banco_id = ch.id_banco AND cb.proveedor_id = ch.id_proveedor AND cb.estado = 1
                ORDER BY ch.id DESC";
        return $this->selectAll($sql);
    }

    public function getCheque($id)
    {
        $sql = "SELECT ch.*, b.nombre as banco, cb.numero_cuenta as numero_cuenta_bancaria, p.nombre as proveedor, p.ruc, u.nombre as usuario, 
                       ua.nombre as anulado_por_nombre
                FROM cheques ch 
                INNER JOIN bancos b ON ch.id_banco = b.id 
                INNER JOIN proveedor p ON ch.id_proveedor = p.id 
                INNER JOIN usuarios u ON ch.id_usuario = u.id
                LEFT JOIN usuarios ua ON ch.anulado_por = ua.id
                LEFT JOIN cuentas_bancarias cb ON cb.banco_id = ch.id_banco AND cb.proveedor_id = ch.id_proveedor AND cb.estado = 1
                WHERE ch.id = $id";
        return $this->select($sql);
    }

    public function getDetalleCheque($idCheque)
    {
        $sql = "SELECT * FROM detalle_cheque WHERE id_cheque = $idCheque";
        return $this->selectAll($sql);
    }

    public function getSiguienteCorrelativo($idBanco)
    {
        try {
            $sql = "SELECT * FROM correlativos_cheques WHERE id_banco = $idBanco";
            $correlativo = $this->select($sql);
            
            if (empty($correlativo)) {
                $sqlInsert = "INSERT INTO correlativos_cheques (id_banco, correlativo_actual, prefijo, longitud) VALUES ($idBanco, 1, '', 8)";
                $this->insertar($sqlInsert, array());
                return array('prefijo' => '', 'correlativo' => 1, 'longitud' => 8);
            }
            return $correlativo;
        } catch (Exception $e) {
            return array('prefijo' => '', 'correlativo' => 1, 'longitud' => 8);
        }
    }

    public function registrarCheque($datos)
    {
        $sql = "INSERT INTO cheques (numero_cheque, id_banco, id_proveedor, concepto, monto, fecha_emision, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->insertar($sql, $datos);
    }

    public function registrarDetalleCheque($datos)
    {
        $sql = "INSERT INTO detalle_cheque (id_cheque, cuenta_contable, tipo, monto, concepto) VALUES (?, ?, ?, ?, ?)";
        return $this->insertar($sql, $datos);
    }

    public function actualizarCorrelativo($idBanco)
    {
        $sql = "UPDATE correlativos_cheques SET correlativo_actual = correlativo_actual + 1 WHERE id_banco = $idBanco";
        return $this->save($sql, array());
    }

    public function anularCheque($id, $motivo, $anulado_por)
    {
        $sql = "UPDATE cheques SET estado = 'anulado', motivo_anulacion = ?, fecha_anulacion = NOW(), anulado_por = ? WHERE id = $id";
        return $this->save($sql, array($motivo, $anulado_por));
    }

    public function getTotalDebe($idCheque)
    {
        $sql = "SELECT SUM(monto) as total FROM detalle_cheque WHERE id_cheque = $idCheque AND tipo = 'Debe'";
        $result = $this->select($sql);
        return $result['total'] ?? 0;
    }

    public function getTotalHaber($idCheque)
    {
        $sql = "SELECT SUM(monto) as total FROM detalle_cheque WHERE id_cheque = $idCheque AND tipo = 'Haber'";
        $result = $this->select($sql);
        return $result['total'] ?? 0;
    }

    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }

    public function getCuentasBancariasProveedor($proveedor_id)
    {
        $sql = "SELECT cb.id, cb.numero_cuenta, cb.cuenta_contable, b.nombre as banco, 
                       b.id as banco_id, cb.tipo_cuenta
                FROM cuentas_bancarias cb
                INNER JOIN bancos b ON cb.banco_id = b.id
                WHERE cb.proveedor_id = $proveedor_id
                  AND cb.estado = 1
                ORDER BY cb.id DESC
                LIMIT 1";
        return $this->select($sql);
    }
}
