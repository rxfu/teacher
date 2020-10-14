<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Building;
use App\Models\Calendar;
use App\Models\Campus;
use App\Models\Campuspivot;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Department;
use App\Models\Mjcourse;
use App\Models\Task;
use App\Models\Timetable;
use App\Models\Tksq;
use App\Models\Tksqyy;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TksqController extends Controller {

	public function index() {
		$title = '调停课申请列表';
		$apps = Tksq::whereJsgh(Auth::user()->jsgh)->orderBy('sqsj', 'desc')->get();

		return view('tksq.index', compact('title', 'apps'));
	}

	public function create() {
		$title = '调停课申请';

        $today = Carbon::now();
	    $nextWeek = $today->addWeek();
        $calendar = Calendar::where('rq', '<', $nextWeek)->orderBy('rq', 'desc')->firstOrFail();
        $currentWeek = $today->diffInWeeks($calendar->rq);

		$reasons = Tksqyy::where('dm', '<>', 0)
			->orderBy('dm')
			->get();
        $campuses = Campus::where('dm', '<>', '')->get();
        $buildings = Building::where('dm', '<>', '')->get();
		$tasks = Task::with([
			'course' => function ($query) {
				$query->select('kch', 'kcmc', 'xs');
			}])
			->whereJsgh(Auth::user()->jsgh)
			->whereNd($calendar->nd)
			->whereXq($calendar->xq)
			->orderBy('kcxh')
			->get();
		$teachers = User::orderBy('jsgh')
			->where('jsgh', '<>', '')
			->whereZt(config('constants.status.enable'))
			->get();

		return view('tksq.create', compact('title', 'reasons', 'tasks', 'teachers', 'campuses', 'buildings',  'currentWeek', 'calendar'));
	}

	public function store(Request $request) {
		if ($request->isMethod('post')) {
			$this->validate($request, [
				'sqly' => 'required',
			]);
	        $today = Carbon::now();
	    	$nextWeek = $today->addWeek();
	        $calendar = Calendar::where('rq', '<', $nextWeek)->orderBy('rq', 'desc')->firstOrFail();
	        $kcxhs = explode(',', $request->input('kcxh'));
	        $course = Mjcourse::whereKcxh($kcxhs[0])
		        ->whereNd($calendar->nd)
		        ->whereXq($calendar->xq)
		        ->firstOrFail();
	        $timetable = Timetable::whereNd($calendar->nd)
		        ->whereXq($calendar->xq)
		        ->whereKcxh($kcxhs[0])
		        ->whereZc($request->input('qzc'))
		        ->where('ksz', '<=', $request->input('qxqz'))
		        ->where('jsz', '>=', $request->input('qxqz'))
		        ->whereKsj($request->input('qksj'))
		        ->whereJsj($request->input('qjsj'))
		        ->firstOrFail();

			$app = new Tksq;
			$app->id = date('YmdHis') . random_int(1000, 9999);
			$app->nd = $calendar->nd;
			$app->xq = $calendar->xq;
			$app->jsgh = Auth::user()->jsgh;
			$app->sqsx = $request->input('sqsx');
			$app->sqyy = $request->input('sqyy') ?? 0;
			$app->sqly = $request->input('sqly');
			$app->kcxh = $request->input('kcxh');
			$app->kcmc = Course::find(Helper::getCno($kcxhs[0]))->kcmc;
			$app->qjs = Auth::user()->jsgh;
			$app->qzc = $request->input('qzc');
			$app->qxqz = $request->input('qxqz');
			$app->qksj = $request->input('qksj');
			$app->qjsj = $request->input('qjsj');
			$app->qcdbh = $timetable->cdbh;

			if (($request->input('sqsx') == 0) || ($request->input('sqsx') == 1)) {
				$app->hjs = $request->input('hjs');

				if ($request->input('sqsx') == 0) {
					$app->hzc = $request->input('hzc');
					$app->hxqz = $request->input('hxqz');
					$app->hksj = $request->input('hksj');
					$app->hjsj = $request->input('hjsj');
				}
			}

			$app->kkxy = $course->kkxy;
			$app->sqsj = Carbon::now();
			$app->save();
		}

		return redirect()->route('tksq.index')->withStatus('申请调停课成功');
	}

	public function edit($id) {
		$title = '调停课申请修改';

        $today = Carbon::now();
	    $nextWeek = $today->addWeek();
        $calendar = Calendar::where('rq', '<', $nextWeek)->orderBy('rq', 'desc')->firstOrFail();
        $currentWeek = $today->diffInWeeks($calendar->rq);

		$reasons = Tksqyy::where('dm', '<>', 0)
			->orderBy('dm')
			->get();
        $campuses = Campus::where('dm', '<>', '')->get();
        $buildings = Building::where('dm', '<>', '')->get();
		$tasks = Task::with([
			'course' => function ($query) {
				$query->select('kch', 'kcmc', 'xs');
			}])
			->whereJsgh(Auth::user()->jsgh)
			->whereNd($calendar->nd)
			->whereXq($calendar->xq)
			->orderBy('kcxh')
			->get();
		$teachers = User::orderBy('jsgh')
			->where('jsgh', '<>', '')
			->whereZt(config('constants.status.enable'))
			->get();
		$app = Tksq::findOrFail($id);

		return view('tksq.edit', compact('title', 'reasons', 'tasks', 'teachers', 'campuses', 'buildings', 'currentWeek', 'calendar', 'app'));
	}

	public function update(Request $request, $id) {
		if ($request->isMethod('put')) {
			$this->validate($request, [
				'sqly' => 'required',
			]);
	        $today = Carbon::now();
	    	$nextWeek = $today->addWeek();
	        $calendar = Calendar::where('rq', '<', $nextWeek)->orderBy('rq', 'desc')->firstOrFail();
	        $kcxhs = explode(',', $request->input('kcxh'));
	        $course = Mjcourse::whereKcxh($kcxhs[0])
		        ->whereNd($calendar->nd)
		        ->whereXq($calendar->xq)
		        ->firstOrFail();
	        $timetable = Timetable::whereNd($calendar->nd)
		        ->whereXq($calendar->xq)
		        ->whereKcxh($kcxhs[0])
		        ->whereZc($request->input('qzc'))
		        ->where('ksz', '<=', $request->input('qxqz'))
		        ->where('jsz', '>=', $request->input('qxqz'))
		        ->whereKsj($request->input('qksj'))
		        ->whereJsj($request->input('qjsj'))
		        ->firstOrFail();

			$app = Tksq::findOrFail($id);
			$app->nd = $calendar->nd;
			$app->xq = $calendar->xq;
			$app->id = date('YmdHis') . random_int(1000, 9999);
			$app->jsgh = Auth::user()->jsgh;
			$app->sqsx = $request->input('sqsx');
			$app->sqyy = $request->input('sqyy') ?? 0;
			$app->sqly = $request->input('sqly');
			$app->kcxh = $request->input('kcxh');
			$app->kcmc = Course::find(Helper::getCno($kcxhs[0]))->kcmc;
			$app->qjs = Auth::user()->jsgh;
			$app->qzc = $request->input('qzc');
			$app->qxqz = $request->input('qxqz');
			$app->qksj = $request->input('qksj');
			$app->qjsj = $request->input('qjsj');
			$app->qcdbh = $timetable->cdbh;

			if (($request->input('sqsx') == 0) || ($request->input('sqsx') == 1)) {
				$app->hjs = $request->input('hjs');

				if ($request->input('sqsx') == 0) {
					$app->hzc = $request->input('hzc');
					$app->hxqz = $request->input('hxqz');
					$app->hksj = $request->input('hksj');
					$app->hjsj = $request->input('hjsj');
				}
			}
			
			$app->kkxy = $course->kkxy;
			$app->xgsj = Carbon::now();
			$app->save();
		}

		return redirect()->route('tksq.index')->withStatus('修改申请成功');
	}

	public function destroy(Request $request, $id) {
		if ($request->isMethod('delete')) {
			$app = Tksq::findOrFail($id);
			$app->delete();
		}

		return redirect()->route('tksq.index')->withStatus('删除申请成功');
	}

	public function course(Request $request) {
        $today = Carbon::now();
	    $nextWeek = $today->addWeek();
        $calendar = Calendar::where('rq', '<', $nextWeek)->orderBy('rq', 'desc')->firstOrFail();

        $courses = Timetable::whereNd($calendar->nd)
	        ->whereXq($calendar->xq)
	        ->whereZc($request->input('zc'))
	        ->where('ksz', '<=', $request->input('xqz'))
	        ->where('jsz', '>=', $request->input('xqz'))
	        ->whereKsj($request->input('ksj'))
	        ->whereJsj($request->input('jsj'))
	        ->whereJsgh(Auth::user()->jsgh)
	        ->get();

	    $message = '';
	    $kcxh = [];
	    foreach ($courses as $course) {
	    	$kcxh[] = $course->kcxh;
	    	$message .= '《' . $course->kcxh . '-' . Course::find(Helper::getCno($course->kcxh))->kcmc . '》';
	    }

	    return json_encode([
	    	'kcxh' => implode(',', $kcxh),
	    	'message' => $message,
	    ]);
	}

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
		$title = '调停课查询';
/*
		$apps = Tksq::with('teacher', 'qclassroom', 'hclassroom')
			->whereNd(session('year'))
			->whereXq(session('term'))
			->orderBy('sqsj', 'desc')
			->get();
*/
		$apps = null;
		if ($request->isMethod('post')) {
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

			$apps = Tksq::with('teacher', 'qclassroom', 'hclassroom')
				->whereNd($input['year'])
				->whereXq($input['term'])
				->whereIn('kkxy', $depts)
				->orderBy('sqsj', 'desc')
				->get();

			$campus_name     = 'all' == $input['campus'] ? '所有校区' : Campus::find($input['campus'])->mc . '校区';
			$department_name = 'all' == $input['department'] ? '所有学院' : Department::find($input['department'])->mc;
			$subtitle        = '查询条件：' . $campus_name . $department_name;

			$condition = [
				'year' => $input['year'],
				'term' => $input['term'],
				'campus' => $input['campus'],
				'department' => $input['department'],
			];
		}

		return view('tksq.search', compact('title', 'departments', 'apps', 'subtitle', 'campuses', 'condition'));
	}

	public function teacher(Request $request) {
		if ($request->ajax()) {
			$keyword = $request->input('q');

			$result = User::join('xt_department', 'xy', 'dw')
				->where('jsgh', 'like', $keyword . '%')
				->orWhere('xm', 'like', $keyword . '%')
				->orderBy('jsgh')
				->select('jsgh', 'xm', 'mc')
				->get();

			return json_encode($result);
		}
	}
}
