<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Building;
use App\Models\Calendar;
use App\Models\Campus;
use App\Models\Classroom;
use App\Models\Course;
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

        $today = Carbon::create(2017,11,27);
        $calendar = Calendar::where('rq', '<', $today)->orderBy('rq', 'desc')->firstOrFail();
        $currentWeek = $today->diffInWeeks($calendar->rq) + 1;

		$reasons = Tksqyy::all();
        $campuses = Campus::where('dm', '<>', '')->get();
        $buildings = Building::where('dm', '<>', '')->get();
        $classrooms = Classroom::all();
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

		return view('tksq.create', compact('title', 'reasons', 'tasks', 'teachers', 'campuses', 'buildings', 'classrooms', 'currentWeek', 'calendar'));
	}

	public function store(Request $request) {
		if ($request->isMethod('post')) {
			$this->validate($request, [
				'sqly' => 'required',
			]);
	        $today = Carbon::create(2017,11,27);
	        $calendar = Calendar::where('rq', '<', $today)->orderBy('rq', 'desc')->firstOrFail();
	        $course = Mjcourse::whereKcxh($request->input('kcxh'))
		        ->whereNd($calendar->nd)
		        ->whereXq($calendar->xq)
		        ->firstOrFail();
	        $classroom = Timetable::whereNd($calendar->nd)
		        ->whereXq($calendar->xq)
		        ->whereKcxh($request->input('kcxh'))
		        ->whereZc($request->input('qzc'))
		        ->where('ksz', '<=', $request->input('qxqz'))
		        ->where('jsz', '>=', $request->input('qxqz'))
		        ->whereKsj($request->input('qksj'))
		        ->whereJsj($request->input('qjsj'))
		        ->firstOrFail();

			$app = new Tksq;
			$app->nd = $calendar->nd;
			$app->xq = $calendar->xq;
			$app->id = date('YmdHis') . random_int(1000, 9999);
			$app->jsgh = Auth::user()->jsgh;
			$app->sqsx = $request->input('sqsx');
			$app->sqyy = $request->input('sqyy');
			$app->sqly = $request->input('sqly');
			$app->kcxh = $request->input('kcxh');
			$app->qjs = Auth::user()->jsgh;
			$app->qzc = $request->input('qzc');
			$app->qxqz = $request->input('qxqz');
			$app->qksj = $request->input('qksj');
			$app->qjsj = $request->input('qjsj');
			$app->qcdbh = $classroom->cdbh;
			$app->hkcxh = $request->input('kcxh');
			$app->hjs = $request->input('hjs');
			$app->hzc = $request->input('hzc');
			$app->hxqz = $request->input('hxqz');
			$app->hksj = $request->input('hksj');
			$app->hjsj = $request->input('hjsj');
			$app->sqsj = Carbon::now();
			$app->save();
		}

		return redirect()->route('tksq.index')->withStatus('申请调停课成功');
	}

	public function edit($id) {
		$title = '调停课申请修改';

        $today = Carbon::create(2017,11,27);
        $calendar = Calendar::where('rq', '<', $today)->orderBy('rq', 'desc')->firstOrFail();
        $currentWeek = $today->diffInWeeks($calendar->rq) + 1;

		$reasons = Tksqyy::all();
        $campuses = Campus::where('dm', '<>', '')->get();
        $buildings = Building::where('dm', '<>', '')->get();
        $classrooms = Classroom::all();
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

		return view('tksq.edit', compact('title', 'reasons', 'tasks', 'teachers', 'campuses', 'buildings', 'classrooms', 'currentWeek', 'calendar', 'app'));
	}

	public function update(Request $request, $id) {
		if ($request->isMethod('put')) {
			$this->validate($request, [
				'sqly' => 'required',
			]);
	        $today = Carbon::create(2017,11,27);
	        $calendar = Calendar::where('rq', '<', $today)->orderBy('rq', 'desc')->firstOrFail();
	        $course = Mjcourse::whereKcxh($request->input('kcxh'))
		        ->whereNd($calendar->nd)
		        ->whereXq($calendar->xq)
		        ->firstOrFail();
	        $classroom = Timetable::whereNd($calendar->nd)
		        ->whereXq($calendar->xq)
		        ->whereKcxh($request->input('kcxh'))
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
			$app->sqyy = $request->input('sqyy');
			$app->sqly = $request->input('sqly');
			$app->kcxh = $request->input('kcxh');
			$app->qjs = Auth::user()->jsgh;
			$app->qzc = $request->input('qzc');
			$app->qxqz = $request->input('qxqz');
			$app->qksj = $request->input('qksj');
			$app->qjsj = $request->input('qjsj');
			$app->qcdbh = $classroom->cdbh;
			$app->hkcxh = $request->input('kcxh');
			$app->hjs = $request->input('hjs');
			$app->hzc = $request->input('hzc');
			$app->hxqz = $request->input('hxqz');
			$app->hksj = $request->input('hksj');
			$app->hjsj = $request->input('hjsj');
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
        $today = Carbon::create(2017,11,27);
        $calendar = Calendar::where('rq', '<', $today)->orderBy('rq', 'desc')->firstOrFail();

        $courses = Timetable::whereNd($calendar->nd)
	        ->whereXq($calendar->xq)
	        ->whereZc($request->input('qzc'))
	        ->where('ksz', '<=', $request->input('qxqz'))
	        ->where('jsz', '>=', $request->input('qxqz'))
	        ->whereKsj($request->input('qksj'))
	        ->whereJsj($request->input('qjsj'))
	        ->whereJsgh(Auth::user()->jsgh)
	        ->get();

	    $result = '';
	    foreach ($courses as $course) {
	    	$result .= '《' . $course->kcxh . '-' . Course::find(Helper::getCno($course->kcxh))->kcmc . '》';
	    }

	    return json_encode([
	    	'message' => $result,
	    ]);
	}
}
