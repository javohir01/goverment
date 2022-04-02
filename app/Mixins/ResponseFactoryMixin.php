<?php

namespace App\Mixins;

use Illuminate\Http\JsonResponse;

class ResponseFactoryMixin
{
    public function successJson()
    {
        return function($result){
            return [
                'success' => true,
                'result' => $result,
                'msg' => 'ok'
            ];
        };
    }

    public function errorJson()
    {
        return function($msg, $status, $error = [], $result = [], $code = null){
            $data = [
                'success' => false,
                'msg' => $msg,
                'error' => $error,
                'result' => $result,
                'code' => $code
            ];
            return new JsonResponse($data, $status);
        };
    }
}
