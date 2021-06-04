<?php
declare(strict_types = 1);

function success(array $data = [],int $code = 200,string  $msg = 'ok') : \think\response\Json
{
    return  error($msg,$code,$data);
}

function error(string $msg = '失败',int $code = 0,array $data = []) : \think\response\Json
{
    return json([
        'code' => $code,
        'msg' => $msg,
        'data' => $data
    ]);
}