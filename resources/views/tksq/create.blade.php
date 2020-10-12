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
                                @foreach (config('constants.suspension') as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="tksqyy">
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
                            <textarea id="sqly" name="sqly" class="form-control" rows="20" placeholder="请写清楚具体事由，字数不少于15字"></textarea>
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
                        <label for="kcxh" class="col-sm-2 control-label">课程名称</label>
                        <div class="col-sm-6">
                            <div class="form-control-static" id="course">请选择课程变更前时间</div>
                            <input type="hidden" name="kcxh" id="kcxh" value="">
                        </div>
                    </div>
                    <div class="form-group" id="bghsj">
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
                    <div class="form-group" id="bghjs">
                        <label for="hjs" class="col-sm-2 control-label">变更后主讲教师</label>
                        <div class="col-sm-6">
                            <!--select name="hjs" id="hjs" class="form-control">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->jsgh }}"{{ auth()->user()->jsgh == $teacher->jsgh ? ' selected' : '' }}>{{ $teacher->jsgh }} - {{ $teacher->xm }}</option>
                                @endforeach
                            </select-->
                            <input type="text" name="jskey" id="jskey" class="form-control" placeholder="请输入主讲教师工号或姓名" data-provide="typeahead" value="{{ Auth::user()->college->mc }} - {{ Auth::user()->xm }}（{{ Auth::user()->jsgh }}）" onfocus="this.select()" autocomplete="off">
                            <input type="hidden" name="hjs" id="hjs" value="{{ Auth::user()->jsgh }}">
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
    $('#qxqz, #qzc, #qksj, #qjsj').change(function () {
        $.ajax({
            type: 'get',
            dataType: 'json',
            url: '{{ route('tksq.course') }}',
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
    $('#sqsx').change(function() {
        $('#sqyy').removeAttr('disabled');
        if ($(this).val() == 1) {
            $('#bghjs, #tksqyy').show();
            $('#bghsj').hide();
        } else if ($(this).val() == 2) {
            $('#tksqyy').show();
            $('#bghsj, #bghjs').hide();
        } else if ($(this).val() == 3) {
            $('#bghsj, #bghjs, #tksqyy').hide();
            $('#sqyy').attr('disabled', 'disabled');
        } else {
            $('#bghsj, #bghjs, #tksqyy').show();
        }
    });
    $('#jskey').typeahead({
        source: function(query, process) {
            var parameter = { q: query };

            $.ajax({
                url: "{{ route('tksq.teacher') }}",
                type: 'get',
                data: parameter,
                dataType: 'json',
                success: function(data) {
                    var results = data.map(function(item) {
                        var teacher = {
                            id: item.jsgh,
                            name: item.xm,
                            department: item.mc
                        };

                        return JSON.stringify(teacher);
                    });

                    return process(results);
                }
            });
        },

        highlighter: function(obj) {
            var item = JSON.parse(obj);
            return '<strong>' + item.department + ' - ' + item.name + '（' + item.id + '）</strong>';
        },

        updater: function(obj) {
            var item = JSON.parse(obj);
            $('#hjs').attr('value', item.id);
            return item.department + ' - ' + item.name + '（' + item.id + '）';
        }
    });
});
</script>
@endpush
