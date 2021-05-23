
    <style>

    </style>
</head>
<body>
<?php include APP_PATH . "/app/views/header.php" ?>
<h1>关键词管理</h1>
<form action="" method="get">
    <div class="form-group">
        <input type="text" class="form-group" value="<?php echo $keyword??"" ?>" name="keyword">
    </div>
    <input type="submit" value="搜索">
</form>

<p><a href="/key/manage">新建</a></p>

    <table class="table table-striped table-hover">
        <thead>
             <tr>
                <th style="width: 15%;">#</th>
                <th style="width: 70%;">关键词内容</th>
                <th style="width: 15%;">操作</th>
            </tr>
        </thead>
    <?php foreach ($keys as $key): ?>
        <tr<?php echo ($key['active']==1?:"style='background-color: #f2dede;'")?>>
            <td><?php echo $key['kid'] ?></td>
            <td>
                <a href="/key/detail/<?php echo $key['kid'] ?>" title="查看详情">
                    <?php echo $key['kname'] ?>
                </a>
            </td>
            <td>
                <a href="/key/manage/<?php echo $key['kid'] ?>">编辑</a>
                <?php if($key['active']=='1'){?>
                <a href="/key/delete/<?php echo $key['kid'] ?>">删除</a>
                <?php }elseif($key['active']=='0'){ ?>
                <a href="/key/recover/<?php echo $key['kid'] ?>">恢复</a>
                <?php }?>
            </td>
        </tr>
    <?php endforeach ?>
</table>`