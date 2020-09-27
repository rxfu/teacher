<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Campus;
use App\Models\Campuspivot;
use App\Models\Course;
use App\Models\Department;
use App\Models\Mjcourse;
use App\Models\Selcourse;
use App\Models\Term;
use App\Models\Timetable;
use App\Models\Tksq;
use Auth;
use Illuminate\Http\Request;

class TimetableController extends Controller {

	/**
	 * 显示课程列表
	 * 2016-05-05：应教务处要求增加年级、专业、考核方式、总学时
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
					'llxf' => $mjcourse->plan->llxf,
					'syxf' => $mjcourse->plan->syxf,
					'zxf'  => $mjcourse->plan->zxf,
					'llxs' => $mjcourse->plan->llxs,
					'syxs' => $mjcourse->plan->syxs,
					'zxs'  => $mjcourse->plan->llxs + $mjcourse->plan->syxs,
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

		$title = Helper::getAcademicYear($inputs['year']) . '学年' . Term::find($inputs['term'])->mc . '学期' . '课程列表';
		return view('timetable.index')
			->withTitle($title)
			->withCourses($courses);
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

		$title = Helper::getAcademicYear($nd) . '学年' . Term::find($inputs['term'])->mc . '学期' . '课程表';
		return view('timetable.timetable')
			->withTitle($title)
			->withCourses($courses)
			->withPeriods($periods);
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
		$departments = Department::with('pivot')
			->where('dw', '<>', '')
			->whereLx(config('constants.department.college'))
			->whereZt(config('constants.status.enable'))
			->orderBy('dw')
			->get();
		$campuses = Campus::where('dm', '<>', '')
			->orderBy('dm')
			->get();
		$title = '听课查询';

		$courses = [];
		if ($request->isMethod('post')) {
			$this->validate($request, [
				'year'       => 'required',
				'term'       => 'required',
				'department' => 'required',
				'week'       => 'required',
				'class'      => 'required',
			]);

			$input = $request->all();

			if ('all' == $input['department']) {
				if ('all' == $input['campus']) {
					$depts = Department::where('dw', '<>', '')
						->whereLx(config('constants.department.college'))
						->whereZt(config('constants.status.enable'))
						->pluck('dw');
				} else {
					$depts = Campuspivot::whereXq($input['campus'])
						->pluck('xy');
				}
			} else {
				$depts = explode(',', $input['department']);
			}

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
				->whereNd($input['year'])
				->whereXq($input['term'])
				->whereZc($input['week'])
				->where('ksj', '<=', $input['class'])
				->where('jsj', '>=', $input['class']);

			$kcxhs = Mjcourse::whereNd($input['year'])
				->whereXq($input['term'])
				->whereIn('kkxy', $depts)
				->select('kcxh')
				->distinct()
				->get()
				->pluck('kcxh');
			$query = $query->whereIn('kcxh', $kcxhs);

			$results = $query->get();

			foreach ($results as $result) {
				$course = [
					'kcmc' => Course::find(Helper::getCno($result->kcxh))->kcmc,
					'xqh'  => $result->campus->mc,
					'jsmc' => is_null($result->classroom) ? '' : $result->classroom->mc,
					'kkxy' => is_null($result->mjcourse) ? '' : $result->mjcourse->college->mc,
					'xy'   => is_null($result->mjcourse) ? '' : $result->mjcourse->major->college->mc,
					'zy'   => is_null($result->mjcourse) ? '' : $result->mjcourse->major->mc,
					'nj'   => is_null($result->mjcourse) ? '' : $result->mjcourse->nj,
					'rs'   => Selcourse::whereNd($input['year'])->whereXq($input['term'])->whereKcxh($result->kcxh)->count(),
					'jsxm' => $result->user->xm,
					'jszc' => $result->user->position->mc,
					'ksz'  => $result->ksz,
					'jsz'  => $result->jsz,
				];

				// 2020-6-3：应教务处要求，添加备注内容
				$apps = Tksq::whereNd($input['year'])
					->whereXq($input['term'])
					->whereKcxh($result->kcxh)
					->whereJsgh($result->user->jsgh)
					->get();

				$bzs = [];
				if (!$apps->isEmpty()) {
					foreach ($apps as $app) {
						$bz = '[ ' . config('constants.suspension.' . $app->sqsx) . '：' . config('constants.audit.' . $app->xyspzt) . ' ] ';

						if ($app->sqsx == 0) {
							$bz .= '第 ' . $app->qxqz . ' 周星期' . config('constants.week.' . $app->qzc) . '第 ' . $app->qksj . ' ~ ' . $app->qjsj . ' 节' . optional($app->qclassroom)->mc . '教室';
							$bz .= '变更为第 ' . $app->hxqz . ' 周星期' . config('constants.week.' . $app->hzc) . '第 ' . $app->hksj . ' ~ ' . $app->hjsj . ' 节' . optional($app->hclassroom)->mc . '教室';
							$bz .= '主讲教师为' . $app->hteacher->xm . $app->hteacher->position->mc;
						} elseif ($app->sqsx == 1) {
							$bz .= '主讲教师变更为' . $app->hteacher->xm . $app->hteacher->position->mc;
						} elseif ($app->sqsx == 2) {
							$bz .= '该课程已停课';
						} elseif ($app->sqsx == 3) {
							if (!is_null($app->hclassroom)) {
								$bz .= '授课教室变更为' . $app->hclassroom->mc . '教室';
							}
						}

						$bzs[] = $bz;
					}
				}

				$course['bz'] = $bzs;

				$courses[] = $course;
			}

			$year_name       = Helper::getAcademicYear($input['year']) . '学年';
			$term_name       = Term::find($input['term'])->mc . '学期';
			$campus_name     = 'all' == $input['campus'] ? '所有校区' : Campus::find($input['campus'])->mc . '校区';
			$department_name = 'all' == $input['department'] ? '所有学院' : Department::find($input['department'])->mc;
			$week_name       = 'all' == $input['week'] ? '所有周次' : '星期' . config('constants.week.' . $input['week']);
			$class_name      = '第 ' . $input['class'] . ' 节课';
			$subtitle        = '查询条件：' . $year_name . $term_name . $campus_name . $department_name . $week_name . $class_name;

			$condition = [
				'year' => $input['year'],
				'term' => $input['term'],
				'campus' => $input['campus'],
				'department' => $input['department'],
				'week' => $input['week'],
				'class' => $input['class'],
			];
		}

		return view('timetable.search', compact('title', 'departments', 'courses', 'subtitle', 'campuses', 'condition'));
	}
}
