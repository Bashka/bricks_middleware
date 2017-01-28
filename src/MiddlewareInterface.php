<?php
namespace Bricks\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * @author Artur Sh. Mamedbekov
 */
interface MiddlewareInterface{
  /**
   * Обработчик должен возвращать результат обработки или делегировать обработку 
   * `$next`.
   *
   * @param Request $request Обрабатываемый запрос.
   * @param Response $response Результирующий ответ.
   * @param MiddlewareInterface $next [optional] Следующий обработчик.
   *
   * @return Response Результат обработки.
   */
  public function __invoke(Request $request, Response $response, MiddlewareInterface $next = null);
}
