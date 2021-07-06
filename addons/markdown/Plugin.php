<?php

namespace addons\markdown;

use think\Addons;


class Plugin extends Addons
{
    public $info = [
        'name' => 'Markdown',    // 插件标识
        'title' => 'edito.md',    // 插件名称
        'description' => 'markdown编辑器',    // 插件简介
        'status' => 1,    // 状态
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

    public function markdown()
    {
        $plugin_css = ' <link rel="stylesheet" type="text/css" href="/static/addons/editor.md/css/editormd.css" />';
        $plugin_script = '<script type="text/javascript" src="/static/js/jquery-3.3.1.min.js"></script>';
        $plugin_script .= '<script type="text/javascript" src="/static/addons/editor.md/editormd.js"></script>';
        $upload_url = url('admin/general.upload/markdown');
        $plugin_func = <<<func
 onDialogOpen:function () {
        let _this = this
        setTimeout(function () {
                    _this.plugins_data.Plugin_markdown_editor = editormd("markdown", {
                        width: "100%",
                        height: "500px",
                        path: "/static/addons/editor.md/lib/",
                        emoji: false,
                        tocm: true,
                        flowChart: true,
                        sequenceDiagram: true,
                        imageUpload: true,
                        imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                        imageUploadURL: "{$upload_url}",
                    });
                }, 300)
    },
    onSubmit:function (form) {
                let _this = this
                this.\$refs[form].validate((valid) => {
                    if (valid) {
                        let field = \$("#markdown").attr('data-id')
                        let str = "_this.form."+field+"=_this.plugins_data.Plugin_markdown_editor.getMarkdown()"
                        eval(str)
                        axios.post(_this.api.post,_this.form).then(function (response) {
                            if(response.data.code == 200){
                                _this.\$message.success({message:response.data.msg,type:'success'})
                                _this.init()
                                _this.dialogFormVisible = false
                            }else{
                                _this.\$message.error(response.data.msg)
                            }
                        })
                    } else {
                        return false
                    }
                });
            },
func;
        return json_encode([
            'plugin_css' => $plugin_css,
            'plugin_script' => $plugin_script,
            'plugin_func' => $plugin_func,
            'plugin_data' => [
                'Plugin_markdown_editor' => ''
            ]
        ]);
    }
}
