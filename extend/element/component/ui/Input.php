<?php

namespace element\component\ui;

class Input
{
    public static function html(array $fields,string $form_name = 'form'): string
    {
        $html = '<el-form-item prop="'.$fields['key'].'" label="'.$fields['label'].'">';
        $html .= '<el-input v-model="'.$form_name.'.'.$fields['key'].'" placeholder="'.$fields['placeholder'].'"></el-input>';
        $html .=   '</el-form-item>';
        return  $html;
    }
}
