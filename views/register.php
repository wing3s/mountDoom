
<style type="text/css">
body {
    background-image:url("/images/lab/clone.jpg");
    background-attachment:fixed;
	background-position:center; 
	background-repeat:no-repeat;
    opacity:0.8;
    background-color:black;
    text-align: center;
}

.outer {
	width: 0px;
	margin: auto;
	overflow: visible;
	text-align: left;
}
#register_form {
	position absolute;
	left: 50%;
	margin-top: 300px;
	margin-left: -480px;
}
</style>
<title>MountDoom - Register</title>
<div class='outer'>
<div id='register_form'>
<?php
	$registerAttribute	= array('id'	=> 'register',
								'class'	=> 'custom');
	$registerFirstName	= array('id'	=> 'register_firstname',
								'name'	=> 'first_name',
								'value'	=> '',
								'placeholder'	=> 'First name');
	$registerLastName	= array('id'	=> 'register_lastname',
								'name'	=> 'last_name',
								'value'	=> '',
								'placeholder'	=> 'Last name');
	$registerSubmit		= array('class'	=> 'btn btn-success',
								'type'	=> 'submit',
								'name'	=> 'submit',
								'value'	=> 'Join',);
	$registerDropdown	= "id='customDropdown'";
	echo form_open($mainURL."registerNewUser",$registerAttribute);
?>
	<div class='row'>
		<div class='span6'>
			<h2> Serving the Empire</h2>
		</div>
	</div>
	<div class='row'>
		<div class='span3'>
			<?php echo form_input($registerFirstName);?>
		</div>
	</div>
	<div class='row'>
		<div class='span3'>
			<?php echo form_input($registerLastName);?>
		</div>
	</div>
	<div class='row'>
		<div class='span3'>
			<?php echo form_dropdown('groupname',$groups,'',$registerDropdown);?>
		</div>
	</div>
	<div class='row'>
		<div class='span3'>
			<?php echo form_submit($registerSubmit);?>
		</div>
	</div>
			<?php echo form_close();?>
</div>
</div>
