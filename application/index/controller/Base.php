<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Base as Basemodel;
use think\Db;
use think\facade\Cache;
class Base extends Controller
{
    //date_default_timezone_set("PRC");
	public function initialize()
    {
		$base= new Basemodel();
		//系统配置
		if(config('app_debug')){
			$system=$base->getsystem();
		}else{
			if(!$system=Cache::get('system')){
				$system=$base->getsystem();
				Cache::set('system',$system,3600);
			}
		}
		$this->assign('system', $system);
		//分配广告
		if(config('app_debug')){
			$ad=$base->ad();
		}else{
			if(!$ad=Cache::get('ad')){
				$ad=$base->ad();
				Cache::set('ad',$ad,3600);
			}
		}
		$this->assign('ad', $ad);
		
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