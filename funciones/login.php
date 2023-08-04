<?php 
function DatosLogin($vUsuario, $vClave, $vConexion){
    $Usuario=array();
    //agrego la función de MD5 para que se encripte y compare con lo de la tabla
    $SQL="SELECT U.Id, U.Nombre, U.Apellido, U.IdNivel, U.Imagen, U.Sexo, U.Activo, 
    N.Denominacion NombreNivel
     FROM Usuarios U, Niveles N
     WHERE Email='$vUsuario' AND Clave = MD5('$vClave') 
     AND U.IdNivel = N.Id ";

    $rs = mysqli_query($vConexion, $SQL);
        
    $data = mysqli_fetch_array($rs) ;
    if (!empty($data)) {
        $Usuario['NOMBRE'] = $data['Nombre'];
        $Usuario['APELLIDO'] = $data['Apellido'];
        $Usuario['NIVEL'] = $data['IdNivel'];
        
        switch ($data['Sexo']) {
            case 'F':
                $Usuario['SALUDO'] = 'Bienvenida';
                break;
            case 'M':
                $Usuario['SALUDO'] = 'Bienvenido';
                break;
            case 'O':
                $Usuario['SALUDO'] = 'Hola ';
                break;
        }
        

        if (empty( $data['Imagen'])) {
            $data['Imagen'] = 'user.png'; 
        }
        $Usuario['IMG'] = $data['Imagen'];
        $Usuario['ACTIVO'] = $data['Activo'];
        //agregados
        $Usuario['ID'] = $data['Id'];
        $Usuario['NOMBRE_NIVEL'] = $data['NombreNivel'];
    }
    return $Usuario;
}

?>