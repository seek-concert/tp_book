<?php
/* |------------------------------------------------------
 * | 读者书签管理模型
 * |------------------------------------------------------
 * */
namespace app\index\model;
use think\Model;

class Readerbookmark extends Model
{
    protected $table = 'reader_bookmark';
    protected $type = [
        'created_at'  =>  'timestamp',
        'updated_at'  =>  'timestamp',
        'read_at'  =>  'timestamp'
    ];
}