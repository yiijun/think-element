<?php

namespace element\component\ui;

class Input
{
    public static function html(array $fields,string $form_name = 'form'): string
    {
        return
            '<el-form-item prop="'.$fields['key'].'" label="'.$fields['label'].'">'.PHP_EOL.
                '<el-input v-model="'.$form_name.'.'.$fields['key'].'" placeholder="'.$fields['placeholder'].'"></el-input>'.PHP_EOL.
             '</el-form-item>'.PHP_EOL;
    }
}
