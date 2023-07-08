<?php

class Usuario
{
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $clave;
    public $role_;
    public $sector;
    public $fechaRegistro;
    public $fechaBaja;

    public function guardar()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if($this->id == null){
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (nombre, apellido, email, clave, role_, sector, fechaRegistro) VALUES (:nombre, :apellido, :email, :clave, :role_, :sector, :fechaRegistro)");
            $consulta->bindValue(':fechaRegistro',$this->fechaRegistro);
        } else {
            $query = "UPDATE usuario SET 
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                role_ = :role_,
                sector = :sector, 
                clave = :clave
                WHERE id = :id";
            $consulta = $objAccesoDatos->prepararConsulta($query);
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        }

        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':role_', $this->role_, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave);
        $consulta->execute();

        return $this->id != null ? $this->id : $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', $fecha->format(DATE_FORMAT));
        $consulta->execute();
    }

    public static function obtenerUsuarioPorEmail($email)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE email = :email");
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->execute();

        $usuario = $consulta->fetchObject('Usuario');
        return $usuario;
    }
}