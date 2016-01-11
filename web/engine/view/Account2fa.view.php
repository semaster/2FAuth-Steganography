<?php if(!defined("IN_RULE")) die ("Oops"); ?>

<div class="jumbotron">
	<h1><?php echo _('Verify your identity'); ?></h1>
	<p class="lead text-justify">
		<?php echo _('Select your special image to complete login process.'); ?>

	</p>

  <form class="form-signin" action="" enctype="multipart/form-data" method="post">

   <div>
     <input type="file" id="inputImage" name="image" class="form-control" value="Select Image" accept="image/*" required>
   </div>

   <button class="btn btn-lg btn-primary btn-block" type="submit" name="signup">Verify</button>
   <p class="help-block text-right"><a href="logout"><?php echo _('Logout'); ?></a></p>
  </form>
</div>