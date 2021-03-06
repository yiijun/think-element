<?php

namespace element\component\ui;

class Radio
{
    public static function html(array $fields,string $form_name = 'form'): string
    {
        if(isset($fields['prop']['options']) && !empty($fields['prop']['options'])){
            $radios = json_encode($fields['prop']['options'],256);
        }else{
            $model = new $fields['prop']['callback'][0]();
            $radios = $model->{$fields['prop']['callback'][1]}() ?: [];
            $radios = json_encode($radios, 256);
        }

        return
            '<el-form-item prop="'.$fields['key'].'" label="' . $fields['label'] . '">'.PHP_EOL.
                '<el-radio-group v-model="'.$form_name.'.' . $fields['key'] . '">'.PHP_EOL.
                    '<el-radio v-for=\'(item,index) in ' . $radios . '\' :label="parseInt(item.' . $fields['prop']['bind_value'] . ')">{{item.' . $fields['prop']['bind_label'] . '}}</el-radio>'.PHP_EOL.
                '</el-radio-group>'.PHP_EOL.
            '</el-form-item>'.PHP_EOL;
    }
}
