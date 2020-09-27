@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="{{ route('tksq.create') }}" title="申请调停课" class="btn btn-success">申请调停课</a>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="active">操作</th>
                                <th class="active">申请单号</th>
                                <th class="active">年度</th>
                                <th class="active">学期</th>
                                <th class="active">课程序号</th>
                                <th class="active">课程名称</th>
                                <th class="active">变更前时间</th>
                                <th class="active">变更前地点</th>
                                <th class="active">变更后时间</th>
                                <th class="active">变更后地点</th>
                                <th class="active">变更后主讲教师</th>
                                <th class="active">申请事项</th>
                                <th class="active">学院审核意见</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($apps as $app)
                            <tr>
                                <td>
                                	@if ((0 == $app->xyspzt) && (now()->diffInHours($app->sqsj) < 1))
                                		<form id="deleteForm" name="deleteForm" action="{{ route('tksq.destroy', $app->id) }}" method="post" role="form">
                                			{!! method_field('delete') !!}
                                			{!! csrf_field() !!}
                                			<button type="submit" class="btn btn-danger">撤销申请</button>
                                		</form>
                                	@endif
                                </td>
                                <td>
                                    @if ((0 == $app->xyspzt) && (now()->diffInHours($app->sqsj) < 1))
                                        <a href="{{ route('tksq.edit', $app->id) }}">{{ $app->id }}</a>
                                    @else
                                        {{ $app->id }}
                                    @endif
                                </td>
                                <td>{{ $app->nd }}</td>
                                <td>{{ $app->term->mc }}</td>
                                <td>{!! str_replace(',', '<br>', $app->kcxh) !!}</td>
                                <td>{{ App\Models\Course::find(App\Http\Helper::getCno($app->kcxh))->kcmc }}</td>
                                <td>第 {{ $app->qxqz }} 周星期{{ config('constants.week.' . $app->qzc) }}<br>第 {{ $app->qksj }} ~ {{ $app->qjsj }} 节</td>
                                <td>{{ optional($app->qclassroom)->mc }}</td>
                                <td>
                                    @if (!is_null($app->hxqz))
                                        第 {{ $app->hxqz }} 周星期{{ config('constants.week.' . $app->hzc) }}<br>第 {{ $app->hksj }} ~ {{ $app->hjsj }} 节
                                    @endif
                                </td>
                                <td>{{ optional($app->hclassroom)->mc }}</td>
                                <td>{{ optional($app->hteacher)->xm }}</td>
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
@stop