<?php
namespace app\manage\controller;
use think\Controller;

use app\manage\controller\Auth;
use think\Db;

class Conn extends Controller
{
    public function initialize()
    {
         //date_default_timezone_set("PRC");
		
        if(!session('?name') or !session('?uid')){
        	return $this->error('请登陆后访问','login/index');
        }
		$auth=new Auth();
		$c=request()->controller();
		$a=request()->action();
		$url=$c.'/'.$a;
		if(session('uid')!==1){
			if($url!='Index/index'){
				if($url!='Index/main'){
					if(!$auth->check($url,session('uid'))){
						return $this->error('没有权限',url('index/main'));
					}
				}
				
			}
		}
	    $message=Db::name('message')->where('isopen','0')->count();
		$this->assign('message', $message);
	
		//获取导航
       $pilot_nav=Db::name('pilot_nav')->where('isopen',1)->order('sort asc')->select();
        $this->assign('pilot_nav', $pilot_nav);
		
        //侧边导航
        foreach ($pilot_nav as $k=>$v){
            $arr=Db::name('pilot_list')->where('fid',0)->where('pn_id',$v['id'])->where('isopen',1)->order('sort asc')->select();
            foreach ($arr as $k1=>$v1){
                $arr[$k1]['zi']=Db::name('pilot_list')->where('fid',$v1['id'])->where('isopen',1)->order('sort asc')->select();
            }
            $pilot_list[]=$arr;
        }
		//dump($pilot_list);
        $this->assign('pilot_list', $pilot_list);


    }
	//远程下载工具
	protected function curl($url='', $data=[])
	{
		if(!$url){
			return '地址必须填写';
		}
		$ch = curl_init();
		$params[CURLOPT_URL] = $url;    //请求url地址
		$params[CURLOPT_HEADER] = FALSE; //是否返回响应头信息
		$params[CURLOPT_SSL_VERIFYPEER] = false;
		$params[CURLOPT_SSL_VERIFYHOST] = false;
		$params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
		 if (!empty($data)) {
			$params[CURLOPT_POST] = true;
			$params[CURLOPT_POSTFIELDS] = \json_encode($data);
		}
		curl_setopt_array($ch, $params); //传入curl参数
		$content = curl_exec($ch); //执行
		curl_close($ch); //关闭连接
		if(json_decode($content, true)){
			return json_decode($content, true);
		}
		return $content;
	}
}
