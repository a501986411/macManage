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
	public function __construct($model)
	{
		$this->model = $model;
	}

	/**
	 * ͳ�ƽ��շ�������
	 * @access public
	 * @param $startTime ��ѯ��ʼʱ��
	 * @param $endTime ��ѯ����ʱ��
	 * @return mixed
	 * @author knight
	 */
	public function count($startTime,$endTime)
	{
		$data = $this->model
			->where('time','>',$startTime)
			->where('time','<=',$endTime)
			->count();
		return $data;
	}


	/**
	 * ͳ�Ʊ��ܷ�������
	 * @access public
	 * @return mixed
	 * @author knight
	 */
	public function countWeek()
	{
		$date = new \DateTime();
		$date->modify('this week'); //���ܵ�һ��
		$weekFirstDay = $date->format('Y-m-d');
		$weekFirstDayTime = strtotime(date('Y-m-d',strtotime($weekFirstDay)));
		$data = $this->model
			->where('time','>',$weekFirstDayTime)
			->where('time','<=',time())
			->count();
		return $data;
	}
}