<?php
namespace app\admin\controller\general;
use app\admin\model\general\Files;
use think\exception\ValidateException;
use think\facade\Filesystem;

class Upload
{
    public function post(): \think\response\Json
    {
        $params = request()->post();
        $files = request()->file();
        $save_name = [];
        foreach ($files as $file) $save_name[]  = $this->fileInsertDatabase($file);
        return success([
            'files'  => $save_name,
            'key' => $params['key']
        ],200,'上传图片成功');
    }

    public function markdown(): \think\response\Json
    {
        $files = request()->file();
        $file_path = '';
        foreach ($files as $file) $file_path = $this->fileInsertDatabase($file);
        $data = [
            'success' => 0,
            'message' => '上传失败',
            'url' => ''
        ];
        if($file_path) {
            $data = [
                'success' => 1,
                'url' => $file_path,
                'message' => 'success',
            ];
        }
        return json($data);
    }

    public function fileInsertDatabase($file): string
    {
        $size = (filesize($file) / 1024);
        list($width, $height, $type, $attr) = getimagesize($file);
        $path = '/storage/'.Filesystem::disk('public')->putFile( 'markdown', $file);
        (new Files())->save([
            'name' => $file->getOriginalName(),
            'mime_type' => $file->getOriginalMime(),
            'size' => $size,
            'width' => $width,
            'height' => $height,
            'url' => $path,
            'ext' => $file->getOriginalExtension()
        ]);
        return $path;
    }
}
