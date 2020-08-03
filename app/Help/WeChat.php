<?php

namespace App\Help;


use App\Exceptions\Response;
use App\Models\WechatApp;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Cache\Adapter\RedisAdapter;

/**
 * Class WeChat
 *
 * @date 2019-08-01 11:08:50
 * @package Help
 */
class WeChat
{

    //获取密钥key
    CONST   AUTH_WECHAT_SERVICE_KEY    =   'p7EpyJ0djgulz8gB0uTOQKlRdLaCwZNN';

    /**
     * 签名
     * @param array $params
     * @param $secret_key
     * @return string
     */
    static public function getSign(array $params, $secret_key)
    {
        unset($params['sign']);
        ksort($params);
        $params['secret_key'] = $secret_key;
        return md5(http_build_query($params));
    }

    static public function verifySign(array $input = []){
        //鉴权
        $verifySign    =    self::getSign($input,self::AUTH_WECHAT_SERVICE_KEY);
        return  $verifySign;
    }

    /**
     * 根据公众号id获取公众号配置
     * @param $app_id
     * @return Builder|Model|object|null
     */
    static public function get_app($app_id)
    {
        return WechatApp::query()
            ->where('app_id', '=', $app_id)
            ->where('status', '=', 'release')
            ->first();
    }

    /**
     * 处理 access_token
     * @param WechatApp $wechatApp
     * @param array $accessArr
     * @param array $access_extend
     * @return array
     */
    static public function doAccessToken(WechatApp $wechatApp,array $accessArr,array $access_extend=[])
    {
        $expire_time = time() + $accessArr['expires_in'];
        $expires_in  = $accessArr['expires_in'];

        if (empty($access_extend) || empty($access_extend['access_token']) || ($access_extend['expire_time'] <= time())) {
            $tokenArr                 = [
                'update_time'        => date('Y-m-d H:i:s'),
                'access_token'       => $accessArr['access_token'],
                'expires_in'         => $expires_in,
                'expire_time'        => $expire_time,
                'expire_time_format' => date('Y-m-d H:i:s', $expire_time),
            ];
            $wechatApp->access_extend = json_encode($tokenArr, JSON_UNESCAPED_UNICODE);
            $wechatApp->save();
        } else {
            $tokenArr = [
                'update_time'        => $access_extend['update_time'] ?? date('Y-m-d H:i:s'),
                'access_token'       => $accessArr['access_token'],
                'expires_in'         => $expires_in,
                'expire_time'        => $access_extend['expire_time'] ?? $expire_time,
                'expire_time_format' => $access_extend['expire_time_format'] ?? date('Y-m-d H:i:s', $expire_time),
            ];
        }

        return $tokenArr;
    }

    /**
     * @param $app_id
     * @param bool $refresh
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    static public function getAccessToken($app_id, $refresh = false)
    {
        try {
            $wechatApp = self::get_app($app_id);
            if (!$wechatApp) throw new \Exception("微信配置不存在");
            $access_extend = $wechatApp->access_extend ? json_decode($wechatApp->access_extend, true) : [];
            if (empty($access_extend)) $refresh = true;
            $app       = self::getWechat($wechatApp);
            $accessArr = $app->access_token->getToken($refresh);
            if (!$accessArr || !isset($accessArr['access_token'])) throw new \Exception("access_token获取失败1001");
            if (!$accessArr['access_token']) throw new \Exception("access_token获取失败1002");

            $tokenArr = self::doAccessToken($wechatApp, $accessArr, $access_extend);

            return [
                'result_code' => 'success',
                'result_data' => $tokenArr,
            ];
        } catch (\Exception $exception) {
            $result = [
                'result_code' => 'fail',
                'result_data' => $exception->getMessage()
            ];
            Log::info(__CLASS__ . '/' . __FUNCTION__ . '--access_token error--' . json_encode($result, JSON_UNESCAPED_UNICODE));
            return $result;
        }
    }


    /**
     * 处理 ticket
     * @param WechatApp $wechatApp
     * @param array $ticketArr
     * @param array $ticket_extend
     * @return array
     */
    static public function doTicket(WechatApp $wechatApp, array $ticketArr, array $ticket_extend=[])
    {
        $expire_time = time() + $ticketArr['expires_in'];
        $expires_in  = $ticketArr['expires_in'];
        if (empty($ticket_extend) || empty($ticket_extend['ticket']) || ($ticket_extend['expire_time'] <= time())) {
            $tokenArr                 = [
                'update_time'        => date('Y-m-d H:i:s'),
                'ticket'             => $ticketArr['ticket'],
                'expires_in'         => $expires_in,
                'expire_time'        => $expire_time,
                'expire_time_format' => date('Y-m-d H:i:s', $expire_time),
            ];
            $wechatApp->ticket_extend = json_encode($tokenArr, JSON_UNESCAPED_UNICODE);
            $wechatApp->save();
        } else {
            $tokenArr = [
                'update_time'        => $ticket_extend['update_time'] ?? date('Y-m-d H:i:s'),
                'ticket'             => $ticketArr['ticket'],
                'expires_in'         => $expires_in,
                'expire_time'        => $ticket_extend['expire_time'] ?? $expire_time,
                'expire_time_format' => $ticket_extend['expire_time_format'] ?? date('Y-m-d H:i:s', $expire_time),
            ];
        }
        return $tokenArr;
    }

    /**
     * @param $app_id
     * @param bool $refresh
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    static public function getTicket($app_id, $refresh = false)
    {
        try {
            $wechatApp = self::get_app($app_id);
            if (!$wechatApp) throw new \Exception("微信配置不存在");
            $ticket_extend = $wechatApp->ticket_extend ? json_decode($wechatApp->ticket_extend, true) : [];
            if (!$ticket_extend) $refresh = true;
            $app       = self::getWechat($wechatApp);
            $ticketArr = $app->jssdk->getTicket($refresh);
            if (!$ticketArr || !isset($ticketArr['ticket'])) throw new \Exception("ticket获取失败1001");
            if (!$ticketArr['ticket']) throw new \Exception("ticket获取失败1002");

            $tokenArr = self::doTicket($wechatApp, $ticketArr, $ticket_extend);
            $result   = [
                'result_code' => 'success',
                'result_data' => $tokenArr,
            ];
            return $result;
        } catch (\Exception $exception) {
            $result = [
                'result_code' => 'fail',
                'result_data' => $exception->getMessage()
            ];
            Log::info(__CLASS__ . '/' . __FUNCTION__ . '--ticket error--' . json_encode($result, JSON_UNESCAPED_UNICODE));
            return $result;
        }
    }

    static public function getWechat($wechat_app, $scope = 'snsapi_userinfo')
    {
        $config = [
            'app_id'        => $wechat_app->app_id,
            'secret'        => $wechat_app->app_secret,

            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',
            'oauth'         => [
                'scopes' => $scope,
            ],
            //...
        ];
        $app    = Factory::officialAccount($config);
        return $app;
    }

    static public function cacheWechat($wechat_app, $scope = 'snsapi_userinfo')
    {
        $predis       = app('redis')->connection()->client();
        $cache        = new RedisAdapter($predis);
        $config       = [
            'app_id'        => $wechat_app->app_id,
            'secret'        => $wechat_app->app_secret,
            'response_type' => 'array',
            'log'           => [
                'level' => 'debug',
                'file'  => storage_path('logs/wechat_server.log'),
            ],
        ];
        $app          = Factory::officialAccount($config);
        $newAccessToken =   \App\Services\WechatService::getAccessToken($app->config->app_id);
        $app['access_token']->setToken($newAccessToken, 3600);
        $app['cache'] = $cache;
        return $app;
    }

}
