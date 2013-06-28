<?php

header("Content-Type: application/xml");

/*****
* First, some generic setup.  It is good to define the policy string as a variable once
* as we are likely to need to reference it later in describing the policy and checking
* reports.  For the same reason, we set the report-uri as a distinct variable and 
* combine it to form the full CSP header.
*****/
$policy_string = "script-src http://www2." . $_SERVER['HTTP_HOST'];
$title = "XSLT should not run with policy \"$policy_string\".";

/*****
* The support script report.php will write the report to a temporary file
* It can be tested asynchronously with ../support/checkReport.js*****/
$reportID=$_GET['reportID'];
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
<?php echo <<< EOXMLD
<?xml-stylesheet type="text/xsl" href="../support/test.xsl.php"?>
EOXMLD;
?>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- Yes, this metadata is important in making these test cases useful
		in assessing conformance.  Please preserve and update it. -->
		<title><?php echo $title ?></title>
		<!--meta description='<?php echo $title ?>' /-->
		<link rel="author" title="bhill@paypal-inc.com" />
		<script src="http://www2.<?php echo $_SERVER['HTTP_HOST'] ?>/resources/testharness.js"></script>
		<script src="http://www2.<?php echo $_SERVER['HTTP_HOST'] ?>/resources/testharnessreport.js"></script>
	</head>
	<body>
		<div id="log"></div>

	<!-- Often when testing CSP you want something *not* to happen. Including this support script
	(from an allowed source!) will give you and the test runner a guaranteed positive signal that
	something is happening.  -->
	<script src="http://www2.<?php echo $_SERVER['HTTP_HOST'] ?>/webappsec/tests/csp/submitted/WG/../support/fail.php"></script>

	</body>
</html>
