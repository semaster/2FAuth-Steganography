<?php if(!defined("IN_RULE")) die ("Oops"); ?>

<?php if (!empty($message)) : ?><div class="alert alert-warning"><?php echo $message; ?></div><?php endif; ?>


  <form class="form-signin" action="" method="post">
   <h2 class="form-signin-heading"><?php echo _('Please sign in'); ?></h2>
   <label for="inputEmail" class="sr-only">Email address</label>
   <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
   <label for="password" class="sr-only">Password</label>
   <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    <?php if ($captcha == 'show') : ?>
      <div class="g-recaptcha" data-sitekey="<?php echo $ReCaptchaSiteKey; ?>"></div>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php endif; ?>
   <button class="btn btn-lg btn-primary btn-block" type="submit" name="signin"><?php echo _('Sign in'); ?></button>
    <p class="help-block text-right"><a href="register"><?php echo _('Register'); ?></a></p>
  </form>

