<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="用于广西师范大学教师信息管理，录入成绩，查询成绩，教师评学">
        <meta name="keywords" content="广西师范大学,教务处,教师信息管理,成绩录入,成绩查询,教师评学">
        <meta name="author" content="Fu Rongxin,符荣鑫">
        <title>{{ $title or '默认页面'}} - 广西师范大学教师信息管理系统</title>
        <!--link rel="shortcut icon" href="favicon.ico"-->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/formValidation.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap-theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/metisMenu.min.css') }}">
        <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/plugins/dataTables/dataTables.bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sb-admin-2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/timeline.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
            <script src="{{ asset('js/html5shiv.js') }}"></script>
            <script src="{{ asset('js/respond.min.js') }}"></script>
        <![endif]-->
    </head>

    <body>
        <div id="wrapper">
            <!-- Header -->
            <header class="header" role="banner"></header>
            <!-- /.header -->

            <!-- Browser alert -->
            <!--section id="browserAlert" class="alert alert-danger">
               <a href="#" class="close" data-dismiss="alert" aria-lable="关闭">
                  <span aria-hidden="true">&times;</span>
               </a>
               <strong>注意！</strong> 你现在使用的是<strong>360浏览器</strong>，将不能正确提交成绩，请更换其他浏览器以便正确提交成绩！
            </section-->
            <!-- /#browserAlert -->

            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                    <a href="{{ route('home') }}" class="navbar-brand">广西师范大学教师信息管理系统</a>
                </div>
                <!-- /.navbar-header -->

                <ul class="nav navbar-top-links navbar-right">
                    <li>欢迎{{ $user->college->mc }}{{ $user->xm }}老师使用教师信息管理系统！</li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-user fa-fw"></i>
                            <span>{{ $user->xm }}</span>
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="{{ url('profile') }}"><i class="fa fa-user fa-fw"></i> 个人资料</a></li>
                            <li><a href="{{ url('password/change') }}"><i class="fa fa-unlock fa-fw"></i> 修改密码</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-fw"></i> 登出</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- /.navbar-top-links -->

                <!-- Menu -->
                <aside class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul id="side-menu" class="nav">
                            <li>
                                <a href="{{ url('home') }}"><i class="fa fa-dashboard fa-fw"></i> 综合管理系统</a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-tasks fa-fw"></i> 成绩管理<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ url('score') }}">成绩录入</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('task') }}">成绩查询</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-calendar fa-fw"></i> 课程管理<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ url('task/timetable') }}">课表查询</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('timetable/search') }}">听课查询</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-anchor fa-fw"></i> 评教管理<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ url('task') }}">评教查询</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-comments fa-fw"></i> 评学管理<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ url('tes') }}">评学录入</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('task') }}">评学查询</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-gear fa-fw"></i> 系统管理<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="{{ url('profile') }}">个人资料</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('password/change') }}">修改密码</a>
                                    </li>
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>
                            <li>
                                <a href="{{ url('logout') }}"><i class="fa fa-sign-out fa-fw"></i> 登出</a>
                            </li>
                        </ul>
                        <!-- /#side-menu -->
                    </div>
                    <!-- /.sidebar-nav -->
                </aside>
                <!-- /.navbar-sidebar -->
            </nav>
            <!-- /.navbar -->

            <!-- Page wrapper -->
            <main id="page-wrapper">
                @if (session('status'))
                <!-- Status -->
                <section class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('status') }}
                        </div>
                    </div>
                    <!-- /.col-sm-12 -->
                </section>
                <!-- /.row -->
                @endif

                @if (isset($info))
                <!-- Information -->
                <section class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-info alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ $info }}
                        </div>
                    </div>
                    <!-- /.col-sm-12 -->
                </section>
                <!-- /.row -->
                @endif

                @if ($errors->any())
                <!-- Errors -->
                <section class="row">
                    <div class="col-sm-12">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <strong>注意：出错啦！</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- /.col-sm-12 -->
                </section>
                <!-- /.row -->
                @endif

                <article class="row">
                    <header class="col-sm-12">
                        <h1 class="page-header">{{ $title or '默认页面' }}</h1>
                    </header>
                    <!-- /.col-sm-12 -->

                    <!-- Loading -->
                    <!--section id="loading">
                        <img src="{{ asset('images/loading.gif') }}" alt="加载中">
                        <p>加载中……请稍后</p>
                    </section-->

                    @yield('content')
                </article>
                <!-- /.row -->
            </main>
            <!-- /#page-wrapper -->

            <!-- Copyright -->
            <footer class="footer" role="contentinfo">
                &copy; 2014{{ (date('Y') == '2014') ? '' : ' - ' . date('Y') }} <a href="http://www.dean.gxnu.edu.cn">广西师范大学教务处</a>.版权所有.
            </footer>
            <!-- /.footer -->
        </div>
        <!-- /#wrapper -->

        <!-- Load JS here for greater good -->
        <script src="{{ asset('js/jquery-1.12.0.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui-1.10.4.custom.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/formValidation.min.js') }}"></script>
        <script src="{{ asset('js/language/zh_CN.js') }}"></script>
        <script src="{{ asset('js/bootstrap-paginator.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-switch.js') }}"></script>
        <script src="{{ asset('js/bootstrap-typeahead.js') }}"></script>
        <script src="{{ asset('js/jquery.placeholder.js') }}"></script>
        <script src="{{ asset('js/jquery.stacktable.js') }}"></script>
        <script src="{{ asset('js/jquery.chained.min.js') }}"></script>
        <script src="{{ asset('js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('js/plugins/dataTables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/plugins/dataTables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/sb-admin-2.js') }}"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        @stack('scripts')
    </body>
</html>