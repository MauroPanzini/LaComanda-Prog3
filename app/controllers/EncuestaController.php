<?php
require_once './models/Encuesta.php';

class EncuestaController extends Encuesta
{
    public function CargarUno($request, $response, $args)
    {
      $parametros = $request->getParsedBody();

      $codigoMesa = $parametros['codigoMesa'];
      $codigoPedido = $parametros['codigoPedido'];
      $puntajeMozo = $parametros['puntajeMozo'];
      $puntajeMesa = $parametros['puntajeMesa'];
      $puntajeRestaurante = $parametros['puntajeRestaurante'];
      $puntajeCocinero = $parametros['puntajeCocinero'];
      $descripcion = $parametros['descripcion'];
  
      if(isset($codigoMesa, $codigoPedido, $puntajeMozo, $puntajeMesa, $puntajeRestaurante, $puntajeCocinero, $descripcion))
      {
        $encuesta = new Encuesta();

        $encuesta->codigoMesa = $codigoMesa;
        $encuesta->codigoPedido = $codigoPedido;
        $encuesta->puntajeMozo = $puntajeMozo;
        $encuesta->puntajeMesa = $puntajeMesa;
        $encuesta->puntajeRestaurante = $puntajeRestaurante;
        $encuesta->puntajeCocinero = $puntajeCocinero;
        $encuesta->promedio = $encuesta->calcularPromedio();
        $encuesta->descripcion = $descripcion;
        
        $encuesta->crearEncuesta();
    
        $payload = json_encode(array("mensaje" => "¡Encuesta cargada exitosamente!"));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "Hubo un problema al crear la encuesta."));
      }
  
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
      $lista = Encuesta::obtenerListadoEncuestas();

      if(isset($lista))
      {
        $payload = json_encode(array("Lista comentarios:" => $lista));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No hay comentarios para mostrar."));
      }
      
      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }
    
    public function ListarMejoresComentarios($request, $response, $args)
    {
      $lista = Encuesta::traerMejoresComentarios();
      
      if(isset($lista))
      {
        $payload = json_encode(array("Mejores comentarios" => $lista));
      }
      else
      {
        $payload = json_encode(array("mensaje" => "No hay comentarios con un promedio mayor a 5 para mostrar."));
      }
      
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}