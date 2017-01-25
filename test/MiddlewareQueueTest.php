<?php
namespace BricksTest\Middleware;

use PHPUnit_Framework_TestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Bricks\Middleware\MiddlewareQueue;
use Bricks\Middleware\DelegateMiddleware;

/**
 * @author Artur Sh. Mamedbekov
 */
class MiddlewareQueueTest extends PHPUnit_Framework_TestCase{
  public function testInvoke(){
    $queue = (new MiddlewareQueue)
    ->pipe(new DelegateMiddleware(function($request, $response){
      $response->getBody()->write('foo');
    }))
    ->pipe(new DelegateMiddleware(function($request, $response){
      $response->getBody()->write('bar');
    }));

    $response = call_user_func($queue, new ServerRequest, new Response);
    $this->assertEquals('foobar', $response->getBody());
  }
}
