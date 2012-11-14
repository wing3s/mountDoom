
<script type="text/javascript">
var mainURL;

// AJAX send Hit of post
$(function(){
	$(".Hit").live('click',function(){
			var hitURL	= mainURL + "sendHit/" + $(this).attr('id');
			var hitId	= $(this).attr('id').replace('/','-');
			$.ajax({
					type: 'POST',
					url: hitURL,
					success:function(response){
						$("#"+hitId).html(response);
					}
			});
	});
});

// Show hidden comments
$(function(){
	$(".show_comment").live('click',function(){
		var commentID	= $(this).attr('id').replace('show','hidden');
		console.log(commentID);
		$("#"+commentID).fadeIn(1000);
		$(this).hide();
		plotTimeline();
	});
});


function getMainURL(lastword){
	currentURL  = window.location.href;
	mainURL		= currentURL.substring(0,currentURL.indexOf(lastword) + lastword.length) + "/";
	return mainURL;
}





function ajaxyComment(){
	$(".addComment").ajaxForm({
		success:function(response){
			var form_url = $(this).attr('url');
			var post_id	= form_url.substring(form_url.indexOf("addComment/") + "addComment/".length);
			$(response).hide().appendTo("#post-comment-"+post_id).fadeIn(1000);
			plotTimeline();
			addLink('comment');
		},
		clearForm: true
	});
}
function addLink(content_type){
	$("." + content_type + "text").each(function(){
		var linked = $(this).html().autoLink({ target: "_blank"});
		$(this).html(linked);
	});	
}
jQuery.fn.extend({
	 propAttr: $.fn.prop || $.fn.attr
});
function autoCompleteUser(input_field){
	var userList	= [
						<?php
							$userArr = array(); 
							foreach($userList as $user=>$fullname)
							{
								$userArr[]="{label:'$fullname',value:'$user'}";
							} 
							echo implode(',',$userArr);
						?>
						];
	$('#'+input_field).autocomplete({
		source: userList 
		
	});
}

function previewURL(contentbox,linkbox){
	$("#"+contentbox).keyup(function(){
		var content=$(this).val();
		var url= content.match(urlRegex);
		if(url){
			if(url.length>0){
				$("#"+linkbox).fadeIn('show');
				$("#"+linkbox).html("<img src='/images/lab/decoration/loader.gif' width='30px' height='30px'>");
				$.get("/urlget.php?url="+url,function(response){
					var title=(/<title>(.*?)<\/title>/m).exec(response)[1];
					$("#"+linkbox).html("<a href='"+url+"' target='_blank' class='link_url'><div><b>"+title+"</b><br/></a>");
					var logo=(/src='(.*?).png'/m).exec(response)[1];
					$("<img src='"+logo+".png' class='preview_img'/>").prependTo("#"+linkbox);
					plotTimeline();
				});
			} else {
				$("#"+linkbox).hide().html('');
			}
		} else {
			$("#"+linkbox).hide().html('');
		}
		plotTimeline();
		return false;
	});
}

function postPreviewURL(){
	$(".post_text").each(function(){
		var contentbox	= $(this).attr('id');
		var linkbox		= contentbox+"_linkbox";
		var content	= $(this).html();
		var url= content.match(urlRegex);
		if(url){
			if(url.length>0){
				$("#"+linkbox).fadeIn('show');
				$.get("/urlget.php?url="+url,function(response){
					var title=(/<title>(.*?)<\/title>/m).exec(response)[1];
					$("#"+linkbox).html("<a href='"+url+"' target='_blank' class='link_url'><div><b>"+title+"</b><br/></a>");
					var logo=(/src='(.*?).png'/m).exec(response)[1];
					$("<img src='"+logo+".png' class='preview_img'/>").prependTo("#"+linkbox);
					plotTimeline();
				});
			}
		}
	});

}
var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;

$(document).ready(function(){
	mainURL	= getMainURL('mountDoom');
	plotTimeline();
	Arrow_Points();
	ajaxyComment();
	addLink('post');addLink('comment');
	$("#addPost_bigboss").keyup(function(){autoCompleteUser('addPost_bigboss');});
	$("#addPost_boss").keyup(function(){autoCompleteUser('addPost_boss');});
	previewURL('addPost_content','addPost_content_linkbox');
	postPreviewURL();

	$("#addPost").ajaxForm({
		success:function(response){
			$(response).hide().prependTo('#posts_container').fadeIn(1000);
			ajaxyComment();
			Arrow_Points();
			addLink('post');
			$("#addPost_content").val('');
			$(".input-medium").val('');
			$("#addPost_content_linkbox").hide().html('');
			postPreviewURL();
			plotTimeline();
		},
		clearForm: true
	});
	$("#searchKeyword").ajaxForm({
		success:function(response){
			if(response.length > 0) {
				$("#posts_container").html(response);
				ajaxyComment();
				Arrow_Points();
				addLink('post');
				postPreviewURL();
				plotTimeline();
			}
		},
		clearForm: true
	});
});

</script>
<title>MountDoom</title>
<div id="timeline_container">

<!--Timeline Navigator-->
<div class="timeline_line_container">
	<div class="timeline_line">
		<div class="plus"></div>
	</div>
</div>

<!--Add Post-->
<div class="post">
	<div class='row'>
		<div class='span3'>
			<h5>Cool idea/ Quick Task</h5>
		</div>
	</div>
		
<?php
	$addPostAttribute	= array('id'			=> 'addPost',
								'class' 		=> 'addPost');
	$addPostBoss		= array('id'			=> 'addPost_boss',
								'class'			=> 'input-medium span2',
								'name'			=> "boss",
								'value' 		=> '',
								'style'			=> 'padding:2px',
								'placeholder'	=> "Who's related to it?");
	$addPostBigBoss		= array('id'			=> 'addPost_bigboss',
								'class'			=> 'input-medium span2',
								'name'			=> 'bigboss',
								'value' 		=> '',
								'style'			=> 'padding:2px',
								'placeholder'	=> "Who's behind it?",);
	$addPostContent		= array('id'			=> 'addPost_content',
								'rows'			=> '3',
								'name'			=> 'post_content',
								'value' 		=> '',
								'style' =>'resize: none;',
								'placeholder'	=> "What's your task/idea?",);
	$addPostSubmit		= array('type'			=> 'submit',
								'class'			=> 'btn btn-small btn-inverse',);
	echo form_open($mainURL."addPost/".$location,$addPostAttribute);
?>
	<div class='row'>
		<div class='span5'>
			<?php echo form_textarea($addPostContent);?>
		</div>
	</div>
	<div class='row'>
		<div class='span2' style='height:35px;'>
			<?php echo form_input($addPostBoss);?>
		</div>
		<div class='span2' style='height:35px;'>
				<?php echo form_input($addPostBigBoss);?>
		</div>
		<div class='span1' style='margin-left:20px;'>
			<?php	
				echo form_submit($addPostSubmit,'   Post   ');
				echo form_close();
			?>
		</div>
	</div>
</div>

<!--Posts-->
<div id='posts_container'>
<?php
	if($posts)
	{ 
		foreach($posts as $post)
		{
			echo $post; 
		}
	}
?>
</div>
