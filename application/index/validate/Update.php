<?php
namespace app\index\validate;
use think\Validate;
class Update extends Validate
{
     protected $rule = [
        'type' =>  'require|token',
		'title' =>  'require',
		'need' =>  'require',
		'phone' =>  'mobile',
    ];
	 protected $message  =   [
        'type.require' => '类型必须填写', 
		'type.token' => '不允许提交', 
		'title.require' => '留言标题必须填写', 
		'need.require' => '内容必须填写', 
		'phone.mobile' => '手机格式不正确', 
    ];
	protected $scene = [
        'edit'  =>  ['password'=>'require|length:6,16'],
    ];
}
