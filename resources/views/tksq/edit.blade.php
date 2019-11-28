@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form id="appForm" name="appForm" method="post" action="{{ route('tksq.update', $app->id) }}" class="form-horizontal">
                    {!! csrf_field() !!}
                	{!! method_field('put') !!}
                    <div class="form-group">
                        <label for="sqsx" class="col-sm-2 control-label">申请事项</label>
                        <div class="col-sm-6">
                            <select name="sqsx" id="sqsx" class="form-control">
                                <option value="0" {{ $app->sqsx == 0 ? 'selected' : '' }}>调课</option>
                                <option value="1" {{ $app->sqsx == 1 ? 'selected' : '' }}>代课</option>
                                <option value="2" {{ $app->sqsx == 2 ? 'selected' : '' }}>停课</option>
                                <option value="3" {{ $app->sqsx == 3 ? 'selected' : '' }}>删课</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sqyy" class="col-sm-2 control-label">申请原因</label>
                        <div class="col-sm-6">
                            <select name="sqyy" id="sqyy" class="form-control">
                                @foreach ($reasons as $reason)
                                    <option value="{{ $reason->dm }}" {{ $app->sqyy == $reason->dm ? 'selected' : '' }}>{{ $reason->mc }}</option>}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sqly" class="col-sm-2 control-label">申请理由</label>
                        <div class="col-sm-6">
                            <textarea id="sqly" name="sqly" class="form-control" rows="20" placeholder="申请理由">{{ $app->sqly }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kcxh" class="col-sm-2 control-label">课程序号</label>
                        <div class="col-sm-6">
                            <select name="kcxh" id="kcxh" class="form-control">
                                @foreach ($tasks as $task)
                                    <option value="{{ $task->kcxh }}" {{ $app->kcxh == $task->kcxh ? 'selected' : '' }}>{{ $task->kcxh }} - {{ $task->course->kcmc }}</option>}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="qxqz" class="col-sm-2 control-label">变更前时间</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-addon">第</div>
                                <select name="qxqz" id="qxqz" class="form-control">
                                    @foreach (range($currentWeek, $calendar->jx) as $xqz)
                                        <option value="{{ $xqz }}" {{ $app->qxqz == $xqz ? 'selected' : '' }}>{{ $xqz }}</option>}
                                    @endforeach
                                </select>
                                <div class="input-group-addon">周星期</div>
                                <select name="qzc" id="qzc" class="form-control">
                                    @foreach (range(1, 7) as $week)
                                        <option value="{{ $week }}" {{ $app->qzc == $week ? 'selected' : '' }}>{{ config('constants.week.' . $week) }}</option>}
                                    @endforeach
                                </select>
                                <div class="input-group-addon">第</div>
                                <select name="qksj" id="qksj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}" {{ $app->qksj == $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-addon">节至第</div>
                                <select name="qjsj" id="qjsj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}" {{ $app->qjsj == $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-addon">节</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hxqz" class="col-sm-2 control-label">变更后时间</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-addon">第</div>
                                <select name="hxqz" id="hxqz" class="form-control">
                                    @foreach (range($currentWeek, $calendar->jx) as $xqz)
                                        <option value="{{ $xqz }}" {{ $app->hxqz == $xqz ? 'selected' : '' }}>{{ $xqz }}</option>}
                                    @endforeach
                                </select>
                                <div class="input-group-addon">周星期</div>
                                <select name="hzc" id="hzc" class="form-control">
                                    @foreach (range(1, 7) as $week)
                                        <option value="{{ $week }}" {{ $app->hzc == $week ? 'selected' : '' }}>{{ config('constants.week.' . $week) }}</option>}
                                    @endforeach
                                </select>
                                <div class="input-group-addon">第</div>
                                <select name="hksj" id="hksj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}" {{ $app->hksj == $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-addon">节至第</div>
                                <select name="hjsj" id="hjsj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}" {{ $app->hjsj == $item ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-addon">节</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hjs" class="col-sm-2 control-label">变更后主讲教师</label>
                        <div class="col-sm-6">
                            <select name="hjs" id="hjs" class="form-control">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->jsgh }}"{{ $app->hjs == $teacher->jsgh ? ' selected' : '' }}>{{ $teacher->jsgh }} - {{ $teacher->xm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-2">
                            <button type="submit" class="btn btn-success">修改申请</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@stop
