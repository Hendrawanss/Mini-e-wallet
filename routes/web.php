<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
$router->get('/', function () {
    echo 'Mini E-Wallet';
});

// General
$router->group(['prefix' => 'user', 'middleware' => 'auth'], function () use ($router) {
    $router->post('/topup',['uses' => 'UsersController@topup']); 
    $router->post('/tf',['uses' => 'UsersController@transferBetweenUser']); 
    $router->post('/id', ['uses' => 'UsersController@getUserById']); 
    $router->put('/{id}', ['uses' => 'UsersController@updateUser']); 
});

$router->post('/login','AuthController@login'); 
$router->post('/logout',['middleware' => 'auth', 'uses' => 'AuthController@logout']); 

// ==== Area Admin ====

// **** Area User ****
$router->group(['prefix' => 'user', 'middleware' => ['auth', 'admin']], function () use ($router) {
    $router->get('/', ['uses' => 'UsersController@getAllUsers']); 
    $router->post('/', ['uses' => 'UsersController@createUsers']); 
    $router->group(['prefix' => 'history'], function () use ($router) {
        $router->get('/', ['uses' => 'UsersController@getAllUserBalanceHistory']); 
        $router->get('/{user_id}', ['uses' => 'UsersController@getUserBalanceHistoryById']); 
    });
    $router->delete('/{id}', ['uses' => 'UsersController@deleteUser']); 
});
// **** Penutup Area User ****

// **** Area Bank ****
$router->group(['prefix' => 'bank', 'middleware' => ['auth', 'admin']], function () use ($router) {
    $router->group(['prefix' => 'history'], function () use ($router) {
        $router->get('/', ['uses' => 'BankController@getAllBankBalanceHistory']); 
        $router->get('/{bank_id}', ['uses' => 'BankController@getBankBalanceHistoryById']); 
    });
    $router->get('/', ['uses' => 'BankController@getAllBanks']); 
    $router->get('/{bank_id}', ['uses' => 'BankController@getBankById']);
    $router->post('/', ['uses' => 'BankController@createBank']); 
    $router->put('/{id}', ['uses' => 'BankController@updateBank']); 
    $router->delete('/{id}', ['uses' => 'BankController@deleteBank']); 
});
// **** Penutup Area Bank ****

// ==== Penutup Area Admin ====