<div>
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
        <?php echo $navigation;?>
    <?php }?>
</div>