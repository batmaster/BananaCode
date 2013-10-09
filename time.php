<?php
date_default_timezone_set('Asia/Bangkok');

$range = 60*60*24;

function checkNew($time) {
	return (time() - $time) <= range ? "y" : "n";
}
?>