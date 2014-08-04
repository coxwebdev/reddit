<?
require_once "parser.php";

$conn = db_connect();
$scrapes = getScrapesToDisplay($conn);
//debug($scrapes);
getSubreddit($scrapes[1], $conn);
$conn->Close();


?>
