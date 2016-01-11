<?php if(!defined("IN_RULE")) die ("Oops"); ?>

<div class="jumbotron">
	<h1><?php echo _('2FA using steganography'); ?></h1>
	<p class="lead text-justify">
		<?php echo _('This example demonstrates the two-factor authentication, where the second factor to authenticate is system
		generated key. This key is embedded in a user image using the technique of steganography. <br>
		To authenticate the user needs to know a password and load an image with embedded key.'); ?>

	</p>
	<p><a class="btn btn-lg btn-success" href="account/register" role="button"><?php echo _('Try it'); ?></a></p>
</div>