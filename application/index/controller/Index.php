<?php

	/**
	 *
	 * Created by PhpStorm.
	 * User: knight
	 * Date: 2017/5/26
	 * Time: 13:35
	 */
	namespace app\index\controller;
	use app\index\logic\MacLogic;
	use think\Controller;
	use think\Exception;
	use think\Request;

	class Index extends Controller
	{
		public function __construct(){
			parent::__construct();
		}

		public function index()
		{
			$logic = new MacLogic();
			$ipBindList = $logic->getMacList();
			return $this->fetch('',['list'=>$ipBindList]);
		}

		/**
		 *
		 * @access public
		 * @return void
		 * @author knight
		 */
		public function edit()
		{
			return $this->fetch('form');
		}

		public function form()
        {
			if(Request::instance()->isPost()){
                $data['mac'] = trim(input('mac'));
                $data['remark'] = trim(input('remark'));
                $result = $this->validate($data,"MacValidate");
                if($result !== true){
                    $this->error($result);
                }
                $logic = new MacLogic();
                $ret = $logic->addMacToRos($data['mac'],$data['remark']);
                if($ret===4001){
                    $this->error('MAC地址重复');
                }
                if($ret){
                    $this->success(lang('success options'),url('index'));
                }
                $this->error(lang('error server'));
			}else{
				throw new Exception(lang('error param'));
			}
		}

        /**
         * 删除MAC地址
         * @return array
         * @throws Exception
         */
		public function delMacAddress()
        {
            if(Request::instance()->isPost() && input('id')){
                $logic = new MacLogic();
                $ret = $logic->delMacFromRos(input('id'));
                if($ret){
                    return ['success'=>true,'msg'=>lang('success options')];
                }
                return ['success'=>false,'msg'=>lang('error server')];
            }else {
                throw new Exception(lang('error param'));
            }
        }
	}