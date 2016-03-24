<?php

namespace App\Http\Controllers;

use App\Models\Dtscore;
use App\Models\Ratio;
use App\Models\Task;
use App\Models\Term;
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

		$title = session('year') . '年度' . Term::find(session('term'))->mc . '学期';

		return view('score.index')
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
			$ratios[$ratio->id] = [
				'id'          => $ratio->id,
				'name'        => $ratio->idm,
				'value'       => $ratio->bl,
				'must_passed' => $ratio->jg,
			];
		}

		$noScoreStudents = Selcourse::whereNd(session('year'))
			->whereXq(session('term'))
			->whereKcxh($kcxh)
			->whereNotExists(function ($query) {
				$query->from('cj_web')
					->where('cj_web.nd', '=', 'xk_xkxx.nd')
					->where('cj_web.xq', '=', 'xk_xkxx.xq')
					->where('cj_web.kcxh', '=', 'xk_xkxx.kcxh')
					->where('cj_web.xh', '=', 'xk_xkxx.xh');
			})
			->select('nd', 'xq', 'kcxh')
			->get();
		if (count($noScoreStudents)) {
			foreach ($noScoreStudents as $student) {
				$score       = new Score;
				$score->xh   = $student->xh;
				$score->xm   = $student->xm;
				$score->kcxh = $student->kcxh;
				$score->kcpt = $student->pt;
				$score->kcxz = $student->xz;
				$score->xl   = $student->xl;
				$score->nd   = $student->nd;
				$score->xq   = $student->xq;
				$score->kh   = $student->plan->kh;
				$score->zpcj = 0;
				$score->kszt = 0; // 考试状态：正常
				$score->zy   = $student->zy;
				$score->tjzt = 0; // 提交状态：未提交
				$score->kkxy = $student->kkxy;

				for ($i = 1; $i <= 6; ++$i) {
					$score->{'cj' . $i} = 0;
				}

				$score->save();
			}
		}

		$title = $task->nd . '年度' . $task->term->mc . '学期' . $task->kcxh . $task->course->kcmc . '课程';

		return view('score.show')
			->withTitle($title . '成绩单')
			->withTask($task)
			->withRatios($ratios)
			->withScores($scores);
	}

	/**
	 * 显示可录入成绩学生名单
	 * @author FuRongxin
	 * @date    2016-03-19
	 * @version 2.0
	 * @param  	string $kcxh 12位课程序号
	 * @return  \Illuminate\Http\Response 学生名单
	 */
	public function edit($kcxh) {
		$course = Mjcourse::whereNd(session('year'))
			->whereXq(session('term'))
			->whereKcxh($kcxh)
			->firstOrFail();

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

		$students = Selcourse::with([
			'score' => function ($query) {
				$query->whereNd(session('year'))
					->whereXq(session('xq'))
					->whereKcxh($course->kcxh);
			},
			'status',
		])
			->whereNd(session('year'))
			->whereXq(session('term'))
			->whereKcxh($course->kcxh)
			->orderBy('xh')
			->get();

		$exists = Score::whereNd(session('year'))
			->whereXq(session('term'))
			->whereKcxh($course->kcxh)
			->whereTjzt(config('constants.score.uncommitted'))
			->exists();

		$statuses = Status::orderBy('dm')->get()->filter(function ($status) {
			return config('constants.score.deferral') != $status->dm;
		});

		$title = $course->college->mc . $course->nd . '年度' . $course->term->mc . '学期' . $course->kcxh . $course->kcmc;

		return view('score.edit')
			->withTitle($title . '成绩录入')
			->withRatios($ratios)
			->withStudents($students)
			->withCourse($course)
			->withExists($exists)
			->withStatuses($statuses);
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
