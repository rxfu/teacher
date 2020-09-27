@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-10 col-sm-offset-4">
        <form id="searchForm" name="searchForm" method="post" action="{{ url('tksq/search') }}" role="form" class="form-inline">
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
            <button class="btn btn-primary" type="submit" title="查询">查询</button>
        </form>
    </div>
</section>

@if (!is_null($apps))
    <section class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">{{ $subtitle }}</div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="active">课程序号</th>
                                    <th class="active">课程名称</th>
                                    <th class="active">任课教师</th>
                                    <th class="active">变更前时间</th>
                                    <th class="active">变更前地点</th>
                                    <th class="active">变更后时间</th>
                                    <th class="active">变更后地点</th>
                                    <th class="active">申请事项</th>
                                    <th class="active">学院审核意见</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($apps as $app)
                                <tr>
                                    <td>{!! str_replace(',', '<br>', $app->kcxh) !!}</td>
                                    <td>{{ App\Models\Course::find(App\Http\Helper::getCno($app->kcxh))->kcmc }}</td>
                                    <td>{{ $app->teacher->xm }}</td>
                                    <td>第 {{ $app->qxqz }} 周星期{{ config('constants.week.' . $app->qzc) }}<br>第 {{ $app->qksj }} ~ {{ $app->qjsj }} 节</td>
                                    <td>{{ optional($app->qclassroom)->mc }}</td>
                                    <td>
                                        @if (!is_null($app->hxqz))
                                            第 {{ $app->hxqz }} 周星期{{ config('constants.week.' . $app->hzc) }}<br>第 {{ $app->hksj }} ~ {{ $app->hjsj }} 节
                                        @endif
                                    </td>
                                    <td>{{ optional($app->hclassroom)->mc }}</td>
                                    <td>
                                        @switch ($app->sqsx)
                                            @case (0)
                                                调课
                                                @break

                                            @case (1)
                                                代课
                                                @break

                                            @case (2)
                                                停课
                                                @break

                                            @case (3)
                                                调教室
                                                @break

                                            @default
                                                无事项

                                        @endswitch
                                    </td>
                                    <td>
                                        @switch ($app->xyspzt)
                                            @case (0)
                                                未审批
                                                @break

                                            @case (1)
                                                同意
                                                @break

                                            @case (2)
                                                不同意
                                                @break

                                            @case(3)
                                                作废
                                                @break

                                            @default
                                                未审批
                                            
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
@stop