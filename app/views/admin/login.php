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


<script>
    $('body').find('nav').addClass('navbar-inverse');
</script>
<?php
echo "<script>".($errMsg ?? ($_GET['errMsg'] ?? ($_POST['errMsg'] ??'')))."</script>";
echo "<script>".($successMsg ?? ($_GET['successMsg'] ?? ($_POST['successMsg'] ??'')))."</script>";
?>

<div class="loginbox">
    <div class="logintitle">
        <p>虚拟商品购物系统后台管理</p>
    </div>
    <br/>
    <form action="/admin/login" method="post" autocomplete="on">
        <input type="hidden" name="prePage" value="<?php echo $prePage ?? ($_GET['prePage'] ?? ($_POST['prePage'] ??'')) ?>">
        <div class="login">
            <label for="uname"></label>
            <input type="text" value="<?php echo $uname??''?>" id="uname" name="uname" maxlength="20" placeholder="请键入名号" required>
        </div>
        <br/>
        <div class="login">
            <label for="password"></label>
            <input type="password" id="password" name="password" maxlength="20" placeholder="请键入令牌" required>
        </div>
        <br/>
        <input type="checkbox" name="keep" id="keep" style="width: 5%;">
        <label for="keep">30天内免登录</label>
        <a href="/user/forget">忘记密码？</a>
        <br/>
        <button type="submit" class="btn btn-success">登录</button>
    </form>
</div>
<br/>