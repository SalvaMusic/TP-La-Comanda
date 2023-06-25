<?php

class Usuario
{
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $clave;
    public $roll;
    public $sector;
    public $fechaAlta;
    public $fechaBaja;

    public function guardarUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if($this->id == null){
            $fechaAlta = $this->fechaAlta != null ? $this->fechaAlta : new DateTime(date("d-m-Y"));
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre, apellido, email, clave, roll, sector, fechaAlta) VALUES (:nombre, :apellido, :email, :clave, :roll, :sector, :fechaAlta)");
            $consulta->bindValue(':fechaAlta', date_format($fechaAlta, 'Y-m-d H:i:s'));
        } else {
            $query = "UPDATE usuario SET 
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                roll = :roll,
                sector = :sector, 
                clave = :clave
                WHERE id = :id";
            $consulta = $objAccesoDatos->prepararConsulta($query);
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        }
        
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':roll', $this->roll, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->execute();

        return $this->id != null ? $this->id : $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, apellido, email, clave, roll, sector FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, apellido, email, clave, roll, sector FROM usuario WHERE id = :id");
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
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}