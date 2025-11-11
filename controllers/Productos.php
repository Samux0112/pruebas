<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Productos extends Controller
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
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
    $data['title'] = 'Productos';
    $data['script'] = 'productos.js';
    $data['medidas'] = $this->model->getDatos('medidas');
    $data['categorias'] = $this->model->getDatos('categorias');
    $data['tipoProducto'] = $this->model->getTipoProducto();
    $this->views->getView('productos', 'index', $data);
    }
    public function listar()
    {
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getProductos(1);
        for ($i = 0; $i < count($data); $i++) {
			if($_SESSION['rol_usuario']=='ADMINISTRADOR' || $_SESSION['rol_usuario']=='SUPERVISOR'){
            $foto = ($data[$i]['foto'] == null) ? 'assets/images/productos/default.png' :  $data[$i]['foto'];
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="'.BASE_URL . $foto . '" width="50">';
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="eliminarProducto(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
            <button class="btn btn-info" type="button" onclick="editarProducto(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
			<a class="btn btn-warning" type="button" target="_blank" href="'.BASE_URL.'/productos/generarBarcodeUni/'.$data[$i]['id'].'"><i class="fas fa-barcode"></i></a>
            </div>';
			}else{
				 $foto = ($data[$i]['foto'] == null) ? 'assets/images/productos/default.png' :  $data[$i]['foto'];
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="'.BASE_URL . $foto . '" width="50">';
            $data[$i]['acciones'] = '<div>
			<a class="btn btn-warning" type="button" target="_blank" href="'.BASE_URL.'/productos/generarBarcodeUni/'.$data[$i]['id'].'"><i class="fas fa-barcode"></i></a>
            </div>';	
			}
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	 public function listarPorBodega($datos)
    {
		$array = explode(',', $datos);
        $idBodega = $array[0];
		
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getProductosPorBodega(1,$idBodega);
        for ($i = 0; $i < count($data); $i++) {
			if($_SESSION['rol_usuario']=='ADMINISTRADOR' || $_SESSION['rol_usuario']=='SUPERVISOR'){
            $foto = ($data[$i]['foto'] == null) ? 'assets/images/productos/default.png' :  $data[$i]['foto'];
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="'.BASE_URL . $foto . '" width="50">';
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="eliminarProducto(' . $data[$i]['id'] . ')"><i class="fas fa-trash"></i></button>
            <button class="btn btn-info" type="button" onclick="editarProducto(' . $data[$i]['id'] . ')"><i class="fas fa-edit"></i></button>
			<a class="btn btn-warning" type="button" target="_blank" href="'.BASE_URL.'/productos/generarBarcodeUni/'.$data[$i]['id'].'"><i class="fas fa-barcode"></i></a>
            </div>';
			}else{
				 $foto = ($data[$i]['foto'] == null) ? 'assets/images/productos/default.png' :  $data[$i]['foto'];
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="'.BASE_URL . $foto . '" width="50">';
            $data[$i]['acciones'] = '<div>
			<a class="btn btn-warning" type="button" target="_blank" href="'.BASE_URL.'/productos/generarBarcodeUni/'.$data[$i]['id'].'"><i class="fas fa-barcode"></i></a>
            </div>';	
			}
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_POST['codigo']) && isset($_POST['nombre'])) {
            $id = strClean($_POST['id']);
            $codigo = strClean($_POST['codigo']);
            $nombre = strClean($_POST['nombre']);
            $precio_venta2 = strClean($_POST['precio_venta2']);
            $precio_venta = strClean($_POST['precio_venta']);
            $id_medida = strClean($_POST['id_medida']);
            $id_categoria = strClean($_POST['id_categoria']);
            $fotoActual = strClean($_POST['foto_actual']);
            $ubi = strClean($_POST['ubi']);
            $foto = $_FILES['foto'];
            $name = $foto['name'];
            $tmp = $foto['tmp_name'];
            $id_tipoProducto = strClean($_POST['id_tipoProducto']);
			$cuentaContable = strClean($_POST['cuentaContable']);
			if($cuentaContable!=''){
			$cuentaContable = explode(" | ", $cuentaContable);
			$nombreCuenta = $cuentaContable[1];
			$cuentaContable = $cuentaContable[0];
			$cuentaContable = $this->model->getCuentaContable($cuentaContable); 
			}
           

            $destino = null;
            if (!empty($name)) {
                $fecha = date('YmdHis');
                $destino = 'assets/images/productos/' . $fecha . '.jpg';
            } else if (!empty($fotoActual) && empty($name)) {
                $destino = $fotoActual;
            }
            if (empty($codigo)) {
                $res = array('msg' => 'EL CODIGO ES REQUERIDO', 'type' => 'warning');

            }
            if (empty($ubi)) {
                $res = array('msg' => 'UBICACION ES REQUERIDO', 'type' => 'warning');


            } else if (empty($nombre)) {
                $res = array('msg' => 'EL NOMBRE ES REQUERIDO', 'type' => 'warning');
          
            } else if (empty($id_medida)) {
                $res = array('msg' => 'LA MEDIDA ES REQUERIDO', 'type' => 'warning');
            } else if (empty($id_categoria)) {
                $res = array('msg' => 'LA CATEGORIA ES REQUERIDO', 'type' => 'warning');
            } else {
                if ($id == '') {
                    $verificar = $this->model->getValidar('codigo', $codigo, 'registrar', 0);
                    if (empty($verificar)) {
                        $data = $this->model->registrar(
                            $codigo,
                            $nombre,
                            $precio_venta2,
                            $precio_venta,
                            $id_medida,
                            $id_categoria,
                            $ubi,
                            $destino,
                            $id_tipoProducto
                        );
                        if ($data > 0) {
						$dataBodega = $this->model->registrarBodega_producto($data);
						if($cuentaContable!=''){
								$dataDetalle = $this->model->registrarCuenta_producto($cuentaContable['id'],$data);
								}
                            if (!empty($name)) {
                                move_uploaded_file($tmp, $destino);
                            }
                            $res = array('msg' => 'PRODUCTO REGISTRADO', 'type' => 'success');
                        } else {
                            $res = array('msg' => 'ERROR AL REGISTRAR', 'type' => 'error');
                        }
                    } else {
                        $res = array('msg' => 'LA CODIGO DEBE SER ÚNICO', 'type' => 'warning');
                    }
                } else {
                    $verificar = $this->model->getValidar('codigo', $codigo, 'actualizar', $id);
                    if (empty($verificar)) {
                        //temp
                        $temp = $this->model->editar($id,0);
                        $imgtemp = ($temp['foto'] != null) ? $temp['foto'] : 'default.png';
                        $data = $this->model->actualizar(
                            $codigo,
                            $nombre,
                            $precio_venta2,
                            $precio_venta,
                            $id_medida,
                            $id_categoria,
                            $ubi,
                            $destino,
                            $id_tipoProducto,
                            $id
                        );
                        if ($data > 0) {
                            if (file_exists($imgtemp) && $imgtemp != 'default.png') {
                                unlink($imgtemp);
                            }
                            if (!empty($name)) {
                                move_uploaded_file($tmp, $destino);
                            }
							if($cuentaContable!=''){
								$validar = $this->model->validarCuentaContable($id);
								if($validar['total']>0){
								$dataDetalle = $this->model->actualizar_Cuenta_Productos($cuentaContable['id'],$id);	
								}else{
								$dataDetalle = $this->model->registrarCuenta_producto($cuentaContable['id'],$id);		
								}
								}else{
								$dataDetalle = $this->model->eliminar_Cuenta_Producto($id);	
								}
                            $res = array('msg' => 'PRODUCTO MODIFICADO', 'type' => 'success');
                        } else {
                            $res = array('msg' => 'ERROR AL MODIFICAR', 'type' => 'error');
                        }
                    } else {
                        $res = array('msg' => 'EL CODIGO DEBE SER ÚNICO', 'type' => 'warning');
                    }
                }
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function eliminar($idProducto)
    {
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET) && is_numeric($idProducto)) {
            $data = $this->model->eliminar(0, $idProducto);
            if ($data == 1) {
                $res = array('msg' => 'PRODUCTO DADO DE BAJA', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL ELIMINAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    public function editar($idProducto)
    {
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $validar = $this->model->validarCuentaContable($idProducto);
		$data = $this->model->editar($idProducto,$validar['total']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function inactivos()
    {
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data['title'] = 'Productos Inactivos';
        $data['script'] = 'productos-inactivos.js';
        $this->views->getView('productos', 'inactivos', $data);
    }

    public function listarInactivos()
    {
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        $data = $this->model->getProductos(0);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['imagen'] = '<img class="img-thumbnail" src="'.BASE_URL . $data[$i]['foto'] . '" width="100">';
            $data[$i]['acciones'] = '<div>
            <button class="btn btn-danger" type="button" onclick="restaurarProducto(' . $data[$i]['id'] . ')"><i class="fas fa-check-circle"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function restaurar($idProducto)
    {
        if (!verificar('productos')) {
            header('Location: ' . BASE_URL . 'admin/permisos');
            exit;
        }
        if (isset($_GET) && is_numeric($idProducto)) {
            $data = $this->model->eliminar(1, $idProducto);
            if ($data == 1) {
                $res = array('msg' => 'PRODUCTO RESTAURADO', 'type' => 'success');
            } else {
                $res = array('msg' => 'ERROR AL RESTAURAR', 'type' => 'error');
            }
        } else {
            $res = array('msg' => 'ERROR DESCONOCIDO', 'type' => 'error');
        }
        echo json_encode($res);
        die();
    }

    //buscar Productos por codigo
    public function buscarPorCodigo($datos)
    {
        $array1 = explode(',', $datos);
        $valor = $array1[0];
        $bodegaSalida = $array1[1];
        $array = array('estado' => false, 'datos' => '');
        $data = $this->model->buscarPorCodigo($valor);
        if (!empty($data)) {
            $array['estado'] = true;
            $array['datos'] = $data;
            $result = $this->model->getStock($data['id'], $bodegaSalida);
            $array['datos']['cantidad'] = $result['cantidad'];
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
    //buscar Productos por nombre
public function buscarPorNombre()
    {
        $array = array();
        $valor = $_GET['term'];
        $data = $this->model->buscarPorNombre($valor);
        foreach ($data as $row) {
            $result['id'] = $row['id'];
            $result['label'] = $row['descripcion'];
            $result['stock'] = $row['cantidad'];
            $result['precio_venta'] = $row['precio_venta'];
            $result['precio_venta2'] = $row['precio_venta2'];
			$result['medida'] = $row['medida'];
			$result['nombre_corte'] = $row['nombre_corte'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
	
	public function buscarPorNombreTraslado()
    {
        $array = array();
        $valor = $_GET['term'];
        $bodegaSalida = $_GET['bodegaSalida'];
        $data = $this->model->buscarPorNombreTraslado($valor);

        foreach ($data as $row) {
            $result['id'] = $row['id'];
            $result['label'] = $row['descripcion'];
            $result['stock'] = $this->model->getStock($row['id'], $bodegaSalida);
            $result['stock'] = $result['stock']['cantidad'];
            //$result['stock'] = $row['cantidad'];
            $result['precio_venta'] = $row['precio_venta'];
            $result['precio_venta2'] = $row['precio_venta2'];
			$result['medida'] = $row['medida'];
			$result['nombre_corte'] = $row['nombre_corte'];
            array_push($array, $result);
        }
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function mostrarDatos()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        $array['productos'] = array();
        $totalVenta2 = 0;
        $totalVenta = 0;
        $gravadas =0;
		$cGravadas =0;
		$exentos = 0;
        if (!empty($datos)) {
            foreach ($datos as $producto) {
				if($producto['id']==0){
				$data['id'] = $producto['id'];
                $data['nombre'] = $producto['descripcion'];
				}else{
				$result = $this->model->editar($producto['id']);
                $data['id'] = $result['id'];
                $data['nombre'] = $result['descripcion'];
				}               
				$data['catalogo'] = $producto['catalogo'];
                $data['precio_venta2']  = number_format((empty($producto['precio'])) ? 0 : $producto['precio'], 6, '.', '');
                $data['precio_venta'] =  number_format((empty($producto['precio'])) ? 0 : $producto['precio'], 4, '.', '');
                $data['cantidad'] = $producto['cantidad'];
                $subTotalVenta2 = $data['precio_venta2'] * $producto['cantidad'];
                $subTotalVenta = $data['precio_venta'] * $producto['cantidad'];
                
                $data['subTotalVenta2'] = number_format($subTotalVenta2, 6);
                $data['subTotalVenta'] = number_format($subTotalVenta, 2);
                array_push($array['productos'], $data);
                $totalVenta2 += $subTotalVenta2;
				$totalVenta += $subTotalVenta;
				if($producto['catalogo'] == "Normal" || $producto['catalogo'] == "Servicio" ){
                $gravadas += number_format(($subTotalVenta/1.13),4,'.','');
				$cGravadas += number_format(($subTotalVenta2),6,'.','');
				}elseif($producto['catalogo'] == "Exento"){
					$exentos += number_format(($subTotalVenta),4,'.','');
					
				}
				
				 
               
            }
        }
		
		$iva =  number_format(( ($gravadas*1.13 )-($gravadas)),4,'.','');
		$cIva = number_format(( ($cGravadas*1.13 )-($cGravadas)),2,'.','');
		$array['cIva'] =number_format($cIva,2,'.','');
		$array['cGravadas'] =number_format($cGravadas,2,'.','');
		$array['gravadas'] =number_format($gravadas,4,'.','');
		$array['exentos'] =number_format($exentos,2,'.','');
		$array['iva'] =$iva;
		$array['cSubtotalventas2']=number_format($totalVenta2, 2,'.','');
        $array['totalVenta2'] = number_format($totalVenta2+$cIva, 2,'.','');
        $array['totalVenta'] = number_format($totalVenta, 2);
        $array['totalVentaSD'] = number_format($totalVenta, 2, '.', '');
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reporteExcel()
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()
            ->setCreator($_SESSION['nombre_usuario'])
            ->setTitle("Listado de Productos");

        $spreadsheet->setActiveSheetIndex(0);

        $hojaActiva = $spreadsheet->getActiveSheet();
        $hojaActiva->getColumnDimension('A')->setWidth(50);
        $hojaActiva->getColumnDimension('B')->setWidth(10);
        $hojaActiva->getColumnDimension('C')->setWidth(20);
        $hojaActiva->getColumnDimension('D')->setWidth(20);
        $hojaActiva->getColumnDimension('E')->setWidth(30);
       

        $spreadsheet->getActiveSheet()->getStyle('A1:E1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('008cff');

        $spreadsheet->getActiveSheet()->getStyle('A1:E1')
            ->getFont()->getColor()->setARGB(Color::COLOR_WHITE);

        $hojaActiva->setCellValue('A1', 'Producto');
        $hojaActiva->setCellValue('B1', 'Cantidad');
        $hojaActiva->setCellValue('C1', 'Precio Compra');
        $hojaActiva->setCellValue('D1', 'Precio Venta');
        $hojaActiva->setCellValue('E1', 'Categoria');

        $fila = 2;
        $productos = $this->model->getProductos(1);
        foreach ($productos as $producto) {
            $hojaActiva->setCellValue('A' . $fila, $producto['descripcion']);
            $hojaActiva->setCellValue('B' . $fila, $producto['cantidad']);
            $hojaActiva->setCellValue('C' . $fila, $producto['precio_venta2']);
            $hojaActiva->setCellValue('D' . $fila, $producto['precio_venta']);
            $hojaActiva->setCellValue('E' . $fila, $producto['categoria']);
            $fila++;
        }

        //Generar archivo Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="productos.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function reportePdf()
    {
        ob_start();
        $data['title'] = 'Listado de Productos';
        $data['empresa'] = $this->model->getEmpresa();
        $data['productos'] = $this->model->getProductos(1);
        $this->views->getView('reportes', 'reportesPdf', $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }

    public function generarBarcode()
    {
        //$redColor = [255, 0, 0];
        $data['productos'] = $this->model->getProductos(1);
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $ruta = 'assets/images/barcode/';
        foreach ($data['productos'] as $producto) {
            file_put_contents($ruta . $producto['id']. '.png', $generator->getBarcode($producto['codigo'], $generator::TYPE_CODE_128, 3, 50));
        }
        ob_start();
        $data['title'] = 'Barcode';
        $this->views->getView('reportes', 'barcode', $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }
    
    	public function generarBarcodeUni($id)
    {
        //$redColor = [255, 0, 0];
        $data['productos'] = $this->model->getProductosUni($id);
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $ruta = 'assets/images/barcode/';
        foreach ($data['productos'] as $producto) {
            file_put_contents($ruta . $producto['id']. '.png', $generator->getBarcode($producto['codigo'], $generator::TYPE_CODE_128, 3, 50));
        }
        ob_start();
        $data['title'] = 'Barcode';
        $this->views->getView('reportes', 'barcode', $data);
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('reporte.pdf', array('Attachment' => false));
    }
}
