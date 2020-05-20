@extends('app')

@section('content')
<form id="appForm" name="appForm" method="post" action="{{ url('dcxm/xmps', $project->id) }}" class="form-horizontal">
    {!! csrf_field() !!}
    <section class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">项目“{{ $project->xmmc }}”评审意见</div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="psyj" class="col-sm-3 control-label">评审意见</label>
                        <div class="col-sm-8">
                            <textarea id="psyj" name="psyj" class="form-control" rows="10">{{ is_null($review) ? '' : $review->psyj }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pf" class="col-sm-3 control-label">项目评分</label>
                        <div class="col-sm-8">
                            <input type="text" id="pf" name="pf" class="form-control" value="{{ is_null($review) ? '' : $review->pf }}">
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