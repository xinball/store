<?php


namespace library;


class MessageBox
{
    ///1
    public static function echoSuccess($msg,$time=5000): string
    {
        return '<div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span><span class="sr-only">Success:</span> '.$msg
            .'</div><script>window.setTimeout(function(){$(".alert-dismissible").children("button").alert("close");},'.$time . ');</script>';
    }
    ///2
    public static function echoInfo($msg,$time=5000): string
    {
        return '$(document.body).append(\''.
            '<div class="alert alert-info alert-dismissible" style="position: fixed;width: 100%;z-index: 1000;top: 0;" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span><span class="sr-only">Info:</span> '.$msg.'</div>\');
            window.setTimeout(function(){$(".alert-dismissible").children("button").alert("close");},'.$time.');';
    }
    ///3
    public static function echoWarning($msg): string
    {
        return '<div class="alert alert-warning" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Warning:</span> '.$msg
                .'</div>';
    }
    ///4
    public static function echoDanger($msg,$time=5000): string
    {
        return '$(document.body).append(\''.
            '<div class="alert alert-danger alert-dismissible" role="alert" style="position: fixed;width: 100%;z-index: 1000;top: 0;" ><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span><span class="sr-only">Danger:</span> '.$msg.'</div>\');
            window.setTimeout(function(){$(".alert-dismissible").children("button").alert("close");},'.$time.');';
    }
}