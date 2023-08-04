<?php 
session_start();

//si tengo vacio mi elemento de sesion me tiene q redireccionar al login.. 
//al cerrarsesion para que mate todo de la sesion y el se encarga de ubicar en el login
if (empty($_SESSION['Usuario_Nombre']) ) {
    header('Location: cerrarsesion.php');
    exit;
}

/* En este script al ingresar, ya necesitamos tener a mano los datos del usuario logueado */
/* Entonces este sript se va a ejecutar la primera vez, trayendo los datos de SESSION
y por cada vez que se pulsa el guardar, usaremos los datos de POST. */

$Nombre='';
$Apellido='';
$Email='';
$Clave='';
$Nombre='';
$Nombre='';
$Nombre='';
$Nombre='';
if (!empty($_POST['botonModificar'])) {
    //si no pulsamos el boton, traemos los datos de la session
    
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
                    <h1 class="page-header">Modifica tu imagen</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                  <?php print_r($_FILES) ; ?>
                        <div class="col-lg-6">
                                        
                            <?php if (!empty($Mensaje)) { ?>
                               <div class="alert alert-<?php echo $Estilo; ?> alert-dismissable">
                               <?php echo $Mensaje; ?>
                               </div>
                            <?php } ?>

                            <form method='post' enctype="multipart/form-data" >    

                                <div class="form-group">
                                    <label>Subi tu avatar</label>
                                    <input type="file" name="Archivo" id="archivo">
                                </div>

                                <button type="submit" class="btn btn-default" value="Subir" name="BotonSubir" >Subir imagen</button>
                            </form>
                        
                            
                    
                   
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php require_once 'footer.inc.php'; ?>