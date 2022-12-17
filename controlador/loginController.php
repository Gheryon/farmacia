<?php
include_once '../modelo/usuario.php';
session_start();
$user = $_POST['user'];
$pass = $_POST['pass'];

$usuario = new Usuario();

if(!empty($_SESSION['us_tipo'])){
    //session_destroy();
    switch ($_SESSION['us_tipo']) {
        case 1:
            header('location: ../vista/adm_catalogo.php');
            break;
        
        case 2:
            header('location: ../vista/adm_catalogo.php');
            break;

        case 3:
            header('location: ../vista/adm_catalogo.php');
            break;
    }
}else{
    if(!empty($usuario->login($user, $pass)=="logueado")){
        $usuario->obtener_datos_login($user);
        foreach ($usuario->objetos as $objeto) {
            $_SESSION['usuario']=$objeto->id_usuario;
            $_SESSION['us_tipo']=$objeto->us_tipo;
            $_SESSION['nombre_us']=$objeto->nombre_us;
        }
        switch ($_SESSION['us_tipo']) {
            case 1:
                header('location: ../vista/adm_catalogo.php');
                break;
            
            case 2:
                header('location: ../vista/adm_catalogo.php');
                break;
            
            case 3:
                header('location: ../vista/adm_catalogo.php');
                break;
        }
    }else{
        header('location: ../index.php');
    }
}

?>