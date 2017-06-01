<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/29
 * Time: 14:11
 */

namespace app\index\validate;


use think\Validate;

class MacValidate extends Validate
{
    protected $rule = [
        'mac'=>'require|regex:^[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]:[0-9A-Fa-f][0-9A-Fa-f]$',
    ];
    protected $message = [
        'mac.require'=>'MAC地址不能为空',
        'mac.regex'=>'MAC地址格式不正确',
    ];
}