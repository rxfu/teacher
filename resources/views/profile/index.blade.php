@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">基本资料</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th class="active">工号</th>
                            <td>{{ $profile->jsgh }}</td>
                            <th class="active">姓名</th>
                            <td>{{ $profile->xm }}</td>
                        </tr>
                        <tr>
                            <th class="active">性别</th>
                            <td>{{ is_null($profile->gender) ? '' : $profile->gender->mc }}</td>
                            <th class="active">出生日期</th>
                            <td>{{ $profile->csrq }}</td>
                        </tr>
                        <tr>
                            <th class="active">证件类型</th>
                            <td>{{ is_null($profile->idtype) ? '' : $profile->idtype->mc }}</td>
                            <th class="active">证件号码</th>
                            <td>{{ $profile->sfzh }}</td>
                        </tr>
                        <tr>
                            <th class="active">国籍</th>
                            <td>{{ is_null($profile->country) ? '' : $profile->country->mc }}</td>
                            <th class="active">职称</th>
                            <td>{{ is_null($profile->position) ? '' : $profile->position->mc }}</td>
                        </tr>
                        <tr>
                            <th class="active">学历</th>
                            <td>{{ is_null($profile->education) ? '' : $profile->education->mc }}</td>
                            <th class="active">学位</th>
                            <td>{{ is_null($profile->degree) ? '' : $profile->degree->mc }}</td>
                        </tr>
                        <tr>
                            <th class="active">专业</th>
                            <td>{{ $profile->zy }}</td>
                            <th class="active">学院</th>
                            <td>{{ is_null($profile->college) ? '' : $profile->college->mc }}</td>
                        </tr>
                        <tr>
                            <th class="active">系所</th>
                            <td>{{ is_null($profile->school) ? '' : $profile->school->mc }}</td>
                            <th class="active">教研室</th>
                            <td>{{ is_null($profile->section) ? '' : $profile->section->mc }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop