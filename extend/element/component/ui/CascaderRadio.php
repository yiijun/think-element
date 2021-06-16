<?php

namespace element\component\ui;

/**
 * Class CascaderRadio
 * @package element\component\ui
 * 单选级联选择器
 */
class CascaderRadio
{
    public static function html(array $fields, string $form_name = 'form'): string
    {
        $model = new $fields['prop']['callback'][0]();
        $select = $model->{$fields['prop']['callback'][1]}() ?: [];
        $select = json_encode($select ?: [], 256);
        return
            '<el-form-item prop="' . $fields['key'] . '" label="' . $fields['label'] . '">' . PHP_EOL .
            ' <el-cascader '.PHP_EOL.
                ':options=\'' . $select . '\' v-model="'.$form_name.'.'.$fields['key'].'"'.PHP_EOL.
                'placeholder="'.$fields['placeholder'].'"'.PHP_EOL.
                ':props=\'{emitPath:'.$fields['prop']['emitPath'].',checkStrictly: true ,label:"' . $fields['prop']['callback'][3] . '",value:"' . $fields['prop']['callback'][2] . '"}\' 
                filterable>' . PHP_EOL .
            '</el-cascader>' . PHP_EOL .
            '</el-form-item>' . PHP_EOL;
    }
}
