<?php
require_once './models/PedidoProducto.php';
require_once './models/Pedido.php';

class PedidoProductoController extends PedidoProducto
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigoPedido = $parametros['codigoPedido'];
        $idProductos = json_decode($parametros['idProductos'], true);
        $cantidades = json_decode($parametros['cantidades'], true);
        $i = 0;

        if(isset($codigoPedido, $idProductos, $cantidades))
        {
            foreach($idProductos as $idProd)
            {
                $comanda = new PedidoProducto();
                $comanda->codigoPedido = $codigoPedido;
                $comanda->idProducto = $idProd;
                $comanda->cantidad = $cantidades[$i];
                $comanda->crearPedidoProducto();
                $i++;
            }

            $payload = json_encode(array("mensaje" => "¡Comanda creada exitosamente!"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Hubo un problema al crear la comanda."));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorCodigoPedido($request, $response, $args)
    {
        $codigoPedido = $args['codigoPedido'];

        if(PedidoProducto::existePedidoProductoCodigoPedido($codigoPedido))
        {
            $pedidos = PedidoProducto::obtenerPorCodigoPedido($codigoPedido);
            $payload = json_encode(array("Lista pedidos" => $pedidos));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se encontraron pedidos con el codigo " . $codigoPedido));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorTipoEmpleadoPendiente($request, $response, $args)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = isset($header[1]) ? trim(explode("Bearer", $header)[1]) : null;
        $parametros = (array)AutentificadorJWT::ObtenerData($token);
        $tipoEmpleado = $parametros['sector'];

        if(PedidoProducto::existePedidoProductoEncargado($tipoEmpleado))
        {
            $pedidos = PedidoProducto::obtenerPorEncargadoPendiente($tipoEmpleado);
            $payload = json_encode(array("Lista pedidos" => $pedidos));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se encontraron pedidos pendientes con el encargado " . $tipoEmpleado));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TomarPedidos($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $id = $parametros['id'];
      $tiempoPreparacion = $parametros['tiempoPreparacion'];
      $estado = $parametros['estado'];

      $header = $request->getHeaderLine('Authorization');
      $token = isset($header[1]) ? trim(explode("Bearer", $header)[1]) : null;
      $parametros = (array)AutentificadorJWT::ObtenerData($token);
      $tipoEmpleado = $parametros['sector'];

      if(PedidoProducto::existePedidoIdPendiente($id, $tipoEmpleado))
      {
        PedidoProducto::modificarPedidoProducto($id, $tiempoPreparacion, $tipoEmpleado, $estado);
        $payload = json_encode(array("mensaje" => "¡Pedido tomado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un pedido pendiente con el codigo " . $id));
      }
      
      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }

    public function VerTiempoPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigoMesa = $parametros['codigoMesa'];
        $codigoPedido = $parametros['codigoPedido'];

        if(isset($codigoMesa, $codigoPedido))
        {
            $tiempoPromedio = PedidoProducto::obtenerTiempoPedido($codigoMesa, $codigoPedido);
            $tiempoRestante = implode($tiempoPromedio);
            $payload = json_encode("El pedido estará listo en {$tiempoRestante} minutos.");
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se encontraron pedidos con el codigo " . $codigoPedido . " y codigo de mesa " . $codigoMesa));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function GenerarTiempoPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $codigoPedido = $parametros['codigoPedido'];

        if(PedidoProducto::existePedidoProductoCodigoPedido($codigoPedido))
        {
            PedidoProducto::generarTiempoEstimado($codigoPedido);
            $pedidoConTiempo = PedidoProducto::obtenerPedidosConTiempoEstimado($codigoPedido);
            
            $payload = json_encode(array(""=>$pedidoConTiempo));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No hay pedidos pendientes para mostrar."));
        }
  
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorTipoEmpleadoPreparacion($request, $response, $args)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = isset($header[1]) ? trim(explode("Bearer", $header)[1]) : null;
        $parametros = (array)AutentificadorJWT::ObtenerData($token);
        $tipoEmpleado = $parametros['sector'];

        if(PedidoProducto::existePedidoProductoEncargado($tipoEmpleado))
        {
            $pedidos = PedidoProducto::obtenerPorEncargadoPreparacion($tipoEmpleado);
            $payload = json_encode(array("Lista pedidos en preparacion" => $pedidos));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No se encontraron pedidos en preparacion con el encargado " . $tipoEmpleado));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TomarPedidoListo($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $id = $parametros['id'];
      $estado = $parametros['estado'];

      $header = $request->getHeaderLine('Authorization');
      $token = isset($header[1]) ? trim(explode("Bearer", $header)[1]) : null;
      $parametros = (array)AutentificadorJWT::ObtenerData($token);
      $tipoEmpleado = $parametros['sector'];

      if(PedidoProducto::existePedidoIdPreparacion($id, $tipoEmpleado))
      {
        PedidoProducto::modificarPedidoProducto($id, 0, $tipoEmpleado, $estado);
        $payload = json_encode(array("mensaje" => "¡Producto terminado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un pedido en preparacion con el codigo " . $id));
      }
      
      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }

    public function PedidosListos($request, $response, $args)
    {
        if(PedidoProducto::existePedidoEstadoListo())
        {
          PedidoProducto::modificarEstadoMesa("cliente comiendo");
          PedidoProducto::cerrarPedido("pedido servido");
          $payload = json_encode(array("mensaje" => "¡Pedido servido exitosamente!"));
        }
        else
        {
          $payload = json_encode(array("mensaje" => "No hay pedidos listos para servir."));
        }
        
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ListarMesaMasUsada($request, $response, $args){
        $mesa = PedidoProducto::listarMesasMasUsadas();

        if($mesa)
        {
            $payload = json_encode(array("La mesa mas usada es:" => $mesa));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "No hay suficiente informacion para determinar la mesa mas usada."));
        }
        
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}