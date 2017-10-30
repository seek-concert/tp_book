<?php
/* |------------------------------------------------------
 * | 小说分类管理模型
 * |------------------------------------------------------
 * | 分类去空
 * | 添加
 * | 修改
 * */
namespace app\system\model;
use think\Model;
use traits\model\SoftDelete;

class Banners extends Model
{
    use SoftDelete;
    protected $table = 'banner';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime='deleted_at';
    protected $autoWriteTimestamp = true;
    protected $field = true;
    public function getTypeAttr($value)
    {
        $status = [1=>'首页轮播图',2=>'个人中心背景图'];
        return $status[$value];
    }
    public function add(){
        $this->data = input();
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }
    public function updata(){
        $id = input('id');
        $rs = $this->save($_POST, ['id' => $id]);
        if($rs){
            return true;
        }else{
            return false;
        }
    }
}