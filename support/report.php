<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//Prevent Caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html");

//header("Set-Cookie: " . $_GET['reportID'] . "=" . urlencode(file_get_contents('php://input')) . "; Path=/;");

if(isset($_GET['reportID'])) {
  $temp_dir = sys_get_temp_dir();
  $report_file = $temp_dir . "/" . md5($_GET['reportID']) . "_cspReport.json";
  $file = fopen($report_file, 'w');
  fwrite($file, urlencode(file_get_contents('php://input')));
  fclose($file);
}
else {
  echo('No reportID set!');
}
?>
<html>
<body>
temp_dir is <?php echo $temp_dir ?>

<?php echo md5($_GET['reportID']) ?>

wrote to file <?php echo $report_file ?>
</body>
</html>
