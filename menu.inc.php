<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
        <span>

                    <img alt="Image placeholder" class="img-responsive" src="dist/img/users/<?php echo $_SESSION['Usuario_Img'] ; ?>" />

                    </span>
            <li>
                <a href="index.php"> <i class="fa fa-home fa-fw"></i>Pagina inicial</a>
            </li>

            <li>
                            <a href="#"><i class="fa fa-table fa-fw"></i>Listados <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li>
                                    <a href="listado_paises1.php">1) Listado Paises</a>
                                </li>
                                <li>
                                    <a href="listado_niveles1.php">2) Listado Niveles </a>
                                </li>
                                <li>
                                    <a href="listado_usuarios.php">3) Listado Usuarios </a>
                                </li>

                                <li>
                                    <a href="listado_categorias.php">4) Listado Categorias </a>
                                </li>

                                <li>
                                    <a href="listado_prioridades.php">5) Listado Prioridades </a>
                                </li>
                                
                                
                                <li>
                                    <a href="listado_consultas.php">6) Listado Consultas </a>
                                </li>
                                
                            </ul>
                            <!-- /.nav-second-level -->
            </li>
            <?php if ($_SESSION['Usuario_Nivel'] == 1 ) { //admin ?>
            <li>
                <a href="insertar_pais.php"><i class="fa fa-flag fa-fw"></i> Cargar pais</a>
            </li>

            <li>
                <a href="insertar_nivel.php"><i class="fa fa-support fa-fw"></i> Cargar nivel</a>
            </li>
            <?php } ?>

            <?php if ($_SESSION['Usuario_Nivel'] == 2 ) {  //suscriptor basico ?>
            <li>
                <a href="insertar_consulta.php"><i class="fa fa-support fa-fw"></i> Cargá tu consulta</a>
            </li>
            <?php } ?>
        </ul>
    </div>   
</div>    