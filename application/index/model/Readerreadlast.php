<?php
/* |------------------------------------------------------
 * | 读者最近阅读管理模型
 * |------------------------------------------------------
 * */
namespace app\index\model;
use think\Model;

class Readerreadlast extends Model
{
    protected $table = 'reader_readlast';
    protected $type = [
        'created_at'  =>  'timestamp',
        'updated_at'  =>  'timestamp',
        'read_at'  =>  'timestamp'
    ];
}