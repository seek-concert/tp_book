<?php
/* |------------------------------------------------------
 * | 前台----分类管理
 * |------------------------------------------------------
 * |
 * | 分类首页
 * | 分类列表页获取
 * */
namespace app\index\controller;

class Classification extends Auth
{
    /* ============ 分类首页 ============== */
    public function index()
    {
        /*+++++ 所有分类 +++++*/
        $bookcate_model = db('book_cate');
        $bookcate_name = $bookcate_model->field('id,name')->select();
        $datas['cate_name'] = $bookcate_name;
        /*+++++ 分类查询 +++++*/
        $where = [];
        /*----- 分页条件(分页条数) -----*/
        $page_num = input('page');
        if($page_num){
            $display_num = $page_num;
            $datas['page_num'] = $page_num;
        }else{
            $display_num = config('paginate.list_rows');
            $datas['page_num'] = $page_num;
        }
        /*----- 查询条件(分类) -----*/
        $cate_id = input('cate_id');
        if(is_numeric($cate_id)){
            $where['cate_id'] = "$cate_id";
            $datas['cate_id'] = $cate_id;
        }
        /*----- 查询条件(状态) -----*/
        $status = input('status');
        if(in_array($status,[0,1])&&is_numeric($status)){
            $where['status'] = "$status";
            $datas['status'] = $status;
        }
        /*----- 查询条件(类型) -----*/
        $type = input('type');
        if(in_array($type,[0,1])&&is_numeric($type)){
            $where['type'] = "$type";
            $datas['type'] = $type;
        }
        $book_list = model('books')
                    ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
                    ->alias('b')
                    ->join('author a','b.author_id = a.id','left')
                    ->where($where)
                    ->where('online',1)
                    ->paginate($display_num);
        $datas['book_list'] = $book_list;
        $this->assign($datas);
        return view();
    }

    /* ============ 分类列表页 ============== */
    public function cate_list()
    {
        /*+++++ 所有分类 +++++*/
        $bookcate_model = db('book_cate');
        $bookcate_name = $bookcate_model->field('id,name')->select();
        $datas['cate_name'] = $bookcate_name;
        /*+++++ 分类查询 +++++*/
        $where = [];
        /*----- 分页条件(分页条数) -----*/
        $page_num = input('page');
        if($page_num){
            $display_num = $page_num;
            $datas['page_num'] = $page_num;
        }else{
            $display_num = config('paginate.list_rows');
            $datas['page_num'] = $page_num;
        }
        /*----- 查询条件(分类) -----*/
        $cate_id = input('cate_id');
        if(is_numeric($cate_id)){
            $where['cate_id'] = "$cate_id";
            $datas['cate_id'] = $cate_id;
        }
        /*----- 查询条件(状态) -----*/
        $status = input('status');
        if(in_array($status,[0,1])&&is_numeric($status)){
            $where['status'] = "$status";
            $datas['status'] = $status;
        }
        /*----- 查询条件(类型) -----*/
        $type = input('type');
        if(in_array($type,[0,1])&&is_numeric($type)){
            $where['type'] = "$type";
            $datas['type'] = $type;
        }
        $book_list = model('books')
            ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->where($where)
            ->paginate($display_num);
        $datas['book_list'] = $book_list;
        $this->assign($datas);
       if($book_list){
           $this->success('获取成功','',$book_list);
       }else{
           $this->error('获取失败','');
       }
    }
}