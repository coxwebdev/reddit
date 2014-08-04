<?
require_once "utils.php";

if (!isset($conn))
   die('Invalid Request');
if (!isset($_REQUEST['scrape_id'])) {
   $_SESSION['errorMsg'] = "No scrape specified";
   redirAfterHeaders("index");
}

$scrape = getScrapeFromDB($_REQUEST['scrape_id'], $conn);
if (empty($scrape)) {
   $_SESSION['errorMsg'] = "Bad scrape specified";
   redirAfterHeaders("index");
}
$scrape['hours_run_str'] = '';
foreach (getHoursToRun() as $key => $val)
   $scrape['hours_run_str'] .= (strpos($scrape['hours_run'], $key) === false) ?'':$val;
?>

<!--<div class="searchForm">
   <form method="post" action="index.php?p=posts">
      <input type="hidden" name="scrape_id" value="<?=$scrape['scrape_id']?>" />
      <fieldset>
         <legend>Filter Scrape</legend>
         <div class="formCol">
            <label for="start_date">Start Date:</label><?=drawCalendar("start_date")?>
         </div>
         <div class="formCol">
            <label for="end_date">End Date:</label><?=drawCalendar("end_date")?>
         </div>

         <div class="formCol">
            <label for="upvote">Upvote:</label><input id="upvote" type="text" name="upvote" />
         </div>
         <div class="formCol">
            <label for="downvote">Downvote:</label><input id="downvote" type="text" name="downvote" />
         </div>
         <div class="formCol">
            <label for="reddit_user_name">Reddit Username:</label><input id="reddit_user_name" type="text" name="reddit_user_name" />
         </div>
         <div class="formCol">
            <br /><input class="button" type="submit" value="Apply Filter" />
         </div>
      </fieldset>
   </form>
</div>-->
   <fieldset>
      <legend>Scrape Details</legend>
      <div class="excel_download"><a href="index.php?action=download&scrape_id=<?=$scrape['scrape_id']?>">Download to Excel</a></div>
      <div class="scrape_subreddit"><?=$scrape['subreddit']?></div>
      <div class="scrape_sort">(<?=$scrape['sort']?>)</div>
      <br /><br />
      <div class="scrape_dates">From <?=date('d M Y', strtotime($scrape['start_date']))?> to <?=date('d M Y', strtotime($scrape['end_date']))?></div>
      <div class="scrape_hours_run">Runs at: <?=$scrape['hours_run_str']?></div>
      <div class="scrape_last_run">Last Run: <?=($scrape['last_exe'] == '0000-00-00 00:00:00') ? 'Never' : date('d M Y g:i a', strtotime($scrape['last_exe']))?></div>
   </fieldset>

<?

$posts = getPostsByScrapeID($scrape['scrape_id'], $conn);
?>
<div class="totalRows">Total Records: <?=sizeof($posts)?></div>
<div class="scrollDiv">
<?
$cols = array("top_level_comments"=>"Top Level Comments","total_comments"=>"TotalComments", "title"=>"Title", "content"=>"Content", "reddit_user"=>"Username", "karma"=>"User Karma", "user_created"=>"User Created", "upvote"=>"Upvote", "downvote"=>"Downvote", "permalink"=>"Permalink", "thumbnail"=>"Thumbnail", "created"=>"Posted On");
drawTable($posts, $cols, 'postListing', 'post_id', false, false, array("index.php?p=comments"=>"View Comments"));

?>
</div>
