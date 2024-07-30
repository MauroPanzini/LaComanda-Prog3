<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

class Usuario
{
    public $id;
    public $sector;
    public $fechaIngreso;
    public $fechaBaja;
    public $nombre;
    public $clave;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (sector, fechaIngreso, nombre, clave) VALUES (:sector, :fechaIngreso, :nombre, :clave)");
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fechaIngreso', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function modificarUsuario($id, $sector, $fechaIngreso, $fechaBaja, $nombre, $clave)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE usuarios SET sector = :sector, fechaIngreso = :fechaIngreso, fechaBaja = :fechaBaja, nombre = :nombre, clave = :clave WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->bindValue(':fechaIngreso', $fechaIngreso, PDO::PARAM_STR);
        $consulta->bindValue(':fechaBaja', $fechaBaja, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function eliminarUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d'));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function obtenerListadoUsuarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public function crearUsuarioCSV()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $resultado = null;

        if(!(Usuario::existeUsuario($this->id)))
        {
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (id, sector, fechaIngreso, fechaBaja, nombre, clave) VALUES (:id, :sector, :fechaIngreso, :fechaBaja, :nombre, :clave)");
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
            $consulta->bindValue(':fechaIngreso', $this->fechaIngreso, PDO::PARAM_STR);
            $consulta->bindValue(':fechaBaja', $this->fechaBaja, PDO::PARAM_STR);
            $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consulta->execute();
            
            $resultado = $objAccesoDatos->obtenerUltimoId();
        }
        else
        {
            Usuario::modificarUsuario($this->id, $this->sector, $this->fechaIngreso, $this->fechaBaja, $this->nombre, $this->clave);
            $resultado = $objAccesoDatos->obtenerUltimoId();
        }

        return $resultado;
    }
    
    public static function existeUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 FROM usuarios WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        return $resultado !== false;
    }
}