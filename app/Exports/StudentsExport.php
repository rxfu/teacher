<?php

namespace App\Exports;

use App\Http\Helper;
use App\Models\Course;
use App\Models\Platform;
use App\Models\Property;
use App\Models\Ratio;
use App\Models\Selcourse;
use App\Models\Task;
use App\Models\Term;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentsExport implements FromCollection, WithColumnFormatting, ShouldAutoSize {

	private $year;
	private $term;
	private $kcxh;

	public function __construct($year, $term, $kcxh) {
		$this->year = $year;
		$this->term = $term;
		$this->kcxh = $kcxh;
	}

	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function collection() {
		$cnos     = Helper::splitCno($this->kcxh);
		$platform = Platform::find($cnos['platform']);
		$property = Property::find($cnos['property']);
		$course   = Course::find($cnos['course']);

		$students = Selcourse::whereKcxh($this->kcxh)
			->whereNd($this->year)
			->whereXq($this->term)
			->orderBy('xh')
			->get();

		$sheetName = $course->kcmc;
		$data[0][] = '广西师范大学' . Helper::getAcademicYear($this->year) . '学年' . Term::find($this->term)->mc . '学期成绩单';
		$data[1][] = '课程名称：' . $course->kcmc;
		$data[2][] = '课程序号：' . $this->kcxh;
		$data[3][] = '课程平台：' . $platform->mc;
		$data[4][] = '课程性质：' . $property->mc;

		$task = Task::whereKcxh($this->kcxh)
			->whereNd($this->year)
			->whereXq($this->term)
			->whereJsgh(Auth::user()->jsgh)
			->firstOrFail();
		$ratios = Ratio::whereFs($task->cjfs)
			->orderBy('id')
			->get()
			->pluck('idm');

		$data[5] = ['学号', '姓名'];
		foreach ($ratios as $ratio) {
			$data[5][] = $ratio;
		}

		foreach ($students as $student) {
			$row   = [];
			$row[] = '\'' . $student->xh;
			$row[] = $student->xm;

			$data[] = $row;
		}

		return collect($data);
	}

	public function columnFormats(): array{
		return [
			'A' => NumberFormat::FORMAT_TEXT,
		];
	}
}
