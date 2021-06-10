<?php

namespace element\component\ui;

use think\helper\Arr;


class Radio
{
    public static function html(array $fields,string $form_name = 'form'): string
    {
        $model = new $fields['prop']['callback'][0]();
        $radios = $model->{$fields['prop']['callback'][1]}() ?: [];
        $radios = json_encode($radios, 256);
        return
            '<el-form-item label="' . $fields['label'] . '">'.PHP_EOL.
                '<el-radio-group v-model="'.$form_name.'.' . $fields['key'] . '">'.PHP_EOL.
                    '<el-radio v-for=\'(item,index) in ' . $radios . '\' :label="item.' . $fields['prop']['callback'][2] . '.toString()">{{item.' . $fields['prop']['callback'][3] . '}}</el-radio>'.PHP_EOL.
                '</el-radio-group>'.PHP_EOL.
            '</el-form-item>'.PHP_EOL;
    }
}
