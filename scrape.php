<?
require_once 'utils.php';

function getSorts() {
   return array("hot"=>"hot", "top"=>"top", "new"=>"new", "random"=>"random", "controversial"=>"controversial");
}

function getHoursToRun() {
   return array("06"=>"&nbsp;&nbsp;6:06 AM", "12"=>"12:06 PM", "18"=>"&nbsp;&nbsp;6:06 PM");
}

function getScrapesToRun($conn = '') {
   $current_date = date("Y-m-d");
   $current_hour = date("H");
   return db_select($conn, 'scrapes', '*', "start_date <= ? and end_date >= ? and hours_run like ?", array($current_date, $current_date, '%'.$current_hour.'%'));
}

function getScrapesToDisplay($conn = '') {
   $cols = "u.email, s.scrape_id, s.start_date, s.end_date, s.subreddit, s.sort, s.last_exe, s.hours_run";
   foreach (getHoursToRun() as $key => $val) {
      $cols .= ", IF(LOCATE('$key', s.hours_run) > 0, 'Yes', '-') as hours_run_$key";
   }
   return db_select($conn, 'scrapes s, users u', $cols, 's.user_id = u.user_id', '', 'start_date, end_date, subreddit');
}

function getScrapeFromDB($scrape_id, $conn = '') {
   $rows = db_select($conn, "scrapes", "*", "scrape_id = ?", array($scrape_id));
   foreach ($rows as $row) {
      return $row; // return first result
   }
   return '';
}

function getScrapeByUserFromDB($user_id, $conn = '') {
   return db_select($conn, "scrapes", "*", "user_id = ?", array($user_id));
}

function createScrape($user_id, $start_date, $end_date, $subreddit, $sort, $hours_run, $last_exe = '') {
   return array("user_id"=>$user_id, "start_date"=>$start_date, "end_date"=>$end_date, "subreddit"=>$subreddit, "sort"=>$sort, "hours_run"=>$hours_run, "last_exe"=>$last_exe);
}

function putScrapeToDB($scrape, $conn = '') {
   if (empty($scrape['scrape_id'])) {
      $id = insertObjIntoTable('scrapes', $scrape, $conn);
      $scrape['scrape_id'] = $id;
   } else {
      updateObjInTable('scrapes', $scrape, 'scrape_id', $conn);
   }
   return $scrape;
}

function deleteScrape($scrape, $conn = '') {
   deleteObjInTable('scrapes', $scrape, 'scrape_id', $conn);
}

?>
