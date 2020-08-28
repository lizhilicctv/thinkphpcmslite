<?php
namespace app\api\controller;

use app\api\controller\Base;
use think\Db;

class Index extends Base
{
    public function _empty()
    {
        header("Location:/404.html");
        exit;
    }
    public function ajax()
    {
        $data=input('post.');
    
        if (!isset($data["lizhili"]) or !isset($data["type"]) or $data["lizhili"]!= "0d89b868429be6158ba1ebc0f7c073de") {
            header("Location:/404.html");
            exit;
        }
		//分享数量加一
		if ($data['type']=='update_fen_num') {
		    Db::name('user')->where('id',$data['uid'])->setInc('fen_num');
		    return ['code'=>1,'message'=>'成功'];
		    
		}
		
        
        return ['code'=>0,'message'=>'非法获取'];
    }
}
