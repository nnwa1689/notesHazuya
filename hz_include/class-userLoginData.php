<?php
/****************************
 * -->使用者資料查詢模組
 * -->設計:hazuya
 * -->2018/2/10
 ****************************/

/**************************
 * 請先確認session_start();
 * 已被加入該頁面
 *************************/

/*************************
 * ->變數:predata
 * 0:使用者名稱
 * 2:email
 * 3:網頁
 * 4:名稱
 * 5:權限
 * 6:帳戶狀態
 * 7:頭像
 * 8:使用者介紹
 * 9:個人資料頁公開狀態
 * 10:個人資料頁背景
 * 11:最後登入IP資料
 * 12:最後登入日期
 * 13:EMAIL公開狀態
 * 14:個人網站公開狀態
 * 15:使用者記住登入狀態
 * 16:簽名內容
 * 17:顯示簽名
 ***************************/

$b = $_SESSION["username"];
if ($b != null) {
    $sqlpre = "SELECT * FROM admin WHERE admin.username='$b'";
    $resultpre = mysqli_query($sqli,$sqlpre);
    $predata = mysqli_fetch_row($resultpre);
    /*當使用者帳戶被關閉時清除登入狀態*/
    if ($predata[6] != "on") {
        $_SESSION["username"] = null;
    }
    //使用者不存在時清除SESSION
    if($predata[0]==null){
        $_SESSION["username"] = null;
    }
}
function follow($follow, $followed)
{
    global $sqli;
    if (mysqli_num_rows(mysqli_query($sqli,"SELECT * FROM followRelationship WHERE follow_UserName='$follow' AND followed_UserName='$followed'")) == 0 && $follow != $followed) {
        if (mysqli_query($sqli,"INSERT INTO followRelationship(follow_UserName,followed_UserName) VALUES ('$follow','$followed') ")) {
            return 1;
        } else
            return 0;
    } else
        return 0;
}
?>