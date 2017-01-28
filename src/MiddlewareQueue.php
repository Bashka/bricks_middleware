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
class MiddlewareQueue implements MiddlewareInterface{
  /**
   * @var SplQueue Очередь обработчиков.
   */
  protected $queue;

  public function __construct(){
    $this->queue = new SplQueue;
  }

  /**
   * Добавляет слой в очередь.
   *
   * @param MiddlewareInterface $middleware Добавляемый слой.
   *
   * @return self
   */
  public function pipe(MiddlewareInterface $middleware){
    $this->queue->enqueue($middleware);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function __invoke(Request $request, Response $response, MiddlewareInterface $next = null){
    if($this->queue->isEmpty()){
      return $response;
    }
    $current = $this->queue->dequeue();
    return call_user_func($current, $request, $response, $this);
  }
}
