<?php
namespace addons\images;

use think\Addons;
use think\facade\Db;

class Plugin extends Addons
{
    public $info = [
        'name' => 'Images',	// 插件标识
        'title' => '图片选择',	// 插件名称
        'description' => '任何地方可以选择附件选择器',	// 插件简介
        'status' => 1,	// 状态
        'author' => 'byron sampson',
        'version' => '0.1'
    ];

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    public function imagesHtml() : string
    {
        $plugins_html = ' <el-dialog title="选择图片" v-model="plugins_data.Plugin_image_dialogImageVisible">';
        $plugins_html .= ' <el-table :data= "plugins_data.Plugin_image_data" border >';
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
        $plugins_html .= '<div style="margin-top: 20px; text-align: right">';
        $plugins_html .= ' <el-pagination background layout="prev, pager, next" :total="plugins_data.Plugin_image_total" :page-size="10"  @current-change="imagesInit"></el-pagination>';
        $plugins_html .= '</div>';
        $plugins_html .= '</el-dialog>';

        return $plugins_html;
    }

    public function imagesMon() : string
    {
        return 'this.imagesInit();';
    }

    public function imagesData() :string
    {
        return json_encode([
            'Plugin_image_dialogImageVisible' => false,
            'Plugin_image_field' => 'image',
            'Plugin_image_form' => 'form',
            'Plugin_image_total' => 0,
            'Plugin_image_page' => 1,
            'Plugin_image_data' => []
        ]);
    }

    public function imagesFunc() : string
    {
        $url = addons_url('images://images/index');
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
},
imagesInit:function(page){
if(!page){page = 1}
let _this = this
_this.plugins_data.Plugin_image_page = page
axios.post("{$url}",{page:_this.plugins_data.Plugin_image_page}).then(function (response) {
    if(response.data.code == 200){
        _this.plugins_data.Plugin_image_data = response.data.data.list
        _this.plugins_data.Plugin_image_total = response.data.data.count
        console.log(_this.plugins_data.Plugin_image_data)
    }else{
        _this.\$message.error('初始化数据失败')
    }
})
}
plugins_func;
    }
}
