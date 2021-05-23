
<style>
</style>
</head>

<body>
<div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: url(/img/bg/footer.png) white;" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="orderDetailModalLabel">订单详情</h4>
            </div>
            <div id="orderdetailmsg"></div>
            <div class="modal-body container-fluid">
                <table class="table table-striped table-hover">
                    <thead>
                    <th>订单状态</th>
                    <th>时间</th>
                    <th>备注</th>
                    <th>操作者</th>
                    </thead>
                    <tbody id="orderdetailTable">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">确定</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="fModal" tabindex="-1" role="dialog" aria-labelledby="fModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: url(/img/bg/footer.png) white;" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="fModalLabel">不发货</h4>
            </div>
            <div id="fmsg"></div>
            <div class="modal-body">
                <form id="f" action="" method="post">
                    <div class="form-group">
                        <label for="contentModal" class="control-label">不发货的理由内容</label>
                        <textarea id="contentModal" name="contentModal" class="form-control" style="resize: none;" rows="3" placeholder="请键入不发货的理由内容【必填】" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="f(this);" id="fBtn" name="fBtn" value="" type="submit">发布</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="notreturnModal" tabindex="-1" role="dialog" aria-labelledby="notreturnModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: url(/img/bg/footer.png) white;" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="notreturnModalLabel">拒绝收货</h4>
            </div>
            <div id="notreturnmsg"></div>
            <div class="modal-body">
                <form id="notreturn" action="" method="post">
                    <div class="form-group">
                        <label for="notreturnContentModal" class="control-label">拒绝退货的理由内容</label>
                        <textarea id="notreturnContentModal" name="notreturnContentModal" class="form-control" style="resize: none;" rows="3" placeholder="请键入拒绝退货理由内容【必填】" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="notreturn(this);" id="notreturnBtn" value="" name="notreturnBtn" type="submit">发布</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<?php include APP_PATH . "/app/views/header.php" ?>


<div>
    <!-- Nav tabs -->
    <ul id="userTab" class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">用户信息</a></li>
        <li role="presentation"><a href="#order" aria-controls="profile" role="tab" data-toggle="tab">订单管理</a></li>
        <li role="presentation"><a href="#del" aria-controls="messages" role="tab" data-toggle="tab">废弃订单</a></li>
        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">设置</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div id="msg"></div>
        <div role="tabpanel" class="tab-pane fade in active" id="home">
            <div style="width: 80%;margin:auto;padding: 20px;">
                <form id="change" class="form-horizontal" action="" method="post">
                    <input type="hidden" name="uidModal" id="uidModal">
                    <div class="form-group">
                        <label for="uname" class="col-sm-2 control-label">用户名</label>
                        <div class="col-sm-10">
                            <input type="text" name="uname" placeholder="请键入用户名" class="form-control" id="uname" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">原密码</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" placeholder="键入原密码才可修改信息" class="form-control" id="password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password1" class="col-sm-2 control-label">新密码</label>
                        <div class="col-sm-10">
                            <input type="password" name="password1" placeholder="若需更改密码，请在此输入" class="form-control" id="password1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">邮箱</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" placeholder="请键入邮箱" class="form-control" id="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tel" class="col-sm-2 control-label">手机</label>
                        <div class="col-sm-10">
                            <input type="tel" name="tel" placeholder="请键入手机" class="form-control" id="tel">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="money" class="col-sm-2 control-label">资产</label>
                        <div class="col-sm-10">
                            <input type="text" name="money" placeholder="请键入资产" value="0" class="form-control" id="money" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="level" class="col-sm-2 control-label">用户级别</label>
                        <div class="col-sm-10">
                            <select name="level" class="form-control" id="level">
                                <option value="0">未激活用户</option>
                                <option value="1">普通用户</option>
                                <option value="2">超级用户</option>
                                <option value="3">商品管理员</option>
                                <option value="4">用户管理员</option>
                                <option value="5">高级管理员</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" id="changeadmin" class="btn btn-success">修改</button>
                </form>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="order">
            <div class="poemlist" id="orderlist">
                <table class="table table-striped table-hover">
                    <thead>
                    <th style="width: 14%;">商品</th>
                    <th style="width: 10%;">单价</th>
                    <th style="width: 10%;">数量</th>
                    <th style="width: 10%;">单价实付</th>
                    <th style="width: 10%;">总实付款</th>
                    <th style="width: 10%;">状态</th>
                    <th style="width: 10%;">最近操作</th>
                    <th style="width: 10%;">备注</th>
                    <th>操作</th>
                    </thead>
                    <tbody id="orderTable">
                    </tbody>
                </table>
            </div>
            <div id="orderNavigation"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="del">
            <div class="poemlist" id="orderlist">
                <table class="table table-striped table-hover">
                    <thead>
                    <th style="width: 14%;">商品</th>
                    <th style="width: 10%;">单价</th>
                    <th style="width: 10%;">数量</th>
                    <th style="width: 10%;">单价实付</th>
                    <th style="width: 10%;">总实付款</th>
                    <th style="width: 10%;">最近操作</th>
                    <th style="width: 15%;">备注</th>
                    <th>操作</th>
                    </thead>
                    <tbody id="delTable">
                    </tbody>
                </table>
            </div>
            <div id="delNavigation"></div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="settings">

        </div>
    </div>
</div>


<script>
    window.onload=function (){
        home();
    };
    $('a[href="#del"]').on('shown.bs.tab', function (e) {
        e.target // newly activated tab
        e.relatedTarget // previous active tab$('#likeTable').empty();
        del(null,"https://store.xinball.top/admin/del?pageNow=1");
    });
    $('a[href="#order"]').on('shown.bs.tab', function (e) {
        e.target // newly activated tab
        e.relatedTarget // previous active tab$('#likeTable').empty();
        order(null,"https://store.xinball.top/admin/order?pageNow=1");
    });
    $('a[href="#home"]').on('show.bs.tab', function (e) {
        e.target // newly activated tab
        e.relatedTarget // previous active tab$('#likeTable').empty();
        home();
    });
    function order(obj,link="href"){
        $('#orderNavigation').empty();
        $('#msg').empty();
        $('#orderTable').empty();
        let linkStr="";
        if(obj==null){
            linkStr=link;
        }else{
            linkStr=obj.getAttribute(link)
        }
        $.getJSON(linkStr,function (data) {
            $('#orderNavigation').append(data.navPage);
            $('#orderNavigation').append('\<script\>$("#pageTo").keydown(function(e){if (e.which === 13) {order(null,"https://store.xinball.top/admin/order?pageNow="+this.getAttribute("value"));}});\<\/script\>');
            $('#msg').append(data.messages);
            $.each(data.orders, function(i, order){
                if(order.type!=="d"){
                    let orderName=order.pname;
                    if(orderName.length><?php echo $productConfig['pnamenum']?>){
                        orderName=orderName.substr(0,<?php echo $productConfig['pnamenum']?>)+"...";
                    }
                    let btn="",type="";
                    if(order.type==="p"){
                        type="待付款";
                    }else if(order.type==="b"){
                        type="待发货";
                        btn+='<textarea class="form-control" style="resize:none;" id="fContent" placeholder="填写不发货的理由【可选】"></textarea>';
                        btn+='<button type="button" onclick="send(this)" class="btn btn-success" value="'+order.oid+'">发货</button>';
                        btn+='<button type="button" onclick="f(this)" class="btn btn-danger" value="'+order.oid+'">不发货</button>';
                    }else if(order.type==="f"){
                        type="不发货";
                    }else if(order.type==="s"){
                        type="待收货";
                    }else if(order.type==="g"){
                        type="待评价";
                    }else if(order.type==="j"){
                        type="拒绝收货";
                        btn+='<button type="button" onclick="returned(this)" class="btn btn-success" value="'+order.oid+'">退货</button>';
                    }else if(order.type==="e"){
                        type="已评价";
                    }else if(order.type==="t"){
                        type="发起退货等待处理";
                        btn+='<textarea class="form-control" style="resize:none;" id="notreturnContent" placeholder="填写不予退货的理由【可选】"></textarea>';
                        btn+='<button type="button" onclick="returned(this)" class="btn btn-success" value="'+order.oid+'">退货</button>';
                        btn+='<button type="button" onclick="notreturn(this)" class="btn btn-danger" value="'+order.oid+'">不退货</button>';
                    }else if(order.type==="r"){
                        type="已退货";
                    }else if(order.type==="n"){
                        type="拒绝退货";
                    }
                    btn+='<button type="button" class="btn btn-info" data-toggle="modal" data-target="#orderDetailModal" data-oid="'+order.oid+'">详情</button>';
                    let str='<tr class="poemitem">'+
                        '<td><a target="_blank" href="/product?pid='+order.pid+'">'+orderName+'</a></td>'+
                        "<td>"+order.pprice+"</td>"+
                        "<td>"+order.number+"</td>"+
                        "<td>"+order.price+"</td>"+
                        "<td>"+order.total+"</td>"+
                        "<td>"+type+"</td>"+
                        "<td>"+order.time+"</td>"+
                        "<td>"+order.tip+"</td>"+
                        "<td>"+btn+"</td>"+
                        "<tr/>";
                    $('#orderTable').append(str);
                }
            });
        });
        return false;
    }
    function del(obj,link="href"){
        $('#delNavigation').empty();
        $('#msg').empty();
        $('#delTable').empty();
        let linkStr="";
        if(obj==null){
            linkStr=link;
        }else{
            linkStr=obj.getAttribute(link)
        }
        $.getJSON(linkStr,function (data) {
            $('#delNavigation').append(data.navPage);
            $('#delNavigation').append('\<script\>$("#pageTo").keydown(function(e){if (e.which === 13) {del(null,"https://store.xinball.top/admin/del?pageNow="+this.getAttribute("value"));}});\<\/script\>');
            $('#msg').append(data.messages);
            $.each(data.orders, function(i, order){
                if(order.type==="d"){
                    let orderName=order.pname;
                    if(orderName.length><?php echo $productConfig['pnamenum']?>){
                        orderName=orderName.substr(0,<?php echo $productConfig['pnamenum']?>)+"...";
                    }
                    let btn='<button type="button" class="btn btn-info" data-toggle="modal" data-target="#orderDetailModal" data-oid="'+order.oid+'">详情</button>';
                    let str='<tr class="poemitem">'+
                        '<td><a target="_blank" href="/product?pid='+order.pid+'">'+orderName+'</a></td>'+
                        "<td>"+order.pprice+"</td>"+
                        "<td>"+order.number+"</td>"+
                        "<td>"+order.price+"</td>"+
                        "<td>"+order.total+"</td>"+
                        "<td>"+order.time+"</td>"+
                        "<td>"+order.tip+"</td>"+
                        "<td>"+btn+"</td>"+
                        "<tr/>";
                    $('#delTable').append(str);
                }
            });
        });
        return false;
    }
    function home(){
        $.getJSON("/admin/jsonadmin/",function (data) {
            $('#uid').val(data.uid);
            $('#uname').val(data.uname);
            $('#email').val(data.email);
            $('#tel').val(data.tel);
            $('#money').val("￥"+data.money);
            select_option_checked("level",data.level);
        })
    }
    function send(obj){
        $.getJSON("/admin/send?oid="+$(obj).val(),function(data){
            echoMsg("#msg",data.status,data.result);
        });
    }
    function f(obj){
        $.getJSON("/admin/f?oid="+$(obj).val()+"&tip="+$('#fContent').val(),function(data){
            echoMsg("#msg",data.status,data.result);
        });
    }
    function returned(obj){
        $.getJSON("/admin/returned?oid="+$(obj).val(),function(data){
            echoMsg("#msg",data.status,data.result);
        });
    }
    function notreturn(obj){
        $.getJSON("/admin/notreturn?oid="+$(obj).val()+"&tip="+$('#notreturnContent').val(),function(data){
            echoMsg("#msg",data.status,data.result);
        });
    }
    $("#changeadmin").click(function(){
        let data='email='+$('#email').val()+'&tel='+$('#tel').val()+'&password='+$('#password').val()+'&password1='+$('#password1').val();
        $.post("/admin/changeadmin",data,function(result,status){
            let json=JSON.parse(result);
            echoMsg("#msg",json.status,json.result);
        });
    });

    $('#orderDetailModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget) // Button that triggered the modal
        let oid = button.data('oid') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        let modal = $(this)

        $('#orderdetailTable').empty();
        $.getJSON("/admin/orderdetail?oid="+oid,function (data) {
            if(data.status!==1){
                echoMsg("#orderdetail",data.status,data.result);
            }else{
                let orders=JSON.parse(data.result);
                $.each(orders, function(i, order){
                    let type="";
                    if(order.rtype==="p"){
                        type="待付款";
                    }else if(order.rtype==="b"){
                        type="待发货";
                    }else if(order.rtype==="f"){
                        type="不发货";
                    }else if(order.rtype==="s"){
                        type="待收货";
                    }else if(order.rtype==="g"){
                        type="待评价";
                    }else if(order.rtype==="j"){
                        type="拒绝收货";
                    }else if(order.rtype==="e"){
                        type="已评价";
                    }else if(order.rtype==="t"){
                        type="发起退货等待处理";
                    }else if(order.rtype==="r"){
                        type="已退货";
                    }else if(order.rtype==="n"){
                        type="拒绝退货";
                    }else if(order.rtype==="d"){
                        type="已废弃";
                    }
                    let str='<tr class="poemitem">'+
                        "<td>"+type+"</td>"+
                        "<td>"+order.time+"</td>"+
                        "<td>"+(order.tip===""?"":order.tip)+"</td>"+
                        "<td>"+(order.auid==='null'||order.auid===''||order.auid===null?"":order.auid)+"</td>"+
                        "<tr/>";
                    $('#orderdetailTable').append(str);
                });
            }
        }).fail(function(jqXHR, status, error){
            echoMsg("#orderdetailmsg",4,'未<a href="/admin/login/" target="_blank">登录</a>，暂时无法查看哟！');
        });
    });
</script>