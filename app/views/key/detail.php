
<?php include APP_PATH . "/app/views/header.php" ?>

ID：<?php echo $key['kid'] ?><br />
Name：<?php echo isset($key['kname']) ? $key['kname'] : '' ?>

<br />
<br />
<a class="big" href="/key/index">返回</a>