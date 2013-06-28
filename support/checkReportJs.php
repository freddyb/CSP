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
$cleanReportExists = json_encode($_GET['reportExists']);

$temp_dir = sys_get_temp_dir();
$report_file = $temp_dir . "/" . md5($_GET['reportID']) . "_cspReport.json";
$file = fopen($report_file, 'r');
$file_data = fread($file, filesize($report_file));
fclose($file);
unlink($report_file);
?>
(function ()
{
  var reportField = <?php echo $cleanReportField ?>;
  var reportValue = <?php echo $cleanReportValue ?>;
  var reportExists = <?php echo $cleanReportExists ?>;
  var aTest = async_test("Verify report contents.");

  self.addEventListener('message', function(e) {
    var data = e.data;
    aTest.step(function() {
      if(reportExists == "false" && (e.data === null || e.data == ""))
      { 
        assert_true(true, "No report sent.");
	aTest.done();
      } 
      else if(reportExists == "true" && (e.data === null || e.data == ""))
      {
        assert_true(false, "Report not sent.");
        aTest.done();
      }
      else if(e.data === null || e.data == "")
      {
        assert_false(true, "Report not sent.");
	aTest.done();
      }
      else
      {

	// Firefox expands 'self' or origins to the actual origin value
	// so "www.example.com" becomes "http://www.example.com:80"
 	// accomodate this by just testing that the correct directive name
        // is reported, not the details... 

        assert_true(data["csp-report"][reportField].indexOf(reportValue.split(" ")[0]) != -1, reportField + " value of  \"" + data["csp-report"][reportField] + "\" did not match " + reportValue.split(" ")[0] + "." ); 
	aTest.done();
      }
    }, "");
  }, false);

  var reportLoader = document.createElement("iframe");
  reportLoader.style.display = "none";
  reportLoader.src = "../support/postMessageReport.php?reportID=<?php echo $_GET['reportID'] ?>";
  document.body.appendChild(reportLoader);

})();

