<?php if(!defined("IN_RULE")) die ("Oops"); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $pageTitle; ?></title>

    <link href="<?php $this->info('css_path'); ?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php $this->info('css_path'); ?>style.css" rel="stylesheet">
 
    <script type="text/javascript" src="<?php $this->info('js_path'); ?>custom.js"></script> 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
      window.siteSettings =   {"base":"<?php $this->info('sitehome'); ?>",
                              "lang":"<?php echo $lang; ?>"
      }; 

    </script>
  </head>

  <body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php $this->info('sitehome'); ?>">2FAuthSteganography</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li id="lang_en" class="nno"> <a href="">English</a></li>
            <li id="lang_ru" class="nno"> <a href="">Russian</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <div class="container">

      <?php if (is_readable(dirname(__FILE__)."/".$content_view.".php")) include(dirname(__FILE__)."/".$content_view.".php"); ?>

    </div> <!-- /container -->
  </body>
</html>