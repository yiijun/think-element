<?php
// +----------------------------------------------------------------------
// | Created by [ PhpStorm ]
// +----------------------------------------------------------------------
// | Copyright (c) 2021-2022.
// +----------------------------------------------------------------------
// | Create Time (2021/7/6 - 2:08 下午)
// +----------------------------------------------------------------------
// | Author: 唐轶俊 <tangyijun@021.com>
// +----------------------------------------------------------------------
namespace element\component\ui;

class Markdown
{
    public static function html(array $fields,string $form_name = 'form'): string
    {
        return '<el-form-item name="editor_content" prop="' . $fields['key'] . '" label="' . $fields['label'] . '"><div id="markdown" data-id="'.$fields['key'].'"><textarea v-model="'.$form_name.'.'.$fields['key'].'" style="display:none;"></textarea></div></el-form-item>';
    }
}