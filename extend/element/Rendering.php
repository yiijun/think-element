<?php
namespace element;


use element\component\hook\Hook;
use think\facade\View;

class Rendering
{
    public $fields;

    public function __construct(string $model = '')
    {
        $class = "\\backend\\fields\\".$model;
        $this->fields = $class::FORM_FIELD;
        $this->renderForm();
    }

    public  function renderForm()
    {
        //根据字段渲染表单
        $form = '';
        foreach ($this->fields as $f => $field) {
            $form .=  Hook::make(ucfirst($field['type']),$field);
        }
        View::assign('form',$form);
    }
}