<?php

namespace backend\traits;

use think\facade\Request;
use think\response\Json;

trait View
{
    /**
     * View constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->rending->html();
    }

    /**
     * @return string|Json
     */
    public function index()
    {
        if (Request::isPost()) {
            $page = Request::post('page');
            $search = Request::post('search'); //根据字段信息拼接查询
            $start = ($page - 1) * 20;
            $where = " 1 = 1 ";
            if (!empty($search)) {
                $fields = array_column($this->rending->fields, null, 'key');
                foreach ($search as $key => $value) {
                    if (!empty($value)) {
                        switch ($fields[$key]['prop']['search']) {
                            case 'like':
                                $where .= " and `{$key}` like '%{$value}%'";
                                break;
                            case '>':
                                $where .= " and `{$key}` > {$value}";
                                break;
                            case '<':
                                $where .= " and `{$key}` < {$value}";
                                break;
                            default:
                                $where .= " and `{$key}` = '{$value}'";
                                break;
                        }
                    }
                }
            }
            //如果是树形表格,采用递归方式获取表格内容，并且默认上级字段为pid
            if (true === $this->rending->tree_table) {
                $where .= " and pid = 0";
                $list = $this->model->whereRaw($where)->limit($start, 20)->select();
                if (!empty($list)) {
                    $c = function (&$list) use (&$c) {
                        foreach ($list as $key => &$value) {
                            //根据父级别ID查询
                            $value['children'] = $this->model->where('pid', $value['id'])->select();
                            if (!empty($value['children'])) {
                                $c($value['children']);
                            }
                        }
                        return $list;
                    };
                    $list = $c($list);
                }
            } else {
                $list = $this->model->whereRaw($where)->limit($start, 21)->select();
            }
            $count = $this->model->whereRaw($where)->count();
            return success([
                'list' => $list,
                'count' => intval($count)
            ], 200, '加载数据成功');
        }
        return \think\facade\View::fetch('common/index');
    }

    /**
     * @return Json
     */
    public function post(): Json
    {
        if (Request::isPost()) {
            $data = Request::only(Request::post());
            if (isset($data[$this->pk]) && !empty($data[$this->pk])) {
                $res = $this->model::update($data, [$this->pk => $data[$this->pk]]);
            } else {
                $res = $this->model->save($data);
            }
            if (false !== $res) {
                return success([], 200, '操作成功');
            }
            return error('操作失败');
        }
        return error('错误的请求方式');
    }

    /**
     * @return Json
     */
    public function delete(): Json
    {
        if (Request::isPost()) {
            $id = Request::post($this->pk);
            if (empty($id) || !isset($id)) return error('缺失的主键');
            $res = $this->model->where('id', '=', $id)->delete();
            if ($res) {
                return success([], 200, '删除数据成功');
            }
            return error('删除数据失败');
        }
        return error('错误的请求方式');
    }

    /**
     * @return Json
     */
    public function deletes() : Json
    {
        if (Request::isPost()) {
            $ids = Request::post('ids');
            if (!is_array($ids) || empty($ids)) {
                return error('缺失的主键');
            }
            $is_tree = $this->rending->tree_table;
            if (true === $is_tree) {
                foreach ($ids as $key => $value) {
                    $row = $this->model->where('pid', $value)->findOrEmpty() ?: [];
                    if (!$row->isEmpty()) {
                        return error('有数据存在下级节点，请先删除！');
                    }
                }
            }
            $ids = implode(',', $ids);
            $res = $this->model->where('id', 'in', $ids)->delete();
            if ($res) {
                return success([], 200, '删除数据成功');
            }
            return error('删除数据失败');
        }
        return error('错误的请求方式');
    }
}