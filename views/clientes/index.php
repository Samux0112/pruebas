<?php include_once 'views/templates/header.php'; 

?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div></div>
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'clientes/inactivos'; ?>"><i class="fas fa-trash text-danger"></i> Inactivos</a>
                    </li>
                </ul>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-clientes-tab" data-bs-toggle="tab" data-bs-target="#nav-clientes" type="button" role="tab" aria-controls="nav-clientes" aria-selected="true">Clientes</button>
                <button class="nav-link" id="nav-nuevo-tab" data-bs-toggle="tab" data-bs-target="#nav-nuevo" type="button" role="tab" aria-controls="nav-nuevo" aria-selected="false">Nuevo</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-clientes" role="tabpanel" aria-labelledby="nav-clientes-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-users"></i> Listado de Clientes</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblClientes" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>IVA/NIT</th>
                                <th>N° Registro</th>
                                <th>DUI</th>
                                <th>Nombre</th>
                                <th>Telefono</th>
                                <th>Correo</th>
                                <th>Dirección</th>
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
                    <div class="row mb-6">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                            <label for="identidad">IVA/NIT<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="number" name="identidad" id="identidad" placeholder="IVA/NIT">
                            
                            <span id="errorNum_identidad" class="text-danger"></span>
                        </div>
                            </div>
                            <span id="errorIdentidad" class="text-danger"></span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="num_identidad">N° Registro<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="text" name="num_identidad" id="num_identidad" placeholder="N° Registro">
                            </div>
                            <span id="errorNum_identidad" class="text-danger"></span>
                        </div>


                        <div class="col-md-4 mb-3">
                            <label for="num_identidad">DUI <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="text" name="DUI" id="DUI" placeholder="DUI">
                            </div>
                            <span id="errorDUI" class="text-danger"></span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre">
                            </div>
                            <span id="errorNombre" class="text-danger"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input class="form-control" type="number" name="telefono" id="telefono" placeholder="Telefono">
                            </div>
                            <span id="errorTelefono" class="text-danger"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="correo">Correo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input class="form-control" type="text" name="correo" id="correo" placeholder="Correo Electrónico">
                            </div>
                            <span id="errorCorreo" class="text-danger"></span>
                        </div>
						  <div class="col-md-6 mb-3">
                            <label for="departamento">Departamento</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" list="listadepartamentoCliente" name="departamentoCliente" id="departamentoCliente" placeholder = "Departamento">
									<datalist id="listadepartamentoCliente">

									<?php
									$servername = HOST;
									$username = USER;
									$password = PASS;
									$dbname = DBNAME;

									// Create connection
									$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
									if ($conn->connect_error) {
									  die("Connection failed: " . $conn->connect_error);
									}
									$sql = "select id_departamento,departamento,id_catalogoMH from departamentos";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) {
									  // output data of each row
									  while($row = $result->fetch_assoc()) {
										echo "<option value='".$row["id_catalogoMH"]."-".$row["departamento"]."'>";
									  }
									} else {
									  echo "0";
									}
									$conn->close();

									?>
									 
									</datalist>
                            </div>
                            <span id="errorCorreo" class="text-danger"></span>
                        </div>
						<div class="col-md-6 mb-3">
                            <label for="municipio">Municipio</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                               <input class="form-control" list="listamunicipioCliente" name="municipioCliente" id="municipioCliente" placeholder = "Municipio">
								<datalist id="listamunicipioCliente">

									<?php
								

									// Create connection
									$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
									if ($conn->connect_error) {
									  die("Connection failed: " . $conn->connect_error);
									}
									$sql = "select municipio, id_municipio, id_catalogoMHmun from municipios";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) {
									  // output data of each row
									  while($row = $result->fetch_assoc()) {
										echo "<option value='".$row["id_catalogoMHmun"]."-".$row["municipio"]."'></option>";
									  }
									} else {
									  echo "0";
									}
									$conn->close();

									?>
									 
									</datalist>
                            </div>
                            <span id="errorCorreo" class="text-danger"></span>
                        </div>
						<div class="col-md-6 mb-3">
                            <label for="municipio">Actividad</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                               <input class="form-control" list="listagiroCliente" name="actividad" id="actividad" placeholder = "Actividad">
									<datalist id="listagiroCliente">

									<?php
									

									// Create connection
									$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
									if ($conn->connect_error) {
									  die("Connection failed: " . $conn->connect_error);
									}
									$sql = "select id_actividad,actividad,id_catalogoActividad from actividad";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) {
									  // output data of each row
									  while($row = $result->fetch_assoc()) {
										echo "<option value='".$row["id_catalogoActividad"]."-".$row["actividad"]."'>";
									  }
									} else {
									  echo "0";
									}
									$conn->close();

									?>
									 
									</datalist>
								
                            </div>
                            <span id="errorCorreo" class="text-danger"></span>
                        </div>
												  <div class="col-md-6 mb-3">
                            <label for="pais">Pais <span class="text-danger">  * Exportacion</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" list="listaPaises" name="pais" id="pais" placeholder = "Pais">
									<datalist id="listaPaises">

									<?php
									$servername = HOST;
									$username = USER;
									$password = PASS;
									$dbname = DBNAME;

									// Create connection
									$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
									if ($conn->connect_error) {
									  die("Connection failed: " . $conn->connect_error);
									}
									$sql = "select * from paises";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) {
									  // output data of each row
									  while($row = $result->fetch_assoc()) {
										echo "<option value='".$row["cod_paisMH"]."-".$row["pais"]."'>";
									  }
									} else {
									  echo "0";
									}
									$conn->close();

									?>
									 
									</datalist>
                            </div>
                            <span id="errorPais" class="text-danger"></span>
                        </div>
						                        <div class="col-md-6 mb-3">
                            <label for="actividadExportacion">Descripcion de Actividad <span class="text-danger">* Exportacion</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" type="text" name="actividadExportacion" id="actividadExportacion" placeholder="Actividad cliente exportacion">
                            </div>
                            <span id="errorNombre" class="text-danger"></span>
                        </div>
						<div class="col-md-6 mb-3">
                            <label for="departamento">Cuentas contables</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" list="listaCuentas" name="cuentaContable" id="cuentaContable" placeholder = "Cuentas">
									<datalist id="listaCuentas">

									<?php
									$servername = HOST;
									$username = USER;
									$password = PASS;
									$dbname = DBNAME;

									// Create connection
									$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
									if ($conn->connect_error) {
									  die("Connection failed: " . $conn->connect_error);
									}
									$sql = "select * from cuentas_contables order by codigo_cuenta;";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) {
									  // output data of each row
									  while($row = $result->fetch_assoc()) {
										echo "<option value='".$row["codigo"]." | ".$row["nombre_cuenta"]."'>";
									  }
									} else {
									  echo "0";
									}
									$conn->close();

									?>
									 
									</datalist>
                            </div>
                            <span id="errorCorreo" class="text-danger"></span>
                        </div>
						
																	<div class="col-md-6">
                        <label for="contribuyente">Contribuyente</label>
                        <select id="contribuyente" name="contribuyente" class="form-control">
                            <option value="Pequeño">Pequeño</option>
                            <option value="Mediano">Mediano</option>
                            <option value="Grande">Grande</option>
							<option value="Otro">Otro</option>
                        </select>
                    </div>
						
						<div class="col-md-12">
					
						<label class="btn btn-warning">
                                <input type="checkbox" id="chekExento" name="chekExento"> Exento
                            </label>
						</div>
						<br>
						<br>

                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="direccion">Dirreción <span class="text-danger">*</span></label>
                                <textarea id="direccion" class="form-control" name="direccion" rows="3" placeholder="Dirección"></textarea>
                            </div>
                            <span id="errorDireccion" class="text-danger"></span>
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