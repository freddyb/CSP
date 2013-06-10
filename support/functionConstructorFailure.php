<?php
header("Content-type: text/javascript");
?>
(function ()
{
	setTimeout('test(function() {assert_true(false)}, "Should always fail if eval-equivalent runs.");',0);

	setInterval('test(function() {assert_true(false)}, "Should always fail if eval-equivalent runs.");',0);
})()
