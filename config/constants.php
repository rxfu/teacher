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
	'status'    => [
		'enable'  => true,
		'disable' => false,
	],

	/**
	 * 成绩状态代码
	 */
	'score'     => [
		'uncommitted' => '0', // 未提交
		'committed'   => '1', // 教师已提交
		'confirmed'   => '2', // 学院已提交
		'dconfirmed'  => '3', // 教务处已确认

		'passline'    => 60, // 及格线

		'deferral'    => '1', // 缓考
	],

	/**
	 * 课程表时间段参数
	 */
	'timetable' => [

		// 上午
		'morning'   => [
			'id'    => 'morning',
			'begin' => 1,
			'end'   => 5,
			'name'  => '上午',
			'rest'  => '午休',
		],

		// 下午
		'afternoon' => [
			'id'    => 'afternoon',
			'begin' => 6,
			'end'   => 9,
			'name'  => '下午',
			'rest'  => '晚饭',
		],

		// 晚上
		'evening'   => [
			'id'    => 'evening',
			'begin' => 10,
			'end'   => 12,
			'name'  => '晚上',
			'rest'  => '熄灯',
		],
	],

	/**
	 * 星期名称对应
	 */
	'week'      => [
		'1' => '一',
		'2' => '二',
		'3' => '三',
		'4' => '四',
		'5' => '五',
		'6' => '六',
		'7' => '日',
	],
];