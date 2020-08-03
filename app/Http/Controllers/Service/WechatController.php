<?php

namespace App\Http\Controllers\Service;

use App\Help\WeChat;
use App\Exceptions\Response;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class WechatController extends BaseController
{
    public function test(Request $request){
        return  Response::successReturn(__CLASS__.'\\'.__FUNCTION__);
    }

    /**
     * 1、获取token
     * 2、刷新token
     * 3、获取ticket
     * 4、刷新ticket
     */

    /**
     * 获取token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken(Request $request){
        $this->validate($request,[
            'app_id'=>'required|string',
            'sign'  =>'required',
            'source'  =>'required',
        ]);

        //验证
        $verifySign =   WeChat::verifySign($request->input());
        if ($verifySign != $request->input('sign')){
            return  Response::failReturn([4000,'验证不通过']);
        }

        $app_id =   $request->input('app_id');
        $result   =   WeChat::getAccessToken($app_id);
        return  Response::successReturn($result);
    }

    /**
     * 刷新token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function refreshToken(Request $request){
        $this->validate($request,[
            'app_id'=>'required|string',
            'sign'  =>'required',
            'source'  =>'required',
        ]);

        //验证
        $verifySign =   WeChat::verifySign($request->input());
        if ($verifySign != $request->input('sign')){
            return  Response::failReturn([4000,'验证不通过']);
        }

        $app_id =   $request->input('app_id');
        $result   =   WeChat::getAccessToken($app_id,true);
        return  Response::successReturn($result);
    }

    /**
     * 获取ticket
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getTicket(Request $request){
        $this->validate($request,[
            'app_id'=>'required|string',
            'sign'  =>'required',
            'source'  =>'required',
        ]);

        //验证
        $verifySign =   WeChat::verifySign($request->input());
        if ($verifySign != $request->input('sign')){
            return  Response::failReturn([4000,'验证不通过']);
        }

        $app_id =   $request->input('app_id');
        $result   =   WeChat::getTicket($app_id);
        return  Response::successReturn($result);
    }

    /**
     * 刷新ticket
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function refreshTicket(Request $request){
        $this->validate($request,[
            'app_id'=>'required|string',
            'sign'  =>'required',
            'source'  =>'required',
        ]);

        //验证
        $verifySign =   WeChat::verifySign($request->input());
        if ($verifySign != $request->input('sign')){
            return  Response::failReturn([4000,'验证不通过']);
        }

        $app_id =   $request->input('app_id');
        $result   =   WeChat::getTicket($app_id,true);
        return  Response::successReturn($result);
    }
}
