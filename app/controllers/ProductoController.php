<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $nombre = $parametros['nombre'];
      $precio = $parametros['precio'];
      $tiempoEstimado = $parametros['tiempoEstimado'];
      $encargado = $parametros['encargado'];

      if(isset($nombre, $precio, $encargado))
      {
        $prod = new Producto();
        $prod->nombre = $nombre;
        $prod->precio = $precio;
        $prod->tiempoEstimado = $tiempoEstimado;
        $prod->encargado = $encargado;
        $prod->crearProducto();
  
        $payload = json_encode(array("mensaje" => "¡Producto creado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Hubo un problema al crear el producto."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
      $prodNombre = $args['nombre'];
      $producto = Producto::obtenerProductoPorNombre($prodNombre);

      if($producto)
      {
        $payload = json_encode($producto);
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un producto con el nombre " . $prodNombre));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $lista = Producto::obtenerListadoProductos();
      if(isset($lista))
      {
        $payload = json_encode(array("listaProductos" => $lista));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No hay productos para mostrar."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();
      
      $id = $parametros['id'];
      $nombre = $parametros['nombre'];
      $precio = $parametros['precio'];
      $tiempoEstimado = $parametros['tiempoEstimado'];
      $encargado = $parametros['encargado'];

      if(Producto::existeProducto($id))
      {
        Producto::modificarProducto($id, $nombre, $precio, $tiempoEstimado, $encargado);
        $payload = json_encode(array("mensaje" => "¡Producto modificado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un producto con el codigo: " . $id));
      }
      
      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }
    
    public function BorrarUno($request, $response, $args)
    {
      $idProducto = $args['id'];
      
      if(isset($idProducto))
      {
        Producto::eliminarProducto($idProducto);
        $payload = json_encode(array("mensaje" => "¡Producto eliminado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un producto con el codigo: " . $idProducto));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSV($request, $response, $args) // GET
    {
      $nombreArchivo = "productos.csv";
      $ruta = "archivos/" . $nombreArchivo;

      if($archivo = fopen($ruta, "w"))
      {
        $lista = Producto::obtenerListadoProductos();
        foreach( $lista as $producto )
        {
          fputcsv($archivo, [$producto->id, $producto->nombre, $producto->precio, $producto->tiempoEstimado, $producto->encargado]);
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

    public function CargarCSV($request, $response, $args) // GET
    {
      $parametros = $request->getUploadedFiles();
      $archivo = isset($parametros['archivo']) ? $parametros['archivo'] : null;
      $tempFilePath = $archivo->getStream()->getMetadata('uri');

      if(($handle = fopen($tempFilePath, "r")) !== false)
      {
        while (($filaPedido = fgetcsv($handle, 0, ',')) !== false)
        {
          $nuevoProducto = new Producto();
          $nuevoProducto->id = $filaPedido[0];
          $nuevoProducto->nombre = $filaPedido[1];
          $nuevoProducto->precio = $filaPedido[2];
          $nuevoProducto->tiempoEstimado = $filaPedido[3];
          $nuevoProducto->encargado = $filaPedido[4];
          $nuevoProducto->crearProductoCSV();
        }
        fclose($handle);
        $payload =  json_encode(array("mensaje" => "¡Productos cargados exitosamente!"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "Hubo un problema al leer el archivo."));
      }
                
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}