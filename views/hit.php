
<?php
	$totalCount	= $stats['totalCount'];
	$totalUser	= $stats['totalUser'];
	$userArr	= array();
	$detailWidth	= ($type=='post')?4:3;
	foreach($users as $user)
	{
		$userCount		= $user['userCount'];
		$userFullName	= $user['userFullName'];
		$username		= $user['username'];
		$userURL		= $mainURL."index/".$username;
		$userArr[]	= "
				<a href='$userURL' class='user_url'>
					$userFullName
				</a>
				($userCount)
				";
	}
	if($totalUser>3)
	{
		$userArr	= array_splice($userArr,3);
	}
?>
<div class='span1' style='width:35px; margin:0; padding:0;'>
	<div class='hit_text'>
		<?php echo "($totalCount) ";?>
	</div>
</div>
<div class='span<?php echo $detailWidth;?>' style='margin-left:0px;'>
	<div class='hit_text'>
		<?php
			echo implode(',',$userArr);
			$restUser	= $totalUser - count($userArr);
			if($restUser > 0) {echo " and $restUser others ";}	
		?>
	damned it
	</div>
</div>
