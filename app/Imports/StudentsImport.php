<?php

namespace App\Imports;

use App\Models\Ratio;
use App\Models\Task;
use Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentsImport implements ToModel
{
	private $kcxh;

	public function __construct($kcxh) {
		$this->kcxh=$kcxh
	}

    /**
    * @param array $row
    */
    public function model(array $row)
    {
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

					// 获取所有成绩
					$results = $excel->skip(6)->get();
					foreach ($results as $result) {
						$student = Score::whereNd(session('year'))
							->whereXq(session('term'))
							->whereKcxh($kcxh)
							->whereXh($result[0])
							->firstOrFail();

						foreach ($items as $item) {
							$score = $result[$item->id + 1];

							if (100 < $score || 0 > $score) {
								return $status = '学生' . $student->xh . '成绩有误，请检查后再重新导入';
							} else {
								$student->{'cj' . $item->id} = empty($score) ? 0 : $score;
							}
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

return $student;
}
    }
}
