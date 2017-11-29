<?php
namespace app\Http\Middleware;

use Closure;

class CorsMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      //Intercepts OPTIONS requests
      if($request->isMethod('OPTIONS')) {
          $response = response('', 200);
      } else {
          // Pass the request to the next middleware
          $response = $next($request);
      }

      // Adds headers to the response
      $response->header('Access-Control-Allow-Methods', 'GET, POST');
      $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
      $response->header('Access-Control-Allow-Origin', '*');

      // Sends it
      return $response;
    }

}