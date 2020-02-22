<?php
/**
 * 站內信函數測試版
 * User: user
 * Date: 2018/5/6
 * Time: 下午 11:41
 *
 */

function sendMessage($title, $date, $cont, $catcher, $sender)
{
    global $sqli;
    if ($catcher != $sender) {
        $searchUser = mysqli_query($sqli,"SELECT * FROM `admin` WHERE username='$catcher'");
        if (mysqli_num_rows($searchUser) > 0)
            $haveuser = true;
        else
            $haveuser = false;
        if ($haveuser)
            $sendsql = mysqli_query($sqli,"INSERT INTO `Message`(`MessageTitle`, `SenderId`, `CatcherId`, `MessageText`, `MessageTime`) VALUES 	
						('$title','$sender','$catcher','$cont','$date')");
        else
            $sendsql = false;

        if ($sendsql)
            return true;
        else
            return false;
    }
}

?>