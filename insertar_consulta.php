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

//ahora voy a llamar el script gral para usar las funciones necesarias
require_once 'funciones/select_general.php';

//genero el listado de categorias
$ListadoCategorias = Listar_General($MiConexion, 'Categorias');
$CantidadCategorias = count($ListadoCategorias);

//genero el listado de prioridades
$ListadoPrioridades = Listar_General($MiConexion, 'Prioridades');
$CantidadPrioridades = count($ListadoPrioridades);


if (!empty($_POST['BotonRegistrarConsulta'])) {
    Validar_Consulta();

    if (empty($_SESSION['Mensaje'])) {
        if (Registrar_Consulta($MiConexion) != false) {
            $_SESSION['Mensaje'] = "Tu consulta se ha registrado! Pronto un asesor te estará respondiendo.";
            $_SESSION['Class_Mensaje']='success';
            header ('Location: insertar_consulta.php');
            exit;
        }

    }else {
        $_SESSION['Class_Mensaje']='warning';
    }
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
                    <h1 class="page-header">Formulario de Registración nueva consulta</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Ingresa detalles para tu consulta
                        </div>
                        <div class="panel-body">
                            <form role="form" method='post'>

                                <div class="row">
                                  
                                    <div class="col-lg-6">
                                        
                                        <?php if (!empty($_SESSION['Mensaje'])) { ?>
                                        <div class="alert alert-<?php echo $_SESSION['Class_Mensaje']; ?> alert-dismissable">
                                        <?php echo $_SESSION['Mensaje']; ?>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="form-group">
                                            <label>Título:</label>
                                            <input class="form-control" type="text" name="Titulo" id="titulo" 
                                            value="<?php echo !empty($_POST['Titulo']) ? $_POST['Titulo'] : ''; ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Consulta:</label>
                                            <textarea class="form-control" name="Consulta" id="consulta"><?php echo !empty($_POST['Consulta']) ? $_POST['Consulta'] : ''; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Categoría:</label>
                                            
                                            <select class="form-control" name="Categoria" id="categoria" >
                                                <option value="">Selecciona... </option>
                                                <?php 
                                                    $Selected='';
                                                    for ($i=0; $i<$CantidadCategorias; $i++) { 
                                                        $Selected = (!empty($_POST['Categoria']) && $_POST['Categoria'] == $ListadoCategorias[$i]['ID'] )?'selected':'';
                                                        ?>
                                                    <option value="<?php echo $ListadoCategorias[$i]['ID']; ?>"   <?php echo $Selected; ?> >
                                                        <?php echo $ListadoCategorias[$i]['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Selecciona la Prioridad:</label>
                                            <div class="radio">
                                                <?php
                                                $Checked='';
                                                for ($i=0; $i<$CantidadPrioridades; $i++) { 
                                                    $Checked = (!empty($_POST['Prioridad']) && $_POST['Prioridad'] == $ListadoPrioridades[$i]['ID'] )?'checked':''; ?>
                                                    <label class="radio-inline">
                                                    <input  type="radio" name="Prioridad" id="prioridad"  <?php echo $Checked; ?> 
                                                        value="<?php echo $ListadoPrioridades[$i]['ID']; ?>" />
                                                        <?php echo $ListadoPrioridades[$i]['NOMBRE']; ?>
                                                    </label>
                                                <?php }?>
                                            </div>
                                        </div>

                                     

                                        <button type="submit" class="btn btn-default" value="Registrar" name="BotonRegistrarConsulta" >Registrar Consulta</button>
                                       
                                    </div>
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