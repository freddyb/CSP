<?php
usleep(200000);
//Prevent Caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html");

$temp_dir = sys_get_temp_dir();
$report_file = $temp_dir . "/" . md5($_GET['reportID']) . "_cspReport.json";
$file = fopen($report_file, 'r');
$file_data = fread($file, filesize($report_file));
fclose($file);
unlink($report_file);
?>
<html>
<head>
<script>

	//reading filename <?php echo $report_file ?>
	
	function reportdecode (str) {
  	  if(str!= null){ str = str.replace(/"/g, '$'); }
  	  return decodeURIComponent((str + '').replace(/\+/g, '%20'));
	}

	var x = reportdecode("<?php echo $file_data ?>");

	if(x === null || x == '')
	{
	  report = "";
	}
	else 
	{
          report = JSON.parse(x);
	}

        self.parent.postMessage(report, "*");
	
</script>
</head>
<body>
</body>
</html>
