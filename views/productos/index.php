<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div></div>
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'productos/reportePdf'; ?>" target="_blank"><i class="fas fa-file-pdf text-danger"></i> Reporte PDF</a>
                    </li>
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'productos/reporteExcel'; ?>"><i class="fas fa-file-excel text-success"></i> Reporte Excel</a>
                    </li>
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'productos/generarBarcode'; ?>" target="_blank"><i class="fas fa-barcode"></i> Barcode</a>
                    </li>
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'productos/inactivos'; ?>"><i class="fas fa-trash text-warning"></i> Inactivos</a>
                    </li>
                </ul>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-productos-tab" data-bs-toggle="tab" data-bs-target="#nav-productos" type="button" role="tab" aria-controls="nav-productos" aria-selected="true">Productos</button>
                <?php if (!in_array($_SESSION['rol_usuario'], ['VENDEDOR', 'VENDEDORES ADMINISTRATIVO'])) { ?>
                <button class="nav-link" id="nav-nuevo-tab" data-bs-toggle="tab" data-bs-target="#nav-nuevo" type="button" role="tab" aria-controls="nav-nuevo" aria-selected="false">Nuevo</button>
                <?php } ?>

            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-productos" role="tabpanel" aria-labelledby="nav-productos-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-list"></i> Listado de Productos</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap" id="tblProductos" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>P. Compra</th>
                                <th>P. Venta</th>
                                <th>Stock</th>
                                <th>Medida</th>
                                <th>Categoria</th>
                                <th>Foto</th>
                                <th>ubicacion</th>
                                 <th>Tipo</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade p-3" id="nav-nuevo" role="tabpanel" aria-labelledby="nav-nuevo-tab" tabindex="0">
                <form id="formulario" autocomplete="off">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="foto_actual" name="foto_actual">
                    <div class="row mb-3">
                        <div class="col-md-4 mb-4">
                            <label for="codigo">Codigo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                <input class="form-control" type="text" name="codigo" id="codigo" placeholder="Barcode">
                            </div>
                            <span id="errorCodigo" class="text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre">
                            </div>
                            <span id="errorNombre" class="text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="precio_venta2">Precio Compra <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input class="form-control" type="number" step="0.01" min="0.01" name="precio_venta2" id="precio_venta2" placeholder="Precio Compra">
                            </div>
                            <span id="errorVenta2" class="text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="precio_venta">Precio Venta <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input class="form-control" type="number" step="0.0001" min="0.0001" name="precio_venta" id="precio_venta" placeholder="Precio Venta">
                            </div>
                            <span id="errorVenta" class="text-danger"></span>
                        </div>
                                                <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="id_medida">Medida <span class="text-danger">*</span></label>
                                <select id="id_medida" class="form-control" name="id_medida">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($data['medidas'] as $medida) { ?>
                                        <option value="<?php echo $medida['id']; ?>"><?php echo $medida['nombre_corto']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <span id="errorMedida" class="text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="id_categoria">Categoria <span class="text-danger">*</span></label>
                                <select id="id_categoria" class="form-control" name="id_categoria">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($data['categorias'] as $categoria) { ?>
                                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['categoria']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <span id="errorCategoria" class="text-danger"></span>
                        </div>
                    </div>

                                        <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="id_tipoProducto">Tipo de Producto <span class="text-danger">*</span></label>
                                <select id="id_tipoProducto" class="form-control" name="id_tipoProducto">
                                    <option value="">Seleccionar</option>
                                    <?php if(isset($data['tipoProducto'])) { foreach ($data['tipoProducto'] as $tipo) { ?>
                                        <option value="<?php echo $tipo['descripcion']; ?>"><?php echo $tipo['descripcion']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            <span id="errorTipoProducto" class="text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="form-group">
                                <label for="foto">Foto (Opcional)</label>
                                <input id="foto" class="form-control" type="file" name="foto">
                            </div>
                            <div id="containerPreview">
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="ubi">ubicacion de producto</label>
                            <select id="ubi" name = "ubi" class="form-control">
                                <option value=""></option>
                                <option value="Estante 1">Estante 1</option>
                                <option value="Estante 2">Estante 2</option>
                                <option value="Estante 3">Estante 3</option>
                                <option value="Estante 4">Estante 4</option>
                                <option value="Estante 5">Estante 5</option>
                                <option value="Estante 6">Estante 6</option>
                                <option value="Estante 7">Estante 7</option>
                                <option value="Estante 8">Estante 8</option>
                                <option value="Estante 9">Estante 9</option>
                                <option value="Estante 10">Estante 10</option>
                                <option value="Estante 11">Estante 11</option>
                                <option value="Estante 12">Estante 12</option>
                                <option value="Estante 13">Estante 13</option>
                                <option value="Estante 14">Estante 14</option>
                                <option value="Estante 15">Estante 15</option>
                                <option value="Estante 16">Estante 16</option>
                                <option value="Estante 17">Estante 17</option>
                                <option value="Estante 18">Estante 18</option>
                                <option value="Estante 19">Estante 19</option>
                                <option value="Estante 20">Estante 20</option>
                                <option value="Estante 21">Estante 21</option>
                                <option value="Estante 22">Estante 22</option>
                                <option value="Estante 23">Estante 23</option>
                                <option value="Estante 24">Estante 24</option>
                                <option value="Estante 25">Estante 25</option>
                            </select>
                            <span id="errorUbi" class="text-danger"></span>
                        </div>
                    </div>

                    <!-- Cuentas contables en una fila, después de los campos principales -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="cuentaVenta">Cuenta Contable de Venta</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-cash-register"></i></span>
                                <input class="form-control" list="listaCuentasVenta" name="cuentaVenta" id="cuentaVenta" placeholder="Cuenta de Venta">
                                <datalist id="listaCuentasVenta">
                                    <?php
                                    $conn = new mysqli(HOST, USER, PASS, DBNAME);
                                    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
                                    $sql = "select * from cuentas_contables order by codigo_cuenta;";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='".$row["codigo"]." | ".$row["nombre_cuenta"]."'>";
                                        }
                                    }
                                    $conn->close();
                                    ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cuentaInventario">Cuenta Contable de Inventario/Gasto</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-warehouse"></i></span>
                                <input class="form-control" list="listaCuentasInventario" name="cuentaInventario" id="cuentaInventario" placeholder="Cuenta de Inventario o Gasto">
                                <datalist id="listaCuentasInventario">
                                    <?php
                                    $conn = new mysqli(HOST, USER, PASS, DBNAME);
                                    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
                                    $sql = "select * from cuentas_contables order by codigo_cuenta;";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='".$row["codigo"]." | ".$row["nombre_cuenta"]."'>";
                                        }
                                    }
                                    $conn->close();
                                    ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cuentaCosto">Cuenta Contable de Costo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                <input class="form-control" list="listaCuentasCosto" name="cuentaCosto" id="cuentaCosto" placeholder="Cuenta de Costo">
                                <datalist id="listaCuentasCosto">
                                    <?php
                                    $conn = new mysqli(HOST, USER, PASS, DBNAME);
                                    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
                                    $sql = "select * from cuentas_contables order by codigo_cuenta;";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='".$row["codigo"]." | ".$row["nombre_cuenta"]."'>";
                                        }
                                    }
                                    $conn->close();
                                    ?>
                                </datalist>
                            </div>
                        </div>
                    </div>


                    
                    <div class="text-end">
                        <button class="btn btn-danger" type="button" id="btnNuevo">Nuevo</button>
                        <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>