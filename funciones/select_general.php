<?php
function Listar_General($vConexion , $vTabla) {

    $Listado=array();

    //1) genero la consulta que deseo con la tabla que especifico
    $SQL = "SELECT * FROM $vTabla ORDER BY Denominacion"; //ordeno por el campo denominacion

    //2) a la conexion actual le brindo mi consulta, y el resultado lo entrego a variable $rs
     $rs = mysqli_query($vConexion, $SQL);
        
     //3) el resultado deberá organizarse en una matriz, entonces lo recorro
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['NOMBRE'] = $data['Denominacion'];
            $i++;
    }


    //devuelvo el listado generado en el array $Listado. (Podra salir vacio o con datos)..
    return $Listado;

}

function Validar_Consulta(){
    $_SESSION['Mensaje']='';
    if (empty($_POST['Titulo'])) {
        $_SESSION['Mensaje'].= "Debe ingresar el Titulo. <br />";
    }
    if (strlen($_POST['Consulta']) == 0) {
        $_SESSION['Mensaje'].= "Debe ingresar el texto de la Consulta.<br />";
    }
    if (empty($_POST['Categoria'])) {
        $_SESSION['Mensaje'].= "Debe ingresar la Categoria.<br />";
    }
    if (empty($_POST['Prioridad'])) {
        $_SESSION['Mensaje'].= "Debe ingresar la Prioridad.<br />";
    }
    return $_SESSION['Mensaje'];
}

function Registrar_Consulta($vConexion) {
    $SQL_Insert="INSERT INTO Consultas (Titulo, TextoConsulta, IdCategoria, IdPrioridad,
     IdUsuarioCarga, FechaCarga )
                VALUES ('{$_POST['Titulo']}' , '{$_POST['Consulta']}' , {$_POST['Categoria']},
                 {$_POST['Prioridad']},
                '{$_SESSION['Usuario_Id']}' ,
                 NOW() )";
    //en la base es un valor tinyint que vale:
        //respondida = 0 --> Sin responder
        //respondida = 1 --> Respondida
        //por defecto se graba en cero

    if (!mysqli_query($vConexion, $SQL_Insert)) {
        //si surge un error, finalizo la ejecucion del script con un mensaje
        die('<h4>Error al intentar insertar la consulta.</h4>');
    }

    return true;
}

function Listar_Consultas($vConexion) {

    $Listado=array();

    //1) genero la consulta que deseo
    //ojo que segun el nivel de usuario debo ver cierto listado.
    //Si soy admin o asesor, vere todas
    
    $SQL = "SELECT CON.Id, CON.Titulo, CON.TextoConsulta, 
                    DATE_FORMAT( CON.FechaCarga , '%d/%m/%Y %H:%i:%s' ) FechaCarga, 
                    CAT.Denominacion Categoria_Nombre, 
                    PRI.Denominacion Prioridad_Nombre,
                    CON.Respondida
            FROM consultas CON, categorias CAT, prioridades PRI
            WHERE CON.IdCategoria = CAT.Id AND 
                  CON.IdPrioridad = PRI.Id   ";

    if ($_SESSION['Usuario_Nivel'] == 2) {
        //si soy usuario normal, veo solo las mias
        $SQL.=" AND CON.IdUsuarioCarga = ".$_SESSION['Usuario_Id'];
    }
    $SQL.="  ORDER BY CON.FechaCarga ASC ";

    //2) a la conexion actual le brindo mi consulta, y el resultado lo entrego a variable $rs
     $rs = mysqli_query($vConexion, $SQL);
        
     //3) el resultado deberá organizarse en una matriz, entonces lo recorro
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['TITULO'] = $data['Titulo'];
            $Listado[$i]['CONSULTA'] = $data['TextoConsulta'];
            $Listado[$i]['FECHA_CARGA'] = $data['FechaCarga'];
            $Listado[$i]['CATEGORIA'] = $data['Categoria_Nombre'];
            $Listado[$i]['PRIORIDAD'] = $data['Prioridad_Nombre'];
            $Listado[$i]['RESPONDIDA'] = $data['Respondida'];
            $i++;
    }


    //devuelvo el listado generado en el array $Listado. (Podra salir vacio o con datos)..
    return $Listado;

}

function Eliminar_Consulta($vConexion , $vIdConsulta) {
    //voy a permitir eliminar si :

    //soy admin 
    if ($_SESSION['Usuario_Nivel'] == 1 ) {
        $SQL_MiConsulta="SELECT Id FROM Consultas 
                        WHERE Id = $vIdConsulta ";
    }else {

    //o soy dueño de la consulta
        $SQL_MiConsulta="SELECT Id FROM Consultas 
                        WHERE Id = $vIdConsulta AND IdUsuarioCarga = ".$_SESSION['Usuario_Id'];
    }
    
    $rs = mysqli_query($vConexion, $SQL_MiConsulta);
        
    $data = mysqli_fetch_array($rs);

    if (!empty($data['Id']) ) {
        //si se cumple todo, entonces elimino:
        mysqli_query($vConexion, "DELETE FROM Consultas WHERE Id = $vIdConsulta");
        return true;

    }else {
        return false;
    }
    
}

function Datos_Consulta($vConexion , $vIdConsulta) {
    $DatosConsulta  =   array();
    //me aseguro que la consulta exista y sea dueño el usuario logueado
    $SQL = "SELECT * FROM consultas 
            WHERE Id = $vIdConsulta AND IdUsuarioCarga = {$_SESSION['Usuario_Id']} ";

    $rs = mysqli_query($vConexion, $SQL);

    $data = mysqli_fetch_array($rs) ;
    if (!empty($data)) {
        $DatosConsulta['ID_CONSULTA'] = $data['Id'];
        $DatosConsulta['TITULO'] = $data['Titulo'];
        $DatosConsulta['CONSULTA'] = $data['TextoConsulta'];
        $DatosConsulta['ID_CATEGORIA'] = $data['IdCategoria'];
        $DatosConsulta['ID_PRIORIDAD'] = $data['IdPrioridad'];
    }
    return $DatosConsulta;

}

function Modificar_Consulta($vConexion) {
    $SQL_MiConsulta="UPDATE Consultas 
                    SET Titulo = '{$_POST['Titulo']}' ,
                        TextoConsulta = '{$_POST['Consulta']}' ,
                        IdCategoria   = {$_POST['Categoria']}, 
                        IdPrioridad   = {$_POST['Prioridad']}
                        WHERE Id = {$_POST['IdConsulta']} ";

    if ( mysqli_query($vConexion, $SQL_MiConsulta) != false) {
        return true;
    }else {
        return false;
    }
    
}

function Datos_Consulta_Para_Resolver($vConexion , $vIdConsulta) {
    $DatosConsulta  =   array();
    $SQL = "SELECT CON.Id as IdConsulta,
            CON.Titulo , 
            CON.TextoConsulta,
            CON.FechaCarga,
            CAT.Denominacion Categoria_Nombre, 
            PRI.Denominacion Prioridad_Nombre,
            CONCAT(USU.Apellido, ',', USU.Nombre ) as Usuario_Nombre
            FROM consultas CON, categorias CAT, prioridades PRI, usuarios USU
            WHERE CON.IdCategoria = CAT.Id AND 
                CON.IdPrioridad = PRI.Id  AND
                CON.IdUsuarioCarga = USU.Id AND
                CON.Id = $vIdConsulta ";

    $rs = mysqli_query($vConexion, $SQL);

    $data = mysqli_fetch_array($rs) ;
    if (!empty($data)) {
        $DatosConsulta['ID_CONSULTA']   = $data['IdConsulta'];
        $DatosConsulta['TITULO']        = $data['Titulo'];
        $DatosConsulta['CONSULTA']      = $data['TextoConsulta'];
        $DatosConsulta['FECHA_CARGA']   = $data['FechaCarga'];
        $DatosConsulta['CATEGORIA']     = $data['Categoria_Nombre'];
        $DatosConsulta['PRIORIDAD']     = $data['Prioridad_Nombre'];
        $DatosConsulta['USUARIO_CARGA']   = $data['Usuario_Nombre'];

    }
    return $DatosConsulta;

}

function Resolver_Consulta($vConexion) {
    $SQL_MiConsulta="UPDATE Consultas 
                    SET Respondida          = 1,
                        Resolucion          = '{$_POST['Resolucion']}' ,
                        FechaResolucion     = NOW(),
                        IdUsuarioResolucion = {$_SESSION['Usuario_Id']}
                        WHERE Id            = {$_POST['IdConsulta']} ";

    if ( mysqli_query($vConexion, $SQL_MiConsulta) != false) {
        return true;
    }else {
        return false;
    }
}

function Datos_Consulta_Completa($vConexion , $vIdConsulta) {
    $DatosConsulta  =   array();
    $SQL = "SELECT CON.Id as IdConsulta,
            CON.Titulo , 
            CON.TextoConsulta,
            DATE_FORMAT( CON.FechaCarga, '%d/%m/%Y %H:%i:%s' ) FechaCarga,
            CON.Resolucion,
            DATE_FORMAT( CON.FechaResolucion, '%d/%m/%Y %H:%i:%s' ) as FechaResolucion,
            CAT.Denominacion Categoria_Nombre, 
            PRI.Denominacion Prioridad_Nombre,
            CONCAT(USU.Apellido, ',', USU.Nombre ) as Usuario_Nombre,
            CONCAT(USURES.Apellido, ',', USURES.Nombre ) as Resolutor_Nombre
            FROM consultas CON, categorias CAT, prioridades PRI, usuarios USU, usuarios USURES
            WHERE CON.IdCategoria = CAT.Id AND 
                CON.IdPrioridad = PRI.Id  AND
                CON.IdUsuarioCarga = USU.Id AND
                CON.IdUsuarioResolucion = USURES.Id  AND
                CON.Id = $vIdConsulta ";

    $rs = mysqli_query($vConexion, $SQL);

    $data = mysqli_fetch_array($rs) ;
    if (!empty($data)) {
        $DatosConsulta['ID_CONSULTA']   = $data['IdConsulta'];
        $DatosConsulta['TITULO']        = $data['Titulo'];
        $DatosConsulta['CONSULTA']      = $data['TextoConsulta'];
        $DatosConsulta['FECHA_CARGA']   = $data['FechaCarga'];
        $DatosConsulta['RESOLUCION']    = $data['Resolucion'];
        $DatosConsulta['FECHA_RESOLUCION']    = $data['FechaResolucion'];
        $DatosConsulta['CATEGORIA']     = $data['Categoria_Nombre'];
        $DatosConsulta['PRIORIDAD']     = $data['Prioridad_Nombre'];
        $DatosConsulta['USUARIO_CARGA']   = $data['Usuario_Nombre'];
        $DatosConsulta['USUARIO_RESOLUTOR']   = $data['Resolutor_Nombre'];

    }
    return $DatosConsulta;

}
?>