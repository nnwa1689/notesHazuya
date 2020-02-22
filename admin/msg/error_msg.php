<style type="text/css">
#msg {
	text-align: center;
}
</style>
<div id="msg"> 
  <p><img src="image/error.png"></p>
<strong>操作失敗!<br/> 
請等候轉跳......
</strong>
<?php //例外情況:跳回列表畫面
if ($pagefilename=="blogedit"){ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=blogm.php>
<?php
	}
else if ($pagefilename=="edit"){ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=web.php>
<?php
		}
else if ($pagefilename=="pageedit"){ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=pagemanage.php>
<?php 
}
else if ($pagefilename=="nd_navigate"){ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=nd_navigate.php?name=<?php print $navName ?>&id=<?php 
	print $navID ?>>

<?php
	}
elseif($pagefilename=="upload"){ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=upload.php?id=<?php print $address ?>>
    
<?php 	} 
else if ($pagefilename=="admin-login"){ ?>
		<meta http-equiv=REFRESH CONTENT=5;url=<?php print $pagefilename.".php"; ?>>
<?php 
}
else if ($pagefilename=="register"){ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=register.php>

<?php	
	}
else{ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=<?php print $pagefilename.".php"; ?>>
	
<?php	}

?>
</div>
<br/>