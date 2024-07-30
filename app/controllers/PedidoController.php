<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';
require_once './models/Mesa.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $codigoMesa = $parametros['codigoMesa'];
      $nombreCliente = $parametros['nombreCliente'];
      
      $mesa = Mesa::obtenerMesa($codigoMesa);

      if(isset($codigoMesa, $nombreCliente))
      {
        if($mesa->estado != "cerrada"){
          $payload = json_encode(array("mensaje" => "La mesa no está disponible"));
        }
        else{
          $pedido = new Pedido();
          $pedido->codigoMesa = $codigoMesa;
          $pedido->codigoPedido = Pedido::crearCodigoPedido();
          $pedido->nombreCliente = $nombreCliente;
          $pedido->crearPedido();
          
          $payload = json_encode(array("mensaje" => "¡Pedido creado exitosamente!", "codigoPedido" => $pedido->codigoPedido));
        }  
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Hubo un problema al crear el pedido."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
      $id = $args['id'];

      if(Pedido::existePedido($id))
      {
        $pedido = Pedido::obtenerPedido($id);
        $payload = json_encode($pedido);
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un pedido con el codigo " . $id));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $lista = Pedido::obtenerListadoPedidos();

      if(isset($lista))
      {
        $payload = json_encode(array("listaPedidos" => $lista));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No hay pedidos para mostrar."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      
      $id = $parametros['id'];
      $codigoMesa = $parametros['codigoMesa'];
      $nombreCliente = $parametros['nombreCliente'];

      if(Pedido::existePedido($id))
      {
        Pedido::modificarPedido($id, $codigoMesa, $nombreCliente);
        $payload = json_encode(array("mensaje" => "¡Pedido modificado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un pedido con el codigo " . $id));
      }
      
      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }
      
    public function BorrarUno($request, $response, $args)
    {
      $idPedido = $args['id'];
      
      if(Pedido::existePedido($idPedido))
      {
        Pedido::eliminarPedido($idPedido);
        $payload = json_encode(array("mensaje" => "¡Pedido eliminado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un pedido con el codigo " . $idPedido));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSV($request, $response, $args) // GET
    {
      $nombreArchivo = "pedidos.csv";
      $ruta = "archivos/" . $nombreArchivo;

      if($archivo = fopen($ruta, "w"))
      {
        $lista = Pedido::obtenerListadoPedidos();
        foreach( $lista as $pedido )
        {
          fputcsv($archivo, [$pedido->id, $pedido->codigoMesa, $pedido->nombreCliente, $pedido->rutaFoto]);
        }
        fclose($archivo);

        $csvContent = file_get_contents($ruta);

        $response->getBody()->write($csvContent);
        return $response
            ->withHeader('Content-Type', 'text/csv')
            ->withHeader('Content-Disposition', 'attachment; filename=' . $nombreArchivo);
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "Hubo un problema al abrir el archivo."));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
      }
    }

    public function CargarCSV($request, $response, $args)
    {
      $parametros = $request->getUploadedFiles();
      $archivo = isset($parametros['archivo']) ? $parametros['archivo'] : null;
      $tempFilePath = $archivo->getStream()->getMetadata('uri');

      if(($handle = fopen($tempFilePath, "r")) !== false)
      {
        while (($filaPedido = fgetcsv($handle, 0, ',')) !== false)
        {
          $nuevoPedido = new Pedido();
          $nuevoPedido->id = $filaPedido[0];
          $nuevoPedido->codigoMesa = $filaPedido[1];
          $nuevoPedido->nombreCliente = $filaPedido[2];
          $nuevoPedido->rutaFoto = $filaPedido[3];
          $nuevoPedido->crearPedidoCSV();
        }
        fclose($handle);
        $payload =  json_encode(array("mensaje" => "¡Pedidos cargados exitosamente!"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "Hubo un problema al leer el archivo."));
      }
      
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function guardarFoto($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $codigoMesa = $parametros['codigoMesa'];
      $archivo = isset($_FILES['foto']) ? $_FILES['foto'] : null;

      if(isset($codigoMesa) && $archivo['name'] != "")
      {
        $tempFilePath = $archivo['tmp_name'];
        Pedido::guardarImagenPedido("archivos/ImagenesPedidos/", $codigoMesa, $tempFilePath);
        Pedido::guardarImagenSQL($codigoMesa);

        $payload = json_encode(array("mensaje" => "¡Imagen guardada exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Hubo un problema al guardar la foto."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function obtenerDemorados($request, $response, $args){
      $lista = Pedido::obtenerPedidosDemorados();

      if(isset($lista))
      {
        $payload = json_encode(array("Pedidos demorados: " => $lista));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No hay pedidos demorados para mostrar."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}