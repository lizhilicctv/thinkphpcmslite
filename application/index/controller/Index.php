<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\Db;


class Index extends Base
{
    public function _empty()
    {
        //重定向浏览器
        header("Location:/404.html");
        //确保重定向后，后续代码不会被执行
        exit;
    }
	protected $beforeActionList = ['init'];
	    
	protected function init()
	{
		//使用缓存
		$action=request()->action();
		$id=input('id');
		if($action=='cate'){
			if(config('app_debug')){
				$datacate=Db::name('cate')->find($id);
			}else{
				if(!$datacate=Cache::get('datacate')){
					$datacate=Db::name('cate')->find($id);
					Cache::set('datacate',$datacate,3600);
				}
			}
			$this->assign('data', $datacate);
		}
		if($action=='show'){
			if(config('app_debug')){
				$dataarticle=Db::name('article')->find($id);
			}else{
				if(!$dataarticle=Cache::get('dataarticle')){
					$dataarticle=Db::name('article')->find($id);
					Cache::set('dataarticle',$dataarticle,3600);
				}
			}
			$this->assign('data', $dataarticle);
		}
		$cateid=0;
		if($action=='show'){
			$cateid	=Db::name('article')->where('id',$id)->value('cateid');
		}
		if($action=='cate'){
			$cateid = $id;
		}
		
		$this->assign('cateid', $cateid);
	}
	
	
    public function index()
    {
		//这里请使用缓存
		return $this->fetch();
    }
	public function cate()
	{
		$this->assign('id', input('id'));
		$html=Db::name('cate')->where('id',\input('id'))->value('catehtml');
		if(substr($html,-5,5)=='.html'){
			$html=substr($html,0,strlen($html)-5);
		}
		if($html){
			return $this->fetch($html);
		}else{
			return $this->fetch();
		}
	}
	public function show()
	{
		$id=input('id');
		$article=Db::name('article')->find($id);
		if(!$article){
			return '文章显示错误！';
		}
		Db::name('article')->where('id',$article['id'])->update([
			'click_wai'=>$article['click_wai']+1,
			'click'=>$article['click']+1,
		]);
		
		$shang=Db::name('article')->where('id','<',$id)->where('cateid',$article['cateid'])->order('id desc')->find();
		if($shang){
			$shang="< a href='".url('index/show',['id'=>$shang['id']])."'>".$shang['title']."</ a>";
		}else{
			$shang="< a href='".url('index/cate',['id'=>$article['cateid']])."'>返回列表</ a>";
		}
		$xia=Db::name('article')->where('id','>',$id)->where('cateid',$article['cateid'])->order('id asc')->find();
		if($xia){
			$xia="< a href='".url('index/show',['id'=>$xia['id']])."'>".$xia['title']."</ a>";
		}else{
			$xia="< a href='".url('index/cate',['id'=>$article['cateid']])."'>返回列表</ a>";
		}
		$this->assign([
			'shang'=>$shang,
			'xia'=>$xia
		]);
		
		
		$html=Db::name('cate')->where('id',$article['cateid'])->value('showhtml');
		if(substr($html,-5,5)=='.html'){
			$html=substr($html,0,strlen($html)-5);
		}
		if($html){
			return $this->fetch($html);
		}else{
			return $this->fetch();
		}
		return $this->fetch();
	}
	public function update()
	{
		if(request()->isPost()){
			$data=input('post.');
			$validate = new \app\index\validate\Update;
			if (!$validate->check($data)) {
			    $this->error($validate->getError());
			}
			if($data['type']=='message'){
				$message=\model('message');
				$res=$message->save([
					'title'=>'未填写',
					'name'=>$data['loginname'],
					'phone'=>$data['phone'],
					'neirong'=>$data['need']
				]);
				if($res){
					 $this->success('留言成功');
				}else{
					$this->error('留言失败');
				}
			}
		}
		return redirect('/');
	}
}
