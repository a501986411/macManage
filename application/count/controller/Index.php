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
	use think\Request;
	class Index
	{
		public function index()
		{
			$model = new WhcWeixinWifiRecord();
			//今日
			$logic = new CountLogic($model,CountLogic::TODAY);
			$var['countToday'] = $logic->count();
			//本周
			$logic = new CountLogic($model,CountLogic::WEEK);
			$var['countWeek'] = $logic->count();
			//本月
			$logic = new CountLogic($model,CountLogic::MONTH);
			$var['countMonth'] = $logic->count();
			//最近30天
			$logic = new CountLogic($model,CountLogic::LAST30);
			$var['count30'] = $logic->count();
			return view('', $var);
		}


		public function getLineData()
		{
			if(Request::instance()->has('lab') && Request::instance()->isPost('lab')){
				$label = input('lab');
				$logic = new CountLogic(new WhcWeixinWifiRecord(),$label);
				$data = $logic->getLineData();
				return json($data);
			}else{
				throw new Exception(lang('error param'));
			}
		}
	}