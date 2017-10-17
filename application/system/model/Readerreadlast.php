<?php
/* |------------------------------------------------------
 * | 读者最近阅读管理模型
 * |------------------------------------------------------
 * */
namespace app\system\model;
use think\Model;

class Readerreadlast extends Model
{
    protected $table = 'reader_readlast';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $autoWriteTimestamp = true;
    protected $type = [
        'read_at'  =>  'timestamp'
    ];
    /*----- 关联小说 -----*/
    public function Books()
    {
        return $this->belongsTo('Books','book_id');
    }
    /*----- 关联小说章节 -----*/
    public function Bookcontents()
    {
        return $this->belongsTo('Bookcontents','content_id');
    }
}