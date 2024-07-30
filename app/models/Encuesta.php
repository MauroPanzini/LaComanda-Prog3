<?php

class Encuesta
{
    public $id;
    public $codigoMesa;
    public $codigoPedido;
    public $puntajeMozo;
    public $puntajeMesa;
    public $puntajeRestaurante;
    public $puntajeCocinero;
    public $promedio;
    public $descripcion;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (codigoMesa, codigoPedido, puntajeMozo, puntajeMesa, puntajeRestaurante, puntajeCocinero, promedio, descripcion) VALUES (:codigoMesa, :codigoPedido, :puntajeMozo, :puntajeMesa, :puntajeRestaurante, :puntajeCocinero, :promedio, :descripcion)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':puntajeMozo', $this->puntajeMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeMesa', $this->puntajeMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeRestaurante', $this->puntajeRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeCocinero', $this->puntajeCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':promedio', $this->promedio, PDO::PARAM_INT);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerEncuesta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuesta WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function obtenerListadoEncuestas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public function calcularPromedio()
    {
        return ($this->puntajeMesa + $this->puntajeRestaurante + $this->puntajeMozo + $this->puntajeCocinero) / 4;
    }

    public static function traerMejoresComentarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE promedio > 5");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
}