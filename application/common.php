<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//计算距离现在多长时间
function tonow($data)
{
    $data=time()-$data;
    //计算天数
    $days = intval($data/86400);
    //计算小时数
    $remain = $data%86400;
    $hours = intval($remain/3600);
    //计算分钟数
    $remain = $data%3600;
    $mins = intval($remain/60);
    //计算秒数
    $secs = $data%60;
    return $days.'天'.$hours.'小时'.$mins.'分钟'.$secs.'秒';
}
function jiequ($data, $num=50)
{
	if(mb_strlen($data) > $num){
		return mb_substr($data, 0, $num).'...';
	}else{
		return mb_substr($data, 0, $num);
	}
}
//下面自己编写的方法
//获取幻灯片
//slide
function slide()
{
    if (config('app_debug')) {
        $slide=db('slide')->field('title,url,img,desc')->where('isopen',1)->order('sort desc,id desc')->select();
    } else {
        if (!$slide=cache('slide')) {
            $slide=db('slide')->field('title,url,img,desc')->where('isopen',1)->order('sort desc,id desc')->select();
            cache('slide', $slide, 3600);
        }
    }
    return $slide;
    // 使用方法
    // {volist name=":slide()" id="vo"}
    // {$vo.id}
    // {/volist}
}
//列表页
function lit($id=0,$num=10){
	if ($id==0) {
	    return 'id必须填写！';
	}
	$lit=model('cate')->lit($id,$num);
	return $lit;
	// 使用方法
	// {volist name=":lit($id,10)" id="vo"}
	// {$vo.id}
	// {/volist}
	//默认分页
	//{:lit($id,10)->render()}
}

function boot($data, $time=3000,$indicators=false,$control=false)
{ //广告幻灯片 利用Bootstrap
	if(!isset($data)){
		return '';
	}
    $html='<div id="carousel-example-generic" class="carousel slide lizhili_ad" data-ride="carousel" data-interval="'.$time.'"> ';
	if($indicators){
		$html.=' <ol class="carousel-indicators">';
				foreach ($data as $k=>$v) {
				    $html.='<li data-target="#carousel-example-generic" data-slide-to="'.$k.'" ';
				    if ($k==0) {
				        $html.='class="active"';
				    }
				    $html.=' ></li>';
				}
		$html.='  </ol>';
	}
	 $html.='<div class="carousel-inner">';
    foreach ($data as $k=>$v) {
        $html.='<div class="item ';
        if ($k==0) {
            $html.='active';
        }
        $html.='">		<a href="'.$v['url'].'" target="_blank"><img src="'.$v['img'].'" /></a>		</div>';
    }
    $html.='</div>';
	if($control){	
	 $html.=' <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	    <span class="sr-only">Next</span>
	  </a>';
	}
	$html.='</div>';
    return $html;
    //使用方法,数组，时间，点，上下页
	//幻灯片 注意一个页面只可以使用一个 幻灯片
	// {:boot(slide(),5000,true,true)} 
	
    //{:boot($ad.index,5000,true,true)}
}
function SuperSlide($data=null,$time=3000,$autoPlay=true,$next=false,$num=false,$color='#f00'){
	$name='a'.uniqid();
		$shu='';
		$shudian='';
		foreach ($data as $k=>$v) {
		    $shu.="<li><a href='".$v['url']."' target='_blank'><img src='".$v['img']."' /></a></li>";
			$shudian.="<li>".($k+1)."</li>";
		}
		if(!$num){
			$shudian='';
		}
		
		
		$qian='';
		if($next){
			$qian="/* 下面是前/后按钮代码，如果不需要删除即可 */
				.$name .prev,
				.$name .next{ position:absolute; left:3%; top:50%; margin-top:-25px; display:block; width:32px; height:40px; background:url(/static/slider-arrow.png) -110px 5px no-repeat; filter:alpha(opacity=50);opacity:0.5;   }
				.$name .next{ left:auto; right:3%; background-position:8px 5px; }
				.$name .prev:hover,
				.$name .next:hover{ filter:alpha(opacity=100);opacity:1;  }
				.$name .prevStop{ display:none;  }
				.$name .nextStop{ display:none;  }";
		}
		$dian='';
		if($num){
			$dian="
			.$name  .hd{ height:15px; overflow:hidden; position:absolute; right:5px; bottom:5px; z-index:1; }
			.$name  .hd ul{ overflow:hidden; zoom:1; float:left;  }
			.$name .hd ul li{ float:left; margin-right:2px;  width:15px; height:15px; line-height:14px; text-align:center; background:#fff; cursor:pointer; }
			";
		}
		
		
		$html=<<<EOF
		<style type="text/css">
				/* 本例子css */
				.$name{ width:100%; height:auto; overflow:hidden; position:relative;}
				.$name .hd ul li.on{ background:$color; color:#fff; }
				.$name .bd{ position:relative; height:100%; z-index:0;   }
				.$name .bd li{ zoom:1; vertical-align:middle;}
				.$name .bd img{ width:100%; height:auto; display:block;  }
				$qian
				$dian
				</style>
				<div class="$name">
					<div class="hd">
						<ul>
						$shudian
						</ul>
					</div>
					<div class="bd">
						<ul>
							$shu
						</ul>
					</div>
					<!-- 下面是前/后按钮代码，如果不需要删除即可 -->
					<a class="prev" href="javascript:void(0)"></a>
					<a class="next" href="javascript:void(0)"></a>
				</div>
				<script src="/static/jquery.SuperSlide.2.1.3.source.js"></script>
				<script type="text/javascript">
				jQuery(".$name").slide({mainCell:".bd ul",effect:"leftLoop",autoPlay:$autoPlay,interTime:$time});
				</script>
EOF;
	
	 return $html;
	 //使用说明
	 //需要提前引入 jq ，
	 //参数 广告数据，间隔时间，自动播放，是否显示 上下箭头
	 // {:SuperSlide($ad.index,$time=3000,$autoPlay=true,$next=false)}
	 
	 //幻灯片
	 //{:SuperSlide(slide(),$time=3000,$autoPlay=true,$next=false)} 
}

function hot($id=0,$num=3,$offset=0,$order=false,$field='*',$where=true) // 热门文章
{ 
    return model('cate')->hot($id, $num,$offset,$order, $field, $where);
    //使用方法
    // {volist name=":hot($id,$num,$offset,$order,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}
function hotimg($id=0,$num=3,$offset=0,$order=false,$field='*',$where=true) // 热门文章图片
{ 
    return model('cate')->hotimg($id, $num,$offset,$order, $field, $where);
    //使用方法
    // {volist name=":hotimg($id,$num,$offset,$order,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}
function rec($id=0,$num=3,$offset=0,$order=false,$field='*',$where=true) // 推荐文章
{ 
    return model('cate')->rec($id, $num,$offset,$order, $field, $where);
    //使用方法
    // {volist name=":rec($id,$num,$offset,$order,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}
function recimg($id=0,$num=3,$offset=0,$order=false,$field='*',$where=true) // 推荐图文
{ 
    return model('cate')->recimg($id, $num,$offset,$order, $field, $where);
    //使用方法
    // {volist name=":recimg($id,$num,$offset,$order,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}

function sui($id=0, $num=3,  $field='*', $where=true) //随机读取文章,默认调用两级
{ 
    return model('cate')->sui($id, $num, $field, $where);
    //使用方法
    // {volist name=":sui($id,$num,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}
function suiimg($id=0, $num=3,  $field='*', $where=true) //随机读取文章,默认调用两级
{ 
    return model('cate')->suiimg($id, $num, $field, $where);
    //使用方法
    // {volist name=":suiimg($id,$num,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}
function cate($id=0, $num=3, $offset=0,$order=false,$field='*', $where=true)
{
    return model('cate')->cate($id, $num, $offset,$order,$field, $where);
    //使用方法 //单个栏目
    // {volist name=":cate($id,$num,$offset,$order,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}
function cateall($ids=[], $num=3,$unids=[],$order=false,$field='*', $where=true)
{
    return model('cate')->cateall($ids, $num,$unids, $order,$field, $where);
    //使用方法 //两层循环 用于首页大循环
    // {volist name=":cateall([]$ids,$num,[]$unids,$order,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}
function catelist($ids=[], $num=3,$order=false,$field='*', $where=true)
{
    return model('cate')->catelist($ids, $num, $order,$field, $where);
    //使用方法  同时调用多个栏目
    // {volist name=":catelist([]$ids,$num,$order,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}
function friend()
{
    if (config('app_debug')) {
        $friend=db('link')->where('isopen', 1)->select();
    } else {
        if (!$friend=cache('friend')) {
            $friend=db('link')->where('isopen', 1)->select();
            cache('friend', $friend, 3600);
        }
    }
    return $friend;
    // 使用方法
    // {volist name=":friend()" id="vo"}
    // {$vo.id}
    // {/volist}
}
function nav($fid=0,$type=false)//第二参数，是否平铺
{ 
    if (config('app_debug')) {
		if($type){
			$cate = db('cate')->field('id,catename,en_name,type,url')->where('isopen', 1)->order('sort asc')->select();
		}else{
			$cate = db('cate')->where('fid', $fid)->field('id,catename,en_name,type,url')->where('isopen', 1)->order('sort asc')->select();
			foreach ($cate as $k=>$v) {
			    $cate[$k]['zi']=db('cate')->where('fid', $v['id'])->field('id,catename,en_name,type,url')->order('sort asc')->where('isopen', 1)->select();
			}
		}
        
    } else {
        if (!$cate=cache('cate')) {
            if($type){
            	$cate = db('cate')->field('id,catename,en_name,type,url')->where('isopen', 1)->order('sort asc')->select();
            }else{
            	$cate = db('cate')->where('fid', $fid)->field('id,catename,en_name,type,url')->where('isopen', 1)->order('sort asc')->select();
            	foreach ($cate as $k=>$v) {
            	    $cate[$k]['zi']=db('cate')->where('fid', $v['id'])->field('id,catename,en_name,type,url')->order('sort asc')->where('isopen', 1)->select();
            	}
            }
            cache('cate', $cate, 3600);
        }
    }
    return $cate;
    // 使用方法
    // {volist name=":nav()" id="vo"}
    // {$vo.id}
    // {/volist}
}
function breadcrumb($fen=''){ //面包屑导航
	$controller=strtolower(request()->controller());
	$action=strtolower(request()->action());
	
	if($controller!='index'){
		return '控制器必须为index';
	}
	if($action!='show' and $action!='cate'){
		return '方法必须为show 或者 cate';
	}
	$breadcrumb=model('cate')->breadcrumb($controller,$action,input('id'));

	$html='<ul class="breadcrumb">';
	$html.='<li><a href="/">首页</a></li>'.$fen;
	foreach ($breadcrumb as $k=>$v) {
	    $html.="<li><a href='/channel/{$v['id']}.html'>{$v['catename']}</a></li>$fen";
	}
	if($action=='show'){
		$html.="<li class='active'>正文</li>";
	}else{
		$html=substr($html, 0, -(strlen($fen)));
	}
	$html.='</ul>';
	if($fen){
		$sty= <<<___
<style type="text/css">
	.breadcrumb > li + li:before{
		content:none
	}
	.breadcrumb li{
		padding: 0 8px;
	}
</style>
___;
$html.=$sty;
	}
	return $html;
	// 使用方法
	// {:breadcrumb(' > ')} boot默认不需要传值
}
//获取下载文件
function download($idarr=[], $order=false,$field='*', $where=true)
{
    return model('cate')->download($idarr, $order,$field, $where);
    //使用方法
    // {volist name=":download([]$idarr,$order,$field,$where)" id="vo"}
    // {$vo.id}
    // {/volist}
}