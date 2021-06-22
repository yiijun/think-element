<?php

namespace element\component\ui;

class Select
{
    public static function html(array $fields, string $form_name = 'form'): string
    {
        if(isset($fields['prop']['options']) && !empty($fields['prop']['options'])){
            $select = json_encode($fields['prop']['options'],256);
        }else{
            $model = new $fields['prop']['callback'][0]();
            $select = $model->{$fields['prop']['callback'][1]}() ?: [];
            $select = json_encode($select?: [], 256);
        }
        return
            '<el-form-item prop="'.$fields['key'].'" label="' . $fields['label'] . '">' . PHP_EOL .
                '<el-select placeholder="'.$fields['placeholder'].'" filterable v-model="'.$form_name.'.'.$fields['key'].'"><el-option v-for=\'(item,index) in '.$select.'\''.PHP_EOL.
                    ':key="item.'.$fields['prop']['bind_value'].'"'.PHP_EOL.
                    ':label="item.'.$fields['prop']['bind_label'].'"'.PHP_EOL.
                    ':value="item.'.$fields['prop']['bind_value'].'">'.PHP_EOL.
                '</el-option></el-select>'.PHP_EOL.
            '</el-form-item>'.PHP_EOL;
    }
}
