<?php

namespace App\Http\Controllers;

use App\Models\Score;
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
	 * 显示学生成绩列表
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  \Illuminate\Http\Response 学生成绩列表
	 */
	public function index() {
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

		$scores = Score::wih('status')
			->whereKcxh($kcxh)
			->whereNd($inputs['year'])
			->whereXq($inputs['term'])
			->orderBy('xh')
			->get();

		$task = Task::whereKcxh($kcxh)
			->whereNd($input['year'])
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

		return view('score.show')
			->withTitle('成绩单')
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
