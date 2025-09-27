<?php
class Clientes extends Controller
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

        if (!verificar('clientes')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Clientes';
        $data['script'] = 'clientes.js';
		$data['catalogo'] = $this->model->getCuentas();
        $this->views->getView('clientes', 'index', $data);
    }
    public function listar()
    {
        if (!verificar('clientes')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getClientes(1);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="eliminarCliente(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
            <button class="btn btn-info" type="button" onclick="editarCliente(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        if (!verificar('clientes')) {
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
            $correo = (empty($_POST['correo'])) ? null : strClean($_POST['correo']);
            $direccion = strClean($_POST['direccion']);
			$departamento = strClean($_POST['departamentoCliente']);
			$municipio = strClean($_POST['municipioCliente']);
			$actividad = strClean($_POST['actividad']);
			$cuentaContable = strClean($_POST['cuentaContable']);
			$pais = strClean($_POST['pais']);
			$actividadExportacion = strClean($_POST['actividadExportacion']);
			$chekExento = strClean($_POST['chekExento']);
			$contribuyente = strClean($_POST['contribuyente']);
			$nombreCuenta = "";
			if($cuentaContable!=''){
			$cuentaContable = explode(" | ", $cuentaContable);
			$nombreCuenta = $cuentaContable[1];
			$cuentaContable = $cuentaContable[0];
			$cuentaContable = $this->model->getCuentaContable($cuentaContable); 

			}
			
             if (empty($DUI)) {
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
								$departamento,
								$municipio,
								$actividad,
								$pais,
								$actividadExportacion,
								$chekExento,
								$contribuyente
                            );
                            if ($data > 0) {
								if($cuentaContable!=''){
								$dataDetalle = $this->model->registrarCuenta_Cliente($cuentaContable['id'],$data);
								}
                                $res = array('msg' => 'CLIENTE REGISTRADO', 'type' => 'success');
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
                            
                            $data = $this->model->actualizar(
                                $identidad,
                                $num_identidad,
                                $DUI,
                                $nombre,
                                $telefono,
                                $correo,
                                $direccion,
                                $id,
								$departamento,
								$municipio,
								$actividad,
								$pais,
								$actividadExportacion,
								$chekExento,
								$contribuyente
                            );
                            if ($data > 0) {
								if($cuentaContable!=''){
								$validar = $this->model->validarCuentaContable($id);
								if($validar['total']>0){
								$dataDetalle = $this->model->actualizar_Cuenta_Cliente($cuentaContable['id'],$id);	
								}else{
								$dataDetalle = $this->model->registrarCuenta_Cliente($cuentaContable['id'],$id);		
								}
								}else{
								$dataDetalle = $this->model->eliminar_Cuenta_Cliente($id);	
								}
                                $res = array('msg' => 'CLIENTE ACTUALIZADO', 'type' => 'success');
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

    public function eliminar($idCliente)
    {

        if (!verificar('clientes')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET) && is_numeric($idCliente)) {
            $data = $this->model->eliminar(0, $idCliente);
            if ($data > 0) {
                $res = array('msg' => 'CLIENTE DADO DE BAJA', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($idCliente)
    {
        if (!verificar('clientes')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
		$validar = $this->model->validarCuentaContable($idCliente);
		$data = $this->model->editar($idCliente,$validar['total']);
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function inactivos()
    {
        if (!verificar('clientes')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Clientes  Inactivos';
        $data['script'] = 'clientes-inactivos.js';
        $this->views->getView('clientes', 'inactivos', $data);
    }
    public function listarInactivos()
    {
        if (!verificar('clientes')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getClientes(0);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="restaurarCliente(' . $data[$i]['id'] . ')"><i class="fas fa-check-circle"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function restaurar($idCliente)
    {
        if (!verificar('clientes')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET) && is_numeric($idCliente)) {
            $data = $this->model->eliminar(1, $idCliente);
            if ($data > 0) {
                $res = array('msg' => 'CLIENTE RESTAURADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL RESTUARAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }

    //buscar clientes para la venta
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
			$result['contribuyente'] = $row['contribuyente'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
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
			$result['actividad'] = $row['actividad'];
			$result['municipio'] = $row['municipio'];
			$result['departamento'] = $row['departamento'];
			$result['DUI'] = $row['DUI'];
			$result['pais'] = $row['pais'];
			$result['actividadExportacion'] = $row['actividadExportacion'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	 public function maxCorrelativo()
    {
        $array = array();
		$tipoDocumento = strClean($_GET['tipoDocumento']);
        $data = $this->model->getMaxCorrelativo($tipoDocumento);
        foreach ($data as $row) {
            $resultado['correlativo'] = $row['numeroControlDte'];
            array_push($array, $resultado);
        }
        echo json_encode($array);
        die();
    }
	
	
}
