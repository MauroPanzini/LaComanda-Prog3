<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $estado = $parametros['estado'];

      if(isset($estado))
      {
        $mesa = new Mesa();
        $mesa->estado = $estado;
        $mesa->codigo = Mesa::crearCodigoMesa();
        $mesa->crearMesa();
  
        $payload = json_encode(array("mensaje" => "¡Mesa creada exitosamente!", "codigoMesa" => $mesa->codigo));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Hubo un problema al crear la mesa."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
      $id = $args['id'];

      if(Mesa::existeMesa($id))
      {
        $mesa = Mesa::obtenerMesa($id);
        $payload = json_encode($mesa);
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro una mesa con el codigo " . $id));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $lista = Mesa::obtenerListadoMesas();

      if(isset($lista))
      {
        $payload = json_encode(array("listaMesas" => $lista));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No hay mesas para mostrar."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      
      $id = $parametros['id'];
      $codigo = $parametros['codigo'];
      $estado = $parametros['estado'];

      if(Mesa::existeMesa($id))
      {
        Mesa::modificarMesa($id, $codigo, $estado);
        $payload = json_encode(array("mensaje" => "¡Mesa modificada exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro una mesa con el codigo " . $id));
      }
      
      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }
      
    public function BorrarUno($request, $response, $args)
    {
      $idMesa = $args['id'];
      if(Mesa::existeMesa($idMesa))
      {
        Mesa::eliminarMesa($idMesa);
        $payload = json_encode(array("mensaje" => "¡Mesa eliminada exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro una mesa con el codigo " . $idMesa));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSV($request, $response, $args) // GET
    {
      $nombreArchivo = "mesas.csv";
      $ruta = "archivos/" . $nombreArchivo;

      if($archivo = fopen($ruta, "w"))
      {
        $lista = Mesa::obtenerListadoMesas();
        foreach( $lista as $mesa )
        {
          fputcsv($archivo, [$mesa->id, $mesa->codigo, $mesa->estado]);
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

    public function CargarCSV($request, $response, $args) // POST
    {
      $parametros = $request->getUploadedFiles();
      $archivo = isset($parametros['archivo']) ? $parametros['archivo'] : null;
      $tempFilePath = $archivo->getStream()->getMetadata('uri');

      if(($handle = fopen($tempFilePath, "r")) !== false)
      {
        while (($filaMesa = fgetcsv($handle, 0, ',')) !== false)
        {
          $nuevaMesa = new Mesa();
          $nuevaMesa->id = $filaMesa[0];
          $nuevaMesa->codigo = $filaMesa[1];
          $nuevaMesa->estado = $filaMesa[2];
          $nuevaMesa->crearMesaCSV();
        }
        fclose($handle);
        $payload =  json_encode(array("mensaje" => "¡Mesas cargadas exitosamente!"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "Hubo un problema al leer el archivo."));
      }
                
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function CobrarMesa($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $codigoMesa = $parametros['codigoMesa'];

      if(Mesa::existeMesaEstado($codigoMesa, "cliente comiendo"))
      {
        Mesa::modificarEstadoMesa($codigoMesa, "cliente pagando");
        $payload = json_encode(array("mensaje" => "El ciente está pagando el pedido"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro una mesa con el codigo " . $codigoMesa . " con un cliente comiendo"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarMesa($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      $codigoMesa = $parametros['codigoMesa'];

      if(Mesa::existeMesaEstado($codigoMesa, "cliente pagando"))
      {
        Mesa::modificarEstadoMesa($codigoMesa, "cerrada");
        $payload = json_encode(array("mensaje" => "Mesa cerrada"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro una mesa con el codigo " . $codigoMesa . " y el cliente pagando"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}