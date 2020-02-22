<?php
/****************************
 * -->使用者登入狀態確認
 * -->設計:hazuya
 * -->2018/2/17
 ****************************/

/**************************
 * 請先確認session_start();
 * 已被加入該頁面
 *************************/

/*************************
 ***************************/
/*TOKEN生成*/
function madetoken()
{
    $length = rand(10, 50);
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * @param $pw
 * @return bool|string
 */
function hashpw($pw)
{
    return password_hash($pw, PASSWORD_DEFAULT);
}

function needRehash($username,$pw){
    global $sqli;
    $result = mysqli_query($sqli, "SELECT * FROM admin where username = '$username'");
    $userpw = mysqli_fetch_array($result);
    if(password_needs_rehash($userpw["pw"], PASSWORD_DEFAULT)){
        return true;
    }else
        return false;
}

/**
 * @param $username
 * @param $pw
 */
function identifyPw($username, $pw)
{
    global $sqli;
    $result = mysqli_query($sqli, "SELECT * FROM admin where username = '$username'");
    $userpw = mysqli_fetch_array($result);
    if($userpw["Position"] != null) {
        if ($userpw["Position"] == "on") {
            if (password_verify($pw, $userpw["pw"]))
                return true;
            /*elseif(md5($pw)===$userpw["pw"])//暫時的
                return true;*/
            else
                return false;
        } else {
            return 2;
        }
    }else{
        return false;
    }

}

if ($_SESSION["username"] == null) { //當SESSION為空時，檢查使用者是否記住登入狀態
    if (($_COOKIE["u"] != null && $_COOKIE["t"] != null)) {    //檢查使用者名稱不為空
        $userid = $_COOKIE["u"];
        $result_u = mysqli_query($sqli, "SELECT * FROM admin WHERE admin.username='$userid'");
        $user = mysqli_fetch_array($result_u);
        if ($user["LoginToken"] === $_COOKIE["t"] && $user["Position"] == "on") {
            $_SESSION["username"] = $userid;
            date_default_timezone_set('Asia/Taipei');
            $lastDate = date("Y-m-d H:i:s");
            $sqlDate = mysqli_query($sqli, "UPDATE admin SET LastDate ='$lastDate' WHERE admin.username='$userid'");
            $IP = $_SERVER['HTTP_CLIENT_IP'] . "/" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "/" . $_SERVER['HTTP_X_FORWARDED'] . "/" . $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] . "/" . $_SERVER['HTTP_FORWARDED_FOR'] . "/" . $_SERVER['HTTP_FORWARDED'] . "/" . $_SERVER['REMOTE_ADDR'] . "/" . $_SERVER['HTTP_VIA'];
            $sqlip = mysqli_query($sqli, "UPDATE admin SET LastIPdata = '$IP' WHERE admin.username='$userid'");

        }

    }
}

?>