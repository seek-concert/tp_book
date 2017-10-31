<?php
/* |------------------------------------------------------
 * | 小说书架
 * |------------------------------------------------------
 * |
 * | 我的书架
 * | 最近阅读
 * | 我的书签
 * | 删除我的书架
 * | 删除我的书签
 * | 添加到我的书架
 * */

namespace app\index\controller;


class Bookshelf extends Auth
{
    /* ============ 小说书架 ============== */
    public function index(){
        $reader_id = $this->reader['id'];
        /*+++++ 我的书架 +++++*/
        $bookshelf_list = model('Readbookshelf')
            ->field(['b.id','b.picture','b.online','b.title','b.status','a.name as author_name','s.content_id','c.order_num','c.name','d.order_num as order_nums'])
            ->alias('s')
            ->join('book b','s.book_id = b.id','left')
            ->join('author a','b.author_id = a.id','left')
            ->join('book_content c','b.edited_at = c.edited_at','left')
            ->join('book_content d','s.content_id = d.id','left')
            ->where('reader_id',$reader_id)
            ->order('s.updated_at desc')
            ->select();
        $datas['bookshelf_list'] = $bookshelf_list;
        $bookshelf_count =count($bookshelf_list);
        $datas['bookshelf_count'] = $bookshelf_count;
        /*+++++ 最近阅读 +++++*/
        $readerreadlast_list = model('Readerreadlast')
            ->field(['b.id','b.picture','b.online','b.title','b.status','a.name as author_name','s.content_id','c.order_num','c.name','d.order_num as order_nums'])
            ->alias('s')
            ->join('book b','s.book_id = b.id','left')
            ->join('author a','b.author_id = a.id','left')
            ->join('book_content c','b.edited_at = c.edited_at','left')
            ->join('book_content d','s.content_id = d.id','left')
            ->where('reader_id',$reader_id)
            ->order('s.updated_at desc')
            ->select();
        $datas['readerreadlast_list'] = $readerreadlast_list;
        /*+++++ 我的书签 +++++*/
        $readerbookmark_list = model('Readerbookmark')
            ->field(['b.id','b.online','b.title','s.content_id','d.order_num','d.name'])
            ->alias('s')
            ->join('book b','s.book_id = b.id','left')
            ->join('book_content d','s.content_id = d.id','left')
            ->where('reader_id',$reader_id)
            ->order('s.updated_at desc')
            ->select();
        $datas['readerbookmark_list'] = $readerbookmark_list;

        $this->assign($datas);
        return view();
    }

    /* ========== 删除我的书架 ========== */
    public function del(){
        $inputs=input('ids');
        $ids=isset($inputs)?$inputs:'';

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        $res=model('Readbookshelf')
            ->whereIn('book_id',$ids)
            ->where('reader_id',$this->reader['id'])
            ->delete(true);
        if($res){
            return $this->success('删除成功','');
        }else{
            return $this->error('删除失败！','');
        }
    }

    /* ========== 删除我的书签 ========== */
    public function readerbookmark_del(){
        $book_id=input('book_id');
        $content_id=input('content_id');
        $reader_id = $this->reader['id'];
        if(empty($book_id)||empty($content_id)||empty($reader_id)){
            return $this->error('参数错误');
        }
        $res=model('Readerbookmark')
            ->where('book_id',$book_id)
            ->where('reader_id',$reader_id)
            ->where('content_id',$content_id)
            ->delete(true);
        if($res){
            return $this->success('删除成功','');
        }else{
            return $this->error('删除失败！','');
        }
    }

    /* ========== 添加到我的书架 ========== */
    public function add_bookshelf(){
        $book_id = input('book_id');
        if(empty($book_id)){
            return $this->error('参数错误');
        }
                $reader_id = $this->reader['id'];

        $select_bookshelf = model('Readbookshelf')
            ->where('book_id',$book_id)
            ->where('reader_id',$reader_id)
            ->select();
        if(count($select_bookshelf)!==0){
            return $this->error('书架已存在该书籍！','');
        }
        $res=model('Readbookshelf')->save(['book_id'=>$book_id,'reader_id'=>$reader_id]);
        if($res){
            return $this->success('加入成功','');
        }else{
            return $this->error('加入失败！','');
        }
    }
}