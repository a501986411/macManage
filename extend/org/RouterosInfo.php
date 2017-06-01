<?php
	/**
	 *
	 * 获取路由服务器信息类
	 * User: knight
	 * Date: 2017/5/19
	 * Time: 14:23
	 */
	namespace org;


	class RouterosInfo extends RouterosApi
	{
		protected $domain; //域名
		protected $user; //登录用户名
		protected $pwd; //密码
		public $runTime; //服务器正常运行时间
		public $version; //系统版本号
		public $totalMemory; //总内存
		public $freeMemory;//空闲内存
		public $memoryRate;//内存占用率（float）
		public $totalHddSpace;//磁盘总空间（M）
		public $freeHddSpace;//剩余磁盘空间(M)
		public $buildTime;//系统建立时间
		public $cpu;//cpu型号
		public $cpuCount;//cpu个数
		public $cpuFrequency;//cpu频率（HZ）
		public $cpuLoad;//cpu负载
		public $boardName;//设备型号（名称）
		public $onLineUserNum;//在线用户数
		public $systemTime; //系统时间
		public $timeZone; //系统时区
		public $status;//状态是否正常
		public function __construct($domain,$port,$user,$pwd)
		{
			$this->port = $port;
			$this->domain = $domain;
			$this->user = $user;
			$this->pwd = $pwd;
			$this->connectSer();
			$this->setOptions();
		}

		/**
		 * 连接服务器
		 * @access public
		 * @return void
		 * @author knight
		 */
		private function connectSer()
		{
			if(!$this->connect($this->domain.':'.$this->port,$this->user,$this->pwd)){
				$this->status = false;
				return false;
			 } else {
				$this->status = true;
			}
		}

		/**
		 *
		 * @access public
		 * @return void
		 * @author knight
		 */
		public function setOptions()
		{
			if($this->status){
				$rosInfo = $this->getSystemHardwareInfo();
				$this->runTime = self::changeRunTime($rosInfo['uptime']);
				$this->version = $rosInfo['version'];
				$this->totalMemory = $rosInfo['total-memory'];
				$this->freeMemory = $rosInfo['free-memory'];
				$this->memoryRate = round((1-($this->freeMemory/$this->totalMemory)),2);
				$this->totalHddSpace = round($rosInfo['total-hdd-space']/1024/1024,1);
				$this->freeHddSpace = round($rosInfo['free-hdd-space']/1024/1024,1);
				$this->buildTime   =   date('Y-m-d H:i:s',strtotime(self::enDateToCn($rosInfo['build-time'])));
				$this->cpu = $rosInfo['cpu'];
				$this->cpuCount = $rosInfo['cpu-count'];
				$this->cpuFrequency = isset($rosInfo['cpu-frequency']) ? $rosInfo['cpu-frequency'] : '';
				$this->cpuLoad = $rosInfo['cpu-load'];
				$this->onLineUserNum = count($this->getOnLineUserInfo());
				$systemTimeInfo = $this->getSystemTimeInfo();
				$this->systemTime = date('Y-m-d H:i:s',strtotime(self::enDateToCn($systemTimeInfo['date']).' '.$systemTimeInfo['time']));
				$this->timeZone = $systemTimeInfo['time-zone-name'];
			}
		}

		/**
		 * 获取系统硬件信息
		 * @access public
		 * @return array
		 * @author knight
		 */
		public function getSystemHardwareInfo()
		{
			if(!$this->connected){
				$this->connectSer();
			}
			$this->write('/system/resource/getall');
			$rosInfo = $this->read(true);
			return $rosInfo[0];
		}

		/**
		 * 获取系统当前时间信息
		 * @access public
		 * @return array
		 * @author knight
		 */
		public function getSystemTimeInfo()
		{
			if(!$this->connected){
				$this->connectSer();
			}
			$this->write('/system/clock/getall');
			$systemTimeInfo = $this->read(true);
			return $systemTimeInfo[0];
		}

		/**
		 * 获取在线用户信息
		 * @access public
		 * @return array
		 * @author knight
		 */
		public function getOnLineUserInfo()
		{
			if(!$this->connected){
				$this->connectSer();
			}
			$this->write('/ppp/active/getall');
			$userInfo = $this->read(true);
			return $userInfo;
		}


		/**
		 * 获取Ip绑定信息
		 * @access public
		 * @return array
		 * @author knight
		 */
		public function getIpBindInfo(){
			if(!$this->connected){
				$this->connectSer();
			}
			$this->write('/ip/hotspot/ip-binding/getall');
			$ipBindInfo = $this->read(true);
			return $ipBindInfo;
		}

		/**
		 * 英文月份转换
		 * @access public
		 * @param $date
		 * @return mixed
		 * @author knight
		 */
		public static function enDateToCn($date)
		{
			$en = ['January','February','March','April',
				'May','June','July','August','September',
				'October','November','December'];

			$cn = ['01','02','03','04','05','06','07','08','09','10','11','12'];
			$en1 = ['Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec'];
			return str_ireplace ($en1,$cn,str_ireplace ($en,$cn,str_ireplace('Z',' ',$date)));
		}

		/**
		 * 转换运行时间格式
		 * @access public
		 * @param $time
		 * @return mixed
		 * @author knight
		 */
		public static function changeRunTime($time){
			//转换运行时间
			if(strpos($time,'w')>-1){
				$w = substr($time,0,strpos($time,'w'));
				$d = $w * 7;
				if(strpos($time,'d')>-1){
					$d += substr($time,strpos($time,'w')+1,(strpos($time,'d')-strpos($time,'w')));
					$time = $d.'d'.substr($time,(strpos($time,'d')+1));
				} else {
					$time = $d.'d'.substr($time,(strpos($time,'w')+1));
				}
			}
			return str_replace(['w','d','h','m','s'],['周','天','小时','分','秒'],$time);//运行时间
		}




	}