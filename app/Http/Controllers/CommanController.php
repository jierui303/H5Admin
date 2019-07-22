<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommanController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function returnJsonCode($code, $msg, $data = [])
    {
        return array(
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data
        );
    }

    public function returnErrorExceptionJsonCode($getMessage, $getCode, $getFile, $getLine)
    {
        return array(
            'msg' => $getMessage,
            'code' => $getCode,
            'file' => $getFile,
            'line' => $getLine
        );
    }

}
