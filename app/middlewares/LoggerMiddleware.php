<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoggerMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        // Fecha antes
        $before = date('Y-m-d H:i:s');
        
        // Continua al controller
        $response = $handler->handle($request);
        $existingContent = json_decode($response->getBody());
    
        // Despues
        $response = new Response();
        $existingContent->fechaAntes = $before;
        $existingContent->fechaDespues = date('Y-m-d H:i:s');
        
        $payload = json_encode($existingContent);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarRolAdmin(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        // $token = trim(explode("Bearer", $header)[1]);
        $token = isset($header[1]) ? trim(explode("Bearer", $header)[1]) : null;

        if($token!=null)
        {
            AutentificadorJWT::VerificarToken($token);
            $parametros = (array)AutentificadorJWT::ObtenerData($token);
    
            $sector = $parametros['sector'];
    
            if ($sector === 'admin') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Se requiere permiso de administrador.'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Token vacio.'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarRolMozo(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = isset($header[1]) ? trim(explode("Bearer", $header)[1]) : null;

        if($token!=null)
        {
            AutentificadorJWT::VerificarToken($token);
            $parametros = (array)AutentificadorJWT::ObtenerData($token);
    
            $sector = $parametros['sector'];
    
            if ($sector === 'mozo') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Se requiere permiso de mozo.'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Token vacio.'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarRolAdminOMozo(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = isset($header[1]) ? trim(explode("Bearer", $header)[1]) : null;

        if($token!=null)
        {
            AutentificadorJWT::VerificarToken($token);
            $parametros = (array)AutentificadorJWT::ObtenerData($token);
    
            $sector = $parametros['sector'];
    
            if ($sector === 'mozo' || $sector === 'admin') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Se requiere permiso de administrador o mozo.'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Token vacio.'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarRolEmpleadosCocina(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = isset($header[1]) ? trim(explode("Bearer", $header)[1]) : null;

        if($token!=null)
        {
            AutentificadorJWT::VerificarToken($token);
            $parametros = (array)AutentificadorJWT::ObtenerData($token);
    
            $sector = $parametros['sector'];
    
            if ($sector === 'cocinero' || $sector === 'bartender' || $sector === 'cervecero') {
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Se requiere permiso de cocinero, bartender o cervecero.'));
                $response->getBody()->write($payload);
            }
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('error' => 'Token vacio.'));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}