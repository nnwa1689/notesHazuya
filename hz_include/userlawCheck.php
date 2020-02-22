<?php
/**
 * userLawCheck 用戶權限檢查
 * 請注意是否已經載入資料庫檔案
 * 請載入SESSION();
 * 請將本模組放於class-LoginToken與LoginData之下，以免檢查不到
 * Date: 2018/4/29
 * Time: 下午 09:35
 *
 * userlaw陣列回傳用戶組各個權限，如下
 * Lawname:該用戶組名稱
 * Law_Read:前台閱讀(0-999)
 * Law_PostReply:允許回覆(0,1)
 * Law_Visit:是否允許維護模式訪問(0,1)
 * Law_ControlVisit:允許訪問後台(0,1)
 * Law_admin:允許修改網站整體(0,1)
 * Law_web:允許發布公告與站內公告信(不受前台站內信影響)(0,1)
 * Law_page:允許新增編輯網站頁面(0,1)
 * Law_navigate:允許管理網站導航(0,1)
 * Law_member:允許刪除或更改網站會員(0,1)
 * Law_PostBlog:允許部落格文章編輯(0:不允許,1:允許編輯自己所發之文章,2:允許編輯所有)
 * Law_BlogClass:允許管理文章分類(0,1)
 * Law_filem:允許管理媒體庫(0,1)
 * Law_reply:允許管理回覆(0,1)
 * Law_msg:允許管理站內信(0,1)
 * Law_mods:允許管理網站模組(0,1)
 * Law_Upload:允許上傳檔案(0,1)
 *
 * 其他:
 * 為使查詢方便，另提供userLawName();方法，輸入用戶組ID可以返回該用戶組名稱
 * 另提供userLawFind()找尋特定用戶之權限內容，而非登入用戶之權限
 *
 */
//判斷是否登入
if($_SESSION["username"] != null){
    //登入狀態去檢查用戶所屬之用戶組
    $userAuthority=$predata[5];
    $userlaw=mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM userlaw WHERE Law_ID='$userAuthority'"));
}else if($_SESSION["username"] == null){
    //若為非登入則指定為guest
    $userlaw=mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM userlaw WHERE Law_ID='4'"));
}

function userLawFind($userLawID){
    global $sqli;
    if(($otheruserlaw=mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM userlaw WHERE Law_ID='$userLawID'")))!=null)
        return $otheruserlaw;
    else
        return 0;
}

function userLawName($userLawID){
    global $sqli;
    if(($userLawName=mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM userlaw WHERE Law_ID='$userLawID'")))!=null)
        return $userLawName["Lawname"];
    else
        return 0;
}
?>