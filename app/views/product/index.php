<style>
    .product{
        position: relative;
        text-align: center;
        padding: 20px;
    }
    .recommend{
        position: relative;
        text-align: center;
        padding: 10px;
    }
    .comment{
        text-align: center;
        position: relative;
        padding: 10px;
    }
    .editor{
        padding: 10px;
    }
    .editor #title {
        text-align: center;
        font-size: 20px;
    }
    .editor #comment{
        font-size: 18px;
    }
    .editor .btn-success{
        font-size: 20px;
    }
    .doBtn span{
        font-size: 24px;
    }
</style>

</head>
<body>
<?php include APP_PATH . "/app/views/header.php" ?>
<div id="msg"></div>
<?php
use library\MessageBox;
?>
<div class="product col-md-8">
    <div class="product">
        <?php
        if(isset($product)){
            $kidsStr="";
            if(isset($keys)){
                $kids=json_decode($keys,true);
                foreach ($kids as $kid){
                    $kidsStr.="<a href='/product/list?keys=[\"".$kid['kid']."\"]' target='_blank'>".$kid['kname']."</a> ";
                }
            }
            echo "<h2>".$product['pname']."</h2>".
                "<h4>".$kidsStr
                ."</h4>".
                "<p style='font-size: 18px;'>商品库存量：".$product['number']."</p>".
                "<p style='font-size: 18px;'>商品销售量：".$product['sellnumber']."</p>".
                "<p style='font-size: 18px;'>商品收藏量：".$product['likednumber']."</p>".
                "<p style='font-size: 18px;'>商品单价：".$product['price']."</p>".
                "<p style='font-size: 18px;'>商品详情：".$product['describe']."</p>".
                '<form class="form-inline">
                <label class="control-label" for="number">购买数量：</label>
                <input class="form-control" min="1" max="'.$product['number'].'" type="number" name="number" id="number" value="0" required>
                </form>
                <div class="btn-group doBtn" role="group" aria-label="...">
                <button style="display:'.(!isset($like)||$like==0?"unset":"none").';" type="button" class="btn btn-default" value="'.$product['pid'].'" id="addlikeBtn">
                    <span id="addlikeICO" class="glyphicon glyphicon-heart-empty" aria-hidden="true" style="color:hotpink;"></span>
                </button>
                <button style="display:'.(!isset($like)||$like==0?"none":"unset").';" type="button" class="btn btn-default" value="'.$product['pid'].'" id="dellikeBtn">
                    <span id="dellikeICO" class="glyphicon glyphicon-heart" aria-hidden="true" style="color:hotpink;"></span>
                </button>
                <button type="button" class="btn btn-default" value="'.$product['pid'].'" id="addPayBtn">
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" style="color:lightseagreen;"></span>
                </button>
            </div>';
        }
        ?>
    </div>

    <div class="comment">
        <?php echo $message;?>
        <?foreach ($comments as $comment){?>
            <blockquote>
                <p style="text-align: left;"><?php echo ($comment['tip']==""?"什么评论都没有留下~":$comment['tip']); ?></p>
                <footer style="text-align: right;"><?php echo $comment['uname'];?></footer>
            </blockquote>
        <?php }?>
        <?php echo $navigation;?>
    </div>
</div>

<div class="recommend col-md-4">
    <p>推荐</p>
</div>



<script>
    function comment(){
        if(!$('#comment').val()){
            return false;
        }
    }

    $('#addlikeBtn').click(function () {
        $.getJSON("https://store.xinball.top/user/addlike?pid="+$(this).val(),function (data) {
            if(echoMsg("#msg",data.status,data.result)===1){
                $('#dellikeBtn').show();
                $('#addlikeBtn').hide();
            }
        });
    });
    $('#dellikeBtn').click(function () {
        $.getJSON("https://store.xinball.top/user/dellike?pid="+$(this).val(),function (data) {
            if(echoMsg("#msg",data.status,data.result)===1){
                $('#dellikeBtn').hide();
                $('#addlikeBtn').show();
            }
        });
    });
    $('#addPayBtn').click(function () {
        $.getJSON("https://store.xinball.top/user/addpay?pid="+$(this).val()+"&number="+$('#number').val(),function (data) {
            echoMsg("#msg",data.status,data.result)!==-1
        });
    });
</script>
