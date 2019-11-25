<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Campus;
use App\Models\Classroom;
use App\Models\Task;
use App\Models\Tksq;
use App\Models\Tksqyy;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class TksqController extends Controller {

	public function index() {
		$title = '调停课申请列表';
		$apps = Tksq::whereJsgh(Auth::user()->jsgh)->orderBy('sqsj', 'desc')->get();

		return view('tksq.index', compact('title', 'apps'));
	}

	public function create() {
		$title = '调停课申请';
		$reasons = Tksqyy::all();
        $campuses = Campus::where('dm', '<>', '')->get();
        $buildings = Building::where('dm', '<>', '')->get();
        $classrooms = Classroom::all();
		$tasks = Task::with([
			'course' => function ($query) {
				$query->select('kch', 'kcmc', 'xs');
			}])
			->whereJsgh(Auth::user()->jsgh)
			->whereNd(session('year'))
			->whereXq(session('term'))
			->orderBy('kcxh')
			->get();
		$teachers = User::orderBy('jsgh')->whereZt(config('constants.status.enable'))->get();

		return view('tksq.create', compact('title', 'reasons', 'tasks', 'teachers', 'campuses', 'buildings', 'classrooms'));
	}

	public function store(Request $request) {
		if ($request->isMethod('post')) {
			$inputs = $request->all();

			$app = new Tksq;
			$app->save();
		}

		return redirect()->route('tksq.index')->withStatus('修改申请成功');
	}

	public function edit($id) {
		$app = Tksq::findOrFail($id);

		return view('tksq.edit', compact('app'));
	}

	public function update(Request $request, $id) {
		if ($request->isMethod('put')) {
			$inputs = $request->all();

			$app = Tksq::findOrFail($id);
			$app->save();
		}

		return redirect()->route('tksq.index')->withStatus('修改申请成功');
	}

	public function destroy($id) {
		if ($request->isMethod('delete')) {
			$app = Tksq::findOrFail($id);
			$app->delete();
		}

		return redirect()->route('tksq.index')->withStatus('删除申请成功');
	}
}
