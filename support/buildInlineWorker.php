<?php
header("Content-Type: text/javascript");
?>
(function () 
{ 
 var workerSource = document.getElementById('inlineWorker');

 var blob = new Blob([workerSource.textContent]);

 // can I create a new script tag like this? ack...
 var url = window.URL.createObjectURL(blob);

 var worker = new Worker(url);

 worker.addEventListener('message', function(e) {
    test(function () { 
 	assert_not_equals(e.data, 'fail', 'inline script ran'); 
	})
 }, false);

 worker.postMessage('');
 
 //document.writeln('<script>test(function() {assert_false(true, "Unsafe inline script ran - document.writeln")});</script>');

})();
