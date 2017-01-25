<?php
namespace Bricks\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SplQueue;

/**
 * Очередь слоев обработки.
 *
 * @author Artur Sh. Mamedbekov
 */
class MiddlewareQueue extends SplQueue implements MiddlewareInterface{
  /**
   * Добавляет слой в очередь.
   *
   * @param MiddlewareInterface $middleware Добавляемый слой.
   *
   * @return self
   */
  public function pipe(MiddlewareInterface $middleware){
    $this->enqueue($middleware);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function __invoke(Request $request, Response $response){
    while(!$this->isEmpty()){
      $response = call_user_func($this->dequeue(), $request, $response);
    }

    return $response;
  }
}
