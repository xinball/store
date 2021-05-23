<?php

?>
<style>
    body {
        position: relative;
        padding-top: 70px;
    }
</style>
</head>
<body data-spy="scroll" data-target="#navbar">
<nav id="navbar" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid navDiv">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">虚拟商品系统</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">首页<span class="sr-only">(current)</span></a></li>
                <li><a href="#service">服务</a></li>
                <li><a href="#about">关于</a></li>
                <li><a href="#sta">统计</a></li>
                <li><a href="/product/list">商品查询</a></li>
                <!--li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a hre="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li-->
            </ul>
            <form class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" class="form-control navInput" placeholder="键入商品名/关键字/描述">
                </div>
                <button type="submit" class="btn btn-default">全站搜索</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <?php
                $flag1=0;$flag2=0;
                if(isset($admin)){
                    echo '
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' .
                        $admin['uname'].
                        ' <span class="glyphicon glyphicon-user" aria-hidden="true" style="color: hotpink"></span> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/admin/">网站管理中心</a></li>
                        <li><a href="/admin/login">登录其他管理员</a></li>
                        <li><a href="/admin/userlist">管理用户</a></li>
                        <li><a href="/admin/productlist">管理商品</a></li>
                        <li><a href="/admin/logout">注销</a></li>
                        <!--li role="separator" class="divider"></li-->
                    </ul>
                </li>
                    ';
                    $flag1=1;
                }
                if(isset($user)){
                    echo '
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' .
                        $user['uname'].
                        ' <span class="glyphicon glyphicon-user" aria-hidden="true" style="color: deepskyblue"></span> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/user/">用户管理中心</a></li>
                        <li><a href="/user/login">登录其他用户</a></li>
                        <li><a href="/user/register">注册新用户</a></li>
                        <li><a href="/user/logout">注销</a></li>
                        <!--li role="separator" class="divider"></li-->
                    </ul>
                </li>
                    ';
                    $flag2=1;
                }
                if($flag1==0&&$flag2==0){
                    echo '
                <li><a href="/user/login">用户登录</a></li>
                <li><a href="/user/register">注册</a></li>
                <li><a href="/admin/login">后台登录</a></li>
                    ';
                }else if($flag1==0){
                    echo '
                <li><a href="/admin/login">后台登录</a></li>
                    ';
                }else if($flag2==0){
                    echo '
                <li><a href="/user/login">用户登录</a></li>
                    ';
                }
                ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<section id="service" class="bg-gradient">
    <div class="container">
        <header class="text-center">
            <h2 class="title">服务</h2>
        </header>
        <div class="row text-center">
            <div data-animate="fadeInUp" class="col-lg-4 animated fadeInUp">
                <div class="icon"><i class="glyphicon glyphicon-search"></i></div>
                <h3>商品查询</h3>
                <p class="text-center">根据商品名称、关键词等进行精确查询</p>
                <p class="text-center">便捷的商品管理中心</p>
            </div>
            <div data-animate="fadeInUp" class="col-lg-4 animated fadeInUp">
                <div class="icon"><i class="glyphicon glyphicon-star"></i></div>
                <h3>收藏与评论</h3>
                <p class="text-center">人们可以便捷地收藏与取消收藏商品。</p>
                <p class="text-center">您也可以针对商品发表自己的感想与思考。</p>
            </div>
            <div data-animate="fadeInUp" class="col-lg-4 animated fadeInUp">
                <div class="icon"><i class="glyphicon glyphicon-cloud-download"></i></div>
                <h3>质量保证</h3>
                <p class="text-center">本平台保证商品质量</p>
                <p class="text-center">为您提供最优质的售后服务</p>
            </div>
        </div>
        <hr data-animate="fadeInUp" class="animated fadeInUp">
        <div data-animate="fadeInUp" class="text-center animated fadeInUp">
            <p class="lead">你想要了解更多有关“虚拟商品购物系统”或加入我们吗?</p>
            <p><a href="#contact" class="btn btn-outline-light link-scroll">联系我们</a></p>
        </div>
    </div>
</section>

<section id="about">
    <div class="container">
        <header class="text-center">
            <h2 class="title">关于我们</h2>
        </header>
        <p></p>
        <h3>系统名称：虚拟商品购物系统</h3>
        <p></p>
        <h3>本系统开发目的：“网页设计与网站建设”大作业</h3>
        <p></p>

        <div class="row text-center">
            <h3>开发人员及开发详细信息：</h3>
            <p></p>
            <div class="col-lg-3">
                <h4>组长：全卿阁</h4>
                <ul>
                    <li>网站框架的搭建</li>
                    <li>数据库搭建与数据表的构建</li>
                    <li>网站实体类的构造</li>
                    <li>后台与数据库的交互</li>
                    <li>网站后台业务处理</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h4>组员：巴祎明</h4>
                <ul>
                    <li>“关键词”的增删改查页面设计</li>
                    <li>商品页面详情页面设计</li>
                    <li>商品列表查询页面设计</li>
                    <li>管理员的“商品列表管理”页面设计</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h4>组员：庞俊飞</h4>
                <ul>
                    <li>用户操作相关页面</li>
                    <li>用户登录、注册页面</li>
                    <li>忘记密码、激活用户页面</li>
                    <li>管理员登录页面</li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h4>组员：娄攀</h4>
                <ul>
                    <li>用户与管理员主页：</li>
                    <li>用户信息修改、网站信息管理</li>
                    <li>订单查询与管理</li>
                    <li>收藏商品查询与管理</li>
                </ul>
            </div>
        <p></p>
        <p></p>
    </div>
</section>

<section id="sta">
    <div class="container">
        <header class="text-center">
            <h2 class="title">统计</h2>
        </header>
        <div class="row text-center">
            <div data-animate="fadeInUp" class="col-lg-3 animated fadeInUp">
                有效商品总数量：2
            </div>
            <div data-animate="fadeInUp" class="col-lg-3 animated fadeInUp">
                有效 关键词 总数量：14
            </div>
            <div data-animate="fadeInUp" class="col-lg-3 animated fadeInUp">
                有效用户总数量：5
            </div>
            <div data-animate="fadeInUp" class="col-lg-3 animated fadeInUp">
                有效评论总数量：0
            </div>
        </div>
        <p></p>
        <p></p>
    </div>
</section>


