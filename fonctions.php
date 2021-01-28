<?php

function postString($name) {
	$text = '';
	if (isset($_POST[$name])) {
		$text = $_POST[$name];
		$text = htmlspecialchars($text, ENT_QUOTES, "UTF-8");
	}
	return $text;
}

function postInt($name) {
	$int = 0;
	if (isset($_POST[$name])) {
		$int = $_POST[$name];
		$int = intval($int);
	}
	return $int;
}
