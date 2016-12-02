<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Selcourse;
use App\Models\Task;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 显示并处理教师任务书
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class TaskController extends Controller {

	/**
	 * 显示教师课程列表
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  \Illuminate\Http\Response 教师课程列表
	 */
	public function index() {
		$tasks = DB::table('pk_jxrw')
			->join('pk_kczy', function ($join) {
				$join->on('pk_kczy.nd', '=', 'pk_jxrw.nd')
					->on('pk_kczy.xq', '=', 'pk_jxrw.xq')
					->on('pk_kczy.kcxh', '=', 'pk_jxrw.kcxh')
					->where('pk_jxrw.jsgh', '=', Auth::user()->jsgh);
			})
			->join('jx_kc', 'jx_kc.kch', '=', 'pk_jxrw.kch')
			->join('jx_jxjh', function ($join) {
				$join->on('jx_jxjh.zy', '=', 'pk_kczy.zy')
					->on('jx_jxjh.nj', '=', 'pk_kczy.nj')
					->on('jx_jxjh.zsjj', '=', 'pk_kczy.zsjj')
					->on('jx_jxjh.kch', '=', 'pk_jxrw.kch');
			})
			->join('zd_xq', 'pk_jxrw.xq', '=', 'zd_xq.dm')
			->leftJoin('cj_lscj', function ($join) {
				$join->on('cj_lscj.nd', '=', 'pk_jxrw.nd')
					->on('cj_lscj.xq', '=', 'pk_jxrw.xq')
					->on('cj_lscj.kcxh', '=', 'pk_jxrw.kcxh');
			})
			->select(DB::raw('t_pk_jxrw.nd, t_pk_jxrw.xq, t_pk_jxrw.kcxh, t_pk_jxrw.kch, t_pk_jxrw.jsgh, t_pk_kczy.zy, t_pk_kczy.nj, t_pk_kczy.zsjj, t_jx_kc.kcmc, t_jx_jxjh.llxf, t_jx_jxjh.syxf, t_jx_jxjh.llxf + t_jx_jxjh.syxf AS zxf, t_jx_jxjh.llxs, t_jx_jxjh.syxs, t_jx_jxjh.llxs + t_jx_jxjh.syxs AS zxs, t_zd_xq.mc as xqmc, COUNT(t_cj_lscj.xh) AS total'))
			->groupBy('pk_jxrw.nd', 'pk_jxrw.xq', 'pk_jxrw.kcxh', 'pk_jxrw.kch', 'pk_jxrw.jsgh', 'pk_kczy.zy', 'pk_kczy.nj', 'pk_kczy.zsjj', 'jx_kc.kcmc', 'jx_jxjh.llxf', 'jx_jxjh.syxf', 'jx_jxjh.llxs', 'jx_jxjh.syxs', 'zd_xq.mc')
			->orderBy('nd', 'desc')
			->orderBy('xq', 'desc')
			->orderBy('kcxh')
			->get();

		return view('task.index')
			->withTitle('历年课程列表')
			->withTasks($tasks);
	}

	/**
	 * 显示课程学生名单
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @param  \Illuminate\Http\Request  $request 学生名单请求
	 * @param   string $kcxh 12位课程序号
	 * @return  \Illuminate\Http\Response 学生名录列表
	 */
	public function show(Request $request, $kcxh) {
		$inputs = $request->all();
		$year   = isset($inputs['year']) ? $inputs['year'] : session('year');
		$term   = isset($inputs['term']) ? $inputs['term'] : session('term');

		$students = Selcourse::with('student.major')
			->whereKcxh($kcxh)
			->whereNd($year)
			->whereXq($term)
			->orderBy('xh')
			->get();

		$task = Task::whereKcxh($kcxh)
			->whereNd($year)
			->whereXq($term)
			->whereJsgh(Auth::user()->jsgh)
			->firstOrFail();

		$title = Helper::getAcademicYear($task->nd) . '学年' . $task->term->mc . '学期' . $task->kcxh . $task->course->kcmc . '课程' . '学生名单';

		return view('task.show', compact('ttile', 'students', 'year', 'term', 'kcxh'));
	}

	/**
	 * 显示课程学期列表
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @return  \Illuminate\Http\Response 课程学期列表
	 */
	public function timetable() {
		$periods = Task::with('term')
			->whereJsgh(Auth::user()->jsgh)
			->select('nd', 'xq')
			->groupBy('nd', 'xq')
			->orderBy('nd', 'desc')
			->orderBy('xq', 'desc')
			->get();

		return view('task.timetable')
			->withTitle('学期列表')
			->withPeriods($periods);
	}

	public function exportStudents($year, $term, $kcxh) {

	}

}
