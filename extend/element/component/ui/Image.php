<?php
namespace element\component\ui;
class Image
{
    public static function html(array $fields,string $form_name = 'form') :string
    {
        $html = '<el-form-item label="'.$fields['label'].'" prop="'.$fields['key'].'">';
        $html .= '<el-upload ';
        $html .= 'action="/admin/general.upload/post" ';
        $html .= 'class="el-upload  el-upload--picture-card" ';
        $html .= ':show-file-list="false" ';
        $html .= 'name = "'.$fields['key'].'" ';
        $html .= ' :on-success="onImageSuccess" ';
        $html .= '  :data=\'{key:"'.$fields['key'].'"}\'';
        $html .= '>';
        $html .= '<img v-if="'.$form_name.'.'.$fields['key'].'" :src="'.$form_name.'.'.$fields['key'].'" class="avatar">';
        $html .= '<i v-else class="el-icon-plus avatar-uploader-icon"></i>';
        $html .= '</el-upload>';
        $html .= '</el-form-item>';
        return  $html;
    }
}
