<?php
namespace Bricks\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Делегирующий слой.
 *
 * @author Artur Sh. Mamedbekov
 */
class DelegateMiddleware implements MiddlewareInterface{
  /**
   * @var callable Обработчик.
   */
  protected $callable;

  /**
   * @param callable $callable Используемый делегатором обработчик.
   */
  public function __construct(callable $callable){
    $this->callable = $callable;
  }

  /**
   * {@inheritdoc}
   */
  public function __invoke(Request $request, Response $response, MiddlewareInterface $next = null){
    $result = call_user_func($this->callable, $request, $response, $next);
    if(!$result instanceof Response){
      $result = $response;
    }

    return $result;
  }
}
