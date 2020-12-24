<?php

namespace app\http\middleware;

use think\Db;

class Check
{
    public function handle($request, \Closure $next)
    {
		$file=env('root_path').'tpl/'.'install.txt';
		if(!file_exists($file)){
		    file_put_contents($file,'install'); 
		}
		if(file_get_contents($file)=='install' and request()->controller()!="Install"){
			$file=env('root_path').'public/'.'.htaccess';
			file_put_contents($file,'<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php?/$1 [QSA,PT,L]
</IfModule>'); 
			return redirect('/install/index.html');
		}
	   if(request()->controller()!=="Notify" and request()->controller()!=="Install"){ //这个是微信支付的回调，以后回调都写在这个控制器里面的了
			$system= Db::name('system')->column('value','enname');
		   if($system["value"] != "开启"){
		       if($system["redirect"]){
		           return redirect($system["redirect"], '',302);
		       }
		       return json(['非法访问'])->code(404);
		       exit('网站关闭');
		   }
	   }
       return $next($request);
    }
}
