<?php
namespace app\manage\controller;

use app\manage\controller\Conn;
use think\Db;
class Map extends Conn
{
	//这里用前置操作，表示提前运行，本来要用于栏目删除子栏目呢，现在不用了
	protected $beforeActionList = [
        
    ];
    public function index()
    {
		$domain=request()->domain();
		$cate=Db::name('cate')->column('id');
		$article=Db::name('article')->column('id');
		
		$html='<?xml version="1.0" encoding="utf-8"?>';
		$html.='<urlset>';
		//添加主域名
		$html.='<url>';
		$html.='<loc>'.$domain.'</loc>';
		$html.=' <changefreq>Always</changefreq>';
		$html.=' <priority>1</priority>';
		$html.='</url>';
		//添加栏目
		foreach($cate as $v){
			$html.='<url>';
			$html.='<loc>'.$domain.'/channel/'.$v.'.html</loc>';
			$html.=' <changefreq>Always</changefreq>';
			$html.=' <priority>0.9</priority>';
			$html.='</url>';
		}
		//详情页
		foreach($article as $v){
			$html.='<url>';
			$html.='<loc>'.$domain.'/show/'.$v.'.html</loc>';
			$html.=' <changefreq>Always</changefreq>';
			$html.=' <priority>0.8</priority>';
			$html.='</url>';
		}
		$html.='</urlset>';
		file_put_contents('sitemap.xml',$html);
       	return $this->fetch('',[
			'sitemap'=>$domain.'/sitemap.xml',
		]);
    }
	
}
