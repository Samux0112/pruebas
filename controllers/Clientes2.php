<?php
class Clientes2 extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
    }
    public function index()
    {
        if (!verificar('proveedores')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Clientes2';
        $data['script'] = 'clientes2.js';
        $this->views->getView('clientes2', 'index', $data);
    }
	
		 public function buscarId()
    {
        $array = array();
		$idCliente = strClean($_GET['id']);
        $data = $this->model->buscarPorId($idCliente);
        foreach ($data as $row) {
            $result['id'] = $row['id'];
            $result['identidad'] = $row['identidad'];
            $result['telefono'] = $row['num_identidad'];
            $result['num_identidad'] = $row['num_identidad'];
			$result['nombre'] = $row['nombre'];
			$result['telefono'] = $row['telefono'];
			$result['correo'] = $row['correo'];
			$result['direccion'] = $row['direccion'];
			$result['fecha'] = $row['fecha'];
			$result['municipio'] = $row['municipio'];
			$result['departamento'] = $row['departamento'];
			$result['DUI'] = $row['DUI'];
			$result['actividad'] = $row['actividad'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
	
    public function listar()
    {
        if (!verificar('proveedores')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getClientes2(1);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="eliminarCliente2(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
            <button class="btn btn-info" type="button" onclick="editarCliente2(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        if (!verificar('proveedores')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_POST['identidad']) && isset($_POST['num_identidad'])) {
            $id = strClean($_POST['id']);
            $identidad = strClean($_POST['identidad']);
            $num_identidad = strClean($_POST['num_identidad']);
            $DUI = strClean($_POST['DUI']);
            $nombre = strClean($_POST['nombre']);
            $telefono = strClean($_POST['telefono']);
			$departamentoCliente = strClean($_POST['departamentoCliente']);
			$municipioCliente = strClean($_POST['municipioCliente']);
            $correo = (empty($_POST['correo'])) ? null : strClean($_POST['correo']);
            $direccion = strClean($_POST['direccion']);
            $actividad = strClean($_POST['actividad']);
			$cuentaContable = strClean($_POST['cuentaContable']);
			$chekExento = strClean($_POST['chekExento']);
			$contribuyente = strClean($_POST['contribuyente']);
			if($cuentaContable!=''){
			$cuentaContable = explode(" | ", $cuentaContable);
			$nombreCuenta = $cuentaContable[1];
			$cuentaContable = $cuentaContable[0];
			$cuentaContable = $this->model->getCuentaContable($cuentaContable); 
			}
            if (empty($identidad)) {
                $res = array('msg' => 'LA IDENTIDAD ES REQUERIDO', 'type' => 'warning');
            } else if (empty($num_identidad)) {
                $res = array('msg' => 'LA N¡Æ DE IDENTIDAD ES REQUERIDO', 'type' => 'warning');
            } else if (empty($DUI)) {
                $res = array('msg' => 'EL DUI ES REQUERIDO', 'type' => 'warning');
            } else if (empty($nombre)) {
                $res = array('msg' => 'EL NOMBRE ES REQUERIDO', 'type' => 'warning');
            } else if (empty($telefono)) {
                $res = array('msg' => 'EL TELEFONO ES REQUERIDO', 'type' => 'warning');
            } else if (empty($direccion)) {
                $res = array('msg' => 'LA DIRECCION ES REQUERIDO', 'type' => 'warning');
            } else {
                if ($id == '') {
                    $verificarIdentidad = $this->model->getValidar('num_identidad', $num_identidad, 'registrar', 0);
                    if (empty($verificarIdentidad)) {
                        $verificarTelefono = $this->model->getValidar('telefono', $telefono, 'registrar', 0);
                        if (empty($verificarTelefono)) {
                            if ($correo != null) {
                                $verificarCorreo = $this->model->getValidar('correo', $correo, 'registrar', 0);
                                if (!empty($verificarCorreo)) {
                                    $res = array('msg' => 'EL CORREO DEBE SER UNICO', 'type' => 'warning');
                                    echo json_encode($res);
                                    die();
                                }
                            }


                                if ($DUI != null) {
                                    $verificarDUI = $this->model->getValidar('DUI', $DUI, 'registrar', 0);
                                    if (!empty($verificarDUI)) {
                                        $res = array('msg' => 'EL DUI DEBE SER UNICO', 'type' => 'warning');
                                        echo json_encode($res);
                                        die();

                                    }

                            }
                            $data = $this->model->registrar(
                                $identidad,
                                $num_identidad,
                                $DUI,
                                $nombre,
                                $telefono,
                                $correo,
                                $direccion,
								$departamentoCliente,
								$municipioCliente,
								$actividad,
								$chekExento,
								$contribuyente
                            );
                            if ($data > 0) {
								if($cuentaContable!=''){
								$dataDetalle = $this->model->registrarCuenta_Cliente2($cuentaContable['id'],$data);
								}
                                $res = array('msg' => 'CLIENTE2 REGISTRADO', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                            }
                        } else {
                            $res = array('msg' => 'EL TELEFONO DEBE SER UNICO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'LA IDENTIDAD DEBE SER UNICO', 'type' => 'warning');
                        $res = array('msg' => 'EL DUI DEBE SER UNICO', 'type' => 'warning');
                    }
                } else {
                    $verificarIdentidad = $this->model->getValidar('num_identidad', $num_identidad, 'actualizar', $id);
                    if (empty($verificarIdentidad)) {
                        $verificarTelefono = $this->model->getValidar('telefono', $telefono, 'actualizar', $id);
                        if (empty($verificarTelefono)) {
                            if ($correo != null) {
                                $verificarCorreo = $this->model->getValidar('correo', $correo, 'actualizar', $id);
                                if (!empty($verificarCorreo)) {
                                    $res = array('msg' => 'EL CORREO DEBE SER UNICO', 'type' => 'warning');
                                    echo json_encode($res);
                                    die();
                                }
                            }
                            $data = $this->model->actualizar(
                                $identidad,
                                $num_identidad,
                                $DUI,
                                $nombre,
                                $telefono,
                                $correo,
                                $direccion,
                                $id,
								$departamentoCliente,
								$municipioCliente,
								$actividad,
								$chekExento,
								$contribuyente
                            );
                            if ($data > 0) {
								if($cuentaContable!=''){
								$validar = $this->model->validarCuentaContable($id);
								if($validar['total']>0){
								$dataDetalle = $this->model->actualizar_Cuenta_Cliente($cuentaContable['id'],$id);	
								}else{
								$dataDetalle = $this->model->registrarCuenta_Cliente2($cuentaContable['id'],$id);		
								}
								}else{
								$dataDetalle = $this->model->eliminar_Cuenta_Cliente($id);	
								}
                                $res = array('msg' => 'CLIENTE2 MODIFICADO', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                            }
                        } else {
                            $res = array('msg' => 'EL TELEFONO DEBE SER UNICO', 'type' => 'warning');
                        }
                    } else {
                        $res = array('msg' => 'LA IDENTIDAD DEBE SER UNICO', 'type' => 'warning');
                    }
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function eliminar($idCliente2)
    {
        if (!verificar('proveedores')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET) && is_numeric($idCliente2)) {
            $data = $this->model->eliminar(0, $idCliente2);
            if ($data > 0) {
                $res = array('msg' => 'CLIENTE2 DADO DE BAJA', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($idCliente2)
    {
        if (!verificar('proveedores')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
		$validar = $this->model->validarCuentaContable($idCliente2);
		$data = $this->model->editar($idCliente2,$validar['total']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function inactivos()
    {
        if (!verificar('proveedores')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Clientes2  Inactivos';
        $data['script'] = 'clientes2-inactivos.js';
        $this->views->getView('clientes2', 'inactivos', $data);
    }
    public function listarInactivos()
    {
        if (!verificar('proveedores')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getClientes2(0);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="restaurarCliente2(' . $data[$i]['id'] . ')"><i class="fas fa-check-circle"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function restaurar($idCliente2)
    {
        if (!verificar('proveedores')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET) && is_numeric($idCliente2)) {
            $data = $this->model->eliminar(1, $idCliente2);
            if ($data > 0) {
                $res = array('msg' => 'CLIENTE2 RESTAURADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL RESTUARAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    //buscar clientes2 para la venta2
    public function buscar()
    {
        $array = array();
        $valor = strClean($_GET['term']);
        $data = $this->model->buscarPorNombre($valor);
		$resultSeriePdv = $this->model->getCodPuntoVenta($_SESSION['id_usuario']);
		$result['codPuntoVentaMH'] = $resultSeriePdv['codPuntoVentaMH'];
        foreach ($data as $row) {
            $result['id'] = $row['id'];
            $result['label'] = $row['nombre'];
            $result['telefono'] = $row['telefono'];
            $result['direccion'] = $row['direccion'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
}
