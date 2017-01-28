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
      ->pipe(new DelegateMiddleware(function($request, $response, $next = null){
        $response = $response->withHeader('Location', '/');
        $response->getBody()->write('foo');
        return $next($request, $response);
      }))
      ->pipe(new DelegateMiddleware(function($request, $response, $next = null){
        $response->getBody()->write('bar');
        return $response;
      }));
  
    $response = call_user_func($queue, new ServerRequest, new Response);
    $this->assertEquals('foobar', $response->getBody());
    $this->assertTrue($response->hasHeader('Location'));
    $this->assertEquals('/', (string) $response->getHeader('Location')[0]);
  }
  
  public function testInvoke_shouldBreakInvokeIfNextNotUse(){
    $queue = (new MiddlewareQueue)
      ->pipe(new DelegateMiddleware(function($request, $response, $next = null){
        $response->getBody()->write('bar');
        return $response;
      }))
      ->pipe(new DelegateMiddleware(function($request, $response, $next = null){
        $response = $response->withHeader('Location', '/');
        $response->getBody()->write('foo');
        return $response;
      }));
  
    $response = call_user_func($queue, new ServerRequest, new Response);
    $this->assertEquals('bar', (string) $response->getBody());
    $this->assertFalse($response->hasHeader('Location'));
  }

  public function testInvoke_shouldReturnResponseIfQueueEmpty(){
    $queue = (new MiddlewareQueue)
      ->pipe(new DelegateMiddleware(function($request, $response, $next = null){
        $response->getBody()->write('foo');
        return $next($request, $response);
      }))
      ->pipe(new DelegateMiddleware(function($request, $response, $next = null){
        $response->getBody()->write('bar');
        return $next($request, $response);
      }));

    $response = call_user_func($queue, new ServerRequest, new Response);
    $this->assertEquals('foobar', (string) $response->getBody());
  }
}
