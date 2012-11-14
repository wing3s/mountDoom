<style type="text/css">
#timeline_container{width:860px; margin:0 auto;}
.post {
	width: 404px;
	margin: 20px 10px 10px 10px;
	float: left;
	background-color: #E0E0E0;
	border: solid 1px #B4BBCD;
	min-height: 50px;
	text-align: justify;
	padding: 2px;
	word-wrap: break-word;
}
.comment {
	padding-top: 2px;
	padding-bottom:4px;
}
.hidden_comment {
	display: none;
}
.post_text {
	padding: 4px;
	margin-left: 4px;
	font-size: 12.5px;
}
.comment_text {
	width: 330px;
	padding: 2px;
	font-size: 12.5px;
}
.hit_text {
	font-size: 12px;
}
.timeline_line_container{
	width: 16px;
	text-align: center;
	margin: 0 auto;
	cursor: pointer;
	display: block;
	overflow: hidden;
}
.timeline_line{
	margin: 0 auto;
	background-color: #e08989;
	display: block;
	float: left;
	height: 100%;
	left: 428px;
	margin-top: 10px;
	position: absolute;
	width: 4px;
}
.timeline_line.hover{cursor:pointer; margin: 0 auto;}
.timeline_line div.plus{width: 14px; height: 14px; position: relative; left: -6px;}

.rightCorner{
	background-image: url("/images/lab/decoration/rightCorner.png");
	display: block;
	height: 15px;
	margin-left: 406px;
	margin-top: 8px;
	padding: 0;
	vertical-align: top;
	width: 13px;
	z-index: 2;
	position: absolute;
	opacity:0.8;
}
.leftCorner{
	background-image: url("/images/lab/decoration/leftCorner.png");
	display: block;
	height: 15px;
	width: 13px;
	margin-left: -15px;
	margin-top: 8px;
	z-index: 2;
	position: absolute;
	opacity:0.8;
}



.commentButton{
	padding:6px 0 6px 0;
	width:100px;
	border:solid black 1px;
	border-bottom:solid #2c5115 1px;
	background-color: #600000 ;
	color:#fff;
	font-weight:bold;
	font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
	cursor:pointer;
}
.Hit {
	background: transparent;
	border-top: 0;
	border-right: 0;
	border-bottom: 0;
	border-left: 0;
	color: #3B5998;
	display: inline;
	margin-left: 4px;
	padding: 0;
	cursor:pointer;
	text-decoration:none;
	font-size: 12px;
	font-weight:bold;
	color:#3B5998
	font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
}
.Hit:hover {
	color:#06C;
	text-decoration: underline;
}	
.hitCount {
	overflow:auto;
	height: 1%;
	color: #3B5998;
	font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
}
form {
	margin: 0px;
}
textarea {
	width: 390px;
}
textarea.add-comment {
	height: 2em;
	z-index: 9;
}
textarea#addPost_content{
	z-index: 9;
}
hr {
	color: #F0F0F0;
	background: #F0F0F0;
}
.row{
	width:400px;
	min-width:400px;
}
.post-hit {
	display: inline;
}
.post_content {
	margin: 0px;
	padding-top: 5px;
	padding-bottom: 10px;
	background-color: #F3F3F3;
	display: block;
}
.post_head {
	background-color: #F3F3F3;
}
.icon_worker_outer_caption{
	position: relative;        
	left: 25px;
}
.icon_worker_caption {
	font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
	text-align: center;        
	position: relative;
	left: -50%;         
	white-space: nowrap; 
}
.user_url {
	text-decoration:none; 
	font-size: 12px;
	font-weight:bold;
	color:#3B5998
}
.user_url:hover {
	color:#06C;
}
.link_url {
	text-decoration:none; 
	color:#3B5998
}
.link_url:hover {
	color:#06C;
}
.icon_arrow {
	height: 10px;
	width: 10px;
}
.time_text {
	font-size: 12px;
	margin-left: 4px;
	color:#999;
	font-family: "lucida grande", tahoma, verdana, arial, sans-serif;
}
ul.ui-autocomplete {
    position: absolute;
    left: 100px;
    top: 0;;
    border-top:5px solid transparent;
	background-color: #EEE;
	list-style: none;
	text-align: left;
	cursor: default;
}
li.ui-menu-item a{
	text-decoration:none;
}
.preview_img {
	width: 140px;
	float:left;
	margin-right:10px;
	text-align:center;
}
.linkbox {
	min-height:30px; 
	padding:10px;
	display:none;
	border:solid 2px #C4CDE0;

}
</style>


<script type="text/javascript">
	function Arrow_Points(){
		var posts = $("#timeline_container").find('.post');
		$.each(posts,function(i,obj){
			$(obj).find("span").remove();
			var posLeft	= $(obj).css("left");
			$(obj).addClass('boarderclass');
			if(posLeft == "0px")
			{
				html = "<span class='rightCorner'></span>";
			} else {
				html = "<span class='leftCorner'></span>";
			}
			$(obj).prepend(html);		
		});
	};

	function plotTimeline(){
		$('.timeline_line_container').mousemove(function(e)
		{
			var topdiv	= $("#header_container").height();
			var pag		= e.pageY - topdiv;
			$('.plus').css({
						"top":pag+"px",	
						"background":"url('http://demos.9lessons.info/timeline/images/plus.png')",
						"margin-left":"1px"
						});
		});

		$('.timeline_line_container').mouseout(function()
		{
			$('.plus').css({"background":"none"});
		});
		$('#timeline_container').masonry({itemSelector : '.post',});
		$('#timeline_container').masonry('reload');
	};
</script>
