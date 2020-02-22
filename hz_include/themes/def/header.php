<?php
require_once ("SQLC.inc.php");
require_once (__DIR__."/../../../vendor/autoload.php");
include_once("hz_include/class-meta.php");
include_once("hz_include/themes/def/msg.php");
session_start();
if (($_SERVER['QUERY_STRING'] != null) && ($pagefilename!="blog"&&$pagefilename!="search")) :
    $nowURL = $pagefilename . "?" . $_SERVER['QUERY_STRING'];
else :
    $nowURL = $pagefilename;
endif; ?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="icon" type="image/png" href="favicon.png"/>
    <link href="hz_include/themes/def/fontawesome-all.css" rel="stylesheet">
    <?php
    include_once("hz_include/class-title.php");
    ?>
    <meta name="description" content="<?php echo $mate[2]; ?>">
    <meta name="keywords" content="<?php echo $mate[1]; ?>">
    <link href="hz_include/common/css/bootstrap.css" rel="stylesheet">
    <!--<link href="hz_include/themes/def/tocas.css" rel="stylesheet">-->
    <link href="hz_include/themes/def/Style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="hz_include/codes/styles/prism.css">
    <script src="hz_include/codes/prism.js"></script>
    <script src="hz_include/common/js/jquery-3.3.1.min.js"></script>

    <? if ($mate[10] == "ShowTopButton") : ?>
        <div id="topbottom" href="#top"><i class="fas fa-chevron-up"></i></div>
        <script>
            console.log("%c不要看啦，人家會害羞>__<", "color: blue; font-size: 30px;");

            $("#topbottom").click(function () {
                $("html,body").animate({scrollTop: 0}, "slow");
                return false;
            });

           /* $(document).ready(function () {
                    if ($(window).scrollTop() < 1) {
                        $('#topbottom').stop().fadeOut("fast");
                    }
                }
            )
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('#topbottom').fadeIn("fast");
                } else {
                    $('#topbottom').stop().fadeOut("fast");
                }
            });*/
        </script>
    <?php endif; ?>

    <?php
    echo $mate[4];//頭部自訂代碼
    include_once("hz_include/class-userLoginToken.php");
    include_once("hz_include/class-userLoginData.php");
    include_once("hz_include/userlawCheck.php");
    //維護模式
    if ($mate[17] != "on") :
        if ($_SESSION["username"] != null && $userlaw["Law_Visit"] == 1) :

        else :
            header('Location: fix');
            exit;
        endif;
    endif;
    ?>
</head>