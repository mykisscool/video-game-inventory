<?php
  require_once(dirname(__DIR__) . '/vendor/autoload.php');

  if (file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env')) {
      $dotEnv = new Dotenv\Dotenv(dirname(__DIR__));
      $dotEnv->load();
  }

  define('APP_PATH', getenv('APP_PATH'));
?>
<!doctype html>
<html lang="en-us">
<head>
  <title>Video Game Inventory</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" media="screen" type="text/css" href="<?php echo APP_PATH; ?>dist/video-game-inventory.css" />
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-142383298-2"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-142383298-2');
  </script>
</head>
<body>
  <header>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo APP_PATH; ?>">
            <i class="fa fa-2x fa-gamepad"></i><h1>Video Game Inventory</h1>
          </a>
        </div> <!-- // .navbar-header -->
        <div class="collapse navbar-collapse" id="navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="<?php echo APP_PATH; ?>catalog/">
                <i class="fa fa-book" aria-hidden="true"></i>Catalog
              </a>
            </li>
          </ul> <!-- // .nav -->
        </div> <!-- //.navbar-collapse -->
      </div> <!-- //.container-fluid -->
    </nav>
  </header>
