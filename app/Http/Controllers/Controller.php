<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use EasyWeChat\Factory;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    public function test(){
        Activity::query()->first()->dd();
    }

    //
    public function getwechat(){

        $config = [
            'app_id' => 'wx3cf0f39249eb0exx',
            'secret' => 'f1c242f4f28f735d4687abb469072axx',

            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            //...
        ];

        $app = Factory::officialAccount($config);
    }
}
