<?php

namespace library;

class Pager
{
    public $arr;//显示数据
    public $rowCount;//数据库获取

    public $pageSize=10;
    public $pageCount;//计算所得

    public $pageNow;//用户指定
    public $pageNum;//用户指定

    /**
     * Pager constructor.
     * @param $pageNow
     * @param $navigation
     */
    public function __construct($pageNow,$pageSize=10,$pageNum=5)
    {
        $this->pageNow = $pageNow;
        $this->pageSize = $pageSize;
        $this->pageNum = $pageNum;
    }
    public function echoMessage($error,$successFlag=1,$psFlag=1):string
    {
        if($this->pageCount==0){
            return MessageBox::echoWarning($error);
        }
        $ps="";
        if($this->pageCount>$this->pageNum&&$psFlag)
            $ps="<br/>页面太多了？底部页码处输入页数快速到达ლ(╹◡╹ლ)！";
        if($successFlag)
            return MessageBox::echoSuccess('我们为您找到了 <strong>'.$this->rowCount.'</strong> 条符合条件的记录ヾ(^∀^)ﾉ<br/>'.$ps);
        else
            return "";
    }
    public function echoNavigation($navigation): string
    {
        if($this->pageCount==0){
            return "";
        }
        $this->pageNow=$this->pageNow>$this->pageCount?$this->pageCount:$this->pageNow;
        $pagination="";
        $pager="";
        $pagenum1=(int)($this->pageNum/2);
        $preCount=$this->pageNow>$pagenum1?$this->pageNow-$pagenum1:1;
        $nextCount=$preCount+$this->pageNum-1<$this->pageCount?$preCount+$this->pageNum-1:$this->pageCount;
        if($preCount-$pagenum1-1>0)
            $pagination.='<li><a href=\''.$navigation.($preCount-$pagenum1-1).'\'><<</a></li>';
        else
            $pagination.='<li><a href=\''.$navigation.'1\'><<</a></li>';
        for($i=$preCount;$i<$this->pageNow;++$i){
            $pagination.='<li><a href=\''.$navigation.$i.'\'>'.$i.'</a></li>';
        }
        $pagination.='<li class="active"><span style="height: 34px;"><label><input id="pageTo" type="number" value="'.$i.'" min="1" max="'.$this->pageCount.'" />/<span style="font-size:12px;">'.$this->pageCount.'</span></label><span></li>';
        for($i=$this->pageNow+1;$i<=$nextCount;++$i){
            $pagination.='<li><a href=\''.$navigation.$i.'\'>'.$i.'</a></li>';
        }
        if($nextCount+$pagenum1+1<$this->pageCount)
            $pagination.='<li><a href=\''.$navigation.($nextCount+$pagenum1+1).'\'>>></a></li>';
        else
            $pagination.='<li><a href=\''.$navigation.$this->pageCount.'\'>>></a></li>';

        if($this->pageNow>1){
            $pager.='<li class="previous"><a href=\''.$navigation.($this->pageNow-1).'\'>上一页</a></li>';
        }else{
            $pager.='<li class="previous disabled"><span>上一页</span></li>';
        }
        if($this->pageNow<$this->pageCount){
            $pager.='<li class="next"><a href=\''.$navigation.($this->pageNow+1).'\'>下一页</a></li>';
        }else{
            $pager.='<li class="next disabled"><span>下一页</span></li>';
        }

        return
        '<nav aria-label="Page navigation">
              <ul class="pagination">
              '.$pagination.'
              </ul>
         </nav>
         <nav aria-label="Page navigation">
          <ul class="pager">
              '.$pager.'
          </ul>
         </nav>
         <script>
        $("#pageTo").keydown(function(e){
            if (e.which === 13) {
                window.location.href=\''.$navigation.'\'+$("#pageTo").val();
            }
        });
        </script>';
    }
    public function echoNavPage($navigation,$funStr): string
    {
        if($this->pageCount==0){
            return "";
        }
        $this->pageNow=$this->pageNow>$this->pageCount?$this->pageCount:$this->pageNow;
        $pagination="";
        $pager="";
        $pagenum1=(int)($this->pageNum/2);
        $preCount=$this->pageNow>$pagenum1?$this->pageNow-$pagenum1:1;
        $nextCount=$preCount+$this->pageNum-1<$this->pageCount?$preCount+$this->pageNum-1:$this->pageCount;
        if($preCount-$pagenum1-1>0)
            $pagination.='<li><a onclick="return '.$funStr.'(this);" href=\''.$navigation.($preCount-$pagenum1-1).'\'><<</a></li>';
        else
            $pagination.='<li><a onclick="return '.$funStr.'(this);" href=\''.$navigation.'1\'><<</a></li>';
        for($i=$preCount;$i<$this->pageNow;++$i){
            $pagination.='<li><a onclick="return '.$funStr.'(this);" href=\''.$navigation.$i.'\'>'.$i.'</a></li>';
        }
        $pagination.='<li class="active"><span style="height: 34px;"><label><input id="pageTo" type="number" value="'.$i.'" min="1" max="'.$this->pageCount.'" />/<span style="font-size:12px;">'.$this->pageCount.'</span></label><span></li>';
        for($i=$this->pageNow+1;$i<=$nextCount;++$i){
            $pagination.='<li><a onclick="return '.$funStr.'(this);" href=\''.$navigation.$i.'\'>'.$i.'</a></li>';
        }
        if($nextCount+$pagenum1+1<$this->pageCount)
            $pagination.='<li><a onclick="return '.$funStr.'(this);" href=\''.$navigation.($nextCount+$pagenum1+1).'\'>>></a></li>';
        else
            $pagination.='<li><a onclick="return '.$funStr.'(this);" href=\''.$navigation.$this->pageCount.'\'>>></a></li>';

        if($this->pageNow>1){
            $pager.='<li class="previous"><a onclick="return '.$funStr.'(this);" href=\''.$navigation.($this->pageNow-1).'\'>上一页</a></li>';
        }else{
            $pager.='<li class="previous disabled"><span>上一页</span></li>';
        }
        if($this->pageNow<$this->pageCount){
            $pager.='<li class="next"><a onclick="return '.$funStr.'(this);" href=\''.$navigation.($this->pageNow+1).'\'>下一页</a></li>';
        }else{
            $pager.='<li class="next disabled"><span>下一页</span></li>';
        }

        return
        '<nav aria-label="Page navigation">
              <ul class="pagination">
              '.$pagination.'
              </ul>
         </nav>
         <nav aria-label="Page navigation">
          <ul class="pager">
              '.$pager.'
          </ul>
         </nav>';
    }
}