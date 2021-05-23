<link rel="stylesheet" type="text/css" href="/static/css/login.css">

</head>
<body>

<?php include APP_PATH . "/app/views/header.php" ?>
<?php include APP_PATH . "/app/views/user/header.php" ?>

<div class="loginbox">
    <div class="logintitle">
        <p>虚拟商品购物系统用户激活</p>
    </div>
    <br/>
    <form action="/user/active" method="get" autocomplete="on">
        请输入您要激活的用户名：<br/>
        <label for="uname">
            <div class="login"><input name="uname" id="uname" maxlength="20" placeholder="请键入用户名" type="text"></div>
        </label>
        <button type="submit" class="btn btn-success">激活</button>
        <p>尚未注册用户？<a href="/user/register">点击此处</a>加入虚拟商品购物系统</p>
    </form>
</div>