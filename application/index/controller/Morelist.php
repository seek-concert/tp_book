<?php
/* |------------------------------------------------------
 * | 小说首页---更多列表
 * |------------------------------------------------------
 * |
 * | 主编推荐
 * | 热门小说
 * | 新书推荐
 * |
 * | 更多下一页
 * */
namespace app\index\controller;

use app\index\model\Books;

class Morelist extends Auth
{
    public $data_setting;
    public function _initialize()
    {
        parent::_initialize();
        $this->data_setting=db('data_setting')->field(['pagenum'])->find();
    }
    /* ========== 更多列表 ========== */
    public function type_list(){
        $type=input('type');
        if(!in_array($type,['is_recommend','is_hot','newbook'])){
            return $this->error('操作错误！');
        }
        return view();
    }

    /* ============ 更多下一页 ============== */
    public function morenext(){
        $type=input('type');
        if(!in_array($type,['is_recommend','is_hot','newbook'])){
            return $this->error('操作错误！');
        }
        $book_model=new Books();
        if($type=='is_recommend'||$type=='is_hot'){
            $more_list=$book_model
                ->field(['b.id','b.title','b.picture','b.summary','b.online','b.'.$type,'a.name as author_name'])
                ->alias('b')
                ->join('author a','a.id=b.author_id','left')
                ->where('b.online',1)
                ->where($type,1)
                ->order('b.sort desc')
                ->select();
        }
        if($type=='newbook'){
            $more_list = $book_model
                ->field(['b.id','b.title','b.picture','b.summary','b.online','a.name as author_name'])
                ->alias('b')
                ->join('author a','b.author_id = a.id','left')
                ->where('online',1)
                ->order('b.created_at desc,b.sort asc')
                ->select();
        }
        $datas['more_list'] = $more_list;
        $datas['pagenum'] = $this->data_setting['pagenum'];
        if($more_list){
            return $this->success('获取成功','',$datas);
        }else{
            return $this->error('没有了！');
        }
    }
}