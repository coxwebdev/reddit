<?
require_once 'utils.php';
require_once 'parser.php';

$log = 'Starting CronJob at '.date("Y-m-d H:i:s")."\n";
$conn = db_connect();

$scrapes = getScrapesToRun($conn);
//debug($scrapes);
foreach ($scrapes as $scrape) {
//   $log .= '\n'."Scrape: ".debug($scrape, true);
   getSubreddit($scrape, $log, $conn);
   $scrape['last_exe'] = date("Y-m-d H:i:s");
   putScrapeToDB($scrape, $conn);
//   debug($scrape);
}

$conn->Close();

//file_put_contents('logs/'.date("YmdHis").'.log', $log);
?>
