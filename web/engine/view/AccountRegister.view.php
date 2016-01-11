<?php if(!defined("IN_RULE")) die ("Oops"); ?>

<?php if (!empty($message)) : ?><div class="alert alert-warning"><?php echo $message; ?></div><?php endif; ?>

  <form class="form-signin" action="" enctype="multipart/form-data" method="post" onsubmit="return validate(this);">
   <h2 class="form-signin-heading"><?php echo _('Sign up form'); ?></h2>
   <div>
     <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
   </div>
   <div>
     <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
   </div>
   <div>
     <input type="password" id="confirmPassword" name="password2" class="form-control" placeholder="Confirm password" required>
   </div>
   <div>
     <input type="file" id="inputImage" name="image" class="form-control" value="Select Image" accept="image/*" required>
   </div>

    <?php if ($captcha == 'show') : ?>
      <div class="g-recaptcha" data-sitekey="<?php echo $ReCaptchaSiteKey; ?>"></div>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php endif; ?>
   <button class="btn btn-lg btn-primary btn-block" type="submit" name="signup"><?php echo _('Register'); ?></button>
   <p class="help-block text-right"><a href="login"><?php echo _('Sign in'); ?></a></p>
  </form>
