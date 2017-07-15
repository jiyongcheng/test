<?php

namespace App\Http\Controllers\Mypal;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{

    public function success($data=null,$message='')
    {
        $body = array(
            'status' => 'success',
            'data' => empty($data) ? array() : $data,
            'message' => $message
        );
        return new JsonResponse($body);

    }

    public function error($data=null,$message='')
    {
        $body = array(
            'status' => 'error',
            'data' => empty($data) ? array() : $data,
            'message' => $message
        );
        return new JsonResponse($body);

    }
}