<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Course;
use App\Models\Department;
use App\Models\Mjcourse;
use App\Models\Term;
use App\Models\Timetable;
use Auth;
use Illuminate\Http\Request;

class TimetableController extends Controller {

	/**
	 * 显示课程列表
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @param   \Illuminate\Http\Request $request 课程列表请求
	 * @return  \Illuminate\Http\Response 课程列表
	 */
	/**
	 * 应教务处要求增加年级、专业、考核方式、总学时
	 * @author FuRongxin
	 * @date    2016-05-05
	 * @version 2.1
	 * @param   \Illuminate\Http\Request $request 课程列表请求
	 * @return  \Illuminate\Http\Response 课程列表
	 */
	public function index(Request $request) {
		$inputs = $request->all();

		$timetables = Timetable::with(['campus', 'classroom'])
			->whereNd($inputs['year'])
			->whereXq($inputs['term'])
			->whereJsgh(Auth::user()->jsgh)
			->get();

		$courses = [];
		foreach ($timetables as $timetable) {

			// 生成课程序号为索引的课程信息数组
			if (!isset($courses[$timetable->kcxh])) {
				$mjcourse = Mjcourse::whereKcxh($timetable->kcxh)
					->whereNd($inputs['year'])
					->whereXq($inputs['term'])
					->firstOrFail();

				$courses[$timetable->kcxh] = [
					'kcxh' => $timetable->kcxh,
					'kcmc' => Course::find(Helper::getCno($timetable->kcxh))->kcmc,
					'xqh'  => $timetable->campus->mc,
					'nj'   => $mjcourse->nj,
					'zy'   => $mjcourse->major->mc,
					'kh'   => $mjcourse->plan->mode->mc,
					'zxs'  => $mjcourse->plan->zxs,
				];
			}

			// 在课程信息数组下生成周次为索引的课程时间数组
			$courses[$timetable->kcxh][$timetable->zc][] = [
				'ksz' => $timetable->ksz,
				'jsz' => $timetable->jsz,
				'ksj' => $timetable->ksj,
				'jsj' => $timetable->jsj,
				'js'  => $timetable->classroom->mc,
			];
		}

		$title = $inputs['year'] . '年度' . Term::find($inputs['term'])->mc . '学期';
		return view('timetable.index')
			->withTitle($title . '课程列表')
			->withCourses($courses);
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
	 * 显示课程表
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @param   \Illuminate\Http\Request  $request 课程表请求
	 * @param   string $nd 年度
	 * @return \Illuminate\Http\Response 课程表
	 */
	public function show(Request $request, $nd) {
		$inputs  = $request->all();
		$periods = config('constants.timetable');

		$timetables = Timetable::with(['campus', 'classroom'])
			->whereNd($nd)
			->whereXq($inputs['term'])
			->whereJsgh(Auth::user()->jsgh)
			->get();

		// 初始化课程数组
		$courses = [];
		for ($i = $periods['morning']['begin']; $i <= $periods['evening']['end']; ++$i) {
			for ($j = 1; $j <= 7; ++$j) {
				$courses[$i][$j]['rbeg'] = $courses[$i][$j]['rend'] = $i;
			}
		}

		// 遍历课程时间
		foreach ($timetables as $timetable) {

			// 课程时间没有冲突
			$courses[$timetable->ksj][$timetable->zc]['conflict'] = false;
			$courses[$timetable->ksj][$timetable->zc]['rbeg']     = $timetable->ksj;
			$courses[$timetable->ksj][$timetable->zc]['rend']     = $timetable->jsj;

			for ($i = $timetable->ksj + 1; $i <= $timetable->jsj; ++$i) {
				$courses[$i][$timetable->zc]['rend'] = $courses[$i][$timetable->zc]['rbeg'] - 1;
			}

			// 生成开始节、周次为索引的课程时间数组
			$courses[$timetable->ksj][$timetable->zc][] = [
				'kcxh' => $timetable->kcxh,
				'kcmc' => Course::find(Helper::getCno($timetable->kcxh))->kcmc,
				'xqh'  => $timetable->campus->mc,
				'ksz'  => $timetable->ksz,
				'jsz'  => $timetable->jsz,
				'ksj'  => $timetable->ksj,
				'jsj'  => $timetable->jsj,
				'js'   => $timetable->classroom->mc,
			];
		}

		$title = $nd . '年度' . Term::find($inputs['term'])->mc . '学期';
		return view('timetable.timetable')
			->withTitle('课程表')
			->withCourses($courses)
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

	/**
	 * 显示查询听课表单
	 * @author FuRongxin
	 * @date    2016-05-17
	 * @version 2.1
	 * @return  \Illuminate\Http\Response 听课查询表单
	 */
	public function showSearchForm() {
		$departments = Department::where('dw', '<>', '')
			->whereLx('1')
			->whereZt(config('constants.status.enable'))
			->orderBy('dw')
			->get();
		$search = false;
		$title  = '听课查询';

		return view('timetable.search', compact('title', 'departments', 'search'));
	}

	/**
	 * 查询听课列表
	 * @author FuRongxin
	 * @date    2016-05-17
	 * @version 2.1
	 * @param   \Illuminate\Http\Request $request 听课查询请求
	 * @return  \Illuminate\Http\Response 听课列表
	 */
	public function search(Request $request) {
		if ($request->isMethod('post')) {
			$this->validate($request, [
				'department' => 'required',
				'week'       => 'required',
				'begclass'   => 'required',
				'endclass'   => 'required',
			]);

			$input = $request->all();

			$query = Timetable::with([
				'classroom' => function ($query) {
					$query->select('jsh', 'mc');
				},
				'user'      => function ($query) {
					$query->select('jsgh', 'xm', 'zc');
				},
				'user.position',
				'campus',
			])
				->whereNd(session('year'))
				->whereXq(session('term'));

			if ('all' != $input['department']) {
				$kcxhs = Mjcourse::whereNd(session('year'))
					->whereXq(session('term'))
					->whereKkxy($input['department'])
					->select('kcxh')
					->distinct()
					->get()
					->pluck('kcxh');

				$query = $query->whereIn('kcxh', $kcxhs);
			}
			if ('all' !== $input['week']) {
				$query = $query->whereZc($input['week']);
			}
			if ('all' != $input['begclass']) {
				$query = $query->where('ksj', '<=', $input['begclass']);
			}
			if ('all' != $input['endclass']) {
				$query = $query->where('jsj', '>=', $input['endclass']);
			}

			$results = $query->get();
		}

		$departments = Department::where('dw', '<>', '')
			->whereLx('1')
			->whereZt(config('constants.status.enable'))
			->orderBy('dw')
			->get();
		$title = '听课查询';

		return view('timetable.search', compact('title', 'departments', 'results'));
	}
}
