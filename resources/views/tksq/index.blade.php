@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
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
                                <th class="active">申请事项</th>
                                <th class="active">学院审核意见</th>
                                <th class="active">学校审核意见</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($apps as $app)
                            <tr>
                                <td>
                                	@if (1 != $app->xyspzt)
                                		<form id="deleteForm" name="deleteForm" action="{{ route('tksq.destroy', $app->id) }}" method="post" role="form">
                                			{!! method_field('delete') !!}
                                			{!! csrf_field() !!}
                                			<button type="submit" class="btn btn-danger">撤销申请</button>
                                		</form>
                                	@endif
                                </td>
                                <td>{{ $app->id }}</td>
                                <td>{{ $app->nd }}</td>
                                <td>{{ $app->term->mc }}</td>
                                <td>{{ $app->kcxh }}</td>
                                <td>{{ App\Models\Course::find(App\Http\Helper::getCno($app->kcxh))->kcmc }}</td>
                                <td>第 {{ $app->qxqz }} 周星期{{ config('constants.week.' . $app->qzc) }}第 {{ $app->qksj }} 节至第 {{ $app->qjsj }} 节</td>
                                <td>{{ $app->qcdbh }}</td>
                                <td>第 {{ $app->hxqz }} 周星期{{ config('constants.week.' . $app->hzc) }}第 {{ $app->hksj }} 节至第 {{ $app->hjsj }} 节</td>
                                <td>{{ $app->hcdbh }}</td>
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
                                            删课
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
                                            不同意
                                            @break

                                        @case (2)
                                            同意
                                            @break

                                        @default
                                            未审批
                                        
                                    @endswitch
                                </td>
                                <td>
                                    @switch ($app->xxspzt)
                                        @case (0)
                                            未审批
                                            @break

                                        @case (1)
                                            不同意
                                            @break

                                        @case (2)
                                            同意
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