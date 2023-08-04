<?php 
session_start();
$DatosUsuario=array();

//si tengo vacio mi elemento de sesion me tiene q redireccionar al login.. 
//al cerrarsesion para que mate todo de la sesion y el se encarga de ubicar en el login
if (empty($_SESSION['Usuario_Nombre']) ) {
    header('Location: cerrarsesion.php');
    exit;
}

//voy a necesitar la conexion: incluyo la funcion de Conexion.
require_once 'funciones/conexion.php';

//genero una variable para usar mi conexion desde donde me haga falta
//no envio parametros porque ya los tiene definidos por defecto
$MiConexion = ConexionBD();


//voy a necesitar los paises para el selector
require_once 'funciones/select_paises.php';
$ListadoPaises = Listar_Paises($MiConexion);
$CantidadPaises= count($ListadoPaises);

/***debo encontrar el usuario para ver sus datos */
require_once 'funciones/select_usuarios.php';
if (!empty($_GET['ID_USER']))
	$DatosUsuario = EncontrarUsuario($_GET['ID_USER'], $MiConexion);
else {
	//me llega vacia la variable de $_GET: 
	$_SESSION['Mensaje']='Sin datos para mostrar...';
	
}


require_once 'header.inc.php'; ?>

</head>

<body>

    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    Usuario:  <?php echo $_SESSION['Usuario_Nombre'].' '.$_SESSION['Usuario_Apellido']; ?>
                </a>
            </div>
            <!-- /.navbar-header -->

            <?php require_once 'user.inc.php'; ?>
            <!-- /.navbar-top-links -->
            
            <?php require_once 'menu.inc.php'; ?>           
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Datos del usuario</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-<?php
					//si no tengo datos de usuario
						if (empty($DatosUsuario))
							echo 'default';
						else {
							//el array tiene valores
							echo $DatosUsuario['ACTIVO']==1 ? 'success' : 'warning'; 
						}?>  ">

						<?php if (empty($DatosUsuario)) { ?>
                        <div class="panel-heading ">
                            No se encuentran datos
                            
                        </div>
                        


						<?php } else { ?>
							<div class="panel-heading ">
                              El usuario se encuentra <?php echo $DatosUsuario['ACTIVO']==1 ? 'activo' : 'inactivo' ; ?>
                       		 </div>

							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-5">
										<div class="form-group">
                                            <label>Nombre:</label>
                                            <input class="form-control" type="text" name="Nombre" id="nombre" disabled
                                            value="<?php echo !empty($DatosUsuario['NOMBRE']) ? $DatosUsuario['NOMBRE'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Apellido:</label>
                                            <input class="form-control" type="text" name="Apellido" id="apellido" disabled
                                            value="<?php echo !empty($DatosUsuario['APELLIDO']) ? $DatosUsuario['APELLIDO'] : ''; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input class="form-control" type="email" name="Email" id="email" disabled
                                            value="<?php echo !empty($DatosUsuario['EMAIL']) ? $DatosUsuario['EMAIL'] : ''; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Pais</label>
                                            <select class="form-control" name="Pais" id="pais" disabled> 
                                                <option value="">Selecciona...</option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadPaises ; $i++) {
                                                    if (!empty($DatosUsuario['ID_PAIS']) && $DatosUsuario['ID_PAIS'] ==  $ListadoPaises[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoPaises[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoPaises[$i]['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label>Sexo:</label>
                                            <br />
                                            <label class="radio-inline">
                                                <input type="radio" name="Sexo" id="SexoF"  disabled
                                                value="F" 
                                                <?php echo (!empty($DatosUsuario['SEXO']) && $DatosUsuario['SEXO'] == 'F') ? 'checked':''; ?>  >Femenino
                                            </label>
                                            <label class="radio-inline" >
                                                <input type="radio" name="Sexo" id="SexoM" disabled
                                                value="M" 
                                                <?php echo (!empty($DatosUsuario['SEXO']) && $DatosUsuario['SEXO'] == 'M') ? 'checked':''; ?>>Masculino
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="Sexo" id="SexoO"  disabled
                                                value="O"
                                                <?php echo (!empty($DatosUsuario['SEXO']) && $DatosUsuario['SEXO'] == 'O') ? 'checked':''; ?>>Otro
                                            </label>
                                        </div>
                                                                    
										
									</div> 
									<div class="col-lg-3">
										<img src='dist/img/users/<?php echo $DatosUsuario['IMG']; ?>' class="img-responsive" />
									</div>
								</div>

							<!-- /.panel-body -->
							</div>
						<?php } ?> 
                        
                            <a href="listado_usuarios.php" 
                                     class="btn btn-success btn-info " 
                                     title="Listado"> Volver al listado  </a>
                        

                    <!-- /.panel -->
                    </div>
                <!-- /.col-lg-12 -->
              
            <!-- /.row -->
            </div>
        <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->
	</div>
<?php $_SESSION['Mensaje']=''; ?>
<?php require_once 'footer.inc.php'; ?>