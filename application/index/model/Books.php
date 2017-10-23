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
    protected $type = [
        'created_at'  =>  'timestamp',
        'updated_at'  =>  'timestamp',
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