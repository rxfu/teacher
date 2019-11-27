@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form id="appForm" name="appForm" method="post" action="{{ route('tksq.store') }}" class="form-horizontal">
                	{!! csrf_field() !!}
                    <div class="form-group">
                        <label for="sqsx" class="col-sm-2 control-label">申请事项</label>
                        <div class="col-sm-6">
                            <select name="sqsx" id="sqsx" class="form-control">
                                <option value="0">调课</option>
                                <option value="1">代课</option>
                                <option value="2">停课</option>
                                <option value="3">删课</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sqyy" class="col-sm-2 control-label">申请原因</label>
                        <div class="col-sm-6">
                            <select name="sqyy" id="sqyy" class="form-control">
                                @foreach ($reasons as $reason)
                                    <option value="{{ $reason->dm }}">{{ $reason->mc }}</option>}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sqly" class="col-sm-2 control-label">申请理由</label>
                        <div class="col-sm-6">
                            <textarea id="sqly" name="sqly" class="form-control" rows="20" placeholder="申请理由"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kcxh" class="col-sm-2 control-label">课程序号</label>
                        <div class="col-sm-6">
                            <select name="kcxh" id="kcxh" class="form-control">
                                @foreach ($tasks as $task)
                                    <option value="{{ $task->kcxh }}">{{ $task->kcxh }} - {{ $task->course->kcmc }}</option>}
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
                                        <option value="{{ $xqz }}">{{ $xqz }}</option>}
                                    @endforeach
                                </select>
                                <div class="input-group-addon">周星期</div>
                                <select name="qzc" id="qzc" class="form-control">
                                    @foreach (range(1, 7) as $week)
                                        <option value="{{ $week }}">{{ config('constants.week.' . $week) }}</option>}
                                    @endforeach
                                </select>
                                <div class="input-group-addon">第</div>
                                <select name="qksj" id="qksj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-addon">节至第</div>
                                <select name="qjsj" id="qjsj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
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
                                        <option value="{{ $xqz }}">{{ $xqz }}</option>}
                                    @endforeach
                                </select>
                                <div class="input-group-addon">周星期</div>
                                <select name="hzc" id="hzc" class="form-control">
                                    @foreach (range(1, 7) as $week)
                                        <option value="{{ $week }}">{{ config('constants.week.' . $week) }}</option>}
                                    @endforeach
                                </select>
                                <div class="input-group-addon">第</div>
                                <select name="hksj" id="hksj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-addon">节至第</div>
                                <select name="hjsj" id="hjsj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
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
                                    <option value="{{ $teacher->jsgh }}"{{ auth()->user()->jsgh == $teacher->jsgh ? ' selected' : '' }}>{{ $teacher->jsgh }} - {{ $teacher->xm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hcdbh" class="col-sm-2 control-label">变更后上课地点</label>
                        <div class="col-sm-2">
                            <select id="campus" name="campus" class="form-control">
                                @foreach ($campuses as $item)
                                    <option value="{{ $item->dm }}">{{ $item->mc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select id="building" name="building" class="form-control">
                                @foreach ($buildings as $item)
                                    <option value="{{ $item->dm }}" class="{{ $item->xqh }}">{{ $item->mc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select id="classroom" name="classroom" class="form-control">
                                @foreach ($classrooms as $item)
                                    <option value="{{ $item->jsh }}" class="{{ $item->xqh }}\{{ $item->jxl }}">{{ $item->mc }}（{{ $item->zws }}人）</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">提交申请</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@stop

@push('scripts')
<script>
$(function() {
    $('#building').chained('#campus');
    $('#classroom').chained('#campus, #building');
});
</script>
@endpush
