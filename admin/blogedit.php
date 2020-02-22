<?php
session_start();
$pagefilename = basename(__FILE__, ".php");//取得本業面的黨名
include("../SQLC.inc.php");
$sql = "SELECT * FROM web ORDER BY `ID` ASC";
$result = mysqli_query($sqli,$sql);
$List = mysqli_num_rows($result);
$mate = array("", "", "", "");
for ($i = 0; $i < $List; $i++) {
    $search = mysqli_fetch_assoc($result);
    $mate[$i] = $search["tittle"];
}
//****************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-TW" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo "<title>文章編輯 - " . $mate[0] . "管理中心</title>"; ?>
    <script src="../hz_include/codes/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>

    <style type="text/css">
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-bottom: 0px;
            font-family: "微軟正黑體";
            background-color: #E9E9E9;
        }

    </style>
</head>

<body>
<div id="leftnav"> <?php include_once("include/admin-nav.php") ?></div>
<div id="tittle">文章編輯</div>
<div id="right"><?PHP
    if ($_SESSION["username"] != null && ($userlaw["Law_PostBlog"]>0)){
    ?>
    <form action="" method="post">
        <p><?php if ($_GET["id"] != null){
            $postId = $_GET["id"];
            $sql = "SELECT * FROM Blog where PostId ='$postId'";
            $result = mysqli_query($sqli,$sql);
            $thispost = mysqli_fetch_row($result);
            if ($thispost[8] != $b && $userlaw["Law_PostBlog"]==1 && $thispost[8] != null){
            ?>
            <div id="msg">
        <p><img src="../image/error.png"></p>
        <strong>您無權編輯此文章</strong>
</div>
<meta http-equiv=REFRESH CONTENT=3;url=blogm.php>
<?php }
else {
    ?> </p>
    <p>
    <?php
    $selectdo = $_POST["doing"];
    if ($selectdo == null) {
        ?>
        <div id="editLeft">
        <p><input id="title" class="form-control" name="title" type="text" value="<?php echo"$thispost[2]"; ?>"
                  size="50" maxlength="30"/></p>
        <p>
            <script>
                tinymce.init({
                    language: 'zh_TW',
                    selector: 'textarea',
                    height: '600',
                    plugins: [
                        "advlist autolink lists link image charmap print preview hr anchor codesample",
                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                        "insertdatetime media nonbreaking save table contextmenu directionality",
                        "emoticons template paste textcolor colorpicker textpattern imagetools",
                        "autoresize"
                    ],
                    toolbar1: "insertfile undo redo | formatselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table hr pagebreak blockquote codesample",
                    toolbar2: "bold italic underline strikethrough subscript superscript | forecolor backcolor charmap emoticons | link unlink image media | cut copy paste | insertdatetime fullscreen code",
                    menubar: false,
                    image_advtab: true,
                    relative_urls: false,
                    convert_urls: false,
                });
            </script>
            <label for="cont"></label>
            <textarea name="cont" id="cont" cols="139" rows="30" value=""><?php echo"$thispost[4]"; ?></textarea>
        </p>
        <div class="alert alert-warning" role="alert">
            請在可視化模式中儲存，否則不會儲存任何變更!
        </div>
        <div class="alert alert-danger" role="alert">
            刪除本文請小心使用，一但按下&quot;保存編輯&quot;將不會再詢問。
        </div>
        <div class="alert alert-danger" role="alert">
            若首次發表文章將權限設為private，就不會向您的追蹤者推播新文章通知。
        </div>
        </div>
        <div id="editRight">
            <p>
                文章ID：
                <?php
                if ($postId != 0) {
                    echo $thispost[0];
                } else {
                    echo "發表後取得";
                } ?></p>
            <p>發表日期：
                <label for="date"></label>
                <input name="date" id="bloginput" class="form-control" type="text" value="<?php
                if ($postId != 0) {
                    echo"$thispost[3]";
                } else if ($postId == 0) {
                    date_default_timezone_set('Asia/Taipei');
                    echo date("Y-m-d H:i:s");
                }
                ?>" size="20"/>
            </p>
            <p>公開權限：
                <label for="competance"></label>
                <select name="competance" id="competance">
                    <option value="public">public</option>
                    <option value="protect">protect</option>
                    <option value="private">private</option>
                </select>
                <script>
                    $('#competance option[value=<?php echo $thispost[1]; ?>]').attr('selected', 'selected');//自動取得目前設定值
                </script>
            </p>
            <p>閱讀權限：<input name="postaut" id="bloginput" class="form-control" type="text"
                           value="<?php echo $thispost[13] ?>" maxlength="3"><br>公開設定protect，0~999</p>
            <p>密碼保護：<input name="postpass" id="bloginput" class="form-control" type="text"
                           value="<?php echo $thispost[11] ?>" size="20"></p>
            <p>密碼提示：<input name="postpasshint" id="bloginput" class="form-control" type="text"
                           value="<?php echo $thispost[12] ?>" size="20"></p>
            <p>分類：
                <?php
                $sql_classes = "SELECT * FROM BClasses";
                $result_classes = mysqli_query($sqli,$sql_classes);
                $ClassesNum = mysqli_num_rows($result_classes);
                ?>
                <label for="Classes"></label>
                <select name="Classes" id="Classes">
                    <?php
                    for ($i = 0; $i < $ClassesNum; $i++) {
                        $classes = mysqli_fetch_assoc($result_classes); ?>
                        <option value="<?php echo $classes["ClassId"]; ?>"><?php echo $classes["ClassName"]; ?></option>
                    <?php } ?>
                    <script>
                        $('#Classes option[value=<?php echo $thispost[10]; ?>]').attr('selected', 'selected');//自動取得目前設定值
                    </script>
                </select>
            </p>
            <p>回覆： <label for="reply"></label>
                <select name="reply" id="reply">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <script>
                    $('#reply option[value=<?php echo $thispost[7]; ?>]').attr('selected', 'selected');//自動取得目前設定值
                </script>
            </p>
            <p>發表：<?php
                if ($postId == 0) {
                    echo $predata[4];
                } else if ($postId > 0) {
                    echo $thispost[5];
                }
                ?>
            <p>操作：
                <label for="doing"></label>
                <select name="doing" id="doing">
                    <option>儲存本文</option>
                    <?php
                    if ($postId != 0) {
                        echo "<option>刪除本文</option>";
                    }
                    ?>
                </select>

            <p><label for="doing"></label>
                <input type="submit" name="button" class="btn btn-primary" value="保存"/>
                ｜<a href="../upload.php?id=blog" target="_blank">上傳圖片</a>
            </p>
        </div>


        <p>&nbsp;<br/></p>
        </form>

        <?php
    } else if ($selectdo == "刪除本文") {
        $sql = "DELETE FROM Blog WHERE PostId='$postId'";
        //刪除該篇回復
        $sqlr="DELETE FROM reply WHERE PostId='$postId'";
        //刪除該篇的所有收藏
        $sqlf="DELETE FROM favpost WHERE postid='$postId'";
        //刪除該篇的所有喜歡
        $sqll="DELETE FROM likepost WHERE postid='$postId'";
        $result = (mysqli_query($sqli,$sql) && mysqli_query($sqli,$sqlr) && mysqli_query($sqli,$sqlf) && mysqli_query($sqli,$sqll));
        if ($result) {
            include("msg/succ_msg.php");
        } else {
            include("msg/error_msg.php");
        }
    } else if ($selectdo == "儲存本文") {
        if ($postId > 0) {
            $title = addslashes($_POST["title"]);
            $date = $_POST["date"];
            $comp = $_POST["competance"];
            $cont =  addslashes($_POST["cont"]);
            $classID = $_POST["Classes"];
            $postpass = addslashes($_POST["postpass"]);
            $postpasshint=addslashes($_POST["postpasshint"]);
            $postaut=addslashes($_POST["postaut"]);
            //取得分類名稱
            $sqlClassName = "SELECT * FROM `BClasses` WHERE ClassId = '$classID'";
            $resultClassName = mysqli_query($sqli,$sqlClassName);
            $ClassNameArray = mysqli_fetch_array($resultClassName);
            $ClassName = $ClassNameArray["ClassName"];

            $Reply = $_POST["reply"];
            $sql = "UPDATE Blog SET Competence = '$comp',PostTittle='$title',PostDate='$date',PostContant='$cont',Classes='$ClassName',Reply='$Reply',ClassId='$classID',Password='$postpass',PasswordHint='$postpasshint',ReadAut='$postaut' WHERE `Blog`.`PostId` = $postId";
            $result = mysqli_query($sqli,$sql);
            if ($result) {
                include("msg/succ_msg.php");
            } else {
                include("msg/error_msg.php");
            }
        } else if ($postId == 0) {
            include_once("../hz_include/func_notice.php");

            $title = addslashes($_POST["title"]);
            $date = $_POST["date"];
            $comp = $_POST["competance"];
            $cont = addslashes($_POST["cont"]);
            $classID = $_POST["Classes"];
            $postpass = $_POST["postpass"];
            $postpasshint=addslashes($_POST["postpasshint"]);
            //取得分類名稱
            $sqlClassName = "SELECT * FROM `BClasses` WHERE ClassId = '$classID'";
            $resultClassName = mysqli_query($sqli,$sqlClassName);
            $ClassNameArray = mysqli_fetch_array($resultClassName);
            $ClassName = $ClassNameArray["ClassName"];

            $UserName = $predata[4];
            $Reply = $_POST["reply"];
            $sql = "INSERT INTO Blog (Competence,PostTittle,PostDate,PostContant,Classes,User,Reply,UserID,ClassId,Password,PasswordHint,ReadAut) VALUES ( '$comp','$title','$date','$cont' ,'$ClassName','$UserName','$Reply','$b','$classID','$postpass','$postpasshint','$postaut')";
            $result = mysqli_query($sqli,$sql);
            $lastid=mysqli_insert_id($sqli);//短期內沒影響
            if ($result) {
                sendFollowMes($b,$lastid,$title,$comp);
                include("msg/succ_msg.php");
            } else {
                include("msg/error_msg.php");
            }
        }
    }
} ?>
<?php
}
}
else {
    include("msg/error_msg.php");
    ?>
    <p>&nbsp;</p>
<?php } ?>
</div>
</body>
</html>