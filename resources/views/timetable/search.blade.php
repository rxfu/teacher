@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <form id="searchForm" name="searchForm" method="post" action="{{ url('timetable/search') }}" role="form" class="form-inline">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="year" class="sr-only">年度</label>
                <select name="year" class="form-control" data-width="150px">
                    <option value="{{ Carbon\Carbon::now()->format('Y') }}"{{ isset($condition) && Carbon\Carbon::now()->format('Y') == $condition['year'] ? ' selected' : '' }}>{{ App\Http\Helper::getAcademicYear(Carbon\Carbon::now()->format('Y')) }}学年</option>
                    <option value="{{ Carbon\Carbon::now()->subYear()->format('Y') }}"{{ isset($condition) && Carbon\Carbon::now()->subYear()->format('Y') == $condition['year'] ? ' selected' : '' }}>{{ App\Http\Helper::getAcademicYear(Carbon\Carbon::now()->subYear()->format('Y')) }}学年</option>
                </select>
            </div>
            <div class="form-group">
                <label for="term" class="sr-only">学期</label>
                <select name="term" class="form-control" data-width="100px">
                    @for ($i = 1; $i < 3; $i++)
                        <option value="{{ $i }}"{{ isset($condition) && $i == $condition['term'] ? ' selected' : '' }}>{{ App\Models\Term::find($i)->mc }}学期</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="campus" class="sr-only">校区</label>
                <select id="campus" name="campus" class="form-control" data-width="100px">
                    <option value="all">全部校区</option>
                    @foreach ($campuses as $campus)
                        <option value="{{ $campus->dm }}"{{ isset($condition) && $campus->dm == $condition['campus'] ? ' selected' : '' }}>{{ $campus->mc }}校区</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="department" class="sr-only">学院</label>
                <select id="department" name="department" class="form-control">
                    <option value="all" class="all {{ $campuses->implode('dm', ' ') }}">全部学院</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->dw }}" class="all {{ @$department->pivot->xq }}"{{ isset($condition) && $department->dw == $condition['department'] ? ' selected' : '' }}>{{ $department->mc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="week" class="sr-only">周次</label>
                <select name="week" class="form-control" data-width="90px">
                    @for($week = 1; $week <= 7; ++$week)
                        <option value="{{ $week }}"{{ isset($condition) && $week == $condition['week'] ? ' selected' : '' }}>星期{{ config('constants.week.' . $week) }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="class" class="sr-only">节次</label>
                <select name="class" class="form-control" data-width="100px">
                    @for($class = 1; $class <= 12; ++$class)
                        <option value="{{ $class }}"{{ isset($condition) && $class == $condition['class'] ? ' selected' : '' }}>第 {{ $class }} 节</option>
                    @endfor
                </select>
            </div>
            <button class="btn btn-primary" type="submit" title="查询">查询</button>
        </form>
    </div>
</section>

@if (count($courses))
    <section class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">{{ $subtitle }}</div>
                </div>
                <div class="panel-body">
                    <table id="search-table" class="table table-bordered table-striped table-hover" width="100%">
                        <thead>
                            <tr>
                                <th class="active">课程名称</th>
                                <th class="active">校区</th>
                                <th class="active">教室</th>
                                <th class="active">开课学院</th>
                                <th class="active">学生所在学院</th>
                                <th class="active">专业</th>
                                <th class="active">年级</th>
                                <th class="active">选课人数</th>
                                <th class="active">教师姓名</th>
                                <th class="active">教师职称</th>
                                <th class="active">开始周</th>
                                <th class="active">结束周</th>
                                <th class="active">备注</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>课程名称</th>
                                <th>校区</th>
                                <th>教室</th>
                                <th>开课学院</th>
                                <th>学生所在学院</th>
                                <th>专业</th>
                                <th>年级</th>
                                <th>选课人数</th>
                                <th>教师姓名</th>
                                <th>教师职称</th>
                                <th>开始周</th>
                                <th>结束周</th>
                                <th>备注</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course['kcmc'] }}</td>
                                    <td>{{ $course['xqh'] }}</td>
                                    <td>{{ $course['jsmc'] }}</td>
                                    <td>{{ $course['kkxy'] }}</td>
                                    <td>{{ $course['xy'] }}</td>
                                    <td>{{ $course['zy'] }}</td>
                                    <td>{{ $course['nj'] }}</td>
                                    <td>{{ $course['rs'] }}</td>
                                    <td>{{ $course['jsxm'] }}</td>
                                    <td>{{ $course['jszc'] }}</td>
                                    <td>{{ $course['ksz'] }}</td>
                                    <td>{{ $course['jsz'] }}</td>
                                    <td>
                                        <ol>
                                            @foreach ($course['bz'] as $bz)
                                                <li>{{ $bz }}</li>
                                            @endforeach
                                        </ol>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endif
@stop

@push('scripts')
<script>
$(function() {
    $('#department').chained('#campus');
});
</script>
@endpush

@push('styles')
<style>
    ol {
        padding-left: 15px;
    }
</style>
@endpush
