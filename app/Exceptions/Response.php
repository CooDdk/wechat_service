<?php

namespace App\Exceptions;

class Response
{
    public static function failReturn($code_msg = '', $data = null, $debug = [])
    {
        $result['code']    = $code_msg[0] ?? 1;
        $result['code']    = intval($result['code'] ?: 1);
        $result['message'] = $code_msg[1] ?? $code_msg;
        $result['data']    = $data;
        $result['debug']   = $debug;
        $status            = 200;
        return response()->json($result, $status);
    }

    public static function successReturn($data = null)
    {
        $result['code']    = 0;
        $result['message'] = 'success';
        $result['data']    = $data ?? null;
        $status            = 200;
        return response()->json($result, $status);
    }

}
