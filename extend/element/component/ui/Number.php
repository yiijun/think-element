<?php

namespace element\component\ui;

class Number
{
    public static function html(array $fields,string $form_name = 'form'): string
    {
        return
            '<el-form-item prop="'.$fields['key'].'" label="'.$fields['label'].'">'.PHP_EOL.
                '<el-input-number '.PHP_EOL.
                    ':min="'.$fields['prop']['ext']['min'].'" '.PHP_EOL.
                    ':max="'.$fields['prop']['ext']['max'].'" '.PHP_EOL.
                    ':step = "'.$fields['prop']['ext']['step'].'"'.PHP_EOL.
                    'v-model="'.$form_name.'.'.$fields['key'].'">'.PHP_EOL.
                '</el-input-number>'.PHP_EOL.
            '</el-form-item>'.PHP_EOL;
    }
}
