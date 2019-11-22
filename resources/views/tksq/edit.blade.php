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
                        <div class="col-sm-4">
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
                        <div class="col-sm-4">
                            <select name="sqyy" id="sqyy" class="form-control">
                                @foreach ($reasons as $reason)
                                    <option value="{{ $reason->dm }}" {{ $app->sqyy == $reason->dm ? 'selected' : '' }}>{{ $reason->mc }}</option>}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sqly" class="col-sm-2 control-label">申请理由</label>
                        <div class="col-sm-4">
                            <textarea id="sqly" name="sqly" class="form-control" rows="20" placeholder="申请理由">{{ $app->sqly }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kcxh" class="col-sm-2 control-label">课程序号</label>
                        <div class="col-sm-4">
                            @foreach ($courses as $course)
                                <option value="{{ $course->kcxh }}" {{ $app->kcxh == $course->kcxh ? 'selected' : '' }}>{{ $course->kcxh }}</option>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kcmc" class="col-sm-2 control-label">课程名称</label>
                        <div class="col-sm-4">
                            <p class="form-control-static">{{ App\Models\Course::find(App\Http\Helper::getCno($app->kcxh))->kcmc }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date" class="col-sm-2 control-label">变更后日期</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="date" name="date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hksj" class="col-sm-2 control-label">变更后节次</label>
                        <div class="col-sm-4">
                            第 <input type="text" class="form-control" id="hksj" name="hksj"> 节至第 <input type="text" class="form-control" id="hjsj" name="hjsj"> 节
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hjs" class="col-sm-2 control-label">变更后主讲教师</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="hjs" name="hjs">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hcdbh" class="col-sm-2 control-label">变更后上课地点</label>
                        <div class="col-sm-4">
                            <select id="campus" name="campus" class="form-control">
                                @foreach ($campuses as $item)
                                    <option value="{{ $item->dm }}">{{ $item->mc }}</option>
                                @endforeach
                            </select>
                            <select id="building" name="building" class="form-control">
                                @foreach ($buildings as $item)
                                    <option value="{{ $item->dm }}" class="{{ $item->xqh }}">{{ $item->mc }}</option>
                                @endforeach
                            </select>
                            <select id="classroom" name="classroom" class="form-control">
                                @foreach ($classrooms as $item)
                                    <option value="{{ $item->dm }}" class="{{ $item->jxl }}">{{ $item->mc }}</option>
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
    @if (('retake' == $type) && (1 < count($courses)))
        alert('在已选课程中未找到原课程，如果确认需要进行重修申请，请在提示结束后手工选择原课程。特别提示：原课程与重修课程不一致会导致重修申请被拒绝，请提交申请后与教学秘书联系并确认。');
    @endif

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