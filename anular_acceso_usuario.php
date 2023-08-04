<?php
    session_start();
    if (empty($_SESSION['Usuario_Nombre']) ) {
        header('Location: cerrarsesion.php');
        exit;
    }
    
    require_once 'funciones/conexion.php';
    $MiConexion = ConexionBD();
   
    require_once 'funciones/select_usuarios.php';
    if (!empty($_GET['ID_USER']) && $_GET['ID_USER']>0  )  {
        
        $Activacion=$_GET['ACTIVE']==1 ? 0 : 1;

        if (Modificar_Acceso_Usuario($_GET['ID_USER'] , $Activacion , $MiConexion ) != false) {
            $_SESSION['Mensaje'].='Se ha modificado el acceso del usuario. <br /> ';
            $_SESSION['Estilo']='success';
        }else {
            $_SESSION['Mensaje'].='No se pudo el acceso del usuario. <br /> ';
            $_SESSION['Estilo']='warning';
        }
    }
    
   
    header('Location: listado_usuarios.php');
    exit;
?>