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

/**
 * @param $data
 * @param int $pid
 * @param int $deep
 * @return array
 */
function tree($data, int $pid): array
{
    $tree = [];
    foreach ($data as $row) {
        if($row['pid'] == $pid) {
            $children = tree($data, $row['id']);
            if(!empty($children)){
                $row['children'] = $children;
            }
            $tree[] = $row;
        }
    }
    return $tree;
}

function directory( $dir ){

    return  is_dir ( $dir ) or directory(dirname( $dir )) and  mkdir ( $dir , 0777);

}

