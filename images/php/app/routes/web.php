<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/*
 * For AJAX Allow Cros call
 * Need to add CorsMiddleware for any endpoint which need to Cors
 */
 $app->options('{all:.*}', [
     'middleware' => 'CorsMiddleware',
     function () {
         return response('');
     }
 ]);

$app->group(['middleware' => ['CorsMiddleware']], function () use ($app) {

    $app->get('/debug', 'Route@debug');
    $app->post('/route', 'Route@submit');
    $app->get('/route/{token}', 'Route@check');

});
