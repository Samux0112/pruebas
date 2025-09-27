<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-cotizaciones2-tab" data-bs-toggle="tab" data-bs-target="#nav-cotizaciones2" type="button" role="tab" aria-controls="nav-cotizaciones2" aria-selected="true">Pacientes</button>
                <button class="nav-link" id="nav-historial3-tab" data-bs-toggle="tab" data-bs-target="#nav-historial3" type="button" role="tab" aria-controls="nav-historial3" aria-selected="false">Historial de Pacientes</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-cotizaciones2" role="tabpanel" aria-labelledby="nav-cotizaciones2-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-list-alt"></i> Nuevo Paciente</h5>
                <hr>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                            <label class="btn btn-primary">
                                <input type="radio" id="barcode" checked name="buscarProducto"><i class="fas fa-barcode"></i> Barcode
                            </label>
                            <label class="btn btn-info">
                                <input type="radio" id="nombre" name="buscarProducto"><i class="fas fa-list"></i> Nombre
                            </label>
                        </div>
                    </div>

                </div>

                <!-- input para buscar codigo -->
                <div class="input-group mb-2" id="containerCodigo">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarProductoCodigo" placeholder="Ingrese Barcode - Enter" autocomplete="off">
                </div>

                <!-- input para buscar nombre -->
                <div class="input-group d-none mb-2" id="containerNombre">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarProductoNombre" placeholder="Buscar Producto" autocomplete="off">
                </div>

                <span class="text-danger fw-bold mb-2" id="errorBusqueda"></span>

                <!-- table productos -->

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="tblNuevaCotizacion2" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>SubTotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row justify-content-between">
                    <div class="col-md-4">
                        <label>Buscar Cliente</label>
                        <div class="input-group mb-2">
                            <input type="hidden" id="idCliente">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input class="form-control" type="text" id="buscarCliente" placeholder="Buscar Cliente">
                        </div>
                        <span class="text-danger fw-bold mb-2" id="errorCliente"></span>

                        <label>Telefono</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input class="form-control" type="text" id="telefonoCliente" placeholder="Telefono" disabled>
                        </div>

                        <label>Dirección</label>
                        <ul class="list-group">
                            <li class="list-group-item" id="direccionCliente"><i class="fas fa-home"></i></li>
                        </ul>

                        <label>Num orden</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numorden" placeholder="Numorden">
                        </div>

                        <label>Fecha</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="fecha1" placeholder="fecha1">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label>Vendedor</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" value="<?php echo $_SESSION['nombre_usuario']; ?>" placeholder="Vendedor" disabled>
                        </div>
                        <label>Descuento</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="descuento" placeholder="Descuento">
                        </div>

                        <label>Total a Pagar</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="totalPagar" placeholder="Total Pagar" disabled>
                        </div>

                        <label>Sucursal</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="sucursal" placeholder="sucursal">
                        </div>

                        <label>Fecha de entrega</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="fechaentre" placeholder="fechaentre">
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <label for="metodo">Metodo</label>
                            <select id="metodo" class="form-control">
                                <option value="CONTADO">CONTADO</option>
                                <option value="CREDITO">CREDITO</option>
                            </select>
                        </div>

                        


                        <div class="form-group mb-2">
                            <label for="validez">Medicos</label>
                            <select id="validez" class="form-control">
                                <option value="Dr uno">Dr uno</option>
                                <option value="Dr dos">Dr dos</option>
                                <option value="Dr tres">Dr tres</option>
                                <option value="Dr cuatro">Dr cuatro</option>
                                <option value="Dr cinco">Dr cinco</option>
                            </select>
                        </div>

                        <label>Num Factura</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numfac" placeholder="Numfac">
                        </div>

                        <label>Optica / Clinica</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="opticli" placeholder="opticli">
                        </div>

                    </div>
                    <h4><br> </h4> 
                    <h4>  <hr> </h4> 
                
                    <div class="col-md-4">
                    <label>ESF/OD</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="ESF/OD">
                        </div>

                        <label>ESF/OI</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="ESF/OI">
                        </div>

                        <label>PRIS/OD</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="PRIS/OD">
                        </div>
                        </div>


                        <div class="col-md-4">
                    <label>CIL/OD</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="CIL/OD">
                        </div>

                        <label>CIL/OI</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="CIL/0I">
                        </div>

                        <label>ADD/OD</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="ADD/OD">
                        </div>
                        </div>


                        <div class="col-md-4">
                    <label>EJE/OD</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="EJE/OD">
                        </div>

                        <label>EJE/OI</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="EJE/OI">
                        </div>                        
                        </div>


                        <div class="col-md-4">
                    <label>PRIS/OI</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="PRIS/OI">
                        </div>
                        </div>

                        <div class="col-md-4">
                        <label>ADD/OI</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="ADD/OI">
                        </div>                        
                        </div>

                        <div class="col-md-4">
                                                
                        </div>



                        <label>Tipo de lente</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="1.60 - 1.67 - 1.74 - poly">
                        </div>
                        <h4> <br></h4>
                        
                        <h4> <hr> </h4>
                        <br>

                        <div class="col-md-4">
                    <label>Diseño</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Diseño">
                        </div>

                        <label>Dnp</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Dnp">
                        </div>

                        <label>Framefito/corredor</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Framefito">
                        </div>
                        
                        <label>Distancia al vertice</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Framefito">
                        </div>

                        <label>Color</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Framefito">
                        </div>

                        <label>C</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Pantoscopico">  
                        </div>


                        </div>


                        <div class="col-md-4">
                    <label>Color</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Color">
                        </div>

                        <label>Ap</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Ap">
                        </div>

                        <label>Angulo Pantoscopico</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Pantoscopico">
                        </div>

                        <label>Aro</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Pantoscopico">
                        </div>

                        <label>A</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Pantoscopico">  
                        </div>

                        <label>D</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Pantoscopico">  
                        </div>
                        
                        </div>


                        <div class="col-md-4">
                    <label>Ar</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Ar">
                        </div>

                        <label>Ao</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Ao">
                        </div> 
                        
                        <label>Angulo panoramico</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Ao">
                        </div> 
                        
                        <label>Material</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Ao">
                        </div>   

                        <label>B</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Ao">
                        </div>  

                        <label>Observaciones</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Pantoscopico">  
                        </div>

                        </div>

                        <h4> <br></h4>

                        <h4><hr></h4>

                        <h5 class="card-title text-center"><i class="fas fa-list-alt"></i> Tipo de aro</h5>

                        <div class="col-md-4">
                    <label>Diseño/aro</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Numcoti">
                        </div>
                        </div>


                        <div class="col-md-4">
                    <label>Color/aro</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Numcoti">
                        </div>
                        </div>


                        <div class="col-md-4">
                    <label>Ar/aro</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numcoti" placeholder="Numcoti">
                        </div>
                        </div>

                        <br>

                    <div class="d-grid">
                            <button class="btn btn-primary" type="button" id="btnAccion">Completar</button>
                        </div>
                        
                </div>
            </div>
            <div class="tab-pane fade p-3" id="nav-historial3" role="tabpanel" aria-labelledby="nav-historial3-tab" tabindex="0">
                <div class="d-flex justify-content-center mb-3">
                    <div class="form-group">
                        <label for="desde">Desde</label>
                        <input id="desde" class="form-control" type="date">
                    </div>
                    <div class="form-group">
                        <label for="hasta">Hasta</label>
                        <input id="hasta" class="form-control" type="date">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblHistorial3" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Total</th>
                                <th>Cliente</th>
                                <th>Medico</th>
                                <th>Metodo</th>
                                <th>numfac</th>
                                <th>numorden</th>
                                <th>sucursal</th>
                                <th>optica /clinica</th>
                                <th>fecha1</th>
                                <th>fecha entrega</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>