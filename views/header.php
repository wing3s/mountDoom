<style type="text/css">
body {
	background-image:url("/images/lab/deathstar.png");
	background-attachment:fixed;
	background-color:black;
	text-align: center;
	filter:alpha(opacity=80);
	-moz-opacity: 0.8;
	opacity: 0.8;
	-khtml-opacity: 0.8;
}
#header_title {
	position: absolute;
	top: 80px;
	left: 50%;
	margin-left: -400px;
	color: white;	
	font-size: 28px;
	font-weight: bold;
	text-align: left;
	font-family: "lucida grande", tahoma, verdana, arial, sans-seri;
}
#profile_view {
	position: absolute;
	top: 100px;
	left: 50%;
	margin-left: -400px;
}
#header_container {
	width:860; margin:0 auto;
}
.cover_image {
	text-align: center; margin:0 auto;
}
#topbar {
	background-color: #2D2D2D;
	height: 39px;
}
#topbar_content {
	width: 860px;
	height: 39px;
	text-align: center; margin:0 auto;
}
#logo {
	color: white;
}
#logo:hover {
	text-decoration: none;
}
#logo h4 {
	padding: 4px;
}
#logo h4:hover {
	background-color: #424242;
}
#searchbar {
	padding:0px;
}
#notification img {
	width: 24px;
	height: 24px;
}
#notification img:hover {
	color: black;
	filter:alpha(opacity=60);
	-moz-opacity: 0.6;
	opacity: 0.6;
	-khtml-opacity: 0.6;
}
</style>
<script>
$(function () {
	var isVisible = false;
	var clickedAway = false;
	$("#notification").popover({
		placement:'bottom',
		trigger:'manual',
		title:'Notifications',
		content:"<h5>No new notifications</h5>"
	}).click(function(e){
		$(this).popover('show');
		isVisible = true;
		e.preventDefault();
		
	});
	$(document).click(function(e){
		if(isVisible & clickedAway) {
			$("#notification").popover('hide');
			isVisible = clickedAway = false;
		} else {
			clickedAway = true;
		}
	});
});
</script>

<div id='topbar'>
	<div class='row' id='topbar_content'>
		<div class='span2' style='height:24px; margin-left:10px;'>
			<a id='logo' href='<?php echo $mainURL;?>'><h4>MountDoom</h4></a>
		</div>
		<div class='span1' style='height:24px; margin-top:12px; margin-left:0px'>
			<a id='notification' href='#' rel="popover">
				<img src='/images/lab/decoration/notification.png'/>
			</a>
		</div>
		<div class='span4 offset4' style='height:24px; margin-top:7px;'>
			<?php $searchURL = $mainURL."searchAllPosts";?>
			<form id='searchKeyword' class='form-search' method='post' action='<?php echo $searchURL;?>'>
				<input id='searchbar' type='text' name='keyword' class='input-large' placeholder=' Search for people or posts'>
				<button class='btn btn-mini' type='submit'>Search</button>
			</form>
		</div>
	</div>
</div>

<div id="header_container">
	<?php 
		$nameImg="default";
		$coverProperties	= array(
				'src'	=> "images/lab/cover/$nameImg.png",
				'class'	=> 'cover_image',
				'width'	=> '840',);
		echo img($coverProperties);
	?>

</div>

<div id='header_title'>
	<?php echo ucwords($fullName);?>
</div>

<div id='profile_view'>
	<?php //echo $profileView; ?>
</div>
