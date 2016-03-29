<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Course;
use App\Models\Task;
use App\Models\Term;
use App\Models\Tesgrade;
use App\Models\Tesresult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 显示并处理教师评学数据
 *
 * @author FuRongxin
 * @date 2016-03-29
 * @version 2.0
 */
class TesController extends Controller {

	/**
	 * 显示当前课程列表
	 * @author FuRongxin
	 * @date    2016-03-29
	 * @version 2.0
	 * @return  \Illuminate\Http\Response 教师课程列表
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

		$title = session('year') . '年度' . Term::find(session('term'))->mc . '学期';

		return view('tes.index')
			->withTitle($title . '课程列表')
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
	 * 显示评学结果
	 * @author FuRongxin
	 * @date    2016-03-29
	 * @version 2.0
	 * @param   \Illuminate\Http\Request  $request 评学结果请求
	 * @param   string $kcxh 12位课程序号
	 * @return  \Illuminate\Http\Response 评学结果
	 */
	public function show(Request $request, $kcxh) {
		$inputs = $request->all();

		$results = Tesresult::with('item', 'item.category')
			->whereNd($inputs['year'])
			->whereXq($inputs['term'])
			->whereJsgh(Auth::user()->jsgh)
			->orderBy('pjbz_id')
			->get();

		$total = 0;
		foreach ($results as $result) {
			$total += $result->fz;
		}

		$grade = Tesgrade::where('zdfz', '<=', $total)
			->where('zgfz', '>=', $total)
			->firstOrFail();

		$title = $inputs['year'] . '年度' . Term::find($inputs['term'])->mc . '学期' . $kcxh . Course::find(Helper::getCno($kcxh))->mc . '课程';

		return view('tes.show')
			->withTitle($title . '评学结果')
			->withResults($results)
			->withTotal($total)
			->withGrade($grade->mc);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($kcxh) {
		$title = session('year') . '年度' . Term::find(session('term'))->mc . '学期' . $kcxh . Course::find(Helper::getCno($kcxh))->mc . '课程';

		$items = Tesitem::with('category')
			->whereZt(config('constants.status.enable'))
			->orderBy('px')
			->get();

		return view('tes.edit')
			->withTitle($title . '评学录入')
			->withItems($items)
			->withKcxh($kcxh);
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
