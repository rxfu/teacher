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
                        <div class="col-sm-4">
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
                        <div class="col-sm-4">
                            <select name="sqyy" id="sqyy" class="form-control">
                                @foreach ($reasons as $reason)
                                    <option value="{{ $reason->dm }}">{{ $reason->mc }}</option>}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sqly" class="col-sm-2 control-label">申请理由</label>
                        <div class="col-sm-4">
                            <textarea id="sqly" name="sqly" class="form-control" rows="20" placeholder="申请理由"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kcxh" class="col-sm-2 control-label">课程序号</label>
                        <div class="col-sm-4">
                            <select name="kcxh" id="kcxh" class="form-control">
                                @foreach ($tasks as $task)
                                    <option value="{{ $task->kcxh }}">{{ $task->kcxh }} - {{ $task->course->kcmc }}</option>}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date" class="col-sm-2 control-label">变更后日期</label>
                        <div class="col-sm-4">
                            <div class="input-group date">
                                <input type="text" class="form-control" id="hrq" name="hrq">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hksj" class="col-sm-2 control-label">变更后节次</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <select name="hksj" id="hksj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}">第 {{ $item }} 节</option>
                                    @endforeach
                                </select>
                                <div class="input-group-addon">至</div>
                                <select name="hjsj" id="hjsj" class="form-control">
                                    @foreach (range(1, 12) as $item)
                                        <option value="{{ $item }}">第 {{ $item }} 节</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hjs" class="col-sm-2 control-label">变更后主讲教师</label>
                        <div class="col-sm-4">
                            <select name="hjs" id="hjs" class="form-control">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->jsgh }}">{{ $teacher->jsgh }} - {{ $teacher->xm }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hcdbh" class="col-sm-2 control-label">变更后上课地点</label>
                        <div class="col-sm-1">
                            <select id="campus" name="campus" class="form-control">
                                @foreach ($campuses as $item)
                                    <option value="{{ $item->dm }}">{{ $item->mc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select id="building" name="building" class="form-control">
                                @foreach ($buildings as $item)
                                    <option value="{{ $item->dm }}" class="{{ $item->xqh }}">{{ $item->mc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select id="classroom" name="classroom" class="form-control">
                                @foreach ($classrooms as $item)
                                    <option value="{{ $item->dm }}" class="{{ $item->jxl }}">{{ $item->mc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
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
    $('#hrq').datepicker({
        language: "zh-CN",
        autoclose: true,
        format: "yyyy-mm-dd"
    });
    $('#ynd').val($('#ykcxh option:selected').attr('data-ynd'));
    $('#yxq').val($('#ykcxh option:selected').attr('data-yxq'));
    $('#yxqmc').val($('#ykcxh option:selected').attr('data-yxqmc'));
    $('#yxf').val($('#ykcxh option:selected').attr('data-yxf'));

    $('#ykcxh').change(function() {
        $('#ynd').val($('#ykcxh option:selected').attr('data-ynd'));
        $('#yxq').val($('#ykcxh option:selected').attr('data-yxq'));
        $('#yxqmc').val($('#ykcxh option:selected').attr('data-yxqmc'));
        $('#yxf').val($('#ykcxh option:selected').attr('data-yxf'));
    });
});
</script>
@endpush