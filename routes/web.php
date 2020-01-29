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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

//auth
$router->group(['prefix' => 'auth'], function() use ($router){
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');
    $router->group(['middleware' => ['auth']], function($router){
        $router->get('/logout', 'AuthController@logout');
    });
});

//guest
$router->group(['prefix' => 'guest'], function() use ($router){
    $router->get('/airlines', 'GuestController@getAirlines');
    $router->get('/flights', 'GuestController@getFlights');
});

$router->group(['middleware' => ['auth']], function($router){    
    //airline
    $router->get('/airlines', 'AirlinesController@index');
    $router->post('/airlines', 'AirlinesController@store');
    $router->get('/airlines/{id}', 'AirlinesController@show');
    $router->put('/airlines/{id}', 'AirlinesController@update');
    $router->delete('/airlines/{id}', 'AirlinesController@destroy');
    
    //flight
    $router->get('/flights', 'FlightsController@index');
    $router->post('/flights', 'FlightsController@store');
    $router->get('/flights/{id}', 'FlightsController@show');
    $router->put('/flights/{id}', 'FlightsController@update');
    $router->delete('/flights/{id}', 'FlightsController@destroy');
    
    //booking
    $router->get('/bookings', 'BookingsController@index');
    $router->post('/bookings', 'BookingsController@store');
    $router->get('/bookings/{id}', 'BookingsController@show');
    $router->put('/bookings/{id}', 'BookingsController@update');
    $router->delete('/bookings/{id}', 'BookingsController@destroy');

    //passenger
    $router->get('/passengers', 'PassengersController@index');
    $router->post('/passengers', 'PassengersController@store');
    $router->get('/passengers/{id}', 'PassengersController@show');
    $router->put('/passengers/{id}', 'PassengersController@update');
    $router->delete('/passengers/{id}', 'PassengersController@destroy');
    
    //payment
    $router->get('/payments', 'PaymentsController@index');
    $router->post('/payments', 'PaymentsController@store');
    $router->get('/payments/{id}', 'PaymentsController@show');
});