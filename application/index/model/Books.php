<?php
/* |------------------------------------------------------
 * | 小说管理模型
 * |------------------------------------------------------
 * */
namespace app\index\model;
use think\Model;

class Books extends Model
{
    protected $table = 'book';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
    protected $autoWriteTimestamp = true;
    protected $field = true;
    protected $type = [
        'deleted_at'  =>  'timestamp'
    ];
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
}