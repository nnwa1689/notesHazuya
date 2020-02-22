<?php
function sendFollowMes($user, $postid, $posttitle, $comp)
{
    if($comp=="private"){
        return 0;
    }else {
        global $sqli;
        date_default_timezone_set('Asia/Taipei');
        $date = date("Y-m-d H:i:s");
        $cont = '您追蹤的用戶' . $user . '發表了一篇文章，快去看看吧！<br><a href="blog?id=' . $postid . '">' . $posttitle . '</a>';
        $resultmem = mysqli_query($sqli, "SELECT * FROM followRelationship WHERE followed_UserName='$user'");
        while ($flouser = mysqli_fetch_array($resultmem)) {
            $value = $flouser["follow_UserName"];
            $sendsql = "INSERT INTO `SystemNotice`(`Title`, `Catcher`, `NoticeTime`) VALUES 	
					('$cont','$value','$date')";
            $resultsend = mysqli_query($sqli, $sendsql);
            if ($resultsend != true) {
                $sendflag = false;
            }

        }
    }

}

function myPostReplied($replyId, $replyusername, $writerid, $title, $postid)
{
    global $sqli;
    if ($replyId != $writerid) {
        $date = date("Y-m-d H:i:s");
        $cont = $replyusername . '對你發表的\"<a href="blog?id=' . $postid . '">' . $title . '</a>\"發表了新的看法，快去看看吧!';
        $sendsql = "INSERT INTO `SystemNotice`(`Title`, `Catcher`, `NoticeTime`) VALUES 	
					('$cont','$writerid','$date')";
        $resultsend = mysqli_query($sqli, $sendsql);
    }

}

function replyReplied($replyID,$replyusername, $repliedid, $posttitle, $postid, $Reply_parent)
{
    global $sqli;
    if($replyID!=$repliedid) {
        $date = date("Y-m-d H:i:s");
        $cont = $replyusername . '對你在\"<a href="blog?id=' . $postid . '#reply' . $Reply_parent . '">' . $posttitle . '</a>\"發表的回覆提出了新的看法，快去看看吧!';
        $sendsql = "INSERT INTO `SystemNotice`(`Title`, `Catcher`, `NoticeTime`) VALUES 	
					('$cont','$repliedid','$date')";
        $resultsend = mysqli_query($sqli, $sendsql);
    }
}

function clearNotice($userid)
{
    global $sqli;
    date_default_timezone_set('Asia/Taipei');
    $today = date("Y-m-d");
    $sql = mysqli_query($sqli, "SELECT * FROM SystemNotice WHERE Catcher = '$userid' ORDER BY ID DESC");
    while ($notic = mysqli_fetch_array($sql)) {
        $mydate = DateTime::createFromFormat("Y-m-d", $notic["NoticeTime"]);

        if ((floor($today - $mydate) / 86400) > 7) {
            $id = $notic["ID"];
            $del = mysqli_query($sqli, "DELETE FROM SystemNotice WHERE ID='$id'");
            if (!($del)) {
                exit("ERROR!!!");
            }
        }

    }

}