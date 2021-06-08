<?php

namespace element\component\ui;

class Number
{
    public static function html(array $fields): string
    {
        return '<el-form-item label="活动名称"><el-input-number v-model="form.name"></el-input-number></el-form-item>';
    }
}
