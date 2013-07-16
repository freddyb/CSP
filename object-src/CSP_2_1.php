<?php
/*****
* First, some generic setup.  It is good to define the policy string as a variable once
* as we are likely to need to reference it later in describing the policy and checking
* reports.  For the same reason, we set the report-uri as a distinct variable and 
* combine it to form the full CSP header.
*****/
$policy_string = "object-src 'self'";
$title = "External object should not run with policy \"$policy_string\".";
$external_url = $_SERVER['HTTP_HOST'];

/*****
* The support script report.php will write the report to a temporary file
* It can be tested asynchronously with ../support/checkReport.js*****/
$reportID=rand();
$report_string = "report-uri ../support/report.php?reportID=$reportID";

header("Content-Security-Policy: $policy_string; $report_string");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/*****
* Run tests with prefixed headers if requested.
* Note this will not really work for Mozilla, as they use
* the old, pre-1.0 directive grammar and vocabulary
*****/
if($_GET['prefixed'] == 'true') {
	header("X-Content-Security-Policy: $policy_string; $report_string");
	header("X-Webkit-CSP: $policy_string; $report_string");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Yes, this metadata is important in making these test cases useful
		in assessing conformance.  Please preserve and update it. -->
		<title><?php echo $title ?></title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta description="<?php echo $title ?>" />
		<link rel="author" title="mwobensmith@mozilla.com" />
		<script src="/resources/testharness.js"></script>
		<script src="/resources/testharnessreport.js"></script>
	</head>
	<body onLoad="object_loaded()">
		<h1><?php echo $title ?></h1>
		<div id=log></div>

		<!-- Often when testing CSP you want something *not* to happen. Including this support script
		(from an allowed source!) will give you and the test runner a guaranteed positive signal that
		something is happening.  -->
		<script src="../support/success.php"></script>


		<!-- This is the object content that we expect to be blocked. -->
		<!-- We have to construct the proper path to load the SWF based on location of media assets. --> 
		<script>
			var relativeMediaURL = "/support/media/flash.swf";
			var pageURL = window.location.toString(); 
			var temp1 = pageURL.split("//"); 
			var temp2 = temp1[1].substring (0, temp1[1].lastIndexOf("/object-src")); 
			var mediaURL = "http://www2." + temp2 + relativeMediaURL;
			var htmlStr = "<object id='flashObject' type='application/x-shockwave-flash' data='" + mediaURL + "' width='200' height='200'></object>";
			document.write (htmlStr);
		</script>

		<!-- This is our test case. -->
		<!-- First, we need to know if the plugin exists, or we cannot perform the test. -->
		<script>
			var len = navigator.mimeTypes.length;
			var allTypes = "";
			var flashMimeType = "application/x-shockwave-flash";
			for ( var i=0;i<len;i++ )
			{
				allTypes+=navigator.mimeTypes[i].type;
			}
			var hasMimeType = allTypes.indexOf(flashMimeType) != -1;

			<!-- The actual test. -->
			var test1 = async_test("Async SWF load test")

			function object_loaded()
			{
				var elem = document.getElementById("flashObject");
				var is_loaded = false;
				try {
					<!-- The Flash Player exposes values to JavaScript if a SWF has successfully been loaded. -->
					var pct_loaded = elem.PercentLoaded();
					is_loaded = true;
				} catch (e) {}

				if (hasMimeType)
				{
 					test1.step(function() {assert_false(is_loaded, "External object loaded.")});
				} else {
					test1.step(function() {assert_true(hasMimeType, "No Flash Player, cannot run test.")}); 
				}
  				test1.done();
			}
		</script>

		<!-- checkReportJs.php allows asynchronous testing of the generated reports. -->
		<script async defer src="../support/checkReportJs.php?reportID=<?php echo $reportID ?>&reportField=violated-directive&reportValue=<?php echo urlencode($policy_string) ?>"
	
		</script>

	</body>
</html>
