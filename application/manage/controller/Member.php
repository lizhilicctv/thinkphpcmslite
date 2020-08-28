<?php
namespace app\manage\controller;

use app\manage\controller\Conn;
use app\manage\model\Member as Membermodel;
use think\Db;

class Member extends Conn
{
    //这里用前置操作，表示提前运行，本来要用于栏目删除子栏目呢，现在不用了
    protected $beforeActionList = [
        
    ];
    public function index()
    {
        $link=new Membermodel();
        
        $key=input('key') ? input('key') : '';
        $this->assign('key', $key);
        $link=$link
            ->where('nickName', 'like', '%'.$key.'%')
            ->order('id desc')->paginate(10, false, ['query'=>request()->param()])->each(function ($item, $key) {
                $item->fname = Db::name('member')->where('id',$item->fid)->value('nickName');
				$item->xia = Db::name('member')->where('fid',$item->id)->count('id');
            });
			
        $this->assign('member', $link);
        $count1=db('member')->count();
        $this->assign('count1', $count1);
        return $this->fetch();
    }
    public function ajax()
    {
        $data=input('param.');
        $member=new Membermodel();
        
        if ($data['type']=='member_start') {
            if (Db::name('member')->where('id', $data['id'])->setField('isopen', 1)) {
                return 1;//修改成功返回1
            } else {
                return 0;
            }
        }
        if ($data['type']=='member_stop') {
            if (Db::name('member')->where('id', $data['id'])->setField('isopen', 0)) {
                return 1;//修改成功返回1
            } else {
                return 0;
            }
        }
        

       
        return 0;
    }
    public function lit()
    {
        $link=new Membermodel();
        
        $key=input('key') ? input('key') : '';
        $this->assign('key', $key);
            
        $link=$link
			->where('fid',input('id'))
            ->where('nickName', 'like', '%'.$key.'%')
            ->order('id desc')->paginate(5, false, ['query'=>request()->param()]);
    		
        $this->assign('member', $link);
        
        
        $count1=db('member')->where('fid',input('id'))->count();
        $this->assign('count1', $count1);
        return $this->fetch();
    }
    
}
