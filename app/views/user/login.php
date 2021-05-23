    <!--link rel="stylesheet" type="text/css" href="/static/css/login.css"-->
    <style>
        .footer{
            background: #22222280 url(/img/bg/footer.png) center center repeat scroll;
            color: white;
        }
    </style>
</head>

<body>

<?php include APP_PATH . "/app/views/header.php" ?>
<?php include APP_PATH . "/app/views/user/header.php" ?>
<br/>
<div class="loginbox">
    <div class="logintitle">
        <p>进入虚拟商品购物系统</p>
    </div>
    <br/>
    <form action="/user/login" method="post" autocomplete="on">
        <input type="hidden" name="prePage" value="<?php echo $prePage ?? ($_GET['prePage'] ?? ($_POST['prePage'] ??'')) ?>">
        <div class="login">
            <label for="uname"></label>
            <input type="text" value="<?php echo $uname??''?>" id="uname" name="uname" maxlength="20" placeholder="请键入会员名" required>
        </div>
        <br>
        <div class="login">
            <label for="password"></label>
            <input type="password" id="password" name="password" maxlength="20" placeholder="请键入密码" required>
        </div>
        <br>
        <input type="checkbox" name="keep" id="keep" style="width: 5%;">
        <label for="keep">30天内免登录</label>
        <a href="/user/forget">忘记密码？</a>
        <br/>
        <button type="submit" class="btn btn-success">登录</button>
        <p>尚未成为会员？<a href="/user/register">点击此处</a>尽享各类虚拟商品</p>
    </form>
</div>
<br/>