<?php
include_once 'conexion.php';
//cada vez que se instancia una variable Usuario, se hace conexion pdo automaticamente
class Usuario{
    var $objetos;
    public function __construct()
    {
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    function login($dni, $pass){
        //usando :dni en lugar de $dni mas seguro, previene inyeccion codigo
        $sql="SELECT * FROM usuario inner join tipo_us on us_tipo=id_tipo_us where dni_us=:dni";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':dni'=>$dni));
        $objetos=$query->fetchAll();
        foreach ($objetos as $objeto) {
            $contrasena_actual=$objeto->contrasena_us;
        }
        //se comprueba si la contraseña de la base de datos está encriptada
        //si está encriptada, tendrá '$2y$10$' en la posición 0, por eso el triple =
        if(strpos($contrasena_actual, '$2y$10$')===0){
            //se compara la contraseña de la base de datos con la introducida
            if(password_verify($pass, $contrasena_actual)){
                return "logueado";
            }
        }else {
            //la contraseña no está encriptada
            if($pass==$contrasena_actual){
                return "logueado";
            }
        }
    }

    function obtener_datos_login($dni){
        $sql="SELECT * FROM usuario join tipo_us on us_tipo=id_tipo_us and dni_us=:dni";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':dni'=>$dni));
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }

    function obtener_datos($id){
        $sql="SELECT * FROM usuario join tipo_us on us_tipo=id_tipo_us and id_usuario=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id));
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }

    function editar($id_usuario, $telefono, $residencia, $correo, $sexo, $adicional){
        $sql="UPDATE usuario SET telefono_us=:telefono, residencia_us=:residencia, correo_us=:correo, sexo_us=:sexo, adicional_us=:adicional WHERE id_usuario=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id_usuario, ':telefono'=>$telefono, ':residencia'=>$residencia, ':correo'=>$correo, ':sexo'=>$sexo, ':adicional'=>$adicional));
        
    }

    function cambiar_contra($id_usuario, $oldpass, $newpass){
        //primero se consulta si la contraseña actual es correcta
        $sql="SELECT * FROM usuario WHERE id_usuario=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id_usuario));
        $this->objetos=$query->fetchAll();
        foreach ($this->objetos as $objeto) {
            $contrasena_actual=$objeto->contrasena_us;
        }
        //se comprueba si la contraseña de la base de datos está encriptada
        //si está encriptada, tendrá '$2y$10$' en la posición 0, por eso el triple =
        if(strpos($contrasena_actual, '$2y$10$')===0){
            //se compara la contraseña de la base de datos con la introducida
            if(password_verify($oldpass, $contrasena_actual)){
                //se encripta la nueva contraseña
                $pass=password_hash($newpass, PASSWORD_BCRYPT, ['cost'=>10]);
                $sql="UPDATE usuario SET contrasena_us=:newpass WHERE id_usuario=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id_usuario, ':newpass'=>$pass));
                echo 'update';
            }else {
                echo 'noupdate';
            }
        }else {
            //la contraseña no está encriptada
            if($oldpass==$contrasena_actual){
                $pass=password_hash($newpass, PASSWORD_BCRYPT, ['cost'=>10]);
                $sql="UPDATE usuario SET contrasena_us=:newpass WHERE id_usuario=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id_usuario, ':newpass'=>$pass));
                echo 'update';
            }else {
                echo 'noupdate';
            }
        }
    }

    function cambiar_avatar($id_usuario, $nombre){
        //primero se consulta si la contraseña actual es correcta
        $sql="SELECT avatar FROM usuario WHERE id_usuario=:id";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id'=>$id_usuario));
        $this->objetos=$query->fetchAll();
        
            $sql="UPDATE usuario SET avatar=:nombre WHERE id_usuario=:id";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':id'=>$id_usuario, ':nombre'=>$nombre));
        
        return $this->objetos;
    }

    function buscar(){
        //se ha introducido algún caracter a buscar, se devuelven los usuarios que encagen con la consulta
        if(!empty($_POST['consulta'])){
            $consulta=$_POST['consulta'];
            $sql="SELECT * FROM usuario JOIN tipo_us on us_tipo=id_tipo_us WHERE nombre_us LIKE :consulta";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':consulta'=>"%$consulta%"));
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }else{
            //se devuelven todos los usuarios
            $sql="SELECT * FROM usuario JOIN tipo_us on us_tipo=id_tipo_us WHERE nombre_us NOT LIKE '' ORDER BY id_usuario LIMIT 25";
            $query=$this->acceso->prepare($sql);
            $query->execute();
            $this->objetos=$query->fetchAll();
            return $this->objetos;
        }
    }

    function crear($nombre, $apellidos, $edad, $dni, $pass, $tipo, $avatar){
        //se busca si ya existe el usuario
        $sql="SELECT id_usuario FROM usuario WHERE dni_us=:dni";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':dni'=>$dni));
        $this->objetos=$query->fetchAll();
        //si ya existe el dni, no se añade el usuario
        if(!empty($this->objetos)){
            echo "noadd";
        }else{
            $sql="INSERT INTO usuario(nombre_us, apellidos_us, edad, dni_us, contrasena_us, us_tipo, avatar) VALUES (:nombre, :apellidos, :edad, :dni, :contrasena, :tipo, :avatar);";
            $query=$this->acceso->prepare($sql);
            $query->execute(array(':nombre'=>$nombre, ':apellidos'=>$apellidos, ':edad'=>$edad, ':dni'=>$dni, ':contrasena'=>$pass, ':tipo'=>$tipo, ':avatar'=>$avatar));
            echo "add";
        }
    }

    function ascender($pass, $id_ascendido, $id_usuario){
        //se comprueba que el id_usuario es correcto
        $sql="SELECT * FROM usuario WHERE id_usuario=:id_usuario";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id_usuario'=>$id_usuario));
        $this->objetos=$query->fetchAll();

        foreach ($this->objetos as $objeto) {
            $contrasena_actual=$objeto->contrasena_us;
        }
        //se comprueba si la contraseña de la base de datos está encriptada
        //si está encriptada, tendrá '$2y$10$' en la posición 0, por eso el triple =
        if(strpos($contrasena_actual, '$2y$10$')===0){
            //se compara la contraseña de la base de datos con la introducida
            if(password_verify($pass, $contrasena_actual)){
                $tipo=1;//1-->administrador
                $sql="UPDATE usuario SET us_tipo=:tipo WHERE id_usuario=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id_ascendido, ':tipo'=>$tipo));
                echo 'ascendido';
            }else{
                echo 'noascendido';
            }
        }else {
            //la contraseña no está encriptada
            if($pass==$contrasena_actual){
                $tipo=1;//1-->administrador
                $sql="UPDATE usuario SET us_tipo=:tipo WHERE id_usuario=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id_ascendido, ':tipo'=>$tipo));
                echo 'ascendido';
            }else{
                echo 'noascendido';
            }
        }
    }

    function descender($pass, $id_descendido, $id_usuario){
        //se comprueba que el id_usuario es correcto
        $sql="SELECT * FROM usuario WHERE id_usuario=:id_usuario";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id_usuario'=>$id_usuario));
        $this->objetos=$query->fetchAll();

        foreach ($this->objetos as $objeto) {
            $contrasena_actual=$objeto->contrasena_us;
        }
        //se comprueba si la contraseña de la base de datos está encriptada
        //si está encriptada, tendrá '$2y$10$' en la posición 0, por eso el triple =
        if(strpos($contrasena_actual, '$2y$10$')===0){
            //se compara la contraseña de la base de datos con la introducida
            if(password_verify($pass, $contrasena_actual)){
                $tipo=2;//2-->tecnico
                $sql="UPDATE usuario SET us_tipo=:tipo WHERE id_usuario=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id_descendido, ':tipo'=>$tipo));
                echo 'descendido';
            }else{
                echo 'nodescendido';
            }
        }else {
            //la contraseña no está encriptada
            if($pass==$contrasena_actual){
                $tipo=2;//2-->tecnico
                $sql="UPDATE usuario SET us_tipo=:tipo WHERE id_usuario=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id_descendido, ':tipo'=>$tipo));
                echo 'descendido';
            }else{
                echo 'nodescendido';
            }
        }
    }

    function borrarUsuario($pass, $id_borrado, $id_usuario){
        //se comprueba que el id_usuario es correcto
        $sql="SELECT * FROM usuario WHERE id_usuario=:id_usuario";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id_usuario'=>$id_usuario));
        $this->objetos=$query->fetchAll();

        foreach ($this->objetos as $objeto) {
            $contrasena_actual=$objeto->contrasena_us;
        }
        //se comprueba si la contraseña de la base de datos está encriptada
        //si está encriptada, tendrá '$2y$10$' en la posición 0, por eso el triple =
        if(strpos($contrasena_actual, '$2y$10$')===0){
            //se compara la contraseña de la base de datos con la introducida
            if(password_verify($pass, $contrasena_actual)){
                $sql="DELETE FROM usuario WHERE id_usuario=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id_borrado));
                echo 'borrado';
            }else{
                //el usuario no existe
                echo 'noborrado';
            }
        }else {
            //la contraseña no está encriptada
            if($pass==$contrasena_actual){
                $sql="DELETE FROM usuario WHERE id_usuario=:id";
                $query=$this->acceso->prepare($sql);
                $query->execute(array(':id'=>$id_borrado));
                echo 'borrado';
            }else{
                //el usuario no existe
                echo 'noborrado';
            }
        }
    }

    function mostrar_avatar_nav($id_usuario){
        $sql="SELECT avatar FROM usuario WHERE id_usuario=:id_usuario";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':id_usuario'=>$id_usuario));
        $this->objetos=$query->fetchAll();
        return $this->objetos;
    }

    function verificar($email, $dni){
        $sql="SELECT * FROM usuario WHERE correo_us=:email AND dni_us=:dni";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':email'=>$email, ':dni'=>$dni));
        $this->objetos=$query->fetchAll();
        if(!empty($this->objetos)){
            //query->rowCount cuenta el número de filas encontradas. Si se encontro 1, el usuario se verifica, si son 2 o más, error
            if($query->rowCount()==1){
                echo'encontrado';
            }else{
                echo 'no-encontrado';
            }
        }else{
            echo 'no-encontrado';
        }
    }

    function reemplazar($email, $dni, $codigo){
        $sql="UPDATE usuario SET contrasena_us=:codigo WHERE correo_us=:email AND dni_us=:dni";
        $query=$this->acceso->prepare($sql);
        $query->execute(array(':codigo'=>$codigo, ':email'=>$email, ':dni'=>$dni));
        //echo 'reemplazado';
    }
}
?>