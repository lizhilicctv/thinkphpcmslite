<?php
namespace app\manage\validate;
use think\Validate;
class Download extends Validate
{
     protected $rule = [
        'title' =>  'require|unique:download',

    ];
	 protected $message  =   [
        'title.require' => '下载名称必须填写', 
        'title.unique'     => '当前下载名称已经存在，不能重复！',
    ];
	protected $scene = [
        'edit'  =>  ['username'],
    ];
}
