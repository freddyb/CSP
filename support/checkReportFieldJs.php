<?php

//Prevent Caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/javascript");

$cleanReportField = json_encode($_GET['reportField']);
$cleanReportValue = json_encode($_GET['reportValue']);

$temp_dir = sys_get_temp_dir();
$report_file = $temp_dir . "/" . md5($_GET['reportID']) . "_cspReport.json";
$file = fopen($report_file, 'r');
$file_data = fread($file, filesize($report_file));
fclose($file);
unlink($report_file);
?>
(function ()
{
	//reading filename <?php echo $report_file ?>
	
	function reportdecode (str) {
  	  if(str!= null){ str = str.replace(/"/g, '$'); }
  	  return decodeURIComponent((str + '').replace(/\+/g, '%20'));
	}


 test(function() {
	var x = reportdecode("<?php echo $file_data ?>");

	assert_false(x === null || x == '', "Report not sent.");

        report = JSON.parse(x);
	
	assert_equals(report["csp-report"][<?php echo $cleanReportField ?>],<?php echo $cleanReportValue ?>);

}, "Verify report contents.");

})();

