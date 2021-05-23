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
        <p>立即加入虚拟商品购物系统！</p>
    </div>
    <br/>
    <form id="register" action="/user/register" onsubmit="return register()" method="post" autocomplete="on">
        <div class="login">
            <label for="uname"></label>
            <input type="text" value="<?php echo $uname??''?>" id="uname" name="uname" maxlength="20" placeholder="请键入会员名" required>
        </div>
        <br>
        <div class="login">
            <label for="email"></label>
            <input type="email" value="<?php echo $email??''?>" id="email" name="email" maxlength="20" placeholder="请键入邮箱账号" required>
        </div>
        <br>
        <div class="login">
            <label for="password"></label>
            <input type="password" id="password" name="password" maxlength="20" placeholder="请键入密码" required>
        </div>
        <br>
        <div class="login">
            <label for="password1"></label>
            <input type="password" id="password1" name="password1" maxlength="20" placeholder="请再次键入密码" required>
        </div>
        <br/>
        <a href="/user/forget">忘记密码？</a>
        <br/>
        <button type="submit" class="btn btn-success">注册</button>
        <p>已为会员？<a href="/user/login">点击此处</a>进入虚拟商品购物系统</p>
    </form>
</div>
<br/>

<script>
    function register(){
        if($('#password').val()!==$('#password1').val()){
            <?php echo \library\MessageBox::echoDanger("两次键入的密码不相同！请检查后重新注册");?>
            return false;
        }
        return true;
    }
</script>