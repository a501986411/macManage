<?php

	/**
	 *
	 * Created by PhpStorm.
	 * User: knight
	 * Date: 2017/5/26
	 * Time: 13:35
	 */
	namespace app\count\controller;

	use app\count\logic\CountLogic;
	use app\count\model\WhcWeixinWifiRecord;

	class Index
	{
		public function index()
		{
			$logic = new CountLogic(new WhcWeixinWifiRecord());
			$date = new \DateTime();
			//今日
			$var['countToday'] = $logic->count(strtotime(date('Y-m-d',time())),time());
			//本周
			$weekFirstDay =$date->modify('this week')->format('Y-m-d');//本周第一天
			$var['countWeek'] = $logic->count(strtotime(date('Y-m-d',strtotime($weekFirstDay))),time());
			//本月
			$var['countMonth'] = $logic->count(strtotime(date('Y-m-01',strtotime(time()))),time());
			//最近30天
			$startTime = strtotime(date("Y-m-d",time()))-(86400*29);
			$var['count30'] = $logic->count($startTime,time());
			return view('',$var);
		}


	}