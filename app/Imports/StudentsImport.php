<?php

namespace App\Imports;

use App\Models\Score;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentsImport implements ToCollection, WithStartRow {

	private $kcxh;

	private $ratios;

	public function __construct($kcxh, $ratios) {
		$this->kcxh   = $kcxh;
		$this->ratios = $ratios;
	}

	public function startRow(): int {
		return 7;
	}

	/**
	 * @param collection $row
	 */
	public function collection(Collection $rows) {

		// 获取所有成绩
		foreach ($rows as $row) {
			$row[0] = trim($row[0], "'");

			$student = Score::whereNd(session('year'))
				->whereXq(session('term'))
				->whereKcxh($this->kcxh)
				->whereXh($row[0])
				->firstOrFail();

			foreach ($this->ratios as $item) {
				$score = $row[$item['id'] + 1];

				if (100 < $score || 0 > $score) {
					return $status = '学生' . $student->xh . '成绩有误，请检查后再重新导入';
				} else {
					$student->{'cj' . $item['id']} = empty($score) ? 0 : $score;
				}
			}

			$total = 0;
			$fails = [];
			foreach ($this->ratios as $ratio) {
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
}
