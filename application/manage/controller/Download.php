<?php
namespace app\manage\controller;

use app\manage\controller\Conn;
use app\manage\model\Cate as Catemodel;
use app\manage\model\Download as Downloadmodel;
use think\Db;

class Download extends Conn
{
    public function index()
    {
		$data=input('get.');
		//获取参数 返回首页
		if(!isset($data['key'])){
			$data['key']='';
		}
		$this->assign('data', $data);
		$info=Db::name('download')
		->where('title', 'like', '%'.$data['key'].'%')
		->order('id desc')
		->paginate(10,false,['query'=>request()->param()]);
		$this->assign('info', $info);
        $count1=Db::name('download')->count();
        $this->assign('count1', $count1);
        return $this->fetch();
    }
    public function ajax()
    {
        $data=input('param.');
        if ($data['type']=='download_del') {
            //删除图片(包括缩略图和内容图片)
            $shan=Db::name('download')->find($data['id']);
            $imgarr=[];
            if ($shan['pic']) {
                $imgarr[]=$shan['pic'];
            }
            preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $shan["text"], $arr);
            foreach ($arr[1] as $k=>$v) {
                if ($v and substr($v, 0, 4)!='http') {
                    $imgarr[]=$v;
                }
            }
            foreach ($imgarr as $k1=>$v1) {
                @unlink(substr($v1, 1));
            }
			
            if (Db::name('download')->delete($data['id'])) {
                return 1;//修改成功返回1
            } else {
                return 0;
            }
        }
        if ($data['type']=='download_all') {
            //删除图片(包括缩略图和内容图片)
            $shan=Db::name('download')->where('id', 'in', $data['id'])->column('text', 'pic');
            $imgarr=[];
            foreach ($shan as $k=>$v) {
                if ($k) {
                    $imgarr[]=$k;
                }
                preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $v, $arr);
                foreach ($arr[1] as $k1=>$v1) {
                    if ($v1 and substr($v1, 0, 4)!='http') {
                        $imgarr[]=$v1;
                    }
                }
            }
            foreach ($imgarr as $k1=>$v1) {
                @unlink(substr($v1, 1));
            }
            
            if (Db::name('download')->delete($data['id'])) {
                return 1;//修改成功返回1
            } else {
                return 0;
            }
        }
		if ($data['type']=='cate_download') {
			$res=Db::name('cate')->where('id',$data['id'])->value('type');
			if ($res) {
			    return $res;
			} else {
			    return 0;
			}
		}
		if ($data['type']=='download_del_img') {
			//删除图片(包括缩略图和内容图片)
			$shan=Db::name('download_img')->find($data['id']);
			@unlink(substr($shan['pic'], 1));
			
			if (Db::name('download_img')->delete($data['id'])) {
			    return 1;//修改成功返回1
			} else {
			    return 0;
			}
			
		}
		if($data['type']=='download_start'){
			if(Db::name('download')->where('id',$data['id'])->setField('isopen',1)){
				return 1;//修改成功返回1
			}else{
				return 0;
			}
		}
		if($data['type']=='download_stop'){
			if(Db::name('download')->where('id',$data['id'])->setField('isopen',0)){
				return 1;//修改成功返回1
			}else{
				return 0;
			}
		}
		
		
        return 0;
    }
    public function add()
    {
        if (request()->isPost()) {
            $data=input('post.');
            $validate = new \app\manage\validate\Download;
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
			
            if (!isset($data['state'])) {
                $data['state']=0;
            } else {
                $data['state']=1;
            }
			if(empty($data['time'])){
				$data['time']=time();
			}else{
				$data['time']=strtotime($data['time']);
			}
            $file = request()->file('');
            if (isset($file['pic'])) {
                $info = $file['pic']->move('download');
                $li=strtr($info->getSaveName(), " \ ", " / ");
                $data['pic']='/download/'.$li;
            } 
			if (isset($file['file'])) {
				$info = $file['file']->move('download');
				$li=strtr($info->getSaveName(), " \ ", " / ");
				$data['file']='/download/'.$li;
			}else{
				$this->error('请添加附件！');
			}
            if (input('desc')=='') {
                $data['desc']=mb_substr(preg_replace('/\&nbsp;/', '', strip_tags(input('text'))), 0, 80);
            }
			
			preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $data["text"], $arr);
			$config=Db::name('config')->column('value', 'key');
			//压缩图片
			foreach ($arr[1] as $k=>$v) {
			    if (substr($v, 0, 4)!='http') {
			        $newarr=getimagesize(substr($v, 1));
				   if ($config["is_ya"] ==1 and $newarr[0] >$config["ya_w"]) {
				      $image = \think\Image::open(substr($v, 1));
				      $h=$config["ya_w"]*$newarr[1]/$newarr[0];
				      $image->thumb($config['ya_w'],$h)->save(substr($v, 1));
							
				   }
			    }
			}
			
			
            $data['faid']=session('uid');
            if (Db::name('download')->strict(false)->insertGetId($data)) {
                return '<script>alert("你好，添加成功了！");parent.location.reload()</script>';
            } else {
                $this->error('添加失败了');
            }
        }

        return $this->fetch();
    }
    public function edit()
    {
        if (request()->isPost()) {
            $data=input('post.');
            $validate = new \app\manage\validate\Download;
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            if (!isset($data['state'])) {
                $data['state']=0;
            } else {
                $data['state']=1;
            }
           if(empty($data['time'])){
           	$data['time']=time();
           }else{
           	$data['time']=strtotime($data['time']);
           }
            if (input('desc')=='') {
                $data['desc']=mb_substr(preg_replace('/\&nbsp;/', '', strip_tags(input('text'))), 0, 80);
            }
            $file = request()->file('');
            if (isset($file['pic'])) {
                $info = $file['pic']->move('uploads');
                $li=strtr($info->getSaveName(), " \ ", " / ");
                $data['pic']='/uploads/'.$li;
            } 
			if (isset($file['file'])) {
				$info = $file['file']->move('download');
				$li=strtr($info->getSaveName(), " \ ", " / ");
				$data['file']='/download/'.$li;
			}
			
			preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $data["text"], $arr);
			$config=Db::name('config')->column('value', 'key');
			//压缩图片
			foreach ($arr[1] as $k=>$v) {
			    if (substr($v, 0, 4)!='http') {
			        $newarr=getimagesize(substr($v, 1));
				   if ($config["is_ya"] ==1 and $newarr[0] >$config["ya_w"]) {
				      $image = \think\Image::open(substr($v, 1));
				      $h=$config["ya_w"]*$newarr[1]/$newarr[0];
				      $image->thumb($config['ya_w'],$h)->save(substr($v, 1));
							
				   }
			    }
			}
            $res=model('download')->allowField(true)->save($data, ['id' => input('id')]);
            if ($res) {
                return $this->success('修改成功', url('download/index'));
            } else {
                return $this->error('修改失败了');
            }
        }
        $cid=input('id');
        $data=Db::name('download')->where('id', $cid)->find();
        $this->assign('data', $data);
        return $this->fetch();
    }
}
