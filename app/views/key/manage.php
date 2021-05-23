</head>
<body>

<?php include APP_PATH . "/app/views/header.php" ?>
<form  <?php if (isset($key['kid'])) { ?>
    action="/key/update/<?php echo $key['kid'] ?>"
<?php } else { ?>
    action="/key/add"
<?php } ?>
    method="post">

    <?php if (isset($key['kid'])): ?>
        <input type="hidden" name="kid" value="<?php echo $key['kid'] ?>">
    <?php endif; ?>
    <input type="text" name="kname" value="<?php echo isset($key['kname']) ? $key['kname'] : '' ?>">
    <input type="submit" value="提交">
</form>

<a class="big" href="/key/index">返回</a>