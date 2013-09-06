<?php
/*****
* First, some generic setup.  It is good to define the policy string as a variable once
* as we are likely to need to reference it later in describing the policy and checking
* reports.  For t same reason, we set the report-uri as a distinct variable and 
* combine it to form the full CSP header.
*****/
$policy_string = "default-src *";
$title = "Worker created from inline text and loaded via blob URI should not run with policy \"$policy_string\".";

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
		<link rel="author" title="bhill@paypal-inc.com" />
		<script src="/resources/testharness.js"></script>
		<script src="/resources/testharnessreport.js"></script>
	</head>
	<body>
		<h1><?php echo $title ?></h1>
		<div id=log></div>

	<!-- Often when testing CSP you want something *not* to happen. Including this support script
	(from an allowed source!) will give you and the test runner a guaranteed positive signal that
	something is happening.  -->
	<script src="../support/success.php"></script>

	<script id="inlineWorker" type="app/worker">

		addEventListener('message', function() {
			postMessage('fail');
		}, false);

	</script>

        <script src="../support/buildInlineWorker.php"></script>

       <!-- checkReportJs.php allows asynchronous testing of the generated reports. -->
	<script async defer src="../support/checkReportJs.php?reportID=<?php echo $reportID ?>&reportField=violated-directive&reportValue=<?php echo urlencode($policy_string) ?>"
	>
	</script>

	</body>
</html>
