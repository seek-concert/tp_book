<?php
/* |------------------------------------------------------
 * | 上传管理
 * |------------------------------------------------------
 * | 图片上传
 * */
namespace app\system\controller;


class Tools extends Auth
{
    public function upload_img(){
        $file = request()->file('picture');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 输出 jpg
//                echo $info->getExtension();
//                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getSaveName();
//                // 输出 42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getFilename();
                $datas = '/uploads/'.$info->getSaveName();
                return $this->success('','',$datas);
            }else{
                return $this->error($file->getError(),'');
            }
        }
    }
}