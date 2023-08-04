<?php
session_start();

//si tengo vacio mi elemento de sesion me tiene q redireccionar al login.. 
//al cerrarsesion para que mate todo de la sesion y el se encarga de ubicar en el login
if (empty($_SESSION['Usuario_Id']) ) {
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

$DatosUsuario=array();

if (!empty($_POST['BotonModificar'])) {

    //el boton se ha pulsado, se tienen que enviar los datos a la base
    $Nombre     =   !empty($_POST['Nombre']) ? $_POST['Nombre'] : '';
    $Apellido   =   !empty($_POST['Apellido'])?  $_POST['Apellido'] : '';
    $Email      =   !empty($_POST['Email'])?  $_POST['Email'] : '';
    $Pais       =   !empty($_POST['Pais'])?  $_POST['Pais'] : '';
    $Sexo       =   !empty($_POST['Sexo'])?  $_POST['Sexo'] : '';
    //la clave gralmente se la pide de nuevo
    //el archivo se debe subir de nuevo

    if ( Validar_Mis_Datos() != false) {  //salio bien la validacion
        /**** subo el archivo ***/
        if (Subir_Archivo() != false ) {
            //modifico en la base
            if (Modificar_Mis_Datos($MiConexion) != false) {
                $_SESSION['Mensaje'] = "Tus datos se han actualizado.";
                $_SESSION['Estilo'] = "success";
            }else {
                $_SESSION['Mensaje'] = "Tus datos no pudieron ser actualizados.";
                $_SESSION['Estilo'] = "warning";
            }
        }
    } 

}else { 
    //no pulsa el boton, es decir se estan trayendo los datos de la base
	$DatosUsuario = EncontrarUsuario($_SESSION['Usuario_Id'], $MiConexion);
    //Alojo los datos del usuario en variables para mostrarlas en el formulario
    $Nombre =   !empty($DatosUsuario['NOMBRE'])?    $DatosUsuario['NOMBRE'] : '';
    $Apellido   =   !empty($DatosUsuario['APELLIDO'])?  $DatosUsuario['APELLIDO'] : '';
    $Email  =   !empty($DatosUsuario['EMAIL'])?     $DatosUsuario['EMAIL'] : '';
    $Pais   =   !empty($DatosUsuario['ID_PAIS'])?   $DatosUsuario['ID_PAIS'] : '';
    $Sexo   =   !empty($DatosUsuario['SEXO'])?      $DatosUsuario['SEXO'] : '';
    //la imagen la tengo en la session!! 
    
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
                <?php require_once 'info_usuario.php'; ?>
               
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
                    <h1 class="page-header">Modifica tus datos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                    <form method='post' enctype="multipart/form-data" > 
                        <div class="col-lg-5">
                                        
                                        <?php if (!empty($_SESSION['Mensaje'])) { ?>
                                        <div class="alert alert-<?php echo $_SESSION['Estilo']; ?> alert-dismissable">
                                        <?php echo $_SESSION['Mensaje']; ?>
                                        </div>
                                        <?php } ?>

                                            
                                        <div class="form-group">
                                            <label>Nombre:</label>
                                            <input class="form-control" type="text" name="Nombre" id="nombre" 
                                            value="<?php echo $Nombre; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Apellido:</label>
                                            <input class="form-control" type="text" name="Apellido" id="apellido" 
                                            value="<?php echo $Apellido;  ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input class="form-control" type="email" name="Email" id="email" 
                                            value="<?php echo $Email; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Si deseas cambiar la Clave, reingresa. Sino deja en blanco:</label>
                                            <input class="form-control" type="password" name="Clave" id="clave" value="">
                                        </div>

                                        <div class="form-group">
                                            <label>Reingresa la clave:</label>
                                            <input class="form-control" type="password" name="ReClave" id="reclave" value="">
                                        </div>

                                        <div class="form-group">
                                            <label>Pais</label>
                                            <select class="form-control" name="Pais" id="pais">
                                                <option value="">Selecciona...</option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadPaises ; $i++) {
                                                    $selected =  !empty($Pais) && $Pais ==  $ListadoPaises[$i]['ID'] ? 'selected' : '' ;
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
                                                <input type="radio" name="Sexo" id="SexoF" 
                                                value="F" 
                                                <?php 
                                                echo !empty($Sexo) && $Sexo ==  'F' ? 'checked' : '' ;
                                                ?> > Femenino
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" name="Sexo" id="SexoM" 
                                                value="M" 
                                                <?php 
                                                echo !empty($Sexo) && $Sexo ==  'M' ? 'checked' : '' ;
                                                ?> > Masculino
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" name="Sexo" id="SexoO" 
                                                value="O"
                                                <?php 
                                                echo !empty($Sexo) && $Sexo ==  'O' ? 'checked' : '' ;
                                                ?> > Otro
                                            </label>
                                        </div>
                                       

                                        <div class="form-group">
                                            <label>Subi tu imagen (aceptados: png, jpg, jpeg, bmp) </label>
                                            <input type="file" name="MiArchivo" id='Archivo'>
                                        </div>

                            <button type="submit" class="btn btn-default" value="Modificar" name="BotonModificar" >Modificar mis datos</button>
                            
                        </div>


                        <div class="col-lg-3">
                                   <div class="form-group">
                                   <img alt="Mi Avatar" class="img-responsive" 
                                   src="dist/img/users/<?php echo $_SESSION['Usuario_Img'] ; ?>" />
                                   </div>
                        </div>

                    </form>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php require_once 'footer.inc.php'; ?>