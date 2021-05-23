    <link rel="stylesheet" type="text/css" href="/static/css/login.css">
</head>
<body>

<?php include APP_PATH . "/app/views/header.php" ?>
<?php include APP_PATH . "/app/views/user/header.php" ?>

<div class="loginbox">
    <?php if(!isset($user)&&!isset($forget)){?>
    <div class="logintitle">
        <p>虚拟商品购物系统密码找回</p>
    </div>
    <br/>
    <form action="/user/forget" method="get" autocomplete="on">
        请输入您要找回密码的用户名：<br/>
        <label for="uname">
            <div class="login"><input name="uname" id="uname" maxlength="20" placeholder="请键入用户名" type="text"></div>
        </label>
        <button type="submit" class="btn btn-success">找回密码</button>
        <p>已找回密码？<a href="/user/login">点击此处</a>进入虚拟商品购物系统</p>
    </form>
    <?php }else{?>
        <div class="title">
            <p>虚拟商品购物系统密码找回</p>
        </div>
        <br/>
        <form action="/user/forget" method="get" autocomplete="on">
            您要重置密码的用户名为：<?php echo $user['uname']?>
            <input type="hidden" name="uid" value="<?php echo $user['uid']?>"/>
            <input type="hidden" name="forget" value="<?php echo $forget?>"/>
            <label for="password">
                <div class="login"><input type="password" id="password" name="password" maxlength="20" placeholder="请键入密码" required></div>
            </label><br>
            <button type="submit" class="btn btn-success">重置密码</button>
            <p>已重置密码？<a href="/user/login">点击此处</a>进入网站</p>
        </form>
    <?php }?>
</div>