<?php
/* |------------------------------------------------------
 * | 小说管理模型
 * |------------------------------------------------------
 * */
namespace app\index\model;
use think\Model;

class Book extends Model
{
    protected $table = 'book';
    protected $type = [
        'created_at'  =>  'timestamp',
        'updated_at'  =>  'timestamp',
        'deleted_at'  =>  'timestamp'
    ];
}