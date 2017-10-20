<?php
/* |------------------------------------------------------
 * | 排行榜
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;

class Ranking extends Auth
{
    public $data_setting;
    public function _initialize()
    {
        parent::_initialize();
        $this->data_setting=db('data_setting')->field(['ranking_top','ranking_total'])->find();
    }

    /* ============ 排行首页 排行前三 ============== */
    public function index(){

    }
}