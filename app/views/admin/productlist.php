
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
@-moz-document url-prefix() {
     fieldset { display: table-cell; }
 }
.footer{
    width: 100%;
}
</style>

</head>
<body>
<?php include APP_PATH . "/app/views/header.php" ?>
<div id="del"></div>
<div class="modal fade" id="changeModal" tabindex="-1" role="dialog" aria-labelledby="changeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: url(/img/bg/footer.png) white;" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="changeModalLabel">修改商品信息</h4>
            </div>
            <div id="msg"></div>
            <div class="modal-body">
                <form id="change" action="" method="post">
                    <input type="hidden" name="pidModal" id="pidModal">
                    <input type="hidden" name="keysModal" id="keysModal">
                    <div class="form-group">
                        <label for="pnameModal" class="control-label">商品名称</label>
                        <input type="text" name="pnameModal" class="form-control" id="pnameModal">
                    </div>
                    <div class="form-group" id="kidModal">
                        <label for="kid0Modal" class="control-label">关键词</label>
                        <select name="kid0Modal" id="kid0Modal" class="form-control">
                            <option value="">选择关键词</option>
                            <?php foreach ($keylist as $key){
                                if($key['active']=='1'){?>
                                    <option value="<?php echo $key['kid'];?>"><?php echo $key['kname'];?></option>
                                <?php }}?>
                        </select>
                    </div>
                    <div class="btn-group doBtn" role="group" aria-label="...">
                        <button type="button" class="btn btn-default findbutton" id="addkidModal" value="1">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true" style="color:lawngreen;"></span>
                        </button>
                        <button type="button" class="btn btn-default findbutton" id="delkidModal" value="1">
                            <span class="glyphicon glyphicon-minus-sign" aria-hidden="true" style="color:darkorange;"></span>
                        </button>
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="priceModal" class="control-label">定价</label>
                        <input type="number" name="priceModal" class="form-control" id="priceModal">
                    </div>
                    <div class="form-group">
                        <label for="numberModal" class="control-label">库存</label>
                        <input type="number" name="numberModal" class="form-control" id="numberModal">
                    </div>
                    <div class="form-group">
                        <label for="describeModal" class="control-label">商品详情</label>
                        <textarea name="describeModal" class="form-control" rows="4" style="resize: none;" id="describeModal"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" id="changeproduct" class="btn btn-success">修改</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: url(/img/bg/footer.png) white;" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="changeModalLabel">添加商品</h4>
            </div>
            <div id="addmsg"></div>
            <div class="modal-body container-fluid">
                <form id="add" action="" method="post">
                    <div class="form-group">
                        <label for="pnameAdd" class="control-label">商品名称</label>
                        <input type="text" name="pnameAdd" class="form-control" id="pnameAdd">
                    </div>
                    <div class="form-group">
                        <label for="priceAdd" class="control-label">定价</label>
                        <input type="number" name="priceAdd" class="form-control" id="priceAdd">
                    </div>
                    <div class="form-group">
                        <label for="numberAdd" class="control-label">库存</label>
                        <input type="number" name="numberAdd" class="form-control" id="numberAdd">
                    </div>
                    <div class="form-group">
                        <label for="describeAdd" class="control-label">商品详情</label>
                        <textarea name="describeAdd" class="form-control" rows="4" style="resize: none;" id="describeAdd"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" id="addproduct" class="btn btn-success">添加</button>
            </div>
        </div>
    </div>
</div>

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
            <a class="navbar-brand" href="#footer">选择页面</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="navbar-collapse collapse" id="list" aria-expanded="false">
                <form id="find" action="/admin/productlist" method="get" autocomplete="on" class="navbar-form navbar-left" role="search">
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
                            <option value="num">库存排行</option>
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
                        <button type="button" class="btn btn-default findbutton" style="background:mediumpurple;" data-toggle="modal" data-target="#addModal">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:white;"></span>
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
<div class="poemlist table-responsive" id="poemlist">
<table class="table table-striped table-hover">
    <thead>
        <th style="width: 5%;">#</th>
        <th style="width: 10%;">商品名称</th>
        <th style="width: 15%;">标签</th>
        <th style="width: 20%;">描述</th>
        <th style="width: 10%;">单价</th>
        <th style="width: 10%;">销量</th>
        <th style="width: 10%;">库存</th>
        <th style="width: 10%;">收藏</th>
        <th >操作</th>
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
                $kidsStr.="<a href='/admin/productlist?keys=[\"".$kid['kid']."\"]' target='_blank'>".$kid['kname']."</a> ";
            }
        }
        echo
            '<tr class="poemitem"'.($product['active']==1?:"style='background-color: #f2dede;'").'>'.
            "<td>".$product['pid']."</td>".
            "<td><a target='_blank' href=\"/product?pid=".$product['pid']."\" >".$productName."</a></td>".
            "<td>".$kidsStr."</td>".
            "<td>".$productDescribe."</td>".
            "<td>".$product['price']."</td>".
            "<td>".$product['sellnumber']."</td>".
            "<td>".$product['number']."</td>".
            "<td>".$product['likednumber']."</td>".
            '<td><div class="btn-group doBtn" role="group" aria-label="...">'.
            ($product['active']==1?'<button type="button" class="btn btn-info" data-toggle="modal" data-target="#changeModal" data-pid="'.$product['pid'].'"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></button> '.
                '<button type="button" class="btn btn-danger" onclick="delproduct(this);" value='.$product['pid'].'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'
                :'<button type="button" class="btn btn-success" onclick="recoverproduct(this);" value='.$product['pid'].'><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span></button>').
            '</div></td>'.
            "<tr/>";
    }
    ?>
    </tbody>
</table>
</div>
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
        <?php } if(isset($pname)){?>
        $("#pname").val("<?php echo trim($pname);?>");
        <?php } if(isset($describe)){?>
        $("#describe").val("<?php echo trim($describe);?>");
        <?php } if(isset($order)){?>
        if(!select_option_checked("order","<?php echo $order?>")){
            select_option_checked("order","sell");
        }
        <?php }else{?>
        select_option_checked("order","sell");
        <?php }?>
    };

    function find(str=""){
        let i=0;
        let kid=document.getElementById("kid"+i+str);
        let keys="[";
        while(kid!=null){
            if(kid.value!=="")
                keys+='"'+kid.value+'",';
            i++;
            kid=document.getElementById("kid"+i+str);
        }
        if(keys.charAt(keys.length-1)===",")
            keys=keys.substr(0,keys.length-1);
        keys+="]";
        $('#keys'+str).val(keys);
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
        $(this).val(parseInt($('#addkid').val()));
        if($(this).val()>1){
            $(this).val(parseInt($(this).val())-1);
            $('#addkid').val($(this).val());
            let kid=document.getElementById("kid"+$(this).val());
            kid.remove();
        }
    });
    $('#addkidModal').click(function () {
        let kid=document.getElementById("kidModal");
        let kid0=document.getElementById("kid0Modal");
        let node=document.createElement("select");
        node.name="kid"+$(this).val()+"Modal";
        node.id="kid"+$(this).val()+"Modal";
        node.innerHTML=kid0.innerHTML;
        node.className="form-control";
        kid.appendChild(node);
        $(this).val(parseInt($(this).val())+1);
        $('#delkidModal').val($(this).val());
    });
    $('#delkidModal').click(function () {
        $(this).val(parseInt($('#addkidModal').val()));
        if($(this).val()>1){
            $(this).val(parseInt($(this).val())-1);
            $('#addkidModal').val($(this).val());
            let kid=document.getElementById("kid"+$(this).val()+"Modal");
            kid.remove();
        }
    });
    $('#changeModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget) // Button that triggered the modal
        let pid = button.data('pid') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        let modal = $(this)
        let kid=document.getElementById("kidModal");
        let kid0Modal=document.getElementById("kid0Modal");
        $.getJSON("https://store.xinball.top/admin/jsonproduct?pid="+pid,function (data) {
            modal.find('#changeModalLabel').text('修改商品 ' + data.pname+' 的信息');
            modal.find('#pidModal').val(data.pid);
            modal.find('#pnameModal').val(data.pname);
            modal.find('#describeModal').val(data.describe);
            modal.find('#priceModal').val(data.price);
            modal.find('#numberModal').val(data.number);
            let kids=JSON.parse(data.keys);
            let i;
            for (i in kids) {
                if(i+""==="0"){
                    select_option_checked("kid0Modal",kids[0]['kid']);
                    continue;
                }
                if(document.getElementById("kid"+i+"Modal")==null){
                    let node=document.createElement("select");
                    node.name="kid"+i+"Modal";
                    node.id="kid"+i+"Modal";
                    node.innerHTML=kid0Modal.innerHTML;
                    node.className="form-control";
                    kid.appendChild(node);
                }
                select_option_checked("kid"+i+"Modal",kids[i]['kid']);
            }
            $('#addkidModal').val(parseInt(i)+1);
        })
    });
    $("#changeproduct").click(function(){
        find("Modal");
        let data='pid='+$('#pidModal').val()+'&pname='+$('#pnameModal').val()+'&price='+$('#priceModal').val()+'&describe='+$('#describeModal').val()+'&number='+$('#numberModal').val()+'&keys='+$('#keysModal').val();
        $.post("/admin/changeproduct",data,function(result,status){
            let json=JSON.parse(result);
            echoMsg("#msg",json.status,json.result);
        });
    });
    $("#addproduct").click(function(){
        let data='pname='+$('#pnameAdd').val()+'&price='+$('#priceAdd').val()+'&describe='+$('#describeAdd').val()+'&number='+$('#numberAdd').val();
        $.post("/admin/addproduct",data,function(result,status){
            let json=JSON.parse(result);
            echoMsg("#addmsg",json.status,json.result);
        });
    });
    function delproduct(thisBtn){
        let data='pid='+$(thisBtn).val();
        $.post("/admin/delproduct",data,function(result,status){
            let json=JSON.parse(result);
            if(echoMsg("#del",json.status,json.result)!==-1){
                window.location.reload();
            }
        });
    }
   function recoverproduct(thisBtn){
        let data='pid='+$(thisBtn).val();
        $.post("/admin/recoverproduct",data,function(result,status){
            let json=JSON.parse(result);
            if(echoMsg("#del",json.status,json.result)!==-1){
                window.location.reload();
            }
        });
    }
</script>