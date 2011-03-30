<?php

function dval($val, $title='', $print=true, $method='var_export') {
	if ($method == 'var_export'){
		$r = var_export($val, true);
	} else {
		$r = print_r($val, true);
	}

	if ($print) {
		print "<p><strong>Debugging: <code>".$title."</code></strong></p><pre>" . htmlspecialchars($r) . "</pre><hr />";
	} else {
		return "<p><strong>Debugging: <code>".$title."</code></strong></p><pre>" . htmlspecialchars($r) . "</pre><hr />";
	}
}