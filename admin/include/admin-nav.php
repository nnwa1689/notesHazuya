<link href="../hz_include/common/css/bootstrap.css" rel="stylesheet">
<link href="include/style.css" rel="stylesheet" type="text/css"/>
<link href="../hz_include/themes/def/fontawesome-all.css" rel="stylesheet">
<style type="text/css">
    #ClassNav {
        border-bottom-width: thin;
        border-bottom-style: solid;
        border-bottom-color: #E1E1E1;
        margin-top: 2px;
        margin-bottom: 2px;
    }

    #by {
        font-size: 14px;
        font-weight: bold;
        margin: 2px;
    }

    #NavItem {
        color: #CFCFCF;
        transition: color 0.3s;
        padding: 4px;
        background-color: #23282d;
        transition: background-color 0.3s;
        font-family: "微軟正黑體";
        font-size: 16px;
    }

    #NavItem:hover {
        background-color: #191e23;
        color: #0099FF;
        transition: color 0.3s;
        padding: 4px;
        transition: background-color 0.3s;
        font-family: "微軟正黑體";
        font-size: 16px;
    }
</style>
<script src="../hz_include/common/js/jquery-3.3.1.min.js"></script>
<script src="../hz_include/common/js/bootstrap.js"></script>
<script src="../hz_include/tinymce/tinymce.min.js"></script>
<p><a href="index.php"><img src="image/Alogo.png" width="140" height="36" longdesc="index.php"></a></p>
<?php
include_once("../hz_include/class-userLoginData.php");
include_once("../hz_include/userlawCheck.php");
$sql_web = "SELECT * FROM web ORDER BY `ID` ASC";
$result_web = mysqli_query($sqli,$sql_web);
$List_web = mysqli_num_rows($result_web);
$mate = array("", "", "");
for ($i = 0; $i < $List_web; $i++) {
    $search = mysqli_fetch_assoc($result_web);
    $mate[$i] = $search["tittle"];
}
if ($_SESSION["username"] == null || $userlaw["Law_ControlVisit"] != 1) {
    header('Location: ../');
    exit;
}
//維護模式
if ($mate[17] != "on" && ($userlaw["Law_Visit"] == 0)) {
    header('Location: ../fix');
    exit;
} ?>
<a style="font-size: 14px"><?php print"您好," . $b; ?></a>
<br/>
<div id="ClassNav">
    <a href="../">
        <div id="NavItem">返回&nbsp;<i class="fas fa-arrow-alt-circle-left"></i></div>
    </a>
    <a href="../logout">
        <div id="NavItem">登出&nbsp;<i class="fas fa-sign-out-alt"></i></div>
    </a>
</div>

<div id="ClassNav">
    <?php if ($userlaw["Law_admin"] == 1): ?>
        <a href="admin.php">
            <div id="NavItem">網站總體&nbsp;<i class="fas fa-cogs"></i></div>
        </a>
    <?php endif; ?>
    <?php if ($userlaw["Law_web"] == 1): ?>
        <a href="web.php">
            <div id="NavItem">公告與廣播&nbsp;<i class="fas fa-bullhorn"></i></div>
        </a>
    <?php endif; ?>
    <?php if ($userlaw["Law_page"] == 1): ?>
        <a href="pagemanage.php">
            <div id="NavItem">頁面&nbsp;<i class="fas fa-clone"></i></div>
        </a>
    <?php endif; ?>
    <?php if ($userlaw["Law_navigate"]): ?>
        <a href="navigate.php">
            <div id="NavItem">導航&nbsp;<i class="fas fa-bars"></i></div>
        </a>
    <?php endif; ?>
    <?php if ($userlaw["Law_member"]): ?>
        <a href="member.php">
            <div id="NavItem">會員&nbsp;<i class="fas fa-users"></i></div>
        </a>
        <a href="useraut.php">
            <div id="NavItem">權限組&nbsp;<i class="far fa-user"></i></div>
        </a>
    <?php endif; ?>
    <?php if ($userlaw["Law_PostBlog"]): ?>
        <a href="blogm.php">
            <div id="NavItem">文章&nbsp;<i class="fas fa-th-list"></i></div>
        </a>
    <?php endif; ?>
    <?php if ($userlaw["Law_filem"]): ?>
        <a href="filem.php">
            <div id="NavItem">媒體庫&nbsp;<i class="fas fa-images"></i></div>
        </a>
    <?php endif; ?>
    <?php if ($userlaw["Law_reply"]): ?>
        <a href="reply.php">
            <div id="NavItem">回覆&nbsp;<i class="fas fa-comments"></i></div>
        </a>
    <?php endif; ?>
    <?php if ($userlaw["Law_msg"]): ?>
        <a href="msg.php">
            <div id="NavItem">站內信&nbsp;<i class="fas fa-envelope-square"></i></div>
        </a>
    <?php endif; ?>
</div>
<div id="ClassNav">
    <?php if ($userlaw["Law_mods"]): ?>
        <a href="mods.php">
            <div id="NavItem">模組管理&nbsp;<i class="fas fa-th"></i></div>
        </a>
        <?php
        $sqlMod = "SELECT * FROM `Mods`";
        $resultMod = mysqli_query($sqli,$sqlMod);
        while ($modlist = mysqli_fetch_array($resultMod)) {
            if ($modlist["Position"] == "Enabled") { ?>
                <a href="<?php print $modlist["ModsCP"] ?>">
                    <div id="NavItem"><?php print $modlist["ModsName"] ?></div>
                </a>
                <?php
            }
        } ?>
    <?php endif; ?>
</div>

<div id="by">
    Ver <?php echo $ver ?>
</div>