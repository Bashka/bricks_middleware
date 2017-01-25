<?php
namespace Bricks\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * @author Artur Sh. Mamedbekov
 */
interface MiddlewareInterface{
  /**
   * @param Request $request Обрабатываемый запрос.
   * @param Response $response Результирующий ответ.
   *
   * @return Response Результат обработки.
   */
  public function __invoke(Request $request, Response $response);
}
