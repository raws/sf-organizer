<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Stratofortress &mdash; Organizer</title>
	<link rel="stylesheet" type="text/css" href="assets/stylesheets/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/stylesheets/application.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="assets/javascripts/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		$(function() {
			// Set active nav bar item
			var path = window.location.pathname.toString();
			var routes = {
				"nav-item-settings": /settings/i
			};
			
			for (navItemId in routes) {
				var pattern = routes[navItemId];
				if (pattern.test(path)) {
					$("#site-nav > li").removeClass("active");
					$("#" + navItemId).addClass("active");
					break;
				}
			}
		});
	</script>
</head>
<body>
	<div class="container">
		<div id="site-navbar" class="navbar">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="/">Organizer</a>
					<ul id="site-nav" class="nav">
						<li id="nav-item-overview" class="active"><a href="/">Overview</a></li>
						<li id="nav-item-settings"><a href="/settings">Settings</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<?php if (Flight::get("organizer.settings") == false): ?>
			<div class="alert">
					<p><strong>You need to configure Organizer!</strong> Visit <a href="/settings">settings</a> to do so.</p>
			</div>
		<?php endif; ?>

		<?php if (isset($error)): ?>
			<div class="alert alert-error">
				<?php echo($error); ?>
			</div>
		<?php endif; ?>

		<?php if (isset($success)): ?>
			<div class="alert alert-success">
				<?php echo($success); ?>
			</div>
		<?php endif; ?>

		<?php echo($content); ?>
	</div>
</body>
</html>
