<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Course;
use App\Models\Mjcourse;
use App\Models\Task;
use App\Models\Term;
use App\Models\Tesgrade;
use App\Models\Tesitem;
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
	 * 显示录入评学界面
	 * @author FuRongxin
	 * @date    2016-03-30
	 * @version 2.0
	 * @param   string $kcxh 12位课程序号
	 * @return  \Illuminate\Http\Response 评学录入界面
	 */
	public function edit($kcxh) {
		$items = Tesitem::with([
			'results' => function ($query) use ($kcxh) {
				$query->whereNd(session('year'))
					->whereXq(session('term'))
					->whereKcxh($kcxh)
					->whereJsgh(Auth::user()->jsgh);
			},
			'category',
		])
			->whereZt(config('constants.status.enable'))
			->orderBy('px')
			->get();

		if (count($items->first()->results)) {
			return redirect()->route('tes.show', ['kcxh' => $kcxh, 'year' => session('year'), 'term' => session('term')])->withStatus($kcxh . '课程评学数据已录入');
		} else {
			$title = session('year') . '年度' . Term::find(session('term'))->mc . '学期' . $kcxh . Course::find(Helper::getCno($kcxh))->mc . '课程';

			return view('tes.edit')
				->withTitle($title . '评学录入')
				->withItems($items)
				->withKcxh($kcxh);
		}
	}

	/**
	 * 录入评学结果
	 * @author FuRongxin
	 * @date    2016-03-30
	 * @version 2.0
	 * @param   \Illuminate\Http\Request  $request 评学结果请求
	 * @param   string $kcxh 12位课程序号
	 * @return  \Illuminate\Http\Response 评学结果
	 */
	public function update(Request $request, $kcxh) {
		$inputs = $request->all();

		$this->validate($request, [
			'score.*.fz' => 'required|numeric|min:0|max:10',
		]);

		$course = Mjcourse::whereNd(session('year'))
			->whereXq(session('term'))
			->whereKcxh($kcxh)
			->firstOrFail();

		foreach ($inputs['score'] as $id => $score) {
			$result          = new Tesresult;
			$result->jsgh    = Auth::user()->jsgh;
			$result->kcxh    = $kcxh;
			$result->kch     = Helper::getCno($kcxh);
			$result->pjbz_id = $id;
			$result->kkxy    = $course->kkxy;
			$result->zy      = $course->zy;
			$result->nj      = $course->nj;
			$result->nd      = $course->nd;
			$result->xq      = $course->xq;
			$result->fz      = $score['fz'];
			$result->save();
		}

		return redirect()->route('tes.show', ['kcxh' => $kcxh, 'year' => $course->nd, 'term' => $course->xq])->withStatus('评学成功');
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
