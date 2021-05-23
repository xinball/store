
<style>

</style>
</head>
<body>

<?php
foreach ($poems as $poem){
    echo "题目：<a href=\"/poem?pid=".$poem['pid']."\">".$poem['title']."</a>&nbsp;&nbsp;&nbsp;".
        "朝代：<a href=\"/poem/list?did=".$poem['did']."\">".$poem['dname']."</a>&nbsp;&nbsp;&nbsp;".
        "作者：<a href=\"/poem/list?aid=".$poem['aid']."\">".$poem['aname']."</a><br/>";
}
echo $navigation;
?>
