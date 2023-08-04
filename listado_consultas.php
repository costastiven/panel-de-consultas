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

//genero una variable para usar mi conexion desde donde me haga falta
//no envio parametros porque ya los tiene definidos por defecto
$MiConexion = ConexionBD();

//ahora voy a llamar el script con la funcion que genera mi listado
require_once 'funciones/select_general.php';







//voy a ir listando lo necesario para trabajar en este script: 
$ListadoConsultas = Listar_Consultas($MiConexion);
$CantidadConsultas = count($ListadoConsultas);

if (empty($ListadoConsultas)) {
    $_SESSION['Mensaje']="No tienes consultas registradas.";
    $_SESSION['Estilo']='info';
}

?>

<?php require_once 'header.inc.php'; ?>

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
                    <h1 class="page-header">Listado de Consultas </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <?php 
                            //el usuario Admin puede 
                            if ($_SESSION['Usuario_Nivel'] == 1 ) { ?>
                                Todas las consultas registradas
                            <?php } ?>
                            <?php 
                            //el usuario Suscriptor Basico puede :
                            if ($_SESSION['Usuario_Nivel'] == 2 ) { ?>
                                Mis consultas
                            <?php } ?>
                            <?php 
                            //el usuario Abogado Asesor puede :
                            if ($_SESSION['Usuario_Nivel'] == 3 ) { ?>
                                Consultas para Resolver
                            <?php } ?>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        <?php if (!empty($_SESSION['Mensaje'])) { ?>
                                        <div class="alert alert-<?php echo $_SESSION['Estilo']; ?> alert-dismissable">
                                        <?php echo $_SESSION['Mensaje'] ?>
                                        </div>
                            <?php } ?>


                            <?php if (!empty($ListadoConsultas)) { ?>
                            <div class="table-responsive table-bordered">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Descripción</th>
                                            <th>Fecha de Carga</th>
                                            <th>Categoria</th>
                                            <th>Prioridad</th>
                                            <th>Acciones</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i=0; $i<$CantidadConsultas; $i++) { ?>
                                            <tr class="<?php echo $ListadoConsultas[$i]['RESPONDIDA'] == 1 ? 'success' : 'danger'; ?>">
                                            <td><?php echo $i+1; ?></td>
                                            <td><?php echo $ListadoConsultas[$i]['TITULO']; ?></td>
                                            <td><?php echo $ListadoConsultas[$i]['CONSULTA']; ?></td>
                                            <td><?php echo $ListadoConsultas[$i]['FECHA_CARGA']; ?></td>
                                            <td><?php echo $ListadoConsultas[$i]['CATEGORIA']; ?></td>
                                            <td><?php echo $ListadoConsultas[$i]['PRIORIDAD']; ?></td>
                                           
                                            <td>
                                                <?php 
                                                //el usuario Admin puede 
                                                if ($_SESSION['Usuario_Nivel'] == 1 ) { ?>
                                                    <!-- eliminar la consulta -->
                                                    <a href="eliminar_consulta.php?ID_CONSULTA=<?php echo $ListadoConsultas[$i]['ID']; ?>" 
                                                        class="btn btn-success btn-circle btn-danger " 
                                                        title="Eliminar"
                                                        onclick="if (confirm('Confirma eliminar esta consulta?')) {return true;} else {return false;}">
                                                             <i class="fa fa-times"></i>
                                                    </a>
                                                    |
                                                    <!-- ver solamente la consulta -->
                                                    <a href="ver_consulta.php?ID_CONSULTA=<?php echo $ListadoConsultas[$i]['ID']; ?>" 
                                                            class="btn btn-success btn-circle btn-info " 
                                                            title="Ver">
                                                            <i class="fa fa-info"></i>
                                                        </a>
                                                <?php } ?>
                                                

                                                <?php 
                                                //el usuario Suscriptor Basico puede :
                                                if ($_SESSION['Usuario_Nivel'] == 2 ) {
                                                    //si la consulta aun no fue respondida:
                                                    if ($ListadoConsultas[$i]['RESPONDIDA'] != 1 ) { ?>
                                                        <!-- eliminar la consulta -->
                                                        <a href="eliminar_consulta.php?ID_CONSULTA=<?php echo $ListadoConsultas[$i]['ID']; ?>" 
                                                        class="btn btn-success btn-circle btn-danger " 
                                                        title="Eliminar"
                                                        onclick="if (confirm('Confirma eliminar esta consulta?')) {return true;} else {return false;}">
                                                             <i class="fa fa-times"></i>
                                                        </a>
                                                        |
                                                        <!-- modificar la consulta -->
                                                        <a href="modificar_consulta.php?ID_CONSULTA=<?php echo $ListadoConsultas[$i]['ID']; ?>" 
                                                        class="btn btn-success btn-circle btn-warning" title="Modificar">
                                                            <i class="fa fa-pencil"></i>
                                                        </a> 

                                                    <?php } else { 
                                                        //si la consulta ya se respondio, solo puede verla   ?>
                                                        <a href="ver_consulta.php?ID_CONSULTA=<?php echo $ListadoConsultas[$i]['ID']; ?>" 
                                                            class="btn btn-success btn-circle btn-info " 
                                                            title="Ver">
                                                            <i class="fa fa-info"></i>
                                                        </a>
                                                    <?php } ?>
                                                <?php } ?>

                                                <?php 
                                                //el usuario Abogado Asesor puede :
                                                if ($_SESSION['Usuario_Nivel'] == 3 ) { 
                                                    //si la consulta aun no fue respondida:
                                                    if ($ListadoConsultas[$i]['RESPONDIDA'] != 1 ) { ?>
                                                    <!-- resolver la consulta : esta accion muestra los datos y puede resolverla-->
                                                    <a href="resolver_consulta.php?ID_CONSULTA=<?php echo $ListadoConsultas[$i]['ID']; ?>" 
                                                        class="btn btn-success btn-circle btn-success " title="Resolver">
                                                        <i class="fa fa-sign-in"></i>
                                                    </a>
                                                <?php } else { 
                                                    //si la consulta ya se respondio, solo puede verla   ?>
                                                        <a href="ver_consulta.php?ID_CONSULTA=<?php echo $ListadoConsultas[$i]['ID']; ?>" 
                                                            class="btn btn-success btn-circle btn-info " 
                                                            title="Ver">
                                                            <i class="fa fa-info"></i>
                                                        </a>
                                                <?php } ?>
                                            <?php } ?>
                                            </td>
                                            </tr>  
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>

                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
            </div>
                
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php 
 $_SESSION['Mensaje']='';
require_once 'footer.inc.php'; ?>