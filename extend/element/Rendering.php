<?php

namespace element;


use element\component\hook\Hook;
use think\facade\View;

class Rendering
{
    public $fields;

    public $tree_table = false;

    public $expend_all = false;

    public function __construct(string $model = '')
    {
        $class = "\\backend\\fields\\" . $model;
        $this->fields = $class::FORM_FIELD;
        $this->tree_table = $class::IS_TREE_TABLE ?: false;
        $this->expend_all = $class::EXPAND_ALL ? 'true ': 'false';
        $this->renderForm();
    }

    public function renderForm()
    {
        $form = [];
        $form_html = '<el-form ref="form" :rules="rules" :model="form" label-width="auto">' . PHP_EOL;
        $table_html = '<el-table ref="multipleTable" border :data="data" tooltip-effect="dark" style="width: 100%" @selection-change="handleSelectionChange" ';
        if (true === $this->tree_table) {
            $table_html .= ' row-key="id"';
            $table_html .= ' :default-expand-all='.$this->expend_all;
            $table_html .= ' :tree-props="{children: \'children\', hasChildren: \'hasChildren\'}"';
        }
        $table_html .= '>';
        $table_html .= '<el-table-column type="selection" width="55"></el-table-column>';
        //查询
        $search_html = '';
        $search = [];

        $rules = [];

        foreach ($this->fields as $field) {
            $form_html .= Hook::make(ucfirst($field['type']), $field, 'form') . PHP_EOL;
            $form[$field['key']] = $field['value'];

            if (true === $field['prop']['table_show']) {
                $table_html .= '<el-table-column prop="' . $field['key'] . '" label="' . $field['label'] . '"></el-table-column>';
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
            '<el-button type="danger" icon="el-icon-delete"></el-button>' . PHP_EOL . '</template>' . PHP_EOL
            . '</el-table-column>';
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