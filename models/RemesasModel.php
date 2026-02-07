<?php
/**
 * Modelo para Remesas y Transferencias
 */
class RemesasModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene todas las remesas
     */
    public function getRemesas()
    {
        $sql = "SELECT r.*, b.nombre as banco_nombre, b.cuenta_contable,
                (SELECT COUNT(*) FROM detalle_remesa WHERE id_remesa = r.id) as num_detalles
                FROM remesas r 
                LEFT JOIN bancos b ON r.id_banco = b.id
                ORDER BY r.id DESC";
        return $this->selectAll($sql);
    }

    /**
     * Obtiene una remesa por ID
     */
    public function getRemesa($id)
    {
        $sql = "SELECT r.*, b.nombre as banco_nombre, b.cuenta_contable as banco_cuenta
                FROM remesas r 
                LEFT JOIN bancos b ON r.id_banco = b.id 
                WHERE r.id = $id";
        return $this->select($sql);
    }

    /**
     * Obtiene detalle de una remesa
     */
    public function getDetalleRemesa($idRemesa)
    {
        $sql = "SELECT d.*, c.nombre_cuenta 
                FROM detalle_remesa d 
                LEFT JOIN cuentas_contables c ON d.cuenta_contable = c.codigo 
                WHERE d.id_remesa = $idRemesa";
        return $this->selectAll($sql);
    }

    /**
     * Registra una nueva remesa
     */
    public function registrarRemesa($datos)
    {
        $sql = "INSERT INTO remesas (id_banco, tipo_transaccion, concepto, tipo_partida_remesa, monto, fecha_creacion, estado, registrado_por) VALUES (?, ?, ?, ?, ?, NOW(), '1', ?)";
        return $this->insertar($sql, $datos);
    }

    /**
     * Registra detalle de remesa
     */
    public function registrarDetalleRemesa($datos)
    {
        $sql = "INSERT INTO detalle_remesa (id_remesa, cuenta_contable, tipo, monto, concepto) VALUES (?, ?, ?, ?, ?)";
        return $this->insertar($sql, $datos);
    }

    /**
     * Anula una remesa
     */
    public function anularRemesa($id, $motivo, $idUsuario)
    {
        $sql = "UPDATE remesas SET estado = 'anulado', motivo_anulacion = '$motivo', 
                fecha_anulacion = NOW(), anulado_por = $idUsuario WHERE id = $id";
        return $this->update($sql);
    }

    /**
     * Obtiene el correlativo de remesa
     */
    public function getCorrelativo()
    {
        $sql = "SELECT MAX(id) as max_id FROM remesas";
        $result = $this->select($sql);
        return $result['max_id'] + 1;
    }

    /**
     * Obtiene los bancos
     */
    public function getBancos()
    {
        $sql = "SELECT id, nombre, cuenta_contable FROM bancos WHERE estado = 1 ORDER BY nombre";
        return $this->selectAll($sql);
    }

    /**
     * Obtiene las cuentas contables
     */
    public function getCuentasContables($search = '')
    {
        $search = strClean($search);
        $sql = "SELECT codigo, nombre_cuenta FROM cuentas_contables 
                WHERE estado = 1 AND (codigo LIKE '%$search%' OR nombre_cuenta LIKE '%$search%')
                ORDER BY codigo LIMIT 20";
        return $this->selectAll($sql);
    }

    /**
     * Obtiene datos de la empresa
     */
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion WHERE nombre = 'datos_empresa' LIMIT 1";
        $result = $this->select($sql);
        if ($result) {
            $valor = json_decode($result['valor'] ?? '{}', true);
            if (is_array($valor)) {
                return $valor;
            }
        }
        return [
            'nombre' => 'EMPRESA',
            'direccion' => '',
            'telefono' => '',
            'email' => ''
        ];
    }
}
