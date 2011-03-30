<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>EXAMPLE SITE</title>

	<link rel="stylesheet" href="/api/css/reset.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/api/css/960.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="/api/css/text.css" type="text/css" media="screen" charset="utf-8" />

	<?php echo $tpl['head_extra']; ?>
</head>

<body>

<div id="nav_primary">
	<div class="container_12">
		<h1>Example Site</h1>
	</div>
</div>
<div id="nav_secondary">
	<div class="container_12">
		<?php
		if (isset($tpl['nav_sub'])) {
			include $_SERVER[DOCUMENT_ROOT].$tpl['nav_sub'].'.php';
		}
		?>
		<span class="clear">&nbsp;</span>
	</div>
</div>


<div class="container_12">
	<?php
	if (!empty($tpl['view'])) {
		require('./views/'.$tpl['view'].'.php');
	} else {
		echo '<div class="grid_7 bg"><h2>Error:</h2><p>No template specified:</p></div>';
		// dval($tpl['view']);
	}
	?>
	<span class="clear">&nbsp;</span>
</div>

<?php
if ($_GET['debug']) {
	echo '<div class="container_12"><div class="grid_7 bg">';
	dval($_SESSION, 'Session');
	dval($_REQUEST, 'Request');
	dval($controller, 'Controller');
	dval($action, 'Action');
	dval($arguments, 'Arguments');
	echo '</div></div>';
}
?>


</body>
</html>