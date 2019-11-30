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
                        <label for="kcxh" class="col-sm-2 control-label">课程名称</label>
                        <div class="col-sm-6">
                            <div class="form-control-static" id="course">请选择课程变更前时间</div>
                            <input type="hidden" name="kcxh" id="kcxh" value="">
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

@push('scripts')
<script>
$(function() {
    $('#qxqz, #qzc, #qksj, #qjsj').change(function () {
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: 'course',
            data:{
                xqz: $('#qxqz').val(),
                zc: $('#qzc').val(),
                ksj: $('#qksj').val(),
                jsj: $('#qjsj').val()
            },
            success: function(result) {
                if (result.message == '') {
                    $('#course').html('<span class="text-danger">此时间段没有可调停课程</span>');
                    $('#kcxh').val('');
                } else {
                    $('#course').html(result.message);
                    $('#kcxh').val(result.kcxh);
                }
            }
        });
    });
    $('#appForm').submit(function() {
        if ($('#kcxh').val() == '') {
            alert('此时间段没有可调停课程，请重新选择时间段！');
            return false;
        }

        return true;
    });
});
</script>
@endpush
