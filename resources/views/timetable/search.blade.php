@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <form id="searchForm" name="searchForm" method="post" action="{{ url('timetable/search') }}" role="form" class="form-inline">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="departmtent" class="sr-only">学院</label>
                <select name="department" class="selectpicker" data-style="btn-success">
                    <option value="all">所有学院</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->dw }}">{{ $department->mc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="week" class="sr-only">周次</label>
                <select name="week" class="selectpicker" data-style="btn-success" data-width="100px">
                    <option value="all">所有周次</option>
                    @for($week = 1; $week <= 7; ++$week)
                        <option value="{{ $week }}">星期{{ config('constants.week.' . $week) }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="begclass" class="sr-only">开始节</label>
                <select name="begclass" class="selectpicker" data-style="btn-success" data-width="120px">
                    <option value="all">所有开始节</option>
                    @for($class = 1; $class <= 12; ++$class)
                        <option value="{{ $class }}">第 {{ $class }} 节</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="endclass" class="sr-only">结束节</label>
                <select name="endclass" class="selectpicker" data-style="btn-success" data-width="120px">
                    <option value="all">所有结束节</option>
                    @for($class = 1; $class <= 12; ++$class)
                        <option value="{{ $class }}">第 {{ $class }} 节</option>
                    @endfor
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Go!</button>
            </div>
        </form>
    </div>
</section>

@if (isset($results))
    <section class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table id="search-table" class="table table-bordered table-striped table-hover">
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
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ $result->task->course->mc }}</td>
                                    <td>{{ $result->campus->mc }}</td>
                                    <td>{{ $result->classroom->mc }}</td>
                                    <td>{{ $result->mjcourse->college->mc }}</td>
                                    <td>{{ $result->task->course->mc }}</td>
                                    <td>{{ $result->mjcourse->major->mc }}</td>
                                    <td>{{ $result->mjcourse->nj }}</td>
                                    <td>{{ $result->task->course->mc }}</td>
                                    <td>{{ $result->user->xm }}</td>
                                    <td>{{ $result->user->position->mc}}</td>
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
