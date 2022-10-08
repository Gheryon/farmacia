<?php
session_start();
//si tipo==2, entonces es tecnico, sino se vuelve a login
if($_SESSION['us_tipo']==2){
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnico</title>
</head>
<body>
    <h1>Hola técnico</h1>
    <a href="../controlador/logout.php">Cerrar sesión</a>
</body>
</html>
<?php
}
else{
    header('Location: ../index.php');
}
?>
