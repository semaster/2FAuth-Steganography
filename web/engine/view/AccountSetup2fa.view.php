<?php if(!defined("IN_RULE")) die ("Oops"); ?>

<div class="jumbotron">
	<h1><?php echo _('Setup 2FA'); ?></h1>
	<p class="lead text-justify">
		<?php echo _('Now you need to setup two-factor authentication. 
		We will generate special auth-key and embed it into picture that you uploaded during registration.'); ?>

	</p>
	<form  method="post" action=""  >
        <input name="createKey" value="plain" type="hidden">
        <button class="btn btn-lg btn-info" type="submit" name="signin" id="fa2">
        	<?php echo _('Generate picture with embeded key'); ?>
        </button>
	</form>
    <br>
	<div class="alert alert-success hidden" id="fa2-message">
        <h3><?php echo _('Keep ganarated picture in safe place - you will need it each time to login.'); ?></h3>
        <p><a class="btn btn-lg btn-success" href="account" role="button"><?php echo _('Continue'); ?></a></p>
	</div>
	<p><a class="btn btn-lg btn-success" href="logout" role="button"><?php echo _('Exit'); ?></a></p>

</div>