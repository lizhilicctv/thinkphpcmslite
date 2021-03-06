<?php
namespace app\index\model;

use think\Model;
use think\Db;
use think\facade\Cache;

class Cate extends Model
{
    //如果表名和文件名不是对应的，用下面代码修改
    public function cate($id, $num, $offset, $order, $field, $where)
    {
        if ($id) {
            $ids=Db::name('cate')->where('fid', $id)->where('type', 'in', [1,2,3])->column('id');
            $ids[]=$id;
        } else {
            $ids=Db::name('cate')->where('type', 'in', [1,2,3])->column('id');
        }
        if ($order) {
            $order='state desc,time desc,id asc';
        } else {
            $order='state desc,time desc,id desc';
        }
        if (config('app_debug')) {
            $cate=Db::name('article')->where('isopen', 1)->where('cateid','in',$ids)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
			foreach($cate as $k=>$v){
				if (!$v['pic']) {
				    $cate[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
				}
			}
        } else {
            if (!$cate=Cache::get('cate'.$id)) {
                $cate=Db::name('article')->where('isopen', 1)->where('cateid','in',$ids)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
				foreach($cate as $k=>$v){
					if (!$v['pic']) {
					    $cate[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
					}
				}
                Cache::set('cate'.$id, $cate, 3600);
            }
        }
        return $cate;
    }

    public function cateall($ids, $num, $unids, $order, $field, $where)
    {
        if (!is_array($ids) or empty($ids)) {
            return 'ids 必须为数组！';
        }
        if ($order) {
            $order1='id asc';
        } else {
            $order1='id desc';
        }
        $id=implode("", $ids);
        if (config('app_debug')) {
            $cateall=Db::name('cate')->where('fid', 0)->where('isopen', 1)->where('id', 'in', $ids)->where('type', 'in', [1,2,3])->order($order1)->field('id,catename,en_name')->select();
            foreach ($cateall as $k=>$v) {
                if ($order) {
                    $order='state desc,time desc,id asc';
                } else {
                    $order='state desc,time desc,id desc';
                }
                $data=Db::name('cate')->where('fid', $v['id'])->where('isopen', 1)->where('id', 'notin', $unids)->where('type', 'in', [1,2,3])->order($order1)->field('id,catename,en_name')->select();
                foreach ($data as $k=>$v) {
                    $data[$k]['list']=Db::name('article')->where('isopen', 1)->where('cateid', $v['id'])->where($where)->field($field)->limit($num)->order($order)->select();
					foreach($data[$k]['list'] as $k1=>$v1){
						if (!$v1['pic']) {
						    $data[$k]['list'][$k1]['pic']=Db::name('article_img')->where('aid', $v1['id'])->value('pic');
						}
					}
                }
                $cateall[$k]['zi']=$data;
            }
        } else {
            if (!$cateall=Cache::get('$cateall'.$id)) {
                $cateall=Db::name('cate')->where('fid', 0)->where('isopen', 1)->where('id', 'in', $ids)->where('type', 'in', [1,2,3])->order($order1)->field('id,catename,en_name')->select();
                foreach ($cateall as $k=>$v) {
                    if ($order) {
                        $order='state desc,time desc,id asc';
                    } else {
                        $order='state desc,time desc,id desc';
                    }
                    $data=Db::name('cate')->where('fid', $v['id'])->where('isopen', 1)->where('id', 'notin', $unids)->where('type', 'in', [1,2,3])->order($order1)->field('id,catename,en_name')->select();
                    foreach ($data as $k=>$v) {
                        $data[$k]['list']=Db::name('article')->where('isopen', 1)->where('cateid', $v['id'])->where($where)->field($field)->limit($num)->order($order)->select();
						foreach($data[$k]['list'] as $k1=>$v1){
							if (!$v1['pic']) {
							    $data[$k]['list'][$k1]['pic']=Db::name('article_img')->where('aid', $v1['id'])->value('pic');
							}
						}
                    }
                    $cateall[$k]['zi']=$data;
                }
                Cache::set('cateall'.$id, $cateall, 3600);
            }
        }
        return $cateall;
    }
    public function catelist($ids, $num, $order, $field, $where)
    {
        if (!is_array($ids) or empty($ids)) {
            return 'ids 必须为数组！';
        }
        $id=implode("", $ids);
        if ($order) {
            $order1='id asc';
        } else {
            $order1='id desc';
        }
        $catelist=[];
        if (config('app_debug')) {
            foreach ($ids as $k => $v) {
                if ($order) {
                    $order='state desc,time desc,id asc';
                } else {
                    $order='state desc,time desc,id desc';
                }
                $res=Db::name('article')->where('isopen', 1)->where('cateid', $v)->where($where)->field($field)->limit($num)->order($order)->select();
                foreach ($res as $k1 => $v1) {
					if (!$v1['pic']) {
						$res[$k1]['pic']=Db::name('article_img')->where('aid', $v1['id'])->value('pic');
					}
                    $res[$k1]['catename']=Db::name('cate')->where('id', $v1['cateid'])->value('catename');
                    $res[$k1]['en_name']=Db::name('cate')->where('id', $v1['cateid'])->value('en_name');
                }
                $catelist=array_merge($catelist, $res);
            }
        } else {
            if (!$catelist=Cache::get('catelist'.$id)) {
                foreach ($ids as $k => $v) {
                    if ($order) {
                        $order='state desc,time desc,id asc';
                    } else {
                        $order='state desc,time desc,id desc';
                    }
                    $res=Db::name('article')->where('isopen', 1)->where('cateid', $v)->where($where)->field($field)->limit($num)->order($order)->select();
                    foreach ($res as $k1 => $v1) {
						if (!$v1['pic']) {
							$res[$k1]['pic']=Db::name('article_img')->where('aid', $v1['id'])->value('pic');
						}
                        $res[$k1]['catename']=Db::name('cate')->where('id', $v1['cateid'])->value('catename');
                        $res[$k1]['en_name']=Db::name('cate')->where('id', $v1['cateid'])->value('en_name');
                    }
                    $catelist=array_merge($catelist, $res);
                }
                Cache::set('catelist'.$id, $catelist, 3600);
            }
        }
        return $catelist;
    }
    
    public function hot($id, $num, $offset, $order, $field, $where)
    {
        if ($id==0) {
            $where2=true;
        } else {
            $ids=Db::name('cate')->where('fid', $id)->column('id');
            $ids[]=$id;
            $where2=[
                'cateid'=>$ids
            ];
        }
        if ($order) {
            $order='click desc,time desc,id asc';
        } else {
            $order='click desc,time desc,id desc';
        }
        if (config('app_debug')) {
            $hot=Db::name('article')->where('isopen', 1)->where($where2)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
			foreach($hot as $k=>$v){
				if (!$v['pic']) {
				    $hot[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
				}
			}
        } else {
            if (!$hot=Cache::get('hot'.$id)) {
                $hot=Db::name('article')->where('isopen', 1)->where($where2)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
				foreach($hot as $k=>$v){
					if (!$v['pic']) {
					    $hot[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
					}
				}
                Cache::set('hot'.$id, $hot, 3600);
            }
        }
        return $hot;
    }
    public function rec($id, $num, $offset, $order, $field, $where)
    { //推荐
        if ($id==0) {
            $where2=true;
        } else {
            $ids=Db::name('cate')->where('fid', $id)->column('id');
            $ids[]=$id;
            $where2=[
                'cateid'=>$ids
            ];
        }
        if ($order) {
            $order='time desc,id asc';
        } else {
            $order='time desc,id desc';
        }
        if (config('app_debug')) {
            $rec=Db::name('article')->where('isopen', 1)->where($where2)->where('state', 1)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
			foreach($rec as $k=>$v){
				if (!$v['pic']) {
				    $rec[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
				}
			}
        } else {
            if (!$rec=Cache::get('rec'.$id)) {
                $rec=Db::name('article')->where('isopen', 1)->where($where2)->where('state', 1)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
				foreach($rec as $k=>$v){
					if (!$v['pic']) {
					    $rec[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
					}
				}
                Cache::set('rec'.$id, $rec, 3600);
            }
        }
        return $rec;
    }
    public function sui($id, $num, $field, $where)
    {
        if ($id==0) {
            $where2=true;
        } else {
            $ids=Db::name('cate')->where('fid', $id)->column('id');
            $ids[]=$id;
            $where2=[
                'cateid'=>$ids
            ];
        }
        if (config('app_debug')) {
            $sui=Db::name('article')->where('isopen', 1)->where($where2)->where($where)->field($field)->limit($num)->order(true)->select();
			foreach($sui as $k=>$v){
				if (!$v['pic']) {
				    $sui[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
				}
			}
        } else {
            if (!$sui=Cache::get('sui'.$id)) {
                $sui=Db::name('article')->where('isopen', 1)->where($where2)->where($where)->field($field)->limit($num)->order(true)->select();
				foreach($sui as $k=>$v){
					if (!$v['pic']) {
					    $sui[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
					}
				}
                Cache::set('sui'.$id, $sui, 3600);
            }
        }
        return $sui;
    }
    public function hotimg($id, $num, $offset, $order, $field, $where)
    {
        if ($id==0) {
            $where2=true;
        } else {
            $ids=Db::name('cate')->where('fid', $id)->column('id');
            $ids[]=$id;
            $where2=[
                'cateid'=>$ids
            ];
        }
        if ($order) {
            $order='click desc,time desc,id asc';
        } else {
            $order='click desc,time desc,id desc';
        }
        if (config('app_debug')) {
            $hotimg=Db::name('article')->whereNotNull('pic')->where('isopen', 1)->where($where2)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
			foreach($hotimg as $k=>$v){
				if (!$v['pic']) {
				    $hotimg[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
				}
			}
        } else {
            if (!$hotimg=Cache::get('hotimg'.$id)) {
                $hotimg=Db::name('article')->whereNotNull('pic')->where('isopen', 1)->where($where2)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
				foreach($hotimg as $k=>$v){
					if (!$v['pic']) {
					    $hotimg[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
					}
				}
                Cache::set('hotimg'.$id, $hotimg, 3600);
            }
        }
        return $hotimg;
    }
    public function recimg($id, $num, $offset, $order, $field, $where)
    {
        if ($id==0) {
            $where2=true;
        } else {
            $ids=Db::name('cate')->where('fid', $id)->column('id');
            $ids[]=$id;
            $where2=[
                'cateid'=>$ids
            ];
        }
        if ($order) {
            $order='time desc,id asc';
        } else {
            $order='time desc,id desc';
        }
        if (config('app_debug')) {
            $recimg=Db::name('article')->whereNotNull('pic')->where('isopen', 1)->where($where2)->where('state', 1)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
			foreach($recimg as $k=>$v){
				if (!$v['pic']) {
				    $recimg[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
				}
			}
        } else {
            if (!$recimg=Cache::get('recimg'.$id)) {
                $recimg=Db::name('article')->whereNotNull('pic')->where('isopen', 1)->where($where2)->where('state', 1)->where($where)->field($field)->limit($offset, $num)->order($order)->select();
				foreach($recimg as $k=>$v){
					if (!$v['pic']) {
					    $recimg[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
					}
				}
                Cache::set('recimg'.$id, $recimg, 3600);
            }
        }
        return $recimg;
    }
    public function suiimg($id, $num, $field, $where)
    {
        if ($id==0) {
            $where2=true;
        } else {
            $ids=Db::name('cate')->where('fid', $id)->column('id');
            $ids[]=$id;
            $where2=[
                'cateid'=>$ids
            ];
        }
        if (config('app_debug')) {
            $suiimg=Db::name('article')->whereNotNull('pic')->where('isopen', 1)->where($where2)->where($where)->field($field)->limit($num)->order(true)->select();
			foreach($suiimg as $k=>$v){
				if (!$v['pic']) {
				    $suiimg[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
				}
			}

        } else {
            if (!$suiimg=Cache::get('suiimg'.$id)) {
                $suiimg=Db::name('article')->whereNotNull('pic')->where('isopen', 1)->where($where2)->where($where)->field($field)->limit($num)->order(true)->select();
				foreach($suiimg as $k=>$v){
					if (!$v['pic']) {
					    $suiimg[$k]['pic']=Db::name('article_img')->where('aid', $v['id'])->value('pic');
					}
				}
                Cache::set('suiimg'.$id, $suiimg, 3600);
            }
        }
        
        return $suiimg;
    }
    public function breadcrumb($controller, $action, $id)
    {
        $cate=[];
        if ($action=='cate') {
            if ($wo=Db::name('cate')->where('id', $id)->field('id,fid,catename')->find()) {
                $cate[]=$wo;
            }
            if ($wo=Db::name('cate')->where('id', $wo['fid'])->field('id,fid,catename')->find()) {
                $cate[]=$wo;
            }
        }
        if ($action=='show') {
            $id=Db::name('article')->where('id', $id)->value('cateid');
            if ($wo=Db::name('cate')->where('id', $id)->field('id,fid,catename')->find()) {
                $cate[]=$wo;
            }
            if ($wo=Db::name('cate')->where('id', $wo['fid'])->field('id,fid,catename')->find()) {
                $cate[]=$wo;
            }
        }
        return array_reverse($cate);
    }
    public function lit($id, $unm)
    {
        if (!$page=input('page')) {
            $page=1;
        }
        if (config('app_debug')) {
            $lit=Db::name('article')->where('isopen', 1)->where('cateid', $id)->order('state desc,time desc,id desc')->paginate($unm)->each(function ($item, $key) {
                if (!$item['pic']) {
                    $item['pic']=Db::name('article_img')->where('aid', $item['id'])->value('pic');
                }
                return $item;
            });
        } else {
            if (!$lit=Cache::get('lit'.$id.$page)) {
                $lit=Db::name('article')->where('isopen', 1)->where('cateid', $id)->order('state desc,time desc,id desc')->paginate($unm)->each(function ($item, $key) {
                    if (!$item['pic']) {
                        $item['pic']=Db::name('article_img')->where('aid', $item['id'])->value('pic');
                    }
                    return $item;
                });
                Cache::set('lit'.$id.$page, $lit, 3600);
            }
        }
        return $lit;
    }
    
    public function download($idarr, $order, $field, $where)
    {
        if (!is_array($idarr)) {
            return 'id 必须为数组！';
        }
        if ($order) {
            $order='state desc,time desc,id asc';
        } else {
            $order='state desc,time desc,id desc';
        }
        $download=Db::name('download')->where('isopen', 1)->where('id', 'in', $idarr)->where($where)->order($order)->field($field)->select();
        return $download;
    }
}
