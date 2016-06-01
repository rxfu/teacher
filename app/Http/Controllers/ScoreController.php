<?php

namespace App\Http\Controllers;

use App\Models\Dtscore;
use App\Models\Mjcourse;
use App\Models\Ratio;
use App\Models\Score;
use App\Models\Selcourse;
use App\Models\Status;
use App\Models\Task;
use App\Models\Term;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
			->whereNd(session('year'))
			->whereXq(session('term'))
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

		$exists = Score::whereNd(session('year'))
			->whereXq(session('term'))
			->whereKcxh($course->kcxh)
			->whereTjzt(config('constants.score.uncommitted'))
			->exists();

		$statuses = Status::orderBy('dm')->get()->filter(function ($status) {
			return config('constants.score.deferral') != $status->dm;
		});

		$noScoreStudents = Selcourse::whereNd(session('year'))
			->whereXq(session('term'))
			->whereKcxh($kcxh)
			->whereNotExists(function ($query) {
				$query->from('cj_web')
					->whereRaw('t_cj_web.nd = t_xk_xkxx.nd AND t_cj_web.xq = t_xk_xkxx.xq AND t_cj_web.kcxh = t_xk_xkxx.kcxh AND t_cj_web.xh = t_xk_xkxx.xh');
			})
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
				$score->kh   = $course->plan->kh;
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

		$scoreNoStudents = Score::whereNd(session('year'))
			->whereXq(session('term'))
			->whereKcxh($kcxh)
			->whereNotExists(function ($query) {
				$query->from('xk_xkxx')
					->whereRaw('t_xk_xkxx.nd = t_cj_web.nd AND t_xk_xkxx.xq = t_cj_web.xq AND t_xk_xkxx.kcxh = t_cj_web.kcxh AND t_xk_xkxx.xh = t_cj_web.xh');
			})
			->select('nd', 'xq', 'xh', 'kcxh')
			->get();

		if (count($scoreNoStudents)) {
			foreach ($scoreNoStudents as $student) {
				$student->delete();
			}
		}

		$students = Score::with('status')
			->wherend(session('year'))
			->whereXq(session('term'))
			->whereKcxh($kcxh)
			->orderBy('xh')
			->get();

		$title = $course->college->mc . $course->nd . '年度' . $course->term->mc . '学期' . $course->kcxh . $course->plan->course->kcmc . '课程';

		return view('score.edit')
			->withTitle($title . '成绩录入')
			->withRatios($ratios)
			->withStudents($students)
			->withCourse($course)
			->withExists($exists)
			->withStatuses($statuses);
	}

	/**
	 * 录入学生成绩
	 * @author FuRongxin
	 * @date    2016-03-24
	 * @version 2.0
	 * @param  	\Illuminate\Http\Request  $request 成绩请求
	 * @param   string $kcxh 12位课程序号
	 * @return 	\Illuminate\Http\Response 学生成绩
	 */
	public function update(Request $request, $kcxh) {
		if ($request->isMethod('put')) {
			$inputs = $request->all();

			$this->validate($request, [
				'score' => 'required|numeric|min:0|max:100',
				'id'    => 'required',
				'sno'   => 'required',
			]);

			$student = Score::whereNd(session('year'))
				->whereXq(session('term'))
				->whereKcxh($kcxh)
				->whereXh($inputs['sno'])
				->firstOrFail();

			$task = Task::whereKcxh($kcxh)
				->whereNd(session('year'))
				->whereXq(session('term'))
				->whereJsgh(Auth::user()->jsgh)
				->firstOrFail();

			$ratios = [];
			$items  = Ratio::whereFs($task->cjfs)
				->orderBy('id')
				->get();
			foreach ($items as $ratio) {
				$ratios[] = [
					'id'           => $ratio->id,
					'name'         => $ratio->idm,
					'value'        => $ratio->bl / $ratio->mf,
					'allow_failed' => $ratio->jg,
				];
			}

			$student->{'cj' . $inputs['id']} = $inputs['score'];

			$total = 0;
			$fails = [];
			foreach ($ratios as $ratio) {
				if (config('constants.score.passline') > $student->{'cj' . $ratio['id']} && config('constants.status.enable') == $ratio['allow_failed']) {
					$fails[] = $student->{'cj' . $ratio['id']};
				} else {
					$total += $student->{'cj' . $ratio['id']} * $ratio['value'];
				}
			}
			$student->zpcj = round(empty($fails) ? $total : min($fails));

			$student->save();

			return $student->zpcj;
		}

		return 'failed';
	}

	/**
	 * 更新学生考试状态
	 * @author FuRongxin
	 * @date    2016-03-29
	 * @version 2.0
	 * @param  	\Illuminate\Http\Request  $request 考试状态请求
	 * @param   string $kcxh 12位课程序号
	 * @return 	\Illuminate\Http\Response 考试状态
	 */
	public function updateStatus(Request $request, $kcxh) {
		if ($request->isMethod('put')) {
			$inputs = $request->all();

			$this->validate($request, [
				'status' => 'required|numeric',
				'sno'    => 'required',
			]);

			$student = Score::whereNd(session('year'))
				->whereXq(session('term'))
				->whereKcxh($kcxh)
				->whereXh($inputs['sno'])
				->firstOrFail();

			$student->kszt = $inputs['status'];

			$student->save();

			return $student->kszt;
		}

		return 'failed';
	}

	/**
	 * 上报学生成绩
	 * @author FuRongxin
	 * @date    2016-03-24
	 * @version 2.0
	 * @param   string $kcxh 12位课程序号
	 * @return \Illuminate\Http\Response 上报成绩
	 */
	public function confirm($kcxh) {
		$noScoreStudents = DB::select('SELECT xh, xm, nd, xq FROM t_xk_xkxx WHERE nd = :year AND xq = :term AND kcxh = :kcxh EXCEPT SELECT xh, xm, nd, xq FROM t_cj_web WHERE nd = :year AND xq = :term AND kcxh = :kcxh',
			['year' => session('year'), 'term' => session('term'), 'kcxh' => $kcxh]);
		$scoreNoStudents = DB::select('SELECT xh, xm, nd, xq FROM t_cj_web WHERE nd = :year AND xq = :term AND kcxh = :kcxh EXCEPT SELECT xh, xm, nd, xq FROM t_xk_xkxx WHERE nd = :year AND xq = :term AND kcxh = :kcxh',
			['year' => session('year'), 'term' => session('term'), 'kcxh' => $kcxh]);

		if (count($noScoreStudents) || count($scoreNoStudents)) {
			return redirect()->route('score.index')->withNoScoreStudents($noScoreStudents)->withScoreNoStudents($scoreNoStudents);
		}

		$affected = DB::update('UPDATE t_cj_web SET tjzt = :committed WHERE nd = :year AND xq = :term AND kcxh = :kcxh',
			['year' => session('year'), 'term' => session('term'), 'kcxh' => $kcxh, 'committed' => config('constants.score.committed')]);

		return redirect()->route('score.index')->withStatus('成绩上报成功');
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

	/**
	 * 批量更新成绩
	 * @author FuRongxin
	 * @date    2016-05-06
	 * @version 2.1
	 * @param   \Illuminate\Http\Request $request 更新成绩请求
	 * @param   string $kcxh 12位课程序号
	 * @return  \Illuminate\Http\Response 学生成绩
	 */
	public function batchUpdate(Request $request, $kcxh) {
		if ($request->isMethod('put')) {
			$inputs = $request->all();

			$snos = array_unique(array_map(function ($val) {
				return Str::substr($val, 0, 12);
			}, array_filter(array_keys($inputs), function ($val) {
				return is_numeric($val);
			})));

			$task = Task::whereKcxh($kcxh)
				->whereNd(session('year'))
				->whereXq(session('term'))
				->whereJsgh(Auth::user()->jsgh)
				->firstOrFail();

			$ratios = [];
			$items  = Ratio::whereFs($task->cjfs)
				->orderBy('id')
				->get();
			foreach ($items as $ratio) {
				$ratios[] = [
					'id'           => $ratio->id,
					'name'         => $ratio->idm,
					'value'        => $ratio->bl / $ratio->mf,
					'allow_failed' => $ratio->jg,
				];
			}

			foreach ($snos as $sno) {
				$student = Score::whereNd(session('year'))
					->whereXq(session('term'))
					->whereKcxh($kcxh)
					->whereXh($sno)
					->firstOrFail();

				$rules = [];
				foreach ($items as $item) {
					$rules[$student->xh . $item->id] = 'numeric|min:0|max:100';

				}
				$rules[$student->xh . 'kszt'] = 'numeric';
				$this->validate($request, $rules);

				foreach ($items as $item) {
					$student->{'cj' . $item->id} = isset($inputs[$student->xh . $item->id]) ? $inputs[$student->xh . $item->id] : 0;
				}

				if (isset($inputs[$student->xh . 'kszt'])) {
					$student->kszt = $inputs[$student->xh . 'kszt'];
				}

				$total = 0;
				$fails = [];
				foreach ($ratios as $ratio) {
					if (config('constants.score.passline') > $student->{'cj' . $ratio['id']} && config('constants.status.enable') == $ratio['allow_failed']) {
						$fails[] = $student->{'cj' . $ratio['id']};
					} else {
						$total += $student->{'cj' . $ratio['id']} * $ratio['value'];
					}
				}
				$student->zpcj = round(empty($fails) ? $total : min($fails));

				$student->save();
			}
		}

		return redirect()->route('score.edit', $kcxh)->withStatus('保存成绩成功');
	}
}
