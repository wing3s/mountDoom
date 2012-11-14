<?php
	foreach($groupMembers as $member)
	{
		$memberURL		= $mainURL."index/".$member;
		$memberSrc		= "/images/lab/profile/$member.png";
		$defaultSrc		= "/images/lab/profile/stormtrooper.png";
		$defaultImage	= array(
					'src'	=> $defaultSrc,
					'class'	=> 'member_image',
					'width'	=> '45',	
					'height'=> '45',);

		echo "	<a href='$memberURL'>
					<object data='$memberSrc' width='45px' height='45px'>";
		echo 			img($defaultImage);
		echo "		</object>
				</a>";
	}
?>

