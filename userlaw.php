<?php
$pagefilename = basename(__FILE__, ".php");//取得本業面的
include "hz_include/themes/def/header.php"; ?>
<body>
<div id="top">
    <div class="top_contant"><?php include("hz_include/themes/def/header_top.php") ?></div>
</div>

    <?php
    include("hz_include/themes/def/navigate.php"); ?>

<div id="contant">
    <div id="PageTitle">
        網站使用者條款
        <br>
    </div>
    <form action="" method="post" enctype="multipart/form-data">

        <p>
            <?php
            $sqluserlaw = "SELECT * FROM web where ID='15'";
            $resultuserlaw = mysqli_query($sqli,$sqluserlaw);
            $userlaw = mysqli_fetch_row($resultuserlaw);
            print $userlaw[1];
            ?>
        </p>
    </form>
    <p>&nbsp;</p>
</div>
<div id="copyr">
    <?php include "hz_include/themes/def/footer.php"; ?>
</div>
</body>
</html>
