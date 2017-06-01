<?php

	/**
	 *
	 * Created by PhpStorm.
	 * User: knight
	 * Date: 2017/5/26
	 * Time: 15:35
	 */
	namespace app\index\logic;
	use org\RouterosApi;
    use org\RouterosInfo;
    use think\Model;


	class MacLogic extends Model
	{
		protected $domain;
		protected $port;
		protected $user;
		protected $pwd;
		public function __construct(){
			parent::__construct();
            $ros = config('ros');
            $this->domain = $ros['domain'];
            $this->port = $ros['port'];
            $this->user = $ros['username'];
            $this->pwd = $ros['password'];
		}

		public function getMacList()
		{
			$ros = new RouterosInfo($this->domain,$this->port,$this->user,$this->pwd);
			$ipBindInfo = $ros->getIpBindInfo();
			foreach ($ipBindInfo as $k=>&$v){
                $v['comment']= isset($v['comment']) ? $v['comment'] : '';
                $v['type']= isset($v['type']) ? $v['type'] : '';
            }
			return $ipBindInfo;
		}

        /**
         * @param $mac
         * @param string $mark
         */
		public function addMacToRos($mac,$mark='')
        {
            $macList = array_column($this->getMacList(),'mac-address');
            if(in_array($mac,$macList)){
                return 4001;
            }
            $ros = new RouterosApi();
            if($ros->connect($this->domain.':'.$this->port,$this->user,$this->pwd)){
                $addCom = '/ip/hotspot/ip-binding/add';
                $ros->write($addCom,false);
                $ros->write('=mac-address='.$mac,false);
                $ros->write('=type=bypassed',false);
                $ros->write('=comment='.$mark);
                $ret = $ros->read(true);
                if($ret){
                    return true;
                }
                return false;
            }else {
                return false;
            }
        }

        /**
         * 删除MAC地址
         * @param $id
         * @return bool
         */
        public function delMacFromRos($id)
        {
            $ros = new RouterosApi();
            if($ros->connect($this->domain.':'.$this->port,$this->user,$this->pwd)){
                $removeCom = '/ip/hotspot/ip-binding/remove';
                $ros->write($removeCom,false);
                $ros->write('=.id='.$id);
                $ros->read(true);
                return true;
            }else {
                return false;
            }
        }

	}