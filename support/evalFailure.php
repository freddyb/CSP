<?php
header("Content-type: text/javascript");
?>
(function ()
{
	eval('test(function() {assert_true(false)}, "Should always fail if eval runs.");');
})()
