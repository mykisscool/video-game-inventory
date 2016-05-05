<?php define('APP_ROOT', str_replace('//', '/', str_replace('\\', '/', dirname(substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])))) . '/')); ?>
<!doctype html>
<html lang="en-us">
<head>
	<title>Video Game Inventory</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" media="screen" type="text/css" href="<?php echo APP_ROOT; ?>dist/video-game-inventory.concat.min.css" />
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
					<a class="navbar-brand" href="<?php echo APP_ROOT; ?>">
						<i class="fa fa-2x fa-gamepad"></i><h1>Video Game Inventory</h1>
					</a>
				</div> <!-- // .navbar-header -->
				<div class="collapse navbar-collapse" id="navbar-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="<?php echo APP_ROOT; ?>catalog/">Catalog</a></li>
					</ul> <!-- // .nav -->
				</div> <!-- //.navbar-collapse -->
			</div> <!-- //.container-fluid -->
		</nav>
	</header>
