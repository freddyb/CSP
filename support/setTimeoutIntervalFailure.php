<?php
header("Content-type: text/javascript");
?>
(function ()
{
	var funq = new Function('test(function() {assert_false(true, "Unsafe eval ran in Function() constructor.")})');
	funq();
})()
