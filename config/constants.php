<?php

/**
 * 常用系统参数
 *
 * @author FuRongxin
 * @date 2016-03-11
 * @version 2.0
 */
return [

	/**
	 * 数据状态代码
	 */
	'status' => [
		'enable'  => true,
		'disable' => false,
	],

	/**
	 * 成绩状态代码
	 */
	'score'  => [
		'uncommitted' => '0', // 未提交
		'committed'   => '1', // 教师已提交
		'confirmed'   => '2', // 学院已提交
		'dconfirmed'  => '3', // 教务处已确认

		'passline'    => 60, // 及格线
	],
];