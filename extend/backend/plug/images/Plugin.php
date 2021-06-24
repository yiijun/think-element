<?php

namespace backend\plug\images;

use backend\plug\PluginInterface;
use think\facade\Db;
use think\facade\View;

/**
 * Class Plugin
 * @package backend\plug\images
 * 插件中必须返回3个变量
 * plugins_html
 * plugins_data
 * plugins_func
 */
class Plugin implements PluginInterface
{

    public function html() : string
    {
        $images = Db::name("files")->select();
        $plugins_html = ' <el-dialog title="选择图片" v-model="plugins_data.Plugin_image_dialogImageVisible">';
        $plugins_html .= ' <el-table :data=\'' . json_encode($images) . '\' border >';
        $plugins_html .= ' <el-table-column prop="name" label="图片名称"> </el-table-column>';
        $plugins_html .= '<el-table-column  label="图片" align="center">';
        $plugins_html .= '<template #default="scope"><el-image';
        $plugins_html .= ' style="width: 50px; height: 50px"';
        $plugins_html .= ' :src="scope.row.url"';
        $plugins_html .= ' :preview-src-list="[scope.row.url]">';
        $plugins_html .= '</el-image>';
        $plugins_html .= '</template>';
        $plugins_html .= '</el-table-column>';
        $plugins_html .= ' <el-table-column prop="width" label="宽(px)"> </el-table-column>';
        $plugins_html .= ' <el-table-column prop="height" label="高(px)"> </el-table-column>';
        $plugins_html .= ' <el-table-column prop="size" label="大小（kb）"> </el-table-column>';
        $plugins_html .= ' <el-table-column label="操作" align="center">';
        $plugins_html .= ' <template #default="scope">';
        $plugins_html .= ' <el-button icon="el-icon-check" @click="onSelectImage(scope.row)" type="primary" size="small">选择</el-button>';
        $plugins_html .= ' </template>';
        $plugins_html .= ' </el-table-column>';
        $plugins_html .= ' </el-table>';
        $plugins_html .= '</el-dialog>';
        return $plugins_html;
    }

    public function data() : array
    {
        return [
            'Plugin_image_dialogImageVisible' => false,
            'Plugin_image_field' => 'image',
            'Plugin_image_form' => 'form'
        ];
    }

    public function func() : string
    {
        return <<< plugins_func
onPluginImage:function(form,field){
let _this = this
_this.plugins_data.Plugin_image_dialogImageVisible = true;
_this.plugins_data.Plugin_image_form = form;
_this.plugins_data.Plugin_image_field = field
},
onSelectImage:function(row){
let _this = this
let str = '_this.'+_this.plugins_data.Plugin_image_form+'.'+_this.plugins_data.Plugin_image_field+'="'+row.url+'"'
eval(str)
_this.plugins_data.Plugin_image_dialogImageVisible = false
}
plugins_func;
    }
}
