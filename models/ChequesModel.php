<?php
class ChequesModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBancos()
    {
        $sql = "SELECT id, nombre, numero_cuenta, cuenta_contable, saldo_actual, correlativo_cheque FROM bancos WHERE estado = 1 ORDER BY nombre";
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
        $sql = "SELECT ch.*, b.nombre as banco, b.numero_cuenta as numero_cuenta_bancaria,
                       u.nombre as usuario, ua.nombre as anulado_por_nombre
                FROM cheques ch 
                INNER JOIN bancos b ON ch.id_banco = b.id 
                INNER JOIN usuarios u ON ch.id_usuario = u.id
                LEFT JOIN usuarios ua ON ch.anulado_por = ua.id
                ORDER BY ch.id DESC";
        return $this->selectAll($sql);
    }

    public function getCheque($id)
    {
        $sql = "SELECT ch.*, b.nombre as banco, b.numero_cuenta as numero_cuenta_bancaria,
                       u.nombre as usuario, ua.nombre as anulado_por_nombre
                FROM cheques ch 
                INNER JOIN bancos b ON ch.id_banco = b.id 
                INNER JOIN usuarios u ON ch.id_usuario = u.id
                LEFT JOIN usuarios ua ON ch.anulado_por = ua.id
                WHERE ch.id = $id";
        return $this->select($sql);
    }

    public function getDetalleCheque($idCheque)
    {
        $sql = "SELECT d.*, c.nombre_cuenta 
                FROM detalle_cheque d 
                LEFT JOIN cuentas_contables c ON d.cuenta_contable = c.codigo 
                WHERE d.id_cheque = $idCheque";
        return $this->selectAll($sql);
    }

    public function registrarCheque($datos)
    {
        $sql = "INSERT INTO cheques (numero_cheque, id_banco, proveedor, concepto, monto, fecha_emision, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->insertar($sql, $datos);
    }

    public function buscarProveedorPorNombre($nombre)
    {
        $nombre = strClean($nombre);
        $sql = "SELECT id FROM proveedor WHERE nombre = '$nombre' AND estado = 1 LIMIT 1";
        $result = $this->select($sql);
        return $result ? $result['id'] : false;
    }

    public function crearProveedorSimple($nombre)
    {
        $nombre = strClean($nombre);
        $ruc = 'P' . substr(time(), -10);
        $sql = "INSERT INTO proveedor (ruc, nombre, telefono, correo, direccion, estado) VALUES (?, ?, 'N/A', 'N/A', 'N/A', 1)";
        return $this->insertar($sql, array($ruc, $nombre));
    }

    public function registrarDetalleCheque($datos)
    {
        $sql = "INSERT INTO detalle_cheque (id_cheque, cuenta_contable, tipo, monto, concepto) VALUES (?, ?, ?, ?, ?)";
        return $this->insertar($sql, $datos);
    }

    public function actualizarCorrelativoBanco($idBanco)
    {
        $sql = "UPDATE bancos SET correlativo_cheque = correlativo_cheque + 1 WHERE id = $idBanco";
        return $this->save($sql, array());
    }

    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }

    public function getFondosBanco($idBanco = null)
    {
        if ($idBanco) {
            $sql = "SELECT f.*, b.nombre as banco_nombre, b.numero_cuenta, u.nombre as usuario_nombre
                    FROM fondos_banco f
                    INNER JOIN bancos b ON f.id_banco = b.id
                    INNER JOIN usuarios u ON f.id_usuario = u.id
                    WHERE f.id_banco = $idBanco
                    ORDER BY f.fecha DESC, f.id DESC";
        } else {
            $sql = "SELECT f.*, b.nombre as banco_nombre, b.numero_cuenta, u.nombre as usuario_nombre
                    FROM fondos_banco f
                    INNER JOIN bancos b ON f.id_banco = b.id
                    INNER JOIN usuarios u ON f.id_usuario = u.id
                    ORDER BY f.fecha DESC, f.id DESC";
        }
        return $this->selectAll($sql);
    }

    public function registrarFondo($datos)
    {
        $sql = "INSERT INTO fondos_banco (id_banco, fecha, concepto, monto, tipo, id_usuario) VALUES (?, ?, ?, ?, 'deposito', ?)";
        return $this->insertar($sql, $datos);
    }

    public function registrarMovimientoCheque($datos)
    {
        // Verificar si la tabla tiene el campo referencia
        $sqlCheck = "SHOW COLUMNS FROM fondos_banco LIKE 'referencia'";
        $hasReferencia = $this->select($sqlCheck);
        
        if ($hasReferencia) {
            $sql = "INSERT INTO fondos_banco (id_banco, fecha, concepto, monto, tipo, referencia, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
        } else {
            $sql = "INSERT INTO fondos_banco (id_banco, fecha, concepto, monto, tipo, id_usuario) VALUES (?, ?, ?, ?, ?, ?)";
        }
        return $this->insertar($sql, $datos);
    }

    public function actualizarSaldoBanco($idBanco, $monto)
    {
        $sql = "UPDATE bancos SET saldo_actual = saldo_actual + $monto WHERE id = $idBanco";
        return $this->save($sql, array());
    }

    public function getSaldoBanco($idBanco)
    {
        $sql = "SELECT saldo_actual FROM bancos WHERE id = $idBanco";
        $result = $this->select($sql);
        return $result ? floatval($result['saldo_actual']) : 0;
    }

    public function getSaldosBancos()
    {
        $sql = "SELECT id, nombre, numero_cuenta, saldo_actual, cuenta_contable FROM bancos WHERE estado = 1 ORDER BY nombre";
        return $this->selectAll($sql);
    }

    public function anularCheque($id, $motivo, $anulado_por)
    {
        $sql = "UPDATE cheques SET estado = 'anulado', motivo_anulacion = ?, fecha_anulacion = NOW(), anulado_por = ? WHERE id = $id";
        return $this->save($sql, array($motivo, $anulado_por));
    }
}
