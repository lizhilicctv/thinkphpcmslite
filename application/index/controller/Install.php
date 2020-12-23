<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
class Install extends Controller
{
    public function _empty()
    {
        //重定向浏览器
        header("Location:/404.html");
        //确保重定向后，后续代码不会被执行
        exit;
    }
	public function index()
	{
		$file=env('root_path').'tpl/'.'install.txt';
		if(file_get_contents($file)!='install'){
			header("Location:/404.html");
			//确保重定向后，后续代码不会被执行
			exit;
		}
		if(request()->isPost()){
			$data=input('post.');
			if(!$data["database"] or !$data["username"] or !$data["password"]){
				$this->error('数据信息必须填写');
			}	
			try{
				$mysql_server_name = '127.0.0.1'; //改成自己的mysql数据库服务器
				$mysql_username = $data["username"]; //改成自己的mysql数据库用户名
				$mysql_password = $data["password"]; //改成自己的mysql数据库密码
				$mysql_database = $data["database"]; //改成自己的mysql数据库名
				$conn=mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database); //连接数据库
				mysqli_close($conn);
			}catch(\Exception $e){
			    $this->error('数据信息不正确,请先创建数据库！');
			}
			//还原数据库
			$dir=env('root_path').'sql';
			$wo=scandir($dir);
			$sqlarr=[];
			foreach($wo as $k=>$v){
				if(\is_file($dir.'/'.$v)){
					$sqlarr[filemtime($dir.'/'.$v)]=[
						'dir'=>strtr($dir.'/'.$v, " \ ", " / "),
						'name'=>$v,
						'time'=>filemtime($dir.'/'.$v)
					];
				}
			}
			rsort($sqlarr);
			if(count($sqlarr)<=0){
				 $this->error('找不到sql文件');
			}
			$sql = new \sql\DatabaseTool([
			    'host' => '127.0.0.1',
			    'port' => 3306,
			    'user' => $data["username"],
			    'password' => $data["password"],
			    'database' => $data["database"],
			    'charset' => 'utf8',
			]);
			$wo=$sql->restore($sqlarr[0]['dir']);
			
			if($wo['code']==1){
				//修改数据配置
				
				$app=env('config_path').'database.php';
				$apphtml=file_get_contents($app);
				
				$apphtml=str_replace('_lizhili_database',$data["database"],$apphtml);
				$apphtml=str_replace('_lizhili_username',$data["username"],$apphtml);
				$apphtml=str_replace('_lizhili_password',$data["password"],$apphtml);
				file_put_contents($app,$apphtml); 
				
				$file=env('root_path').'tpl/'.'install.txt';
				file_put_contents($file,'lock'); 
				 $this->success('还原成功','index/index');
			}
			 $this->error('还原数据库失败');
		}
	
		
		//这里请使用缓存
		return $this->fetch('install');
	}
}
