@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <h1 class="page-header">
            <?php echo $info['kkxy'] ?><?php echo $info['nj'] ?>级<?php echo $info['zy'] ?>专业<?php echo $session['year'] ?>年度<?php echo Dictionary::get('xq', $session['term']) ?>学期<?php echo $info['kcxh'] ?><?php echo $info['kcmc'] ?>成绩录入
        </h1>
        <div class="alert alert-danger" role="alert">注意：录入成绩自动保存，无需点击提交按钮。点击“<strong>成绩上报</strong>”后，<strong>成绩不可更改！</strong></div>
    </div>
</section>

<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="panel-title pull-left">成绩方式：<?php echo $ratios['name'] ?></div>
                <?php if (Config::get('score.submit.uncommitted') == $report): ?>
                    <div class="pull-right">
                        <form method="post" action="<?php echo Route::to('score.confirm') ?>" role="form">
                            <button type="button" class="btn btn-primary" title="成绩上报" data-toggle="modal" data-target="#confirmDialog" data-title="成绩上报" data-message="注意：请检查成绩是否已经录入完毕并且正确，成绩确认后将不可更改！">成绩上报</button>
                            <input type="hidden" name="cno" id="cno" value="<?php echo $info['kcxh'] ?>">
                        </form>
                    </div>
                <?php endif;?>
            </div>
            <div class="panel-body">
                <div class="table-responsive tab-table">
                    <table class="table table-bordered table-striped table-hover data-table">
                        <thead>
                            <tr>
                                <th class="active">学号</th>
                                <th class="active">姓名</th>
                                <?php foreach ($ratios['mode'] as $ratio): ?>
                                    <th class="active"><?php echo $ratio['idm'] ?></th>
                                <?php endforeach;?>
                                <th class="active">总评</th>
                                <th class="active">状态</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr data-row="<?php echo $student['xh'] ?>">
                                    <td><p class="form-control-static"><?php echo $student['xh'] ?></p></td>
                                    <td><p class="form-control-static"><?php echo $student['xm'] ?></p></td>
                                    <?php foreach ($ratios['mode'] as $key => $value): ?>
                                        <td>
                                            <?php if (Config::get('score.submit.uncommitted') == $student['tjzt']): ?>
                                                <form method="post" name="scoreForm" action="<?php echo Route::to('score.enter', $info['kcxh']) ?>" role="form">
                                                    <div class="form-group">
                                                        <input type="text" name="score" value="<?php echo $student['cj' . $key] ?>" class="form-control">
                                                        <input type="hidden" name="sno" value="<?php echo $student['xh'] ?>">
                                                        <input type="hidden" name="mode" value="score<?php echo $key ?>">
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <p class="form-control-static"><?php echo $student['cj' . $key] ?></p>
                                            <?php endif;?>
                                        </td>
                                    <?php endforeach;?>
                                    <td data-name="total"><p class="form-control-static total"><?php echo $student['zpcj'] ?></p></td>
                                    <td>
                                        <?php if (Config::get('score.submit.uncommitted') == $student['tjzt'] && Config::get('score.exam.deferral') != $student['ksztdm']): ?>
                                            <form method="post" action="<?php echo Route::to('score.status', $info['kcxh']) ?>" role="form">
                                                <select name="status<?php echo $student['xh'] ?>" id="status<?php echo $student['xh'] ?>" class="form-control">
                                                    <?php foreach ($statuses as $status): ?>
                                                        <option value="<?php echo $status['dm'] ?>"<?php echo $status['dm'] === $student['ksztdm'] ? ' selected="selected"' : '' ?>><?php echo $status['mc'] ?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </form>
                                        <?php else: ?>
                                            <p class="form-control-static"><?php echo $student['kszt'] ?></p>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop