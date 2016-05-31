<?php

namespace App\Http\Controllers;

use App\Models\Selcourse;
use App\Models\Task;
use Auth;
use Illuminate\Http\Request;

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
		$tasks = Task::with([
			'course' => function ($query) {
				$query->select('kch', 'kcmc', 'xs');
			},
			'term'])
			->whereJsgh(Auth::user()->jsgh)
			->orderBy('nd', 'desc')
			->orderBy('xq', 'desc')
			->orderBy('kcxh')
			->get();

		return view('task.index')
			->withTitle('历年课程列表')
			->withTasks($tasks);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
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

		$students = Selcourse::whereKcxh($kcxh)
			->whereNd($year)
			->whereXq($term)
			->orderBy('xh')
			->get();

		$task = Task::whereKcxh($kcxh)
			->whereNd($year)
			->whereXq($term)
			->whereJsgh(Auth::user()->jsgh)
			->firstOrFail();

		$title = $task->nd . '年度' . $task->term->mc . '学期' . $task->kcxh . $task->course->kcmc . '课程';

		return view('task.show')
			->withTitle($title . '学生名单')
			->withStudents($students);
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

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
}
