<?
require_once "utils.php";

if (!isset($conn))
   die('Invalid Request');


?>

<div class="newForm">
   <form method="post" action="index.php?p=scrapes">
      <fieldset>
         <legend>Add New Scrape</legend>
         <div class="formCol">
            <label for="subreddit">Subreddit:</label><input id="subreddit" type="text" name="subreddit" />
            <label for="sort">Sort by:</label><?=drawSelect("sort", getSorts())?>
            <br /><input class="button" type="submit" target="_blank" value="Preview" onclick="this.form.target='_blank'; this.form.action='index.php?p=preview'" />
         </div>

         <div class="formCol">
            <label for="start_date">Start Date:</label><?=drawCalendar("start_date")?>
            <label for="end_date">End Date:</label><?=drawCalendar("end_date")?>
         </div>

         <div class="formCol">
            <label for="hours_to_run">Hours to Run (<?=date('T')?>):</label><?=drawCheckboxes("hours_run", getHoursToRun())?>
            <br /><input class="button" type="submit" value="Add Scrape" onclick="this.form.target='_self'; this.form.action='index.php?p=scrapes'" />
         </div>
      </fieldset>
   </form>
</div>
<br />

<?
$scrapes = getScrapesToDisplay($conn);

$cols = array("subreddit"=>"Subreddit", "sort"=>"Sort By", "start_date"=>"Start Date", "end_date"=>"End Date");
foreach (getHoursToRun() as $key => $val)
   $cols['hours_run_'.$key] = "Runs at ".str_replace('&nbsp;', '', $val);
$cols["email"] = "Requested by";
$cols["last_exe"] = "Last Run";
drawTable($scrapes, $cols, '', 'scrape_id', false, true, array("index.php?p=posts"=>"Search"));

?>
