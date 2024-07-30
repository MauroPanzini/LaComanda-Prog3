<?php

class Pedido
{
    public $id;
    public $codigoMesa;
    public $codigoPedido;
    public $nombreCliente;
    public $precioFinal;
    public $rutaFoto;
    public $tiempoEstimadoPedido;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigoMesa, codigoPedido, nombreCliente, rutaFoto, tiempoEstimadoPedido) VALUES (:codigoMesa, :codigoPedido, :nombreCliente, :rutaFoto, :tiempoEstimadoPedido)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':rutaFoto', null, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimadoPedido', null, PDO::PARAM_STR);
        $consulta->execute();

        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas SET estado = 'cliente esperando pedido' WHERE codigo = :codigoMesa");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function modificarPedido($id, $codigoMesa, $nombreCliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET codigoMesa = :codigoMesa, nombreCliente = :nombreCliente, rutaFoto = :rutaFoto WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $nombreCliente, PDO::PARAM_STR);
        $rutaFoto = $codigoMesa . ".jpg";
        $consulta->bindValue(':rutaFoto', $rutaFoto, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function eliminarPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("DELETE FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerPedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerListadoPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public function crearPedidoCSV()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $resultado = null;

        if(!(Pedido::existePedido($this->id)))
        {
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (id, codigoMesa, nombreCliente, rutaFoto) VALUES (:id, :codigoMesa, :nombreCliente, :rutaFoto");
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
            $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
            $consulta->bindValue(':rutaFoto', $this->rutaFoto, PDO::PARAM_STR);
            $consulta->execute();
            
            $resultado = $objAccesoDatos->obtenerUltimoId();
        }
        else
        {
            Pedido::modificarPedido($this->id, $this->codigoMesa, $this->nombreCliente);   
            $resultado = $objAccesoDatos->obtenerUltimoId();
        }

        return $resultado;
    }
    
    public static function existePedido($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT 1 FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        return $resultado !== false;
    }

    public static function guardarImagenPedido($path, $codigoMesa, $tempName)
    {
        $resultado = false;
        $destino = $path . $codigoMesa . ".jpg";
        
        if(move_uploaded_file($tempName, $destino))
        {
            $resultado = true;
        }
        return $resultado;
    }

    public static function guardarImagenSQL($codigoMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET rutaFoto = :rutaFoto WHERE codigoMesa = :codigoMesa");
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $rutaFoto = $codigoMesa . ".jpg";
        $consulta->bindValue(':rutaFoto', $rutaFoto, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function crearCodigoPedido($longitud = 5) 
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigoRandom = '';
        $max = strlen($caracteres) - 1;
    
        for ($i = 0; $i < $longitud; $i++) {
            $codigoRandom .= $caracteres[random_int(0, $max)];
        }
    
        return $codigoRandom;
    }

    public static function obtenerPedidosDemorados(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos_productos WHERE estado = :estado");
        $consulta->bindValue(':estado', "entregado con demora", PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
}