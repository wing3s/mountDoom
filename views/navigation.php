<style type='text/css'>
#navigation_panel {
	position: absolute;
	width: 120px;
	top: 20px;
	left: 50%;
	margin-left: -630px;
	color: white;
	text-align: left;
}
#navigation_panel ul li {
	font-family: "lucida grande", tahoma, verdana, arial, sans-serif; 
	font-size:11px;
	width: 160px;
}
#navigation_panel ul li a {
	color:#BEBEC5; 
}
#navigation_panel ul li a:hover {
	color:#000000; 
}
</style>

<div id='navigation_panel'>
<ul class='nav nav-pills nav-stacked'>
<?php 
	foreach($allGroups as $name=>$fullName)
	{
		$groupURL	= $mainURL."index/".$name;
		$iconDefault= array(
				'src'	=> 'images/lab/icon/default.png',
				'class'	=> 'icon',
				'width'	=> '12',
				'height'=> '12',);

		echo "<li>
				<a href='$groupURL' class='navigation_url'>
					<object data='/images/lab/icon/$name.png' width='12px' height='12px'>";
						echo img($iconDefault);
		echo "		</object>
					$fullName
				</a>
			</li>";
	}
?>
</ul>
</div>
