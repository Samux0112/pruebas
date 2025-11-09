<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo BASE_URL; ?>assets/images/favicon.ico">
    <link href="<?php echo BASE_URL; ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL; ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL; ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="<?php echo BASE_URL; ?>assets/css/pace.min.css" rel="stylesheet" />
    <script src="<?php echo BASE_URL; ?>assets/js/pace.min.js"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/jquery-ui.min.css">
    <!-- Bootstrap CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/app.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/dark-theme.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/semi-dark.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/header-colors.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/DataTables/datatables.min.css" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/plugins/fullcalendar/css/main.min.css" />

    <title><?php echo TITLE . ' - ' . $data['title']; ?></title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="<?php echo BASE_URL; ?>assets/images/logo.png" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">POSTWO</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">



                <li>
                    <a href="<?php echo BASE_URL . 'admin'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-house-user"></i>
                        </div>
                        <div class="menu-title">Tablero</div>
                    </a>
                </li>



                <?php if (verificar('configuracion') || verificar('usuario') || verificar('roles') || verificar('log de acceso') || verificar('puntoVentas')) { ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                            <div class="menu-title">Administracion</div>
                        </a>
                        <ul>
                            <?php } if (verificar('usuarios')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'usuarios'; ?>"><i class="bx bx-right-arrow-alt"></i>Usuarios</a>
                            </li>
                           
                            <?php } if (verificar('roles')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'roles'; ?>"><i class="bx bx-right-arrow-alt"></i>Roles</a>
                            </li>

                            <?php } if (verificar('configuracion')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'admin/datos'; ?>"><i class="bx bx-right-arrow-alt"></i>Configuracion</a>
                            </li>
                            
                            <?php } if (verificar('log de acceso')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'admin/logs'; ?>"><i class="bx bx-right-arrow-alt"></i>Log de Acceso</a>
                            </li>
							
                            <?php } if (verificar('contingencias')) { ?>
                        <li> <a href="<?php echo BASE_URL . 'contingencias'; ?>"><i class="bx bx-right-arrow-alt"></i>Contingencias</a>
                        </li>
						<?php } if ( verificar('contingencias') || verificar('tasaMora') || verificar('medidas') || verificar('categorias' ) || verificar('productos' ) || verificar('bodegas')) { ?>
                    </ul>
                </li>
                
                
                <!--DESDE AQUI PUSE EL CODIGO DE NUEVO SI ES NECESARIO QUITARLO LO QUITAS PARA NO INTERFERIR CON TU PROGRAMACION SI ESTA BIEN DEJARLO-->
                
                 <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <div class="menu-title">Mantenimiento</div>
                    </a>
                    <ul>
                    <?php } if (verificar('tasaMora')) { ?>
                        <li> <a href="<?php echo BASE_URL . 'tasaMora'; ?>"><i class="bx bx-right-arrow-alt"></i>Interes por mora</a>
                        </li>
                        <?php } if (verificar('medidas')) { ?>
                        <li> <a href="<?php echo BASE_URL . 'medidas'; ?>"><i class="bx bx-right-arrow-alt"></i>Medidas</a>
                        </li>
                        <?php } if (verificar('categorias')) { ?>
                        <li> <a href="<?php echo BASE_URL . 'categorias'; ?>"><i class="bx bx-right-arrow-alt"></i>Categorias</a>
                        </li>
                        <?php } if (verificar('productos')) { ?>
                        <li> <a href="<?php echo BASE_URL . 'productos'; ?>"><i class="bx bx-right-arrow-alt"></i>Productos</a>
                        </li>
						<?php } if (verificar('bodegas')) { ?>
                        <li> <a href="<?php echo BASE_URL . 'bodegas'; ?>"><i class="bx bx-right-arrow-alt"></i>Bodegas</a>
                        </li>
						<?php } if (verificar('contingencias')) { ?>
                        <li> <a href="<?php echo BASE_URL . 'contingencias'; ?>"><i class="bx bx-right-arrow-alt"></i>Contingencias</a>
                        </li>
                    </ul>
                </li>
                
                <!--FIN DESDE AQUI PUSE EL CODIGO DE NUEVO SI ES NECESARIO QUITARLO LO QUITAS PARA NO INTERFERIR CON TU PROGRAMACION SI ESTA BIEN DEJARLO-->
                
                
                <?php }  if (verificar('tipoProducto')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'tipoProducto'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div class="menu-title">Tipo de Producto</div>
                    </a>
                </li>
                
                <?php }  if (verificar('clientes')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'clientes'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-people-group"></i>
                        </div>
                        <div class="menu-title">Clientes</div>
                    </a>
                </li>
                
                <?php }  if (verificar('proveedores')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'clientes2'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-people-carry-box"></i>
                        </div>
                        <div class="menu-title">Proveedores</div>
                    </a>
                </li>


                <?php }  if (verificar('cajas')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'cajas'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-box-open"></i>
                        </div>
                        <div class="menu-title">Cajas</div>
                    </a>
                </li> 
				
                <?php } if ((verificar('ingresos')) || (verificar('salidas')) ) { ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                            <div class="menu-title">Traslados</div>
                        </a>
                        <ul>
                            <?php } if (verificar('ingresos')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'ingresos'; ?>"><i class="bx bx-right-arrow-alt"></i>Ingresos</a>
                            </li>
                           
                            <?php } if (verificar('salidas')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'salidas'; ?>"><i class="bx bx-right-arrow-alt"></i>Salidas</a>
                            </li>
                        </ul>
                    </li>
					<?php } if (verificar('ingresos') && ! verificar('salidas') ) { ?>
					</ul>
                    </li>
					<?php } if ((verificar('pedidos')) || (verificar('FacturarPedidos')) || (verificar('despacho')) ) { ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                            <div class="menu-title">Pedidos</div>
                        </a>
                        <ul>
                            <?php } if (verificar('pedidos')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'pedidos'; ?>"><i class="bx bx-right-arrow-alt"></i>Nuevo Pedido</a>
                            </li>
                           
                            <?php } if (verificar('FacturarPedidos')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'pedidos/facturacion'; ?>"><i class="bx bx-right-arrow-alt"></i>Facturacion</a>
                            </li>
							<?php } if (verificar('despacho')) { ?>
                            <li> <a href="<?php echo BASE_URL . 'pedidos/despacho'; ?>"><i class="bx bx-right-arrow-alt"></i>Despacho</a>
                            </li>
					<?php } if (verificar('pedidos') || verificar('FacturarPedidos') || verificar('despacho') ) { ?>
					</ul>
                    </li>
                <?php } if (verificar('compras')) { ?>
				
                <li>
                    <a href="<?php echo BASE_URL . 'ventas2'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div class="menu-title">Compras</div>
                    </a>
                </li>



                <?php }  if (verificar('ventas')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'ventas'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-cash-register"></i>
                        </div>
                        <div class="menu-title">Ventas</div>
                    </a>
                </li>

                 <?php }  if (verificar('requisiciones')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'requisiciones'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-cash-register"></i>
                        </div>
                        <div class="menu-title">Requisiciones</div>
                    </a>
                </li>
				
				<?php }if (verificar('Nota de credito')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'notaCredito'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-cash-register"></i>
                        </div>
                        <div class="menu-title">Nota de crédito</div>
                    </a>
                </li>



                <?php }  if (verificar('cuentas por cobrar')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'creditos'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-hand-holding-heart"></i>
                        </div>
                        <div class="menu-title">Cuentas por cobrar</div>
                    </a>
                </li>




                <?php }  if (verificar('cuentas por pagar')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'creditos2'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-money-bill"></i>
                        </div>
                        <div class="menu-title">Cuentas por pagar</div>
                    </a>
                </li>




                <?php }  if (verificar('formulario medico')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'cotizaciones2'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-glasses"></i>
                        </div>
                        <div class="menu-title">Formulario Medico</div>
                    </a>
                </li>

                <?php }  if (verificar('cotizaciones')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'cotizaciones'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-rectangle-list"></i>
                        </div>
                        <div class="menu-title">Cotizaciones</div>
                    </a>
                </li>


                <?php }  if (verificar('apartados')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'apartados'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-people-arrows"></i>
                        </div>
                        <div class="menu-title">Renovaciones</div>
                    </a>
                </li>
				
				  <?php }  if (verificar('librosIva')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'librosIva'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div class="menu-title">Libros de IVA</div>
                    </a>
                </li>

                <?php }  if (verificar('inventario y kardex')) { ?>
                <li>
                    <a href="<?php echo BASE_URL . 'inventarios'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div class="menu-title">Inventario & Kardex</div>
                    </a>
                </li>
                <?php }  if (verificar('Cortez')) { ?> 
				<li>
                    <a href="<?php echo BASE_URL . 'cortez'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div class="menu-title">Corte Z</div>
                    </a>
                </li>
                 <?php } if (verificar('catalogoCuentas')) { ?> 
				<li>
                    <a href="<?php echo BASE_URL . 'catalogoCuentas'; ?>">
                        <div class="parent-icon"><i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div class="menu-title">Catalogo de Cuentas</div>
                    </a>
                </li>
                <?php } ?>		  
            </ul>
            
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="search-bar flex-grow-1">
                        <div class="position-relative">
                            <h6><?php echo TITLE; ?></h6>
                        </div>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if ($_SESSION['perfil_usuario'] == null) {
                                $perfil = BASE_URL . 'assets/images/logo.png';
                            } else {
                                $perfil = BASE_URL . $_SESSION['perfil_usuario'];
                            } ?>
                            <img src="<?php echo $perfil; ?>" class="user-img" alt="user avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?php echo $_SESSION['nombre_usuario']; ?></p>
                                <p class="designattion mb-0"><?php echo $_SESSION['correo_usuario']; ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL . 'usuarios/profile'; ?>"><i class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL . 'usuarios/salir'; ?>"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">