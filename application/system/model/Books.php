<?php
/* |------------------------------------------------------
 * | 小说管理模型
 * |------------------------------------------------------
 * | 自动转换时间戳
 * | 小说标题去空
 * | 关联小说分类
 * | 添加
 * | 修改
 * */
namespace app\system\model;
use think\Model;
use traits\model\SoftDelete;

class Books extends Model
{
    use SoftDelete;
    protected $table = 'book';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $deleteTime='deleted_at';
    protected $autoWriteTimestamp = true;
    protected $field = true;
    protected $type = [
        'free_start'  =>  'timestamp',
        'free_end'  =>  'timestamp'
    ];

    public function setTitleAttr($value)
    {
        return trim($value);
    }

    public function getStatusAttr($value)
    {
        $status = [0=>'连载',1=>'完结'];
        return $status[$value];
    }

    public function getTypeAttr($value)
    {
        $status = [0=>'男生',1=>'女生'];
        return $status[$value];
    }
    public function getFreeStatusAttr($value)
    {
        $status = [0=>'收费',1=>'限时免费',2=>'完全免费'];
        return $status[$value];
    }
    public function getIsRecommendAttr($value)
    {
        $status = [0=>'否',1=>'是'];
        return $status[$value];
    }
    public function getOnlineAttr($value)
    {
        $status = [0=>'下架',1=>'在架'];
        return $status[$value];
    }
    /*----- 关联小说分类 -----*/
    public function Bookcates()
    {
        return $this->belongsTo('Bookcates','cate_id');
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
        $rs = $this
            ->allowField(['cate_id','status','type','picture','title','author_id','summary','click_num','submit_num','buy_num','amount','free_status','free_start','free_end','is_hot','is_recommend','sort'])
            ->save($_POST, ['id' => $id]);
        if($rs){
            return true;
        }else{
            return false;
        }
    }
}