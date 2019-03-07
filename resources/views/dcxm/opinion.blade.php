@extends('app')

@section('content')
<form id="appForm" name="appForm" method="post" action="{{ url('dcxm/jsyj', $project->id) }}" class="form-horizontal">
    {!! csrf_field() !!}
    <section class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">项目“{{ $project->xmmc }}”导师意见</div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="jsyj" class="col-sm-3 control-label">导师意见</label>
                        <div class="col-sm-8">
                            <textarea id="jsyj" name="jsyj" class="form-control" rows="20">{{ is_null($project->application) ? '' : $project->application->jsyj }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jssfty" class="col-sm-3 control-label">是否同意推荐</label>
                        <div class="col-sm-8">
                            <div class="radio-inline">
                                <input type="radio" name="jssfty" value="1" placeholder="同意"{{ 1 == $project->jssfty ? ' checked' : '' }}>同意
                            </div>
                            <div class="radio-inline">
                                <input type="radio" name="jssfty" value="0" placeholder="不同意"{{ 0 == $project->jssfty ? ' checked' : '' }}>不同意
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="form-group">
        <div class="col-sm-2 col-sm-offset-5">
            <button type="submit" class="btn btn-primary btn-block btn-lg">保存</button>
        </div>
    </div>
</form>
@stop