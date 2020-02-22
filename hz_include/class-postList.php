<?php
/****************************
 * -->Blog文章列表(含UI)、文章輸出物件
 * -->設計:hazuya
 * -->2018/2/20
 ****************************/

/**************************
 * printpost->傳入:SQL指令、每頁顯示文章數
 * 每頁顯示數量:若輸入0則代表不分頁
 * 直接輸出文章列表
 *************************/

/*************************
 ***************************/
require_once (__DIR__."/../vendor/autoload.php");
use Carbon\Carbon;//需要將USE套回HEADER檔案，不知道為什麼HEADER先被INCLUDE近來但是這個文件讀不到namespace use
Carbon::setLocale('zh-TW');
class post
{
    private $sqlresult, $type;
    public $numViews, $likesnum, $favnum, $postid, $competence, $posttitle, $postdate, $postcontant, $user, $classes, $classname, $reply, $userid, $classid, $password, $passwordhint, $readaut;
    public $wAvatar, $wSignature, $wSignatureshow, $wName,$Permissions;

    /**
     * @param $type :blog,homepost
     */
    function __construct($Nowid, $type)
    {
        global $sqli;
        $this->type = $type;
        if ($this->type == "blog") {
            $this->sqlresult = "SELECT * FROM Blog where Blog.PostId='$Nowid'";
            $this->likesnum = mysqli_num_rows(mysqli_query($sqli,"SELECT * FROM `likepost` WHERE postid='$Nowid'"));
            $this->favnum = mysqli_num_rows(mysqli_query($sqli,"SELECT * FROM `favpost` WHERE postid='$Nowid'"));
        } elseif ($this->type == "homepost")
            $this->sqlresult = "SELECT * FROM HomePost where HomePost.PostId='$Nowid'";

        if ($array = mysqli_fetch_row(mysqli_query($sqli,$this->sqlresult))) {
            if ($this->type == "blog") {
                $this->postid = $array[0];
                $this->competence = $array[1];
                $this->posttitle = $array[2];
                $this->postdate = $array[3].'（'.Carbon::createFromFormat('Y-m-d H:i:s',$array[3])->diffForHumans().'）';
                $this->postcontant =  $array[4];
                $this->user = $array[5];
                $this->classes = $array[6];
                $this->reply = $array[7];
                $this->userid = $array[8];
                $this->numViews = $array[9];
                $this->classid = $array[10];
                $this->password = $array[11];
                $this->passwordhint = $array[12];
                $this->readaut = $array[13];
                $ClassNameArray = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM `BClasses` WHERE ClassId = '$this->classid'"));
                $this->classname = $ClassNameArray["ClassName"];
                $writersql = mysqli_fetch_row(mysqli_query($sqli,"SELECT * FROM admin WHERE admin.username='$this->userid'"));
                $this->wAvatar = $writersql[7];
                $this->wSignature = $writersql[16];
                $this->wSignatureshow = $writersql[17];
                $this->wName = $writersql[4];
                $this->Permissions=$writersql[5];
            } elseif ($this->type == "homepost") {
                $this->postid = $array[0];
                $this->competence = $array[1];
                $this->posttitle = $array[2];
                $this->postdate = $array[3].'（'.Carbon::createFromFormat('Y-m-d',$array[3])->diffForHumans().'）';
                $this->postcontant = $array[4];
            }
        } else
            return false;
    }

    function updateNumViews()
    {
        global $sqli;
        if (!isset($_COOKIE[$this->postid . "read"])) {
            $this->numViews += 1;
            if ($viewSql = mysqli_query($sqli,"UPDATE Blog SET NumViews = '$this->numViews' WHERE PostId='$this->postid'")) {
                setcookie($this->postid . "read", "1", time() + 3600);
                return true;
            } else
                return false;
        }
    }

    function verification($userlaw)
    {
        global $sqli;
        if (($this->competence == "public") || ($this->competence == "protect" && ($userlaw["Law_Read"] >= $this->readaut))) {
            return true;
        } else {
            return false;
        }
    }

    function idenpassword($password)
    {
        global $sqli;
        if (($_POST["postpass"] != null) && ($password == $this->password) && preg_match("/^([0-9A-Za-z\x7f-\xff]+)$/", $password)) {
            $_SESSION[$this->postid . "passed"] = $this->password;
            header('Location: blog?id=' . $this->postid);
        }
    }

    function getLeftPost()
    {
        global $sqli;
        if ($lrs = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM Blog WHERE PostId<'$this->postid' AND (Competence='public' OR Competence='protect') ORDER BY PostId DESC LIMIT 0,1"))) {
            return $lrs;
        }else
            return false;
    }

    function getNextPost()
    {
        global $sqli;
        if ($nrs = mysqli_fetch_array(mysqli_query($sqli,"SELECT * FROM Blog WHERE PostId>'$this->postid' AND (Competence='public' OR Competence='protect') ORDER BY PostId ASC LIMIT 0,1"))) {
            return $nrs;
        }else
            return false;
    }
}

function printpost($sql, $per,$pagefilename)
{
    global $sqli;
    $q = $_GET["q"];
    $classID = $_GET["classid"];
    $conandtit=$_GET["conandtit"];
    if($pagefilename=="person"){
        $id=$_GET["id"];
        $act=$_GET["act"];
    }
    if ($per > 0) {
        include_once("hz_include/themes/def/pagenum.php");
    } else {
        $result = mysqli_query($sqli,$sql);
    }
    while ($OPost = mysqli_fetch_array($result)) {
        if ($OPost["Competence"] == "public" || $OPost["Competence"] == "protect") {
            $numReplyPostId = $OPost["PostId"];
            $numReplySql = "SELECT * FROM `reply` WHERE PostId = '$numReplyPostId' AND Competence = 'public' ORDER BY `reply`.`ReplyId` ASC";
            $numReplyResult = mysqli_query($sqli,$numReplySql);
            $numReply = mysqli_num_rows($numReplyResult);
            //取得分類名稱
            $classID = $OPost["ClassId"];
            $sqlClassName = "SELECT * FROM `BClasses` WHERE ClassId = '$classID'";
            $resultClassName = mysqli_query($sqli,$sqlClassName);
            $ClassNameArray = mysqli_fetch_array($resultClassName);
            $ClassName = $ClassNameArray["ClassName"];
            //取得發文者名稱
            $puserid = $OPost["UserID"];
            $sqlpuser = "SELECT * FROM admin WHERE admin.username='$puserid'";
            $resultpuser = mysqli_query($sqli,$sqlpuser);
            $puser = mysqli_fetch_row($resultpuser);
            //計算喜歡人次
            $likesnum = mysqli_num_rows(mysqli_query($sqli,"SELECT * FROM `likepost` WHERE postid='$numReplyPostId'"));
            //計算收藏人次
            $favnum = mysqli_num_rows(mysqli_query($sqli,"SELECT * FROM `favpost` WHERE postid='$numReplyPostId'"));
            //日期
            //$postdate = strtotime($OPost["PostDate"]);
            $postdate=Carbon::createFromFormat('Y-m-d H:i:s',$OPost["PostDate"]);
            $postdateymd=$postdate->format("Y-m-d");
            //$postday=$postdate->diffForHumans();
            ?>

            <div id="HomePostList">
                <div id="PostDate">
                    <?php echo $likesnum ?><br>喜歡<br>
                    <?php
                    if ($likesnum == 0) : ?>
                        <i class="far fa-heart"></i>
                    <?php
                    elseif ($likesnum > 0) :?>
                        <i class="fas fa-heart"></i>
                    <? endif; ?>
                </div>
                <div id="PostReply">
                    <?php
                    if ($numReply == 0) :
                        echo $numReply ?><br>回覆<br><i class="far fa-comment"></i>
                    <?php elseif ($numReply > 0) :
                        echo $numReply; ?><br>回覆<br><i class="fas fa-comment"></i>
                    <? endif; ?>
                </div>
                <div id="favpost">
                    <?php
                    if ($favnum == 0):
                        echo ($favnum) ?><br>收藏<br><i class="far fa-star"></i>
                    <?php elseif ($favnum > 0) :
                        echo ($favnum) ?><br>收藏<br><i class="fas fa-star"></i>
                    <? endif; ?>
                </div>
                <div id="PostTittle"><a
                            href="blog?id=<?=$OPost["PostId"]?>"><?php echo $OPost["PostTittle"] ?></a>
                </div>
                <div id="PostOther">
                    <div class="postClassLayout">
                        <div id="PostClass">
                            <a href="category?classid=<?=$classID?>"><i class="fas fa-folder"></i><?=$ClassName ?></a>
                        </div>&nbsp;
                    </div>
                    <div id="PostWriter"><a href="person?id=<?=$puserid?>"><i class="fas fa-user"></i><?=$puser[4]?></a>
                        &nbsp;<i class="fas fa-clock"></i><?=$postdateymd?> &nbsp; <i class="fas fa-eye"></i> <?=$OPost["NumViews"]?>點閱
                        <?if ($OPost["Password"] != null) : ?>
                            &nbsp;&nbsp;<i class="fas fa-lock"></i>&nbsp;密碼保護
                        <? endif; ?>
                    </div>
                </div>
            </div>
        <?php } else if ($OPost["Competence"] == "private") {
        }
    }
    if ($per > 0) :?>
        <div id="pageBotton">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php
                for ($i = 1; $i <= $pages; $i++) :
                    if ($_GET["page"] == null) :
                        $nowpagenum = 1;
                    else :
                        $nowpagenum = $_GET["page"];
                    endif;
                    if ($nowpagenum == $i) :?>
                        <li class="page-item active"><a class="page-link"><?php echo $i ?></a></li>
                    <?php
                    else :?>
                        <li class="page-item"><a class="page-link"
                                                 href="<?php
                                                 if($pagefilename=="search")
                                                 echo "?q=" . $q."&conandtit=".$conandtit;
                                                 if($pagefilename=="category")
                                                 echo "?classid=" . $classID;
                                                 if($pagefilename=="person")
                                                     echo "?id=".$id."&act=".$act;
                                                 if($pagefilename==null)
                                                 echo "?page=" . $i ;
                                                 else
                                                     echo "&page=".$i;
                                                 ?>"><?php echo $i ?></a>
                        </li>
                    <?endif;
                endfor; ?>
            </ul>
        </nav>
        </div>
    <?endif;
}