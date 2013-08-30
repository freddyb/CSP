<?php
header("Content-Type: text/javascript");
?>
(function () 
{ 
 var attachPoint = document.getElementById('attachHere');

 var blob = new Blob(['test(function() {assert_false(true)}, "Eval-equivalent script ran.");']);

 var url = window.URL.createObjectURL(blob);

 var outlineScript = document.createElement('script');
 outlineScript.src = url;

 attachPoint.appendChild(outlineScript);

})();
