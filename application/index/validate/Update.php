<?php
namespace app\index\validate;
use think\Validate;
class Update extends Validate
{
     protected $rule = [
        'type' =>  'require|token',
    ];
	 protected $message  =   [
        'type.require' => '类型必须填写', 
		'type.token' => '不允许提交', 
    ];
	protected $scene = [
        'edit'  =>  ['password'=>'require|length:6,16'],
    ];
}
