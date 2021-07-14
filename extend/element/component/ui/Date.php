<?php

namespace element\component\ui;

class Date
{
    public static function html(array $fields,string $form_name = 'form'): string
    {
        $html = '<el-form-item prop="'.$fields['key'].'" label="'.$fields['label'].'">';
        $html .= '<el-date-picker
      v-model="'.$form_name.'.'.$fields['key'].'"
      type="date"
     placeholder="'.$fields['placeholder'].'"
      format="YYYY 年 MM 月 DD 日"
      value-format="YYYY-MM-DD"
    >
    </el-date-picker>';
        $html .=   '</el-form-item>';
        return  $html;
    }
}
