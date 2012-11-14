<div class='post'>


<div class='post_head'>
	<div class='row'>
	<?php
		$postID		= $post['post_id'];
		$author		= $post['username'];
		$boss		= $post['boss'];
		$bigboss	= $post['bigboss'];
		$postContent= $post['post_content'];
		$postLocation			= $post['location'];
		$postLocationFullName	= $post['locationFullName'];
		$addUTC		= $post['add_utc'];
		$addTime	= date("D M j  h:m A",$addUTC);

		$workChain	= array($author,$boss,$bigboss);
		foreach($workChain as $key=>$worker)
		{
			if($worker == '') 
			{ 
				echo "<div class='span1'>&nbsp</div>";
				continue; 
			}
			switch ($key)
			{
				case 0: $imgDefault	= 'stormtrooper';
						$workerFullName	= $post['user_fullname'];
						break;
				case 1: $imgDefault	= 'imperialofficer';
						$workerFullName	= ( is_null($post['boss_fullname'])?
										$worker: $post['boss_fullname'] );
						break;
				case 2: $imgDefault = 'darthvader';
						$workerFullName	= ( is_null($post['bigboss_fullname'])?
										$worker: $post['bigboss_fullname'] );
						break;
			}
			$iconDefault	= array(
					'src'	=> "images/lab/profile/$imgDefault.png",
					'class'	=> 'icon_image',
					'width'	=> '50',
					'height'=> '50',);
			$arrowProperties= array(
					'src'	=> "images/lab/decoration/leftredarrow.png",
					'class'	=> 'icon_arrow',);
			$workerURL	= $mainURL."index/".$worker;
	
			echo "<div class='span1'>";
			// Profile image
			echo "	<a href='$workerURL'>
						<object data='/images/lab/profile/$worker.png' width='50px' height='50px'>";
			// Default image
			echo 		img($iconDefault);
			echo "		</object>
					</a>";
			// URL
			echo "	<a href='$workerURL' class='user_url'>
							$workerFullName
					</a>";
			echo "</div>";
		}
	?>
		<div class='span2'>
			<div class='row'>	
				<div class='span2'>
					<div class='time_text'><?php echo $addTime;?></div>
				</div>
			</div>
			<div class='row'>
				<div class='span2'>
				<?php
					if($postLocation!=$author)
					{
						$rightArrow	= array(
							'src'	=> "images/lab/decoration/rightbluearrow.png",
							'class'	=> 'icon_arrow',);
						$postLocationURL	= $mainURL."index/".$postLocation;
						echo img($rightArrow);
						echo "<a href='$postLocationURL' class='user_url'>
								 $postLocationFullName
							</a>";
					}
				?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='post_content'>
	<div class='row'>
		<div class='span5'>
			<div id='post_text-<?php echo $postID;?>' class='post_text'>
				<?php echo $postContent; ?>
			</div>
		</div>
	</div>
	<div class='row'>
		<div class='span5'>
			<div id='post_text-<?php echo $postID;?>_linkbox' class='linkbox'>
			</div>
		</div>
	</div>

	<div class='row'>
		<div class='span1' style='width:45px;' >
			<?php
				$sendHit	= array('class'	=> 'Hit',
									'type'	=> 'button',
									'value'	=> 'Damn',
									'id'	=> "post/$postID",);
				echo form_submit($sendHit);
			?>
		</div>
		<div class='span4' style='width:335px; margin-left:0px;'>
		<?php
			echo "<div class='hitCount' id='post-$postID'>$postHit</div>";
		?>
		</div>
</div>
</div>
<hr style="margin:1px">



<div class='post-comment'id="post-comment-<?php echo $postID;?>" >
	<?php
		$commentCount	= count($comments);
		$displayNumber	= 2;
		$hideNumber		= $commentCount - $displayNumber - 1;
		if($commentCount > $displayNumber)
		{
			echo "<div class='row'>
					<div class='span3' style='margin-left:75px;'>";
			echo "		<div class='show_comment Hit' id='post-show-comment-$postID'>
							Show all $commentCount comments
						</div>";
			echo "	</div>
				</div>";
			echo "<hr style='margin:1px'>";
		}
		foreach($comments as $key=>$comment)
		{
			if($commentCount > $displayNumber && $key == 0)
			{
				echo "<div class='hidden_comment' id='post-hidden-comment-$postID'>";
			}
			echo "<div class='row'>
					<div class='span5'>";
			echo 		$comment;
			echo "	</div>
				</div>";
			if($commentCount > $displayNumber && $key == $hideNumber)
			{
				echo "</div>";
			}
		}	
	?>
</div>

<hr style="margin:1px">

<div class='post-add-comment'>
	<div class='row'>
		<div class='span5'>
	<?php
		$addCommentAttribute= array('class'	=> 'addComment');
		$addCommentContent	= array('class'	=> 'add-comment',
									'name'	=> 'comment',	
									'rows'	=> '2',
									'value'	=> '',
									'style'	=> 'resize: none;',
									'placeholder'	=> "Write a comment..",);
		$addCommentButton	= array('type'	=> 'submit',
									'class'	=> 'btn btn-small btn-danger',);
		echo form_open($mainURL."addComment/$postID",$addCommentAttribute);
		echo form_textarea($addCommentContent);
	?>
		</div>
	</div>
	<div class='row'>
		<div class='span4'>
			&nbsp;
		</div>
		<div class='span1'>
	<?php
		echo form_submit($addCommentButton,'  Send  ');
		echo form_close();
	?>	
		</div>
	</div>							
</div>

</div>
