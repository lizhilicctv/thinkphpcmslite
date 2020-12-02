<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\facade\Cache;
class Cate extends Model
{
	//如果表名和文件名不是对应的，用下面代码修改
	public function cate($id,$num,$offset,$order,$field,$where){
		if($order){
			$order='state desc,time desc,id asc';
		}else{
			$order='state desc,time desc,id desc';
		}
		if(config('app_debug')){
			$cate=Db::name('article')->where('isopen',1)->where('cateid',$id)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
		}else{
			if(!$cate=Cache::get('cate'.$id)){
				$cate=Db::name('article')->where('isopen',1)->where('cateid',$id)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
				Cache::set('cate'.$id,$cate,3600);
			}
		}
		return $cate;
	}

	public function cateall($unid, $num, $order,$field, $where){
		if(!is_array($unid)){
			return 'unid 必须为数组！';
		}
		if($order){
			$order1='id asc';
		}else{
			$order1='id desc';
		}
		$id=implode("",$unid);
		if(config('app_debug')){
			$cateall=Db::name('cate')->where('fid',0)->where('isopen',1)->where('id','notin',$unid)->where('type','in',[1,2])->order($order1)->field('id,catename,en_name')->select();
			foreach($cateall as $k=>$v){
				if($order){
					$order='state desc,time desc,id asc';
				}else{
					$order='state desc,time desc,id desc';
				}
				$data=Db::name('cate')->where('fid',$v['id'])->where('isopen',1)->where('id','notin',$unid)->where('type','in',[1,2])->order($order1)->field('id,catename,en_name')->select();
				foreach($data as $k=>$v){
					$data[$k]['list']=Db::name('article')->where('isopen',1)->where('cateid',$v['id'])->where($where)->field($field)->limit($num)->order($order)->select();
				}
				$cateall[$k]['zi']=$data;
			}
		}else{
			if(!$cateall=Cache::get('$cateall'.$id)){
				$cateall=Db::name('cate')->where('fid',0)->where('isopen',1)->where('id','notin',$unid)->where('type','in',[1,2])->order($order1)->field('id,catename,en_name')->select();
				foreach($cateall as $k=>$v){
					if($order){
						$order='state desc,time desc,id asc';
					}else{
						$order='state desc,time desc,id desc';
					}
					$data=Db::name('cate')->where('fid',$v['id'])->where('isopen',1)->where('id','notin',$unid)->where('type','in',[1,2])->order($order1)->field('id,catename,en_name')->select();
					foreach($data as $k=>$v){
						$data[$k]['list']=Db::name('article')->where('isopen',1)->where('cateid',$v['id'])->where($where)->field($field)->limit($num)->order($order)->select();
					}
					$cateall[$k]['zi']=$data;
				}
				Cache::set('cateall'.$id,$cateall,3600);
			}
		}
		return $cateall;
	}
	public function catelist($unid, $num, $order,$field, $where){
		if(!is_array($unid)){
			return 'unid 必须为数组！';
		}
		$id=implode("",$unid);
		if($order){
			$order1='id asc';
		}else{
			$order1='id desc';
		}
	
		if(config('app_debug')){
			$catelist=Db::name('cate')->where('isopen',1)->where('id','notin',$unid)->where('type','in',[1,2])->order($order1)->field('id,catename,en_name')->select();
			foreach($catelist as $k=>$v){
				if($order){
					$order='state desc,time desc,id asc';
				}else{
					$order='state desc,time desc,id desc';
				}
				$catelist[$k]['list']=Db::name('article')->where('isopen',1)->where('cateid',$v['id'])->where($where)->field($field)->limit($num)->order($order)->select();
			}
		}else{
			if(!$catelist=Cache::get('catelist'.$id)){
				$catelist=Db::name('cate')->where('isopen',1)->where('id','notin',$unid)->where('type','in',[1,2])->order($order1)->field('id,catename,en_name')->select();
				foreach($catelist as $k=>$v){
					if($order){
						$order='state desc,time desc,id asc';
					}else{
						$order='state desc,time desc,id desc';
					}
					$catelist[$k]['list']=Db::name('article')->where('isopen',1)->where('cateid',$v['id'])->where($where)->field($field)->limit($num)->order($order)->select();
				}
				Cache::set('catelist'.$id,$catelist,3600);
			}
		}
		return $catelist;
	}
	
	public function hot($id,$num,$offset,$order,$field,$where){
		if($id==0){
			$where2=true;
		}else{
			$ids=Db::name('cate')->where('fid',$id)->column('id');
			$ids[]=$id;
			$where2=[
				'cateid'=>$ids
			];
		}
		if($order){
			$order='click desc,time desc,id asc';
		}else{
			$order='click desc,time desc,id desc';
		}
		if(config('app_debug')){
			$hot=Db::name('article')->where('isopen',1)->where($where2)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
		}else{
			if(!$hot=Cache::get('hot'.$id)){
				$hot=Db::name('article')->where('isopen',1)->where($where2)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
				Cache::set('hot'.$id,$hot,3600);
			}
		}
		return $hot;
	}
	public function rec($id,$num,$offset,$order,$field,$where){ //推荐
		if($id==0){
			$where2=true;
		}else{
			$ids=Db::name('cate')->where('fid',$id)->column('id');
			$ids[]=$id;
			$where2=[
				'cateid'=>$ids
			];
		}
		if($order){
			$order='time desc,id asc';
		}else{
			$order='time desc,id desc';
		}
		if(config('app_debug')){
			$rec=Db::name('article')->where('isopen',1)->where($where2)->where('state',1)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
		}else{
			if(!$rec=Cache::get('rec'.$id)){
				$rec=Db::name('article')->where('isopen',1)->where($where2)->where('state',1)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
				Cache::set('rec'.$id,$rec,3600);
			}
		}
		return $rec;
	}
	public function sui($id,$num,$field,$where){
		if($id==0){
			$where2=true;
		}else{
			$ids=Db::name('cate')->where('fid',$id)->column('id');
			$ids[]=$id;
			$where2=[
				'cateid'=>$ids
			];
		}
		if(config('app_debug')){
			$sui=Db::name('article')->where('isopen',1)->where($where2)->where($where)->field($field)->limit($num)->order(true)->select();
		}else{
			if(!$sui=Cache::get('sui'.$id)){
				$sui=Db::name('article')->where('isopen',1)->where($where2)->where($where)->field($field)->limit($num)->order(true)->select();
				Cache::set('sui'.$id,$sui,3600);
			}
		}
		
		return $sui;
	}
	public function hotimg($id,$num,$offset,$order,$field,$where){
		if($id==0){
			$where2=true;
		}else{
			$ids=Db::name('cate')->where('fid',$id)->column('id');
			$ids[]=$id;
			$where2=[
				'cateid'=>$ids
			];
		}
		if($order){
			$order='click desc,time desc,id asc';
		}else{
			$order='click desc,time desc,id desc';
		}
		if(config('app_debug')){
			$hotimg=Db::name('article')->whereNotNull('pic')->where('isopen',1)->where($where2)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
		}else{
			if(!$hotimg=Cache::get('hotimg'.$id)){
				$hotimg=Db::name('article')->whereNotNull('pic')->where('isopen',1)->where($where2)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
				Cache::set('hotimg'.$id,$hotimg,3600);
			}
		}
		return $hotimg;
	}
	public function recimg($id,$num,$offset,$order,$field,$where){
		if($id==0){
			$where2=true;
		}else{
			$ids=Db::name('cate')->where('fid',$id)->column('id');
			$ids[]=$id;
			$where2=[
				'cateid'=>$ids
			];
		}
		if($order){
			$order='time desc,id asc';
		}else{
			$order='time desc,id desc';
		}
		if(config('app_debug')){
			$recimg=Db::name('article')->whereNotNull('pic')->where('isopen',1)->where($where2)->where('state',1)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
		}else{
			if(!$recimg=Cache::get('recimg'.$id)){
				$recimg=Db::name('article')->whereNotNull('pic')->where('isopen',1)->where($where2)->where('state',1)->where($where)->field($field)->limit($offset,$num)->order($order)->select();
				Cache::set('recimg'.$id,$recimg,3600);
			}
		}
		return $recimg;
	}
	public function suiimg($id,$num,$field,$where){
		if($id==0){
			$where2=true;
		}else{
			$ids=Db::name('cate')->where('fid',$id)->column('id');
			$ids[]=$id;
			$where2=[
				'cateid'=>$ids
			];
		}
		if(config('app_debug')){
			$suiimg=Db::name('article')->whereNotNull('pic')->where('isopen',1)->where($where2)->where($where)->field($field)->limit($num)->order(true)->select();
		}else{
			if(!$suiimg=Cache::get('suiimg'.$id)){
				$suiimg=Db::name('article')->whereNotNull('pic')->where('isopen',1)->where($where2)->where($where)->field($field)->limit($num)->order(true)->select();
				Cache::set('suiimg'.$id,$suiimg,3600);
			}
		}
		
		return $suiimg;
	}
	public function breadcrumb($controller,$action,$id){
		$cate=[];
		if($action=='cate'){
			if($wo=Db::name('cate')->where('id',$id)->field('id,fid,catename')->find()){
				$cate[]=$wo;
			}
			if($wo=Db::name('cate')->where('id',$wo['fid'])->field('id,fid,catename')->find()){
				$cate[]=$wo;
			}
		}
		
		if($action=='show'){
			$id=Db::name('article')->where('id',$id)->value('cateid');
			if($wo=Db::name('cate')->where('id',$id)->field('id,fid,catename')->find()){
				$cate[]=$wo;
			}
			if($wo=Db::name('cate')->where('id',$wo['fid'])->field('id,fid,catename')->find()){
				$cate[]=$wo;
			}
		}
		return array_reverse($cate);
	}
	public function lit($id,$unm){
		if(config('app_debug')){
			$lit=Db::name('article')->where('isopen',1)->where('cateid',$id)->order('state desc,time desc,id desc')->paginate($unm);
		}else{
			if(!$lit=Cache::get('lit'.$id)){
				$lit=Db::name('article')->where('isopen',1)->where('cateid',$id)->order('state desc,time desc,id desc')->paginate($unm);
				Cache::set('lit'.$id,$lit,3600);
			}
		}
		return $lit;
	}
	
	
	
	
}