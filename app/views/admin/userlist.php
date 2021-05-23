
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
.modal .form-control{
    margin-bottom:20px;
    font-size: 20px;
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
                <h4 class="modal-title" id="changeModalLabel">修改用户信息</h4>
            </div>
            <div id="msg"></div>
            <div class="modal-body container-fluid">
                <form id="change" class="form-inline" action="" method="post">
                    <input type="hidden" name="uidModal" id="uidModal">
                    <div class="form-group col-sm-6">
                        <label for="unameModal" class="control-label">用户名</label>
                        <input type="text" name="unameModal" placeholder="请键入用户名" class="form-control" id="unameModal">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="emailModal" class="control-label">邮箱</label>
                        <input type="email" name="emailModal" placeholder="请键入邮箱" class="form-control" id="emailModal">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="telModal" class="control-label">手机</label>
                        <input type="tel" name="telModal" placeholder="请键入手机号码" class="form-control" id="telModal">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="moneyModal" class="control-label">资产</label>
                        <input type="number" name="moneyModal" placeholder="请键入资产" value="0" class="form-control" id="moneyModal">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="levelModal" class="control-label">用户级别</label>
                        <select name="levelModal" class="form-control" id="levelModal">
                            <option value="0">未激活用户</option>
                            <option value="1">普通用户</option>
                            <option value="2">超级用户</option>
                            <option value="3">商品管理员</option>
                            <option value="4">用户管理员</option>
                            <option value="5">高级管理员</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" id="changeuser" class="btn btn-success">修改</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: url(/img/bg/footer.png) white;" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="changeModalLabel">添加用户</h4>
            </div>
            <div id="addmsg"></div>
            <div class="modal-body container-fluid">
                <form id="add" class="form-inline" action="" method="post">
                    <div class="form-group col-sm-6">
                        <label for="unameAdd" class="control-label">用户名</label>
                        <input type="text" name="unameAdd" placeholder="请键入用户名" class="form-control" id="unameAdd">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="passwordAdd" class="control-label">密码</label>
                        <input type="password" name="passwordAdd" placeholder="请键入密码" class="form-control" id="passwordAdd">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="emailAdd" class="control-label">邮箱</label>
                        <input type="email" name="emailAdd" placeholder="请键入邮箱"  class="form-control" id="emailAdd">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="telAdd" class="control-label">手机</label>
                        <input type="tel" name="telAdd" placeholder="请键入手机号码" class="form-control" id="telAdd">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="moneyAdd" class="control-label">资产</label>
                        <input type="number" name="moneyAdd" placeholder="请键入资产" value="0" class="form-control" id="moneyAdd">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="levelAdd" class="control-label">用户级别</label>
                        <select name="levelAdd" class="form-control" id="levelAdd">
                            <option value="0">未激活用户</option>
                            <option value="1">普通用户</option>
                            <option value="2">超级用户</option>
                            <option value="3">商品管理员</option>
                            <option value="4">用户管理员</option>
                            <option value="5">高级管理员</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" id="addeuser" class="btn btn-success">添加</button>
            </div>
        </div>
    </div>
</div>
<datalist id="anameList">
</datalist>
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
            <a class="navbar-brand" href="#">用户管理</a>
            <a class="navbar-brand" href="#footer">选择页面</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="navbar-collapse collapse" id="list" aria-expanded="false">
            <form id="find" action="/admin/userlist" method="get" autocomplete="on" class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input class="form-control" type="text" id="uname" name="uname" maxlength="20" placeholder="键入用户名关键词">
                </div>
                <div class="form-group">
                    <input class="form-control" type="email" id="email" name="email" maxlength="255" placeholder="键入邮箱关键词">
                </div>
                <div class="form-group">
                    <input class="form-control" type="tel" id="tel" name="tel" maxlength="20" placeholder="键入手机号关键词">
                </div>
                <div class="form-group">
                    <input class="form-control" type="datetime-local" id="regtime1" name="regtime1" maxlength="20" placeholder="选择注册时间段起始时间">
                </div>
                <div class="form-group">
                    <input class="form-control" type="datetime-local" id="regtime2" name="regtime2" maxlength="20" placeholder="选择注册时间段终止时间">
                </div>
                <div class="form-group">
                    <select name="order" id="order" class="form-control">
                        <option value="likeproductnumber">收藏量降序</option>
                        <option value="money">资产降序</option>
                    </select>
                </div>
                <br/>
                <div class="btn-group doBtn" role="group" aria-label="...">
                    <button type="reset" class="btn btn-default findbutton">
                        <span class="glyphicon glyphicon-refresh" aria-hidden="true" style="color:deepskyblue;"></span>
                    </button>
                    <button type="button" class="btn btn-default findbutton" style="background:mediumpurple;" data-toggle="modal" data-target="#addModal">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:white;"></span>
                    </button>
                    <button type="submit" class="btn btn-default findbutton" style="background:hotpink;">
                        <span class="glyphicon glyphicon-search" aria-hidden="true" style="color:white;"></span>
                    </button>
                </div>
                <input name="pageNow" id="pageNow" type="hidden" value="1"/>
            </form>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<?php echo $message;?>
<?php if(isset($users)&&$users!=null&&!empty($users)){?>
<div class="userlist table-responsive" id="userlist">
<table class="table table-striped table-hover table-condensed table-bordered">
    <thead>
        <tr>
            <th style="width: 1%;">#</th>
            <th style="width: 14%;">名号</th>
            <th style="width: 15%;">邮箱</th>
            <th style="width: 10%;">手机</th>
            <th style="width: 15%;">注册时间</th>
            <th style="width: 10%;">收藏商品</th>
            <th style="width: 10%;">资产</th>
            <th style="width: 10%;">级别</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php
    foreach ($users as $user){
        $color="style='background-color:";
        switch($user['level']){
            case '-':$color.="#f2dede;'";break;
            case '0':$color.="lightgoldenrodyellow;'";break;
            case '1':$color="";break;
            case '2':$color.="darkseagreen;'";break;
            case '3':$color.="darkseagreen;'";break;
            case '4':$color.="lightskyblue;'";break;
            case '5':$color.="lightskyblue;'";break;
        }
        echo
            '<tr class="useritem" '.$color.' >'.
            "<td>".$user['uid']."</td>".
            "<td>".$user['uname']."</td>".
            "<td>".$user['email']."</td>".
            "<td>".$user['tel']."</td>".
            "<td>".$user['regtime']."</td>".
            "<td>".$user['likeproductnumber']."</td>".
            "<td>".$user['money']."</td>".
            "<td>".$user['level']."</td>".
            '<td><div class="btn-group doBtn" role="group" aria-label="...">'.
            ($user['level']!='-'?'<button type="button" class="btn btn-info" data-toggle="modal" data-target="#changeModal" data-uid="'.$user['uid'].'"><span class="glyphicon glyphicon-cog" aria-hidden="true"></button> '.
                '<button type="button" class="btn btn-danger" onclick="deluser(this);" value='.$user['uid'].'><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>'
                :'<button type="button" class="btn btn-success" onclick="recoveruser(this);" value='.$user['uid'].'><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span></button>').
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
        <?php if(isset($uname)){?>
        $("#uname").val("<?php echo trim($uname)?>");
        <?php }?>
        <?php if(isset($email)){?>
        $("#email").val("<?php echo trim($email)?>");
         <?php }?>
        <?php if(isset($tel)){?>
        $("#tel").val("<?php echo trim($tel)?>");
         <?php }?>
        <?php if(isset($regtime1)){?>
        $("#regtime1").val("<?php echo trim($regtime1)?>");
         <?php }?>
        <?php if(isset($regtime2)){?>
        $("#regtime2").val("<?php echo trim($regtime2)?>");
         <?php }?>
        <?php if(isset($order)){?>
        if(!select_option_checked("order","<?php echo $order?>")){
            select_option_checked("order","likeproductnumber");
        }
        <?php }else{?>
        select_option_checked("order","likeproductnumber");
        <?php }?>
    };
    $('#changeModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget) // Button that triggered the modal
        let uid = button.data('uid') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        let modal = $(this);
        $.getJSON("/admin/jsonuser?uid="+uid,function (data) {
            modal.find('#changeModalLabel').text('修改用户 ' + data.uname+' 的信息');
            modal.find('#uidModal').val(data.uid);
            modal.find('#unameModal').val(data.uname);
            modal.find('#emailModal').val(data.email);
            modal.find('#telModal').val(data.tel);
            modal.find('#moneyModal').val(data.money);
            select_option_checked("levelModal",data.level);
        });
    });

    $('#addModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
    });
    $("#changeuser").click(function(){
        let data='uid='+$('#uidModal').val()+'&uname='+$('#unameModal').val()+'&email='+$('#emailModal').val()+'&tel='+$('#telModal').val()+'&money='+$('#moneyModal').val()+'&level='+$('#levelModal').val();
        $.post("/admin/changeuser",data,function(result,status){
            let json=JSON.parse(result);
            echoMsg("#msg",json.status,json.result);
        });
    });

    $("#addeuser").click(function(){
        let data='uname='+$('#unameAdd').val()+'&password='+$('#passwordAdd').val()+'&email='+$('#emailAdd').val()+'&tel='+$('#telAdd').val()+'&money='+$('#moneyAdd').val()+'&level='+$('#levelAdd').val();
        $.post("/admin/adduser",data,function(result,status){
            let json=JSON.parse(result);
            echoMsg("#addmsg",json.status,json.result);
        });
    });
    function deluser(thisBtn){
        let data='uid='+$(thisBtn).val();
        $.post("/admin/deluser",data,function(result,status){
            let json=JSON.parse(result);
            if(echoMsg("#del",json.status,json.result)!==-1){
                window.location.reload();
            }
        });
    }
    function recoveruser(thisBtn){
        let data='uid='+$(thisBtn).val();
        $.post("/admin/recoveruser",data,function(result,status){
            let json=JSON.parse(result);
            if(echoMsg("#del",json.status,json.result)!==-1){
                window.location.reload();
            }
        });
    }
</script>
