<?php
class CatalogoCuentasModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function registrar($codCuenta, $codigo, $descripcion, $ctaMayor, $nivel, $id_naturaleza, $mayor, $naturaleza)

    {
        $sql = "INSERT INTO cuentas_contables (codigo_cuenta,codigo,nombre_cuenta,cuenta_mayor,nivel,id_naturaleza,mayor,naturaleza) VALUES (?,?,?,?,?,?,?,?)";
        $array = array($codCuenta, $codigo, $descripcion, $ctaMayor, $nivel, $id_naturaleza, $mayor, $naturaleza);
        return $this->insertar($sql, $array);
	
    }
	
	public function registrarPadre($codCuenta, $descripcion)
    { 
        $sql = "INSERT INTO naturaleza_cuentas (codigo_cuenta, nombre) VALUES (?,?)";
        $array = array($codCuenta,$descripcion);
        return $this->insertar($sql, $array);
    }
	
	public function getCuentasPadre() 
    {
        $sql = "select * from naturaleza_cuentas;";
        return $this->selectAll($sql);
    }
	
	public function getCuentasDetalle($id) 
    {
        $sql = "SELECT 
    n1.codigo   AS codigo,
    n1.nombre_cuenta AS nombre_cuenta,
    n1.cuenta_mayor  AS cuenta_mayor,
    n1.mayor         AS mayor,
    n1.naturaleza    AS naturaleza,
    1 AS nivel,
    CONCAT(LPAD(n1.codigo,20,'0')) AS orden
FROM cuentas_contables n1
WHERE (n1.cuenta_mayor IS NULL OR n1.cuenta_mayor = '')
  AND n1.naturaleza = $id

UNION ALL
SELECT 
    n2.codigo,
    CONCAT('   ', n2.nombre_cuenta),
    n2.cuenta_mayor,
    n2.mayor,
    n2.naturaleza,
    2 AS nivel,
    CONCAT(LPAD(n1.codigo,20,'0'), LPAD(n2.codigo,20,'0'))
FROM cuentas_contables n1
JOIN cuentas_contables n2 ON n2.cuenta_mayor = n1.codigo
WHERE (n1.cuenta_mayor IS NULL OR n1.cuenta_mayor = '')
  AND n2.naturaleza = $id

UNION ALL
SELECT 
    n3.codigo,
    CONCAT('      ', n3.nombre_cuenta),
    n3.cuenta_mayor,
    n3.mayor,
    n3.naturaleza,
    3 AS nivel,
    CONCAT(LPAD(n1.codigo,20,'0'), LPAD(n2.codigo,20,'0'), LPAD(n3.codigo,20,'0'))
FROM cuentas_contables n1
JOIN cuentas_contables n2 ON n2.cuenta_mayor = n1.codigo
JOIN cuentas_contables n3 ON n3.cuenta_mayor = n2.codigo
WHERE (n1.cuenta_mayor IS NULL OR n1.cuenta_mayor = '')
  AND n3.naturaleza = $id

UNION ALL
SELECT 
    n4.codigo,
    CONCAT('         ', n4.nombre_cuenta),
    n4.cuenta_mayor,
    n4.mayor,
    n4.naturaleza,
    4 AS nivel,
    CONCAT(LPAD(n1.codigo,20,'0'), LPAD(n2.codigo,20,'0'), LPAD(n3.codigo,20,'0'), LPAD(n4.codigo,20,'0'))
FROM cuentas_contables n1
JOIN cuentas_contables n2 ON n2.cuenta_mayor = n1.codigo
JOIN cuentas_contables n3 ON n3.cuenta_mayor = n2.codigo
JOIN cuentas_contables n4 ON n4.cuenta_mayor = n3.codigo
WHERE (n1.cuenta_mayor IS NULL OR n1.cuenta_mayor = '')
  AND n4.naturaleza = $id

UNION ALL
SELECT 
    n5.codigo,
    CONCAT('            ', n5.nombre_cuenta),
    n5.cuenta_mayor,
    n5.mayor,
    n5.naturaleza,
    5 AS nivel,
    CONCAT(LPAD(n1.codigo,20,'0'), LPAD(n2.codigo,20,'0'), LPAD(n3.codigo,20,'0'), LPAD(n4.codigo,20,'0'), LPAD(n5.codigo,20,'0'))
FROM cuentas_contables n1
JOIN cuentas_contables n2 ON n2.cuenta_mayor = n1.codigo
JOIN cuentas_contables n3 ON n3.cuenta_mayor = n2.codigo
JOIN cuentas_contables n4 ON n4.cuenta_mayor = n3.codigo
JOIN cuentas_contables n5 ON n5.cuenta_mayor = n4.codigo
WHERE (n1.cuenta_mayor IS NULL OR n1.cuenta_mayor = '')
  AND n5.naturaleza = $id

UNION ALL
SELECT 
    n6.codigo,
    CONCAT('               ', n6.nombre_cuenta),
    n6.cuenta_mayor,
    n6.mayor,
    n6.naturaleza,
    6 AS nivel,
    CONCAT(LPAD(n1.codigo,20,'0'), LPAD(n2.codigo,20,'0'), LPAD(n3.codigo,20,'0'), LPAD(n4.codigo,20,'0'), LPAD(n5.codigo,20,'0'), LPAD(n6.codigo,20,'0'))
FROM cuentas_contables n1
JOIN cuentas_contables n2 ON n2.cuenta_mayor = n1.codigo
JOIN cuentas_contables n3 ON n3.cuenta_mayor = n2.codigo
JOIN cuentas_contables n4 ON n4.cuenta_mayor = n3.codigo
JOIN cuentas_contables n5 ON n5.cuenta_mayor = n4.codigo
JOIN cuentas_contables n6 ON n6.cuenta_mayor = n5.codigo
WHERE (n1.cuenta_mayor IS NULL OR n1.cuenta_mayor = '')
  AND n6.naturaleza = $id

ORDER BY orden;

";
        return $this->selectAll($sql);
    }
	
	public function getCuenta($id)
    {
        $sql = "SELECT * FROM cuentas_contables WHERE codigo = '$id'";
        return $this->select($sql);
    }
	public function getCuentaDetalleCliente($id,$tabla)
    {
        $sql = "SELECT count(id) as total from $tabla WHERE id_cuenta = '$id'";
        return $this->select($sql);
    }
	
	public function getCuentaPadre($id)
    {
        $sql = "SELECT nombre_cuenta, codigo from cuentas_contables where codigo = (SELECT cuenta_mayor from cuentas_contables where codigo = $id)";
        return $this->select($sql);
    }
	
	public function maxCuenta($id)
    {
        $sql = "SELECT codigo as maxCuenta FROM cuentas_contables WHERE cuenta_mayor = $id ORDER BY CAST(SUBSTRING_INDEX(codigo_cuenta, '.', 1) AS UNSIGNED), CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo_cuenta, '.', 2), '.', -1) AS UNSIGNED), CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo_cuenta, '.', 3), '.', -1) AS UNSIGNED), CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo_cuenta, '.', 4), '.', -1) AS UNSIGNED), CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(codigo_cuenta, '.', 5), '.', -1) AS UNSIGNED), CAST(SUBSTRING_INDEX(codigo_cuenta, '.', -1) AS UNSIGNED) DESC;";
        return $this->select($sql);
    }
	
	public function editar($id)
    {
        $sql = "SELECT id, nombre, codigo_cuenta FROM naturaleza_cuentas WHERE id = $id";
        return $this->select($sql);
    }
	
	    public function actualizar($descripcion, $mayor, $id)
    {
        $sql = "UPDATE cuentas_contables SET nombre_cuenta=?, mayor=? WHERE codigo=?";
        $array = array($descripcion, $mayor, $id);
        return $this->save($sql, $array);
    }
	
	public function actualizarPadre($codCuenta, $descripcion, $id)
    {
        $sql = "UPDATE naturaleza_cuentas SET codigo_cuenta=?, nombre=? WHERE id=?";
        $array = array($codCuenta, $descripcion, $id);
        return $this->save($sql, $array);
    }
	
	 public function eliminar($id)
    {
        $sql = "DELETE from naturaleza_cuentas WHERE id = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }
	
	public function eliminarhijas($id)
    {
        $sql = "DELETE from cuentas_contables WHERE id_naturaleza = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }
	
	public function eliminarHija($id)
    {
        $sql = "DELETE from cuentas_contables WHERE id = ?";
        $array = array($id);
        return $this->save($sql, $array);
    }
	
}


?>