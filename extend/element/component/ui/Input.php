<?php

namespace element\component\ui;

class Input
{
    public static function html(array $fields): string
    {
        return
            '<el-form-item label="'.$fields['label'].'">'.PHP_EOL.
                '<el-input v-model="form.'.$fields['key'].'" placeholder="'.$fields['placeholder'].'"></el-input>'.PHP_EOL.
             '</el-form-item>'.PHP_EOL;
    }
}
