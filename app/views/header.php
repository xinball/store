
<?php
if(isset($errMsg))
    echo "<script>".$errMsg."</script>";
if(isset($infoMsg))
    echo "<script>".$infoMsg."</script>";
else if(isset($warnMsg))
    echo $warnMsg;
else if(isset($successMsg))
    echo $successMsg;
?>
<style>
    body {
        position: relative;
        padding-top: 70px;
    }
</style>
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
                <li><a href="/">首页</a></li>
                <li><a href="/product/list">商品查询</a></li>
                <!--li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
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
                <button type="submit" class="btn btn-success">全站搜索</button>
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

<div style="z-index:2000;position: fixed;top: 60%;right: 0;">
    <a href="#top">
        <button type="reset" class="btn btn-default findbutton">
            <span class="glyphicon glyphicon-triangle-top" aria-hidden="true" style="color:hotpink;"></span>
        </button>
    </a>
</div>