
<style>

#aid{
    display: unset;
}
#aname{
    display: unset;
}
input::-webkit-input-placeholder, textarea::-webkit-input-placeholder {
    color: #000000;
}

input:-moz-placeholder, textarea:-moz-placeholder {
    color: #000000;
}

input::-moz-placeholder, textarea::-moz-placeholder {
    color: #000000;
}

input:-ms-input-placeholder, textarea:-ms-input-placeholder {
    color: #000000;
}
.find{
    text-align: center;
    font-size: 20px;
    background: #f0f0f0 url(/img/bg/footer.png) center center repeat scroll;
}
.find input,button, textarea, .form-control , .btn-info{
    /*width:max-content;*/
    font-size: 20px;
}
.footer{
    width: 100%;
}
</style>

</head>
<body>

<?php include APP_PATH . "/app/views/header.php" ?>

<nav class="find navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#list" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#footer"><span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true" style="color:deepskyblue;"></span></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="navbar-collapse collapse" id="list" aria-expanded="false">

                <form id="find" action="/product/list" method="get" autocomplete="on" class="navbar-form navbar-left" role="search">
                    <input type="hidden" name="keys" id="keys">
                    <div class="form-group" id="kid">
                        <select name="kid0" id="kid0" class="form-control">
                            <option value="">选择关键词</option>
                            <?php foreach ($keylist as $key){
                                if($key['active']=='1'){?>
                                    <option value="<?php echo $key['kid'];?>"><?php echo $key['kname'];?></option>
                                <?php }}?>
                        </select>
                    </div>
                    <br/>
                    <div class="form-group">
                        <input class="form-control" type="text" id="pname" name="pname" maxlength="20" placeholder="键入商品名称关键词">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" id="describe" name="describe" maxlength="30" placeholder="键入描述内容关键词">
                    </div>
                    <div class="form-group">
                        <select name="order" id="order" class="form-control">
                            <option value="sell">销售量排行</option>
                            <option value="like">收藏量排行</option>
                            <option value="prices">价格升序</option>
                            <option value="pricej">价格降序</option>
                        </select>
                    </div>
                    <div class="btn-group doBtn" role="group" aria-label="...">
                        <button type="reset" class="btn btn-default findbutton">
                            <span class="glyphicon glyphicon-refresh" aria-hidden="true" style="color:deepskyblue;"></span>
                        </button>
                        <button type="button" class="btn btn-default findbutton" id="addkid" value="1">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="color:lawngreen;"></span>
                        </button>
                        <button type="button" class="btn btn-default findbutton" id="delkid" value="1">
                            <span class="glyphicon glyphicon-minus-sign" aria-hidden="true" style="color:darkorange;"></span>
                        </button>
                        <button type="submit" onclick="return find();" class="btn btn-default findbutton" style="background:hotpink;">
                            <span class="glyphicon glyphicon-search" aria-hidden="true" style="color:white;"></span>
                        </button>
                    </div>
                    <input name="pageNow" id="pageNow" type="hidden" value="1"/>
                </form>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<?php echo $message;?>
<?php if(isset($products)&&$products!=null&&!empty($products)){?>
<div class="poemlist" id="poemlist">
<table class="table table-striped table-hover">
    <thead>
        <th style="width: 1%;">#</th>
        <th style="width: 14%;">商品名称</th>
        <th style="width: 20%;">标签</th>
        <th style="width: 25%;">描述</th>
        <th style="width: 10%;">单价</th>
        <th style="width: 10%;">销量</th>
        <th style="width: 10%;">库存</th>
        <th style="width: 10%;">收藏</th>
    </thead>
    <tbody>
    <?php
    foreach ($products as $i=>$product){
        $productName = $product['pname'];
        $productDescribe=$product['describe'];
        if(mb_strlen($productName)>$productConfig['pnamenum']){
            $productName = mb_substr($productName,0,$productConfig['pnamenum'])."……";
        }
        if(mb_strlen($productDescribe)>$productConfig['describenum']){
            $productDescribe = mb_substr($productDescribe,0,$productConfig['describenum'])."……";
        }
        $kidsStr="";
        if(isset($kidslist[$i])){
            $kids=json_decode($kidslist[$i],true);
            foreach ($kids as $kid){
                $kidsStr.="【<a href='/product/list?keys=[\"".$kid['kid']."\"]' target='_blank'>".$kid['kname']."</a>】 ";
            }
        }
        echo
            '<tr class="poemitem">'.
            "<td>".($i+1)."</td>".
            "<td><a target='_blank' href=\"/product?pid=".$product['pid']."\" >".$productName."</a></td>".
            "<td>".$kidsStr."</td>".
            "<td>".$productDescribe."</td>".
            "<td>".$product['price']."</td>".
            "<td>".$product['sellnumber']."</td>".
            "<td>".$product['number']."</td>".
            "<td>".$product['likednumber']."</td>".
            "<tr/>";
    }
    ?>
    </tbody>
</table>
</div>
    <!--div class="main-grid">
        <div class="agile-grids">
            <div class="grids-heading gallery-heading">
                <h2></h2>
            </div>
            <div class="gallery-grids">
                <div class="show-reel">
                    <?php foreach ($products as $i=>$product){?>
                        <div class="col-md-2 agile-gallery-grid">
                            <div class="agile-gallery">
                                <a href="/product?pid=<?php echo $product['pid']?>" target="_blank" class="_lsb-preview" data-lsb-group="header">
                                    <img src="" alt="">
                                    <div class="agileits-caption">
                                        <h4>价格</h4>
                                        <p>名称-描述</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php }?>
                </div>
                <script>
                    $(window).load(function() {
                        $.fn.lightspeedBox();
                    });
                </script>
            </div>
        </div>
    </div-->
    <?php echo $navigation;?>
<?php }?>
<script>
    window.onload=function (){
        <?php if(isset($keys)){ ?>
        let kid=document.getElementById("kid");
        let kid0=document.getElementById("kid0");
        let kids=JSON.parse('<?php echo $keys?>');
        let i=0;
        for (i in kids) {
            if(i+""==="0"){
                select_option_checked("kid0",kids[0]);
                continue;
            }
            if(document.getElementById("kid"+i)==null){
                let node=document.createElement("select");
                node.name="kid"+i;
                node.id="kid"+i;
                node.innerHTML=kid0.innerHTML;
                node.className="form-control";
                kid.appendChild(node);
            }
            select_option_checked("kid"+i,kids[i]);
        }
        $('#addkid').val(parseInt(i)+1);
        <?php }if(isset($pname)){?>
        $("#pname").val("<?php echo trim($pname);?>");
        <?php }?>
        <?php if(isset($describe)){?>
        $("#describe").val("<?php echo trim($describe);?>");
        <?php }?>
        <?php if(isset($order)){?>
        if(!select_option_checked("order","<?php echo $order?>")){
            select_option_checked("order","sell");
        }
        <?php }else{?>
        select_option_checked("order","sell");
        <?php }?>
    };

    function find(){
        let i=0;
        let kid=document.getElementById("kid"+i);
        let keys="[";
        while(kid!=null){
            if(kid.value!=="")
                keys+='"'+kid.value+'",';
            i++;
            kid=document.getElementById("kid"+i);
        }
        if(keys.charAt(keys.length-1)===",")
            keys=keys.substr(0,keys.length-1);
        keys+="]";
        $('#keys').val(keys);
        return true;
    }
    $('#addkid').click(function () {
        let kid=document.getElementById("kid");
        let kid0=document.getElementById("kid0");
        let node=document.createElement("select");
        node.name="kid"+$(this).val();
        node.id="kid"+$(this).val();
        node.innerHTML=kid0.innerHTML;
        node.className="form-control";
        kid.appendChild(node);
        $(this).val(parseInt($(this).val())+1);
        $('#delkid').val($(this).val());
    });
    $('#delkid').click(function () {
        $(this).val($('#addkid').val());
        if($(this).val()>1){
            $(this).val(parseInt($(this).val())-1);
            $('#addkid').val($(this).val());
            let kid=document.getElementById("kid"+$(this).val());
            kid.remove();
        }
    });
</script>

