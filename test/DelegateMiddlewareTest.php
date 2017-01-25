<?php
namespace BricksTest\Middleware;

use PHPUnit_Framework_TestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Bricks\Middleware\DelegateMiddleware;

/**
 * @author Artur Sh. Mamedbekov
 */
class DelegateMiddlewareTest extends PHPUnit_Framework_TestCase{
  public function testInvoke(){
    $delegate = new DelegateMiddleware(function($request, $response){
      $response->getBody()->write('test');
    });
    
    $response = call_user_func($delegate, new ServerRequest, new Response);
    $this->assertEquals('test', $response->getBody());
  }

  public function testInvoke_shouldReturnResponse(){
    $delegate = new DelegateMiddleware(function($request, $response){
      return true;
    });
  
    $response = new Response;
    $response->getBody()->write('test');
    $response = call_user_func($delegate, new ServerRequest, $response);
    $this->assertEquals('test', $response->getBody());
  }
}
