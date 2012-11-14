

<div class='comment'>

<div class='row'>
	<div class='span1'>
			<?php
				$commentID	= $comment['comment_id'];
				$author		= $comment['username'];
				$authorFullName	= $comment['first_name']." ".$comment['last_name'];
				$authorURL	= $mainURL."index/".$author;
				$commentContent	= $comment['comment'];
				$addUTC		= $comment['add_utc'];
				$addTime	= date_create(date("Y-m-d H:i:s",$addUTC));
				$nowTime	= date_create(date("Y-m-d H:i:s",time()));
				$timeRange	= date_diff($addTime,$nowTime);
				$timeTable	= array('y'=>'years','m'=>'months','d'=>'days','h'=>'hours','i'=>'minutes','s'=>'seconds');
				$timeRangeStr	= '';
				foreach($timeTable as $timeItem=>$timeName)
				{
					if($timeRange->$timeItem !=0)
					{
						$timeRangeStr	= $timeRange->$timeItem. " $timeName ago";
						break;
					}	
				}
				$iconDefault	= array(
						'src'	=> "images/lab/profile/stormtrooper.png",
						'class'	=> 'icon_image',
						'width'	=> '50',
						'height'=> '50',);
				echo "<a href='$authorURL'>
						<object data='/images/lab/profile/$author.png' width='50px' height='50px'>";
				echo img($iconDefault);
				echo "	</object>
					</a>";
			?>
	</div>		
	
	<div class='span4' style='margin-left:0px;'>
		<div class='row'>
			<div class='span3'>
				<a href='<?php echo $authorURL;?>' class='user_url'>
					<?php	echo $authorFullName;?>
				</a>
			</div>
			<div class='span2'>
				<div class='time_text'>
					<?php echo $timeRangeStr;?>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='span4'>
				<div class='comment_content'>
					<div class='comment_text'>
						<?php echo $commentContent; ?>
					</div>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='span1' style='width:45px'>
			<?php
				$sendHit	= array('class'	=> 'Hit',
								'type'	=> 'button',
								'value'	=> 'Damn',
								'id'	=> "comment/$commentID",);
				echo form_submit($sendHit);
			?>
			</div>
			<div class='span4' style='width:300px; margin-left:0px'>
			<?php
				echo "<div class='hitCount' id='comment-$commentID'>$commentHit</div>";
			?>
			</div>
		</div>
	</div>
</div>
</div>
