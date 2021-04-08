<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\Db;
use think\facade\Cache;

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
		if($action=='index'){
			if(config('app_debug')){
				$dataindex=[
					'catename'=>'首页',
					'en_name'=>'index',
					'fid'=>0,
					'cateid'=>0
				];
			}else{
				if(!$dataindex=Cache::get('dataindex'.$id)){
					$dataindex=[
						'catename'=>'首页',
						'en_name'=>'index',
						'fid'=>0,
						'cateid'=>0
					];
					Cache::set('dataindex',$dataindex,3600);
				}
			}
			$this->assign('data', $dataindex);
		}
		if($action=='cate'){
			if(config('app_debug')){
				$datacate=Db::name('cate')->find($id);
			}else{
				if(!$datacate=Cache::get('datacate'.$id)){
					$datacate=Db::name('cate')->find($id);
					Cache::set('datacate'.$id,$datacate,3600);
				}
			}
			if(!$datacate){
				header("Location:/404.html");
				die;
			}
			$datacate['cateid']=$id;
			$this->assign('data', $datacate);
			
			//这里做跳转判断
			if($datacate['tiao_type']==1){
				$this->redirect('index/cate', ['id' =>$datacate['tiao_id'] ]);
			}
			if($datacate['tiao_type']==2){
				$this->redirect('index/show', ['id' =>$datacate['tiao_id'] ]);
			}
		}
		if($action=='show'){
			if(config('app_debug')){
				$dataarticle=Db::name('article')->find($id);
				if(!$dataarticle){
					header("Location:/404.html");
					die;
				}
				$datacate=Db::name('cate')->find($dataarticle['cateid']);
				$dataarticle['catename']=$datacate['catename'];
				$dataarticle['en_name']=$datacate['en_name'];
				$dataarticle['fid']=$datacate['fid'];
				$dataarticle['cateid']=$datacate['id'];
			}else{
				if(!$dataarticle=Cache::get('dataarticle'.$id)){
					$dataarticle=Db::name('article')->find($id);
					if(!$dataarticle){
						header("Location:/404.html");
						die;
					}
					$datacate=Db::name('cate')->find($dataarticle['cateid']);
					$dataarticle['catename']=$datacate['catename'];
					$dataarticle['en_name']=$datacate['en_name'];
					$dataarticle['fid']=$datacate['fid'];
					$dataarticle['cateid']=$datacate['id'];
					Cache::set('dataarticle'.$id,$dataarticle,3600);
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
		
		//拦截远程跳转
		$cate=Db::name('cate')->find(\input('id'));
		if($cate['type']==4 and isset($cate['url'])){
			$this->redirect($cate['url'],302);
		}
		$html=$cate['catehtml'];
		$this->assign('id', input('id'));
		if(substr($html,-5,5)=='.html'){
			$html=substr($html,0,strlen($html)-5);
		}
		if($html){
			return $this->fetch($html);
		}else{
			return $this->fetch();
		}
	}
	public function search()
	{
		$keyword=input('key');
		$cateid=input('cateid');
		$num=input('num') ?? 10;
		$catename='全部';
		$where=true;
		if($cateid){
			$catename=Db::name('cate')->where('id',$cateid)->value('catename');
			$where=[
				'cateid'=>$cateid
			];
		}
		$list=Db::name('article')->where('isopen',1)->where($where)->where('title','like','%'.$keyword.'%')->paginate($num,false,['query'=>request()->param()]);
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		return $this->fetch('',[
			'keyword'=>$keyword,
			'catename'=>$catename,
		]);
	}
	public function show()
	{
		$id=input('id');
		$article=Db::name('article')->find($id);
		if(!$article){
			header("Location:/404.html");
			die;
		}
		Db::name('article')->where('id',$article['id'])->update([
			'click_wai'=>$article['click_wai']+1,
			'click'=>$article['click']+1,
		]);
		
		$shang1=Db::name('article')->where('id','<',$id)->where('cateid',$article['cateid'])->order('id desc')->find();
		if($shang1){
			$shang="<a href='".url('index/show',['id'=>$shang1['id']])."'>".$shang1['title']."</a>";
			$shangurl=url('index/show',['id'=>$shang1['id']]);
		}else{
			$shang="<a href='".url('index/cate',['id'=>$article['cateid']])."'>返回列表</a>";
			$shangurl=url('index/cate',['id'=>$article['cateid']]);
		}
		$xia1=Db::name('article')->where('id','>',$id)->where('cateid',$article['cateid'])->order('id asc')->find();
		if($xia1){
			$xia="<a href='".url('index/show',['id'=>$xia1['id']])."'>".$xia1['title']."</a>";
			$xiaurl=url('index/show',['id'=>$xia1['id']]);
		}else{
			$xia="<a href='".url('index/cate',['id'=>$article['cateid']])."'>返回列表</a>";
			$xiaurl=url('index/cate',['id'=>$article['cateid']]);
		}
		$this->assign([
			'shang'=>$shang,
			'xia'=>$xia,
			'shangurl'=>$shangurl,
			'xiaurl'=>$xiaurl
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
