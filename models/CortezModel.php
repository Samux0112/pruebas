<?php
class CortezModel extends Query{
    public function __construct() {
        parent::__construct();
    }
   
   
    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        return $this->select($sql);
    }

    public function getDatosFactura($fInicio,$fFin, $tipo)
    {
		$sql = "SELECT (SELECT numeroControlDte FROM ventas where id = (SELECT MAX(id) from ventas where fecha BETWEEN '$fInicio' AND '$fFin' AND docuemi = '$tipo' ) ) as maximo , (SELECT numeroControlDte FROM ventas where id = (SELECT MIN(id) from ventas where fecha BETWEEN '$fInicio' AND '$fFin' AND docuemi = '$tipo')) as minimo, SUM(vGravadas) as sumatoria, SUM(vIva) as iva, SUM(vExentas) as exentas  FROM ventas where fecha BETWEEN '$fInicio' AND '$fFin' AND estado = 1 AND docuemi = '$tipo'";
       // $sql = "SELECT v.*, c.identidad, c.num_identidad, c.nombre, c.telefono, c.direccion, c.departamento, c.municipio, c.actividad, c.correo FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.id = $idVenta";
        return $this->select($sql);
    } 

    public function getSerie()
    {
        $sql = "SELECT MAX(id) AS total FROM ventas";
        return $this->select($sql);
    }

	
}


?>