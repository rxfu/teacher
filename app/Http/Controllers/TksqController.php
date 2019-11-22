<?php

namespace App\Http\Controllers;

use App\Models\Tksq;
use App\Models\Tksqyy;
use Illuminate\Http\Request;

class TksqController extends Controller {

	public function index() {
		$apps = Tksq::whereJsgh(Auth::user()->jsgh)->orderBy('sqsj', 'desc')->get();

		return view('tksq.index', compact('apps'));
	}

	public function create() {
		$reasons = Tksqyy::all();

		return view('tksq.create', compact('reasons'));
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
