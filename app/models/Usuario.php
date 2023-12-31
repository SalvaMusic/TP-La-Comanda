<?php

class Usuario
{
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $clave;
    public $sector;
    public $estado;
    public $fechaRegistro;
    public $fechaBaja;

    const SECTOR_COCINA = 'Cocina';
    const SECTOR_CERVECERIA = 'Cervecería';
    const SECTOR_BAR = 'Barra';
    const SECTOR_MOZO = 'Mozo';
    const SECTOR_ADMIN = 'Admin';

    const ESTADO_ACTIVO = 'ACTIVO';
    const ESTADO_SUSPENDIDO = 'SUSPENDIDO';
    const ESTADO_DESACTIVADO = 'DESACTIVADO';

    public function guardar()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if($this->id == null){
            $query = 
                "INSERT INTO usuario (nombre, apellido, email, clave, sector, estado, fechaRegistro)
                VALUES (:nombre, :apellido, :email, :clave, :sector, :estado, :fechaRegistro)";
            $consulta = $objAccesoDatos->prepararConsulta($query);
            $consulta->bindValue(':estado', Usuario::ESTADO_ACTIVO);
            $consulta->bindValue(':fechaRegistro',$this->fechaRegistro);
        } else {
            $query = "UPDATE usuario SET 
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                sector = :sector, 
                clave = :clave
                WHERE id = :id";
            $consulta = $objAccesoDatos->prepararConsulta($query);
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        }

        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
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
        $query = "UPDATE usuario SET fechaBaja = :fechaBaja, estado = :estado WHERE id = :id";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $fecha = new DateTime(date("Y-m-d"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':estado', Usuario::ESTADO_DESACTIVADO, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', $fecha->format("Y-m-d"));
        return $consulta->execute();
    }

    public static function suspenderUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $query = "UPDATE usuario SET estado = :estado WHERE id = :id";
        $consulta = $objAccesoDato->prepararConsulta($query);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':estado', Usuario::ESTADO_DESACTIVADO, PDO::PARAM_INT);
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