<?php
session_start();

//si tengo vacio mi elemento de sesion me tiene q redireccionar al login.. 
//al cerrarsesion para que mate todo de la sesion y el se encarga de ubicar en el login
if (empty($_SESSION['Usuario_Nombre']) ) {
    header('Location: cerrarsesion.php');
    exit;
}


//voy a necesitar la conexion: incluyo la funcion de Conexion.
require_once 'funciones/conexion.php';
$MiConexion = ConexionBD();

//ahora voy a llamar el script gral para usar las funciones necesarias
require_once 'funciones/select_general.php';

//este array contendra los datos de la consulta que se desea resolver
$DatosConsulta=array();

if (!empty($_GET['ID_CONSULTA'])) {  //cuando llego del listado
    $DatosConsulta = Datos_Consulta_Completa($MiConexion ,  $_GET['ID_CONSULTA'] );
}

if ( empty($_GET['ID_CONSULTA']) || empty($DatosConsulta) ) {
    $_SESSION['Mensaje']='Esta consulta no contiene datos.';
    $_SESSION['Estilo']='warning';
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
                    <h1 class="page-header">Consulta Resuelta</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        
                        <div class="panel-body">
                            <form role="form" method='post'>

                                <div class="row">
                                    <?php 
                                        if (!empty($_SESSION['Mensaje'])) { ?>
                                        <div class="alert alert-<?php echo $_SESSION['Estilo']; ?> alert-dismissable">
                                        <?php echo $_SESSION['Mensaje']; ?>
                                        </div>
                                        <?php }  else { ?>
                                        

                                    <div class="col-lg-6">

                                        <div class="form-group">
                                            <label>Título:</label>
                                            <input class="form-control" type="text" name="Titulo" id="titulo" disabled
                                            value="<?php echo !empty($DatosConsulta['TITULO']) ? $DatosConsulta['TITULO'] : ''; ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Consulta:</label>
                                            <textarea class="form-control" name="Consulta" id="consulta" disabled><?php echo !empty($DatosConsulta['CONSULTA']) ? $DatosConsulta['CONSULTA'] : ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Categoría:</label>
                                            <input class="form-control" type="text" name="Categoria" id="categoria" disabled
                                            value="<?php echo !empty($DatosConsulta['CATEGORIA']) ? $DatosConsulta['CATEGORIA'] : ''; ?>">
                                            
                                        </div>

                                        <div class="form-group">
                                            <label>Prioridad:</label>
                                            <input class="form-control" type="text" name="Prioridad" id="prioridad" disabled
                                            value="<?php echo !empty($DatosConsulta['PRIORIDAD']) ? $DatosConsulta['PRIORIDAD'] : ''; ?>">
                                        </div>
                                           
                                        <div class="form-group">
                                            <label>Fecha de carga:</label>
                                            <input class="form-control" type="text" name="FechaCarga" id="fechacarga" disabled
                                            value="<?php echo !empty($DatosConsulta['FECHA_CARGA']) ? $DatosConsulta['FECHA_CARGA'] : ''; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Usuario que consulta:</label>
                                            <input class="form-control" type="text" name="UsuarioCarga" id="usuariocarga" disabled
                                            value="<?php echo !empty($DatosConsulta['USUARIO_CARGA']) ? $DatosConsulta['USUARIO_CARGA'] : ''; ?>">
                                        </div>
                                    
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                                <label>Resolución:</label>
                                                <textarea class="form-control" name="Resolucion" disabled
                                                rows="10"
                                                id="resolucion"><?php echo !empty($DatosConsulta['RESOLUCION']) ? $DatosConsulta['RESOLUCION'] : ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Fecha de resolución:</label>
                                            <input class="form-control" type="text" name="FechaCarga" id="fechacarga" disabled
                                            value="<?php echo !empty($DatosConsulta['FECHA_RESOLUCION']) ? $DatosConsulta['FECHA_RESOLUCION'] : ''; ?>">
                                        </div>

                                        <div class="form-group">
                                            <label>Quien resuelve:</label>
                                            <input class="form-control" type="text" name="UsuarioCarga" id="usuariocarga" disabled
                                            value="<?php echo !empty($DatosConsulta['USUARIO_RESOLUTOR']) ? $DatosConsulta['USUARIO_RESOLUTOR'] : ''; ?>">
                                        </div>

                                    </div>

                                    <?php }   ?>
                                            
                                    <a href="listado_consultas.php" 
                                        class="btn btn-success btn-info " 
                                        title="Listado"> Volver al listado  </a>
                                        
                                    <!-- /.row (nested) -->
                                </div>
                            </form>

                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

    </div>
    <!-- /#wrapper -->
<?php  
$_SESSION['Mensaje'] = ''; 
$_SESSION['Class_Mensaje'] = '';
?>
<?php require_once 'footer.inc.php'; ?>