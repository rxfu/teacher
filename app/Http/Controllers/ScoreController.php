<?php

namespace App\Http\Controllers;

use App\Models\Dtscore;
use App\Models\Ratio;
use App\Models\Task;
use Auth;
use Illuminate\Http\Request;

/**
 * 显示并处理学生成绩
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class ScoreController extends Controller {

	/**
	 * 显示当前课程列表
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  \Illuminate\Http\Response 学生成绩列表
	 */
	public function index() {
		$tasks = Task::with([
			'course' => function ($query) {
				$query->select('kch', 'kcmc', 'xs');
			}])
			->whereJsgh(Auth::user()->jsgh)
			->whereNd(session('year'))
			->whereXq(session('term'))
			->orderBy('kcxh')
			->get();

		return view('score.index')
			->withTitle('当前课程列表')
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
	 * 显示课程成绩单
	 * @author FuRongxin
	 * @date    2016-03-13
	 * @version 2.0
	 * @param  	\Illuminate\Http\Request  $request 成绩请求
	 * @param   string $kcxh 12位课程序号
	 * @return  \Illuminate\Http\Response 课程成绩列表
	 */
	public function show(Request $request, $kcxh) {
		$inputs = $request->all();

		$scores = Dtscore::with('status')
			->whereKcxh($kcxh)
			->whereNd($inputs['year'])
			->whereXq($inputs['term'])
			->orderBy('xh')
			->get();

		$task = Task::whereKcxh($kcxh)
			->whereNd($inputs['year'])
			->whereXq($inputs['term'])
			->whereJsgh(Auth::user()->jsgh)
			->firstOrFail();

		$ratios = [];
		$items  = Ratio::whereFs($task->cjfs)
			->orderBy('id')
			->get();
		foreach ($items as $ratio) {
			$ratios[] = [
				'id'    => $ratio->id,
				'name'  => $ratio->idm,
				'value' => $ratio->bl,
			];
		}

		$title = $task->nd . '年度' . $task->term->mc . '学期' . $task->kcxh . $task->course->kcmc . '课程';

		return view('score.show')
			->withTitle($title . '成绩单')
			->withTask($task)
			->withRatios($ratios)
			->withScores($scores);
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
