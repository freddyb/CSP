<?php
header("Content-Type: text/javascript");
?>
(function () 
{ 
 var attachPoint = document.getElementById('attachHere');

 var inlineSource = document.getElementById('inlineEvil');

 var blob = new Blob([inlineSource.textContent]);

 var url = window.URL.createObjectURL(blob);

 var outlineScript = document.createElement('script');
 outlineScript.src = url;

 attachPoint.appendChild(outlineScript);

})();
