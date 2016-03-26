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
	<div id="modal-about" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="about">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="about"><i class="fa fa-gamepad"></i>Video Game Inventory</h4>
				</div> <!-- // .modal-header -->
				<div class="modal-body">
					<p>It's an application that I developed primarily using <a href="http://www.slimframework.com" target="_blank" rel="nofollow">Slim</a>, <a href="http://backbonejs.org" target="_blank" rel="nofollow">Backbone.js</a>, <a href="https://www.datatables.net/" target="_blank" rel="nofollow"> DataTables</a>, and a robust API provided by <a href="http://www.giantbomb.com/api" target="_blank" rel="nofollow">Giant Bomb</a> to report on &amp; manage my video game inventory.  You can use it, too, as it's available for free use under the <a href="https://github.com/mykisscool/video-game-inventory/blob/master/LICENSE.txt" target="_blank" rel="nofollow">MIT software license</a>.</p>
					<p>Video Game Inventory is <a href="https://github.com/mykisscool/video-game-inventory" target="_blank" rel="nofollow">hosted on GitHub</a>.</p>
				</div> <!-- // .modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div> <!-- // .modal-footer -->
			</div> <!-- // .modal-content -->
		</div> <!-- // .modal-content -->
	</div> <!-- // #modal-about -->
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
						<li><a data-toggle="modal" data-target="#modal-about">About</a></li>
					</ul> <!-- // .nav -->
				</div> <!-- //.navbar-collapse -->
			</div> <!-- //.container-fluid -->
		</nav>
	</header>
