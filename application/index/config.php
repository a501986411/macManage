<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
	return [
        'default_filter'         => 'trim,strip_tags,htmlspecialchars',
		'captcha'  => [
			// 验证码字符集合
			'codeSet'  => '0123456789',
			// 验证码字体大小(px)
			'fontSize' => 25,
			// 是否画混淆曲线
			'useCurve' => true,
			// 验证码图片高度
			'imageH'   => 30,
			// 验证码图片宽度
			'imageW'   => 100,
			// 验证码位数
			'length'   => 5,
			// 验证成功后是否重置
			'reset'    => true
		],
		// +----------------------------------------------------------------------
		// | Cookie设置
		// +----------------------------------------------------------------------
		'cookie'                 => [
			// cookie 名称前缀
			'prefix'    => 'admin_',
			// cookie 保存时间
			'expire'    => 30*60,
			// cookie 保存路径
			'path'      => '/',
			// cookie 有效域名
			'domain'    => '',
			//  cookie 启用安全传输
			'secure'    => false,
			// httponly设置
			'httponly'  => '',
			// 是否使用 setcookie
			'setcookie' => true,
		],
		'template'               => [
			'layout_on'     =>  true,
			'layout_name'   =>  'layout/layout',
		],

		'ros'=>[
			'domain'=>'home.webok.me',
			'port'=>'8728',
			'username'=>'api',
			'password'=>'api'
		]
	];

