<?php

namespace addons\images\controller;

use think\facade\Db;
use think\facade\Request;

class Images
{
    public function index()
    {
        $page = Request::post('page') ?: 1;
        $start = ($page - 1) * 2;

        $count = Db::name('files')->count();
        $list = Db::name('files')->limit($start, 10)->select();
        return success([
            'count' => $count,
            'list' => $list
        ]);
    }
}