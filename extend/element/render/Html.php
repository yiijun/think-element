<?php

namespace element\render;

use element\component\hook\Hook;
use think\facade\View;

/**
 * Class Html
 * @package element\render
 * 渲染element ui table,form,search,rules
 */
class Html
{
    public $expend_all = false;

    public function render(string $field_class, array $fields, $tree_table = false, string $pk = 'id')
    {
        $this->expend_all = defined("$field_class::EXPAND_ALL") && $field_class::EXPAND_ALL == true ? 'true' : 'false';
        $form = [];
        $form_html = '<el-form ref="form" :rules="rules" :model="form" label-width="auto">' . PHP_EOL;
        $table_html = '<el-table ref="multipleTable" border :data="data" tooltip-effect="dark" style="width: 100%" @selection-change="handleSelectionChange" ';
        if (true === $tree_table) {
            $table_html .= ' row-key="id"';
            $table_html .= ' :default-expand-all=' . $this->expend_all;
            $table_html .= ' :tree-props="{children: \'children\', hasChildren: \'hasChildren\'}"';
        }

        $table_html .= '>';
        $table_html .= '<el-table-column type="selection" width="55"></el-table-column>';
        $search_html = '';
        $search = [];
        $rules = [];

        foreach ($fields as $field) {
            $form_html .= Hook::make(ucfirst($field['type']), $field, 'form');
            $form[$field['key']] = $field['value'];

            if (true === $field['prop']['table_show']) {
                if (isset($field['prop']['callback']) && !empty($field['prop']['callback'])) {
                    $model = new $field['prop']['callback'][0]();
                    $option = $model->{$field['prop']['callback'][1]}() ?: [];
                    $option = json_encode($option ?: [], 256);
                    $table_html .= '<el-table-column  label="' . $field['label'] . '">
                        <template #default="scope"> 
                            <span v-for=\'(item,index) in ' . $option . '\'>
                            <el-tag v-if="item.' . $field['prop']['callback'][2] . ' == scope.row.' . $field['key'] . '"> {{item.' . $field['prop']['callback'][3] . '}}</el-tag>
                            </span>
                        </template>
                    </el-table-column>';
                } else {
                    $table_html .= '<el-table-column prop="' . $field['key'] . '" label="' . $field['label'] . '"></el-table-column>';
                }
            }
            if (isset($field['prop']['search'])) {
                $search_html .= Hook::make(ucfirst($field['type']), $field, 'search') . PHP_EOL;
                $search[$field['key']] = $field['value'];
            }
            if (true === $field['prop']['is_null']) {
                $rules[$field['key']] = [
                    [
                        'required' => true,
                        'message' => '请输入' . $field['label'],
                        'trigger' => $field['prop']['trigger'] ?? ''
                    ]
                ];
            }
        }
        $table_html .= '<el-table-column fixed="right" label="操作" width="120">' . PHP_EOL .
            '<template #default="scope">' . PHP_EOL .
            '<el-button type="info" icon="el-icon-edit" @click="onEdit(scope.row)"></el-button>' . PHP_EOL .
            '<el-popconfirm title="确定删除吗？" @confirm="onDelete(scope.row.' . $pk . ')">' . PHP_EOL .
            '<template #reference><el-button type="danger" icon="el-icon-delete"></el-button></template>' . PHP_EOL .
            '</el-popconfirm>' . PHP_EOL .
            '</template></el-table-column>';
        $table_html .= ' </el-table>';
        $form_html .= '</el-form>';

        View::assign([
            'form' => json_encode($form, JSON_UNESCAPED_UNICODE),
            'form_html' => $form_html,
            'table_html' => $table_html,
            'search_html' => $search_html,
            'search' => json_encode($search, JSON_UNESCAPED_UNICODE),
            'rules' => json_encode($rules, JSON_UNESCAPED_UNICODE)
        ]);
    }
}