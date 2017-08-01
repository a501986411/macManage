<?php
/**
 *
 * Created by PhpStorm.
 * User: knight
 * Date: 2017/7/26
 * Time: 16:54
 */
namespace app\count\logic;
use think\Model;

class CountLogic extends Model{
	private $model;
	private $startTime = '';
	private $endTime = '';
	private $label;
	const TODAY=1;//金额
	const WEEK=2;//本周
	const MONTH=3;//本月
	const LAST30=4;//最近30天
	private $weekDay =  ['星期一','星期二','星期三','星期四','星期五','星期六','星期日'];
	public function __construct($model,$label=self::TODAY)
	{
		$this->model = $model;
		$this->label = $label;
		switch($label){
			case self::TODAY:
				$this->startTime = strtotime(date('Y-m-d', time()));
				break;
			case self::WEEK:
				$date  = new \DateTime();
				$weekFirstDay     = $date->modify('this week')->format('Y-m-d');//本周第一天
				$this->startTime = strtotime(date('Y-m-d', strtotime($weekFirstDay)));
				break;
			case self::MONTH:
				$this->startTime = strtotime(date('Y-m-01', strtotime(time())));
				break;
			case self::LAST30:
				$this->startTime = strtotime(date("Y-m-d", time())) - (86400 * 29);
				break;
		}
		$this->endTime = time();
	}

	/**
	 * 统计今日访问人数
	 * @access public
	 * @param $startTime 查询开始时间
	 * @param $endTime 查询结束时间
	 * @return mixed
	 * @author knight
	 */
	public function count()
	{
		$data = $this->model
			->where('time','>',$this->startTime)
			->where('time','<=',$this->endTime)
			->count();
		return $data;
	}

	/**
	 * 构造X轴
	 * @access public
	 * @return array
	 * @author knight
	 */
	protected function getXData()
	{
		$xData = [];
		switch($this->label){
			case self::TODAY:
				$nowHours = date("H",time());
				for($i=0;$i<=intval($nowHours);$i++){
					$xData[] = [$i,$i.':00'];
				}
				break;
			case self::WEEK:
				$date  = new \DateTime();
				for($i=0;$i<7;$i++){
					$day = $date->modify('this week +'.$i.' days')->format('Y-m-d');
					if(strtotime($day)<time())
					{
						$xData[] = [$i,$day];
					}
				}
				break;
			case self::MONTH:
				$month = strtotime(date("Y-m-01",time()));
				$j=0;
				for($i=$month;$i<time();$i=$i+86400){
					$xData[] = [$j,date('d日',$i)];
					$j++;
				}
				break;
		}
		return $xData;
	}

	/**
	 * 获取折线统计图数据
	 * @access public
	 * @return array
	 * @author knight
	 */
	public function getLineData()
	{
		$timeFormat = $this->label == self::TODAY ? '%k:00' : ($this->label == self::WEEK ? '%Y-%m-%d' : '%d日');
		$sql = "select count(id) as num,groupField from (select id,	DATE_FORMAT(FROM_UNIXTIME(time), '{$timeFormat}') AS groupField
						from whc_weixin_wifi_record where time>{$this->startTime} and time <= {$this->endTime}) as tmp
						GROUP by tmp.groupField";
		$data = $this->model->query($sql);
		$data = array_column($data,'num','groupField');
		$retData = [];
		$ticks = $this->getXData();
		foreach($ticks as $k=>&$v){
			if(isset($data[$v[1]])){
				$retData[] = [$v[0],$data[$v[1]]];
			}else{
				$retData[] = [$v[0],0];
			}
			if($this->label == self::WEEK){
				$v[1] = $this->weekDay[$k];
			}
		}
		return ['data'=>$retData,'ticks'=>$ticks];
	}

	/**
	 * 获取连接用户列表
	 * @access public
	 * @param int $startTime
	 * @param int $endTime
	 * @param int $page
	 * @return mixed
	 * @author knight
	 */
	public function getTableData($startTime=0,$endTime=0,$page=1)
	{
		if(!empty($startTime)){
			$this->model->where('time','>=',$startTime);
		}
		if(!empty($endTime)){
			$this->model->where('time','<',$endTime);
		}
		$data = $this->model->order('id desc')->limit(($page-1)*20,20)->select();
		foreach($data as &$value){
			$value['time'] = date('Y-m-d H:i',$value['time']);
		}
		return $data;
	}
}