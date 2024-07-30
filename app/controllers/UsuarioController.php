<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $sector = $parametros['sector'];
      $nombre = $parametros['nombre'];
      $clave = $parametros['clave'];

      if(isset($sector, $nombre, $clave))
      {
        $usr = new Usuario();
        $usr->sector = $sector;
        $usr->nombre = $nombre;
        $usr->clave = $clave;
        $usr->crearUsuario();
  
        $payload = json_encode(array("mensaje" => "¡Usuario creado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Hubo un problema al crear el usuario."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
      $usrId = $args['id'];

      if(Usuario::existeUsuario($usrId))
      {
        $usuario = Usuario::obtenerUsuario($usrId);
        $payload = json_encode($usuario);
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un usuario con el id " . $usrId));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $lista = Usuario::obtenerListadoUsuarios();
      if(isset($lista))
      {
        $payload = json_encode(array("listaUsuarios" => $lista));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No hay usuarios para mostrar."));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args) // x-www-form-unlencoded
    {
      $parametros = $request->getParsedBody();

      $id = $parametros['id'];
      $sector = $parametros['sector'];
      $fechaIngreso = $parametros['fechaIngreso'];
      $fechaBaja = $parametros['fechaBaja'];
      $nombre = $parametros['nombre'];
      $clave = $parametros['clave'];

      if(Usuario::existeUsuario($id))
      {
        Usuario::modificarUsuario($id, $sector, $fechaIngreso, $fechaBaja, $nombre, $clave);
        $payload = json_encode(array("mensaje" => "¡Usuario modificado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un usuario con el id: " . $id));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
      $idUsuario = $args['id'];

      if(Usuario::existeUsuario($idUsuario))
      {
        Usuario::eliminarUsuario($idUsuario);
        $payload = json_encode(array("mensaje" => "¡Usuario eliminado exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No se encontro un usuario con el id: " . $idUsuario));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function GuardarCSV($request, $response, $args) // GET
    {
      $nombreArchivo = "usuarios.csv";
      $ruta = "archivos/" . $nombreArchivo;

      if($archivo = fopen($ruta, "w"))
      {
        $lista = Usuario::obtenerListadoUsuarios();
        foreach( $lista as $usuario )
        {
          fputcsv($archivo, [$usuario->id, $usuario->sector, $usuario->fechaIngreso, $usuario->fechaBaja, $usuario->nombre, $usuario->clave]);
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
        while (($filaPedido = fgetcsv($handle, 0, ',')) !== false)
        {
          $nuevoUsuario = new Usuario();
          $nuevoUsuario->id = $filaPedido[0];
          $nuevoUsuario->sector = $filaPedido[1];
          $nuevoUsuario->fechaIngreso = $filaPedido[2];
          $nuevoUsuario->fechaBaja = $filaPedido[3];
          $nuevoUsuario->nombre = $filaPedido[4];
          $nuevoUsuario->clave = $filaPedido[5];
          $nuevoUsuario->crearUsuarioCSV();
        }
        fclose($handle);
        $payload =  json_encode(array("mensaje" => "¡Usuarios cargados exitosamente!"));
      }
      else
      {
        $payload =  json_encode(array("mensaje" => "Hubo un problema al leer el archivo."));
      }
                
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function LoginUsuario($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $nombre = $parametros['nombre'];
      $clave = $parametros['clave'];

      $existe = false;
      $listaUsuarios = Usuario::obtenerListadoUsuarios();

      foreach ($listaUsuarios as $usuario) 
      {
        if($usuario->nombre == $nombre && $usuario->clave == $clave)
        {
          $existe = true;
          $idUsuario = $usuario->id;
          $sector = $usuario->sector;
        }
      }
      
      if($existe)
      {
        $datos=array('idUsuario' => $idUsuario, 'sector' => $sector);
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
      }
      else
      {
        $payload = json_encode(array('error' => 'Hubo un problema al iniciar sesion. Nombre de usuario o clave incorrectos.'));
      }

      $response->getBody()->write($payload);

      return $response->withHeader('Content-Type', 'application/json');
    }
}