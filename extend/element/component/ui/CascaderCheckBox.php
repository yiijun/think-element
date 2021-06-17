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
        $model = new $fields['prop']['callback'][0]();
        $options = $model->{$fields['prop']['callback'][1]}() ?: [];
        $options = json_encode($options ?: [], 256);
        return
            '<el-form-item prop="' . $fields['key'] . '" label="' . $fields['label'] . '">' . PHP_EOL .
            ' <el-cascader '.PHP_EOL.
                ':options=\'' . $options . '\' v-model="'.$form_name.'.'.$fields['key'].'"'.PHP_EOL.
                'placeholder="'.$fields['placeholder'].'"'.PHP_EOL.
                ':props=\'{multiple:true,emitPath:'.$fields['prop']['emitPath'].',label:"' . $fields['prop']['callback'][3] . '",value:"' . $fields['prop']['callback'][2] . '"}\' 
                clearable>' . PHP_EOL .
            '</el-cascader>' . PHP_EOL .
            '</el-form-item>' . PHP_EOL;
    }
}
