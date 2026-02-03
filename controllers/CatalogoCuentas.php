<?php
require 'vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Dompdf\Dompdf;

class CatalogoCuentas extends Controller
{
    private $id_usuario;
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        if (empty($_SESSION['permisos']) || !in_array('contabilidad', $_SESSION['permisos'])) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $this->id_usuario = $_SESSION['id_usuario'];
    }
    public function index()
    {	$data['title'] = 'CatalogoCuentas';
		$data['script'] = 'catalogoCuentas.js';
        $this->views->getView('catalogoCuentas', 'index', $data);
    }
	
	 public function verDetalle($id)
    {	$data['title'] = 'CatalogoCuentas';
		$data['script'] = 'catalogoCuentasDetalle.js';
		$data['id'] = $id;
        $this->views->getView('catalogoCuentas', 'detalle', $data);
    }
	
	public function agregarCuenta($id)
    {	$data['title'] = 'AgregarCuenta';
		$data['script'] = 'agregarCuenta.js';
		$data['cuenta'] = $this->model->getCuenta($id);
		$data['maxCuenta'] = $this->model->maxCuenta($id);
		if($data['maxCuenta']['maxCuenta']==0){
			$data['maxCuenta']['maxCuenta'] = $id.'00';
		}
		if($id<9){ 
			$data['maxCuenta']['maxCuenta'] =$id; 
			}
		$data['id'] = $id;
		$data['idCuenta']="";
        $this->views->getView('catalogoCuentas', 'agregarCuenta', $data);
    }
	
	public function editarCuenta($id)
    {	$data['title'] = 'AgregarCuenta';
		$data['script'] = 'agregarCuenta.js';
		$data['cuenta'] = $this->model->getCuenta($id);
		$data['cuenta']['padre']= $this->model->getCuentaPadre($id);
		$data['maxCuenta']['maxCuenta'] = $data['cuenta']['codigo'];
		$data['idCuenta'] = $data['cuenta']['codigo'];
        $this->views->getView('catalogoCuentas', 'agregarCuenta', $data);
    }
	
	public function registrarPadre(){
		
		        if (isset($_POST)) {
            if (empty($_POST['descripcion'])) {
                $res = array('msg' => 'LA DESCRIPCION ES REQUERIDA', 'type' => 'warning');
            } else if (empty($_POST['codCuenta'])) {
                $res = array('msg' => 'EL CODIGO ES REQUERIDO', 'type' => 'warning');
            } else {
                $descripcion = strClean($_POST['descripcion']);
                $codCuenta = strClean($_POST['codCuenta']);
                $id = strClean($_POST['id']);

                if ($id == '') {
					
			$data = $this->model->registrarPadre(
								$codCuenta,
                                $descripcion
                            );
							$ctaMayor = '';
							$nivel = 0;
							$mayor = 'SI';
                            if ($data > 0) {
								$data = $this->model->registrar(
								$codCuenta,
								$codCuenta,
                                $descripcion,
                                $ctaMayor,
								$nivel,
								$data,
								$mayor,
								$codCuenta
                            );
                                $res = array('msg' => 'CUENTA REGISTRADA', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                            }
                } else {
                            $data = $this->model->actualizarPadre(
                                $codCuenta,
                                $descripcion,
                                $id
                            );
                            if ($data > 0) {
                                $res = array('msg' => 'CUENTA ACTUALIZADA', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'ERROR AL ACTUALIZAR', 'type' => 'error');
                            }
                       
                     
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
		
		
	}
	
	    //metodo para registrar y modificar
    public function registrar()
    {
        if (!verificar('catalogoCuentas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_POST)) {
            if (empty($_POST['descripcion'])) {
                $res = array('msg' => 'LA DESCRIPCION ES REQUERIDA', 'type' => 'warning');
            }else {
                $descripcion = strClean($_POST['descripcion']);
                $codCuenta = strClean($_POST['codCuenta']);
				$codigo = strClean($_POST['codCuenta']);
				$ctaMayor = strClean($_POST['ctaMayor']);
				$nivel = strClean($_POST['nivel']);
				$naturaleza = strClean($_POST['naturaleza']);
				$id_naturaleza = strClean($_POST['idNaturaleza']);
				$mayor = strClean($_POST['mayor']);
				$idCuenta = strClean($_POST['idCuenta']);
                $id = "";
				if($nivel==1){
				$codCuenta = substr($codCuenta,0,1) .'.'.substr($codCuenta,1,1);
				}elseif($nivel==2){
				$codCuenta = substr($codCuenta,0,1) .'.'.substr($codCuenta,1,1).'.'.substr($codCuenta,2,2);	
				}elseif($nivel==3){
				$codCuenta = substr($codCuenta,0,1) .'.'.substr($codCuenta,1,1).'.'.substr($codCuenta,2,2).'.'.substr($codCuenta,4,2);
				}elseif($nivel==4){
				$codCuenta = substr($codCuenta,0,1) .'.'.substr($codCuenta,1,1).'.'.substr($codCuenta,2,2).'.'.substr($codCuenta,4,2).'.'.substr($codCuenta,6,2);	
				}elseif($nivel==5){
				$codCuenta = substr($codCuenta,0,1) .'.'.substr($codCuenta,1,1).'.'.substr($codCuenta,2,2).'.'.substr($codCuenta,4,2).'.'.substr($codCuenta,6,2).'.'.substr($codCuenta,8,(strlen($codCuenta)-8));	
				}elseif($nivel==6){
				$codCuenta = substr($codCuenta,0,1) .'.'.substr($codCuenta,1,1).'.'.substr($codCuenta,2,2).'.'.substr($codCuenta,4,2).'.'.substr($codCuenta,6,2).'.'.substr($codCuenta,8,2).'.'.substr($codCuenta,10,2);	
				}elseif($nivel==7){
				$codCuenta = substr($codCuenta,0,1) .'.'.substr($codCuenta,1,1).'.'.substr($codCuenta,2,2).'.'.substr($codCuenta,4,2).'.'.substr($codCuenta,6,2).'.'.substr($codCuenta,8,2).'.'.substr($codCuenta,10,2).'.'.substr($codCuenta,12,2);	
				}elseif($nivel==8){
				$codCuenta = substr($codCuenta,0,1) .'.'.substr($codCuenta,1,1).'.'.substr($codCuenta,2,2).'.'.substr($codCuenta,4,2).'.'.substr($codCuenta,6,2).'.'.substr($codCuenta,8,2).'.'.substr($codCuenta,10,2).'.'.substr($codCuenta,12,2).'.'.substr($codCuenta,14,2);	
				}
				

                if ($idCuenta == '') {
					
			$data = $this->model->registrar(
								$codCuenta,
								$codigo,
                                $descripcion,
                                $ctaMayor,
								$nivel,
								$id_naturaleza,
								$mayor,
								$naturaleza
                            );
                            if ($data >= 0) {
                                $res = array('msg' => 'CUENTA REGISTRADA', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'CUENTA REGISTRADA', 'type' => 'success');
                            }
                } else {
                            $data = $this->model->actualizar(
                                $descripcion,
                                $mayor,
                                $idCuenta
                            );
                            if ($data > 0) {
                                $res = array('msg' => 'CUENTA ACTUALIZADA', 'type' => 'success');
                            } else {
                                $res = array('msg' => 'CUENTA ACTUALIZADA', 'type' => 'success');
                            }
                       
                     
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	 public function listar()
    {
        if (!verificar('catalogoCuentas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getCuentasPadre();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-success" type="button" onclick="verCuentas(' . $data[$i]['id'] . ')"><i class="fa-solid fa-magnifying-glass"></i></button>
			<button class="btn btn-info" type="button" onclick="editarCuenta(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
			<button class="btn btn-danger" type="button" onclick="eliminarCuentaContable(' . $data[$i]['id'] . ')"><i class="fas fa-times-circle"></i></button>
            </div>';
        $data[$i]['id'] = '<span class="badge bg-success">'.$data[$i]['id'].'</span>';
    }
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
	
	}
	
	public function listarDetalle($id)
    {
        if (!verificar('catalogoCuentas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getCuentasDetalle($id);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-success" type="button" onclick="agregar(' . $data[$i]['codigo'] . ')"><i class="fa-solid fa-plus"></i></button>
			<button class="btn btn-info" type="button" onclick="editarCuenta(' . $data[$i]['codigo'] . ')"><i class="fas fa-edit"></i>
			<button class="btn btn-danger" type="button" onclick="eliminarCuentaContable(' . $data[$i]['id'] . ')"><i class="fas fa-times-circle"></i></button>
            </div>';
        $data[$i]['id'] = '<span class="badge bg-success">'.$data[$i]['id'].'</span>';
    }
	
	echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
	}
	
	 public function editar($id)
    {
        if (!verificar('catalogoCuentas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->editar($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	    public function eliminarPadre($id)
    {
        if (!verificar('catalogoCuentas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET)) {
            if (is_numeric($id)) {
				$data = $this->model->eliminarhijas($id);
                $data = $this->model->eliminar($id);
                if ($data == 1) {
                    $res = array('msg' => 'CUENTA ELIMINADA', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
                }
            } else {
                $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	  public function eliminar($id)
    {
        if (!verificar('catalogoCuentas')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
		
        if (isset($_GET)) {
            if (is_numeric($id)) {
				$dataCuentaCliente =  $this->model->getCuentaDetalleCliente($id,'detalle_cuentas_clientes');
				$dataCuentaCliente2 =  $this->model->getCuentaDetalleCliente($id,'detalle_cuentas_clientes2');
				$dataCuentaProducto =  $this->model->getCuentaDetalleCliente($id,'detalle_cuentas_productos');
				if($dataCuentaCliente['total']>0){
					$res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
				}elseif($dataCuentaCliente2['total']>0){
					$res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');	
				}elseif($dataCuentaProducto['total']>0){
				$res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');		
				}else{
				$data = $this->model->eliminarHija($id); 	
				if ($data == 1) {
                    $res = array('msg' => 'CUENTA ELIMINADA', 'type' => 'success');
                } else {
                    $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
                }
				}
				
                
            } else {
                $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die();
    }
	

	


    

}
