<?php
namespace app\admin\controller\general;
use app\admin\model\general\Files;
use think\exception\ValidateException;
use think\facade\Filesystem;

class Upload
{
    public function post()
    {
        $params = request()->post();
        $files = request()->file();
        $save_name = [];
        try{
            validate(['image'=>'filesize:10240|fileExt:jpg,jpeg,png,gif'])->check($files);
        }catch (ValidateException $e){
            return  error($e->getMessage());
        }
        foreach ($files as $file) {
            $size = (filesize($file) / 1024);
            list($width, $height, $type, $attr) = getimagesize($file);
            $path = '/storage/'.Filesystem::disk('public')->putFile( 'images', $file);
            $save_name[]  = $path;
            (new Files())->save([
                'name' => $file->getOriginalName(),
                'mime_type' => $file->getOriginalMime(),
                'size' => $size,
                'width' => $width,
                'height' => $height,
                'url' => $path,
                'ext' => $file->getOriginalExtension()
            ]);
        }
        return success([
            'files'  => $save_name,
            'key' => $params['key']
        ],200,'上传图片成功');
    }
}
