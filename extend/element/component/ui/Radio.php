<?php

namespace element\component\ui;

class Radio
{
    public static function html(array $fields): string
    {
        return '<el-form-item label="活动名称"><el-input v-model="form.name"></el-input></el-form-item>';
    }
}
