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

$router->get('/', function () use ($router) {
    return "welcome shangjiadao !";
});

$router->group(
    [
        'prefix'=>'api',
        'namespace'=>'Service',
    ],
    function() use ($router){
        $router->group(
            [
                'prefix'=>'wechat',
            ],
            function () use ($router){
                $router->get('test',"WechatController@test");
                $router->get('get_access_token',"WechatController@getToken");
                $router->get('refresh_access_token',"WechatController@refreshToken");
                $router->get('get_ticket',"WechatController@getTicket");
                $router->get('refresh_ticket',"WechatController@refreshTicket");
            }
        );
        $router->get('test',function (){
            return  date('Y-m-d H:i:s');
        });
        $router->get('v',function (){
            return app()->version();
        });
    }
);
