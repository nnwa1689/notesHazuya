<style type="text/css">
#msg {
	text-align: center;
}
</style>
<div id="msg"> 
  <p><img src="image/succ.png"></p>
<?php if ($pagefilename!="upload"){ ?>
<strong>操作成功!<br/> 
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
else if ($pagefilename=="register"){ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=admin-login.php>

<?php	
	}	
else{ ?>
	<meta http-equiv=REFRESH CONTENT=3;url=<?php print $pagefilename.".php"; ?>>
	
<?php	}
}
else{//檔案上傳專用情況 ?>
	<strong>操作成功!<br/> 
檔案位置：
</strong>
	
<?php	}
?>

</div>
<br/>
