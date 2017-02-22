<?php

namespace App\Http;

use Illuminate\Support\Str;

/**
 * 辅助函数类
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Helper {

	/**
	 * 去除字符串两端空白
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @param   string $value 字符串
	 * @return  string 去除空白后的字符串
	 */
	public static function trimString($value) {
		return is_string($value) ? trim($value) : $value;
	}

	/**
	 * 12位课程序号转换为8位课程号
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @param   string $kcxh 12位课程序号
	 * @return  string 8位课程号
	 */
	public static function getCno($kcxh) {
		return Str::substr($kcxh, 2, 8);
	}

	/**
	 * 拆分12位课程序号
	 * @author FuRongxin
	 * @date    2016-12-04
	 * @version 2.1.3
	 * @param   string $kcxh 12位课程序号
	 * @return  array 拆分后数组，其中platform为课程平台，property为课程性质，course为课程号，seq为顺序号
	 */
	public static function splitCno($kcxh) {
		$data['platform'] = Str::substr($kcxh, 0, 1);
		$data['property'] = Str::substr($kcxh, 1, 1);
		$data['course']   = Str::substr($kcxh, 2, 8);
		$data['seq']      = Str::substr($kcxh, 10);

		return $data;
	}

	/**
	 * 将系统年度设置转换为学年度设置
	 * @author FuRongxin
	 * @date    2016-12-02
	 * @version 2.1.3
	 * @param   string $year 系统年度
	 * @return  string 学年度
	 */
	public static function getAcademicYear($year) {
		return $year . '~' . ($year + 1);
	}
}