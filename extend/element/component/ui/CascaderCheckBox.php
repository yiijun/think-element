<?php

namespace element\component\ui;

/**
 * Class CascaderCheckBox
 * @package element\component\ui
 * 复选级联选择器
 */
class CascaderCheckBox
{
    public static function html(array $fields, string $form_name = 'form'): string
    {
        if(isset($fields['prop']['options']) && !empty($fields['prop']['options'])){
            $options = json_encode($fields['prop']['options'],256);
        }else{
            $model = new $fields['prop']['callback'][0]();
            $options = $model->{$fields['prop']['callback'][1]}() ?: [];
            $options = json_encode($options ?: [], 256);
        }
        return
            '<el-form-item prop="' . $fields['key'] . '"  label="' . $fields['label'] . '">' . PHP_EOL .
            ' <el-cascader' . PHP_EOL .
            'v-model="' . $form_name . '.' . $fields['key'] . '"' .
            ':options=\'' . $options . '\' ' . PHP_EOL .
            'placeholder="' . $fields['placeholder'] . '"' . PHP_EOL .
            ':props=\'{children:"children",multiple:true,label:"' . $fields['prop']['bind_label'] . '",value:"' . $fields['prop']['bind_value'] . '"}\' 
                clearable filterable>' . PHP_EOL .
            '</el-cascader>' . PHP_EOL .
            '</el-form-item>' . PHP_EOL;
    }
}
