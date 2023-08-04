<?php
function Listar_Usuarios($vConexion) {
    $Listado=array();

    //1) genero la consulta que deseo
    $SQL = "SELECT U.Id, U.Nombre, U.Apellido, U.Email, N.Denominacion as Nivel, P.Denominacion as Pais, 
                    U.Sexo, U.Activo
        FROM usuarios U, niveles N, paises P
        WHERE U.IdPais=P.Id AND U.IdNivel=N.Id
        ORDER BY U.Apellido, U.Nombre";

    //2) a la conexion actual le brindo mi consulta, y el resultado lo entrego a variable $rs
     $rs = mysqli_query($vConexion, $SQL);
        
     //3) el resultado deberá organizarse en una matriz, entonces lo recorro
     $i=0;
     
     
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['NOMBRE'] = $data['Nombre'];
            $Listado[$i]['APELLIDO'] = $data['Apellido'];
            $Listado[$i]['EMAIL'] = $data['Email'];
            $Listado[$i]['NIVEL'] = $data['Nivel'];
            $Listado[$i]['PAIS'] = $data['Pais'];
            $Listado[$i]['SEXO'] = $data['Sexo'];
            $Listado[$i]['ACTIVO'] = $data['Activo'];

            $i++;
    }


    //devuelvo el listado generado en el array $Listado. (Podra salir vacio o con datos)..
    return $Listado;

}

function EncontrarUsuario($vIDUsuario, $vConexion){
    $Usuario=array();
    
    $SQL="SELECT U.*, P.Id as Id_Pais, P.Denominacion as Pais, 
                N.Id as Id_Nivel, N.Denominacion as Nivel
     FROM Usuarios U, Paises P, Niveles N
     WHERE U.Id = $vIDUsuario
     AND U.IdNivel=N.Id
     AND U.IdPais=P.Id";

    $rs = mysqli_query($vConexion, $SQL);
        
    $data = mysqli_fetch_array($rs) ;
    if (!empty($data)) {
        $Usuario['NOMBRE']     = $data['Nombre'];
        $Usuario['APELLIDO']   = $data['Apellido'];
        $Usuario['EMAIL']      = $data['Email'];
        $Usuario['NIVEL']      = $data['Nivel'];
        $Usuario['PAIS']       = $data['Pais'];
        $Usuario['SEXO']       = $data['Sexo'];
        if (empty( $data['Imagen'])) {
            $data['Imagen'] = 'user.png'; 
        }
        $Usuario['IMG']        = $data['Imagen'];
        $Usuario['ACTIVO']     = $data['Activo'];
        //agregados
        $Usuario['ID']              = $data['Id'];
        $Usuario['ID_NIVEL']    = $data['Id_Nivel'];
        $Usuario['ID_PAIS']    = $data['Id_Pais'];
        
    }
    return $Usuario;
}

function Modificar_Acceso_Usuario($vIdUsuario, $vActivo, $vConexion){
    
    $SQL="UPDATE Usuarios SET Activo = $vActivo WHERE Id = $vIdUsuario";
    
    if (!mysqli_query($vConexion, $SQL)) {
        return false;
    }
    
    return true;
}

/***** seccion Mis Datos ****/
function Validar_Mis_Datos() {
    $_SESSION['Mensaje']='';
    $_SESSION['Estilo']='warning';
    
    if (strlen($_POST['Nombre']) < 3) {
        $_SESSION['Mensaje'].='Debes ingresar un nombre con al menos 3 caracteres. <br />';
    }
    if (strlen($_POST['Apellido']) < 3) {
        $_SESSION['Mensaje'].='Debes ingresar un apellido con al menos 3 caracteres. <br />';
    }
    if (strlen($_POST['Email']) < 5) {
        $_SESSION['Mensaje'].='Debes ingresar un correo con al menos 5 caracteres. <br />';
    }

    if (strlen($_POST['Clave']) > 0  && $_POST['Clave'] != $_POST['ReClave']) {
        $_SESSION['Mensaje'].='Las claves ingresadas deben coincidir. <br />';
    }
    
    if (empty($_POST['Pais']) ) {
        $_SESSION['Mensaje'].='Debes seleccionar tu pais. <br />';
    }
    if (empty($_POST['Sexo'])) {
        $_SESSION['Mensaje'].='Debes seleccionar el sexo. <br />';
    }
    
    //con esto aseguramos que limpiamos espacios y limpiamos de caracteres de codigo ingresados
    foreach($_POST as $Id=>$Valor){
        $_POST[$Id] = trim($_POST[$Id]);
        $_POST[$Id] = strip_tags($_POST[$Id]);
    }

    if (empty($_SESSION['Mensaje'])) 
        return true;
    else
        return false;

}


function Subir_Archivo() {
    //https://www.php.net/manual/es/function.pathinfo.php
    $TamanioMaximo=5000000;  //expresados en bytes..  5000000 --> 5mb 
    $DatoArchivo = pathinfo($_FILES["MiArchivo"]["name"]);
    $_SESSION['Mensaje']='';
    $_SESSION['Estilo']='warning';

    if (!empty($_FILES['MiArchivo']['name'])) {  //si se sube algun archivo, opero con el

        $CarpetaAlojamiento='dist/img/users';
            //vamos a agregar una restriccion de tamaño de archivos
        

        if($_FILES['MiArchivo']['size']>$TamanioMaximo){
            //se asegura q el tamanio maximo sea el especificado
            $_SESSION['Mensaje'] = 'Tu imagen supera el tamaño permitido';
            return false;

        }else {
            if (strtolower($DatoArchivo['extension']) != 'png' 
                &&  strtolower($DatoArchivo['extension']) != 'jpg'  
                &&  strtolower($DatoArchivo['extension']) != 'jpeg'  
                &&  strtolower($DatoArchivo['extension']) != 'bmp'  )   {
                    //requiere que el archivo a subir sea una imagen (para los pdf sera extension 'pdf')
                    $_SESSION['Mensaje'] = 'El archivo debe ser una imagen. ';
                    return false;

            } else {
                
                    //verificacion si el archivo se subio al servidor en forma correcta
                    if(is_uploaded_file($_FILES['MiArchivo']['tmp_name'])) {
                        //si el directorio no existe, lo creamos
                        if (!is_dir($CarpetaAlojamiento)) {
                        mkdir($CarpetaAlojamiento); //creo la carpeta
                        chmod($CarpetaAlojamiento, 0777); //asigno permisos para escribir
                        }
                        //en este caso, muevo, alojo el archivo en el servidor
                        move_uploaded_file($_FILES['MiArchivo']['tmp_name'], $CarpetaAlojamiento.'/'.$_FILES['MiArchivo']['name']);
                        return true;

                    } else {
                        $_SESSION['Mensaje'] =  'Problemas al intentar subir el archivo <strong>'.$_FILES['MiArchivo']['name'].'</strong>';
                        return false;
                    }
            }
        }

    }else {
        //si no se sube ningun archivo, no hay problema, se puede seguir
        return true;
    }
}

function Modificar_Mis_Datos($vConexion){
    $SQL="UPDATE Usuarios SET 
            Nombre      = '".$_POST['Nombre']."' ,
            Apellido    = '".$_POST['Apellido']."' ,
            Email       = '".$_POST['Email']."' ,
            IdPais        = {$_POST['Pais']} ,
            Sexo        = '".$_POST['Sexo']."' ";
    if (!empty($_POST['Clave']))
        $SQL.= ", Clave = MD5('".$_POST['Clave']."') ";
    if (!empty($_FILES['MiArchivo']["name"]))
        $SQL.= ", Imagen = '".$_FILES['MiArchivo']["name"]."' ";

    $SQL.= "WHERE Id = {$_SESSION['Usuario_Id']}";

    if (!mysqli_query($vConexion, $SQL)) {
        return false;
    }else if (!empty($_FILES['MiArchivo']["name"]))
        $_SESSION['Usuario_Img'] = $_FILES['MiArchivo']["name"];

    return true;
}

//***********************/

?>