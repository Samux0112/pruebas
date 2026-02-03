<?php
class BodegasModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getBodegas($estado)
    {
        $sql = "SELECT * FROM bodegas WHERE estado = $estado";
        return $this->selectAll($sql);
    }

    public function getPermisos()
    {
        $sql = "SELECT * FROM permisos";
        return $this->selectAll($sql);
    }

    public function registrar($nombre, $telefono, $nombreContacto, $direccion)
    {
        $sql = "INSERT INTO bodegas (nombre,telefono,nombreContacto,direccion) VALUES (?,?,?,?)";
        $array = array($nombre, $telefono, $nombreContacto, $direccion);
        return $this->insertar($sql, $array);
    }
    public function getValidar($campo, $valor, $accion, $id)
    {
        if ($accion == 'registrar' && $id == 0) {
            $sql = "SELECT id FROM bodegas WHERE $campo = '$valor'";
        }else{
            $sql = "SELECT id FROM bodegas WHERE $campo = '$valor' AND id != $id";
        }
        return $this->select($sql);
    }
    public function eliminar($estado, $idRol)
    {
        $sql = "UPDATE bodegas SET estado = ? WHERE id = ?";
        $array = array($estado, $idRol);
        return $this->save($sql, $array);
    }

    public function editar($idRol)
    {
        $sql = "SELECT * FROM bodegas WHERE id = $idRol";
        return $this->select($sql);
    }
    public function actualizar($nombre, $telefono, $nombreContacto, $direccion, $id)
    {
        $sql = "UPDATE bodegas SET nombre=?, telefono=?, nombreContacto=?, direccion=? WHERE id=?";
        $array = array($nombre, $telefono, $nombreContacto, $direccion, $id);
        return $this->save($sql, $array);
    }
}

?>