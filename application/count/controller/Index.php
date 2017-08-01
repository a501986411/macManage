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
			$var['tableData'] = json_encode($logic->getTableData(0,0,1));
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

		/**
		 * 改变table数据
		 * @access public
		 * @return void
		 * @author knight
		 */
		public function changeTableData()
		{
			if(Request::instance()->isPost()){
				$startTime = !empty(input('startTime')) ? strtotime(input('startTime')): '';
				$endTime = !empty(input('endTime')) ? strtotime(input('endTime'))+86400 : '';
				$page = !empty(input('page')) ? input('page'):1;
				$logic = new CountLogic(new WhcWeixinWifiRecord(),CountLogic::TODAY);
				return json($logic->getTableData($startTime,$endTime,$page));
			}else{
				throw new Exception(lang('error param'));
			}
		}
	}