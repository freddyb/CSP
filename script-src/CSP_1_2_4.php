<?php
/*****
* First, some generic setup.  It is good to define the policy string as a variable once
* as we are likely to need to reference it later in describing the policy and checking
* reports.  For the same reason, we set the report-uri as a distinct variable and 
* combine it to form the full CSP header.
*****/
$policy_string = "script-src http://www2." . $_SERVER['HTTP_HOST'];
$title = "XSLT should not run with policy \"$policy_string\".";
$reportID=rand();

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

	<iframe width="100%" height="300"
	  src="CSP_1_2_4_inner.php?reportID=<?php echo $reportID?>&prefixed=<?php echo $_GET['prefixed']?>">
	</iframe>

       <!-- checkReportJs.php allows asynchronous testing of the generated reports. -->
	<script async defer src="../support/checkReportJs.php?reportID=<?php echo $reportID ?>&reportField=violated-directive&reportValue=<?php echo urlencode($policy_string) ?>"
	>
	</script>

	</body>
</html>
