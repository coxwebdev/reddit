<?
require_once "utils.php";

if (!isset($conn))
   die('Invalid Request');

if (!isset($_REQUEST['subreddit'])) {
   $_SESSION['errorMsg'] = "No subreddit specified";
   redirAfterHeaders("index");
}
if (!isset($_REQUEST['sort'])) {
   $_SESSION['errorMsg'] = "No sort specified";
   redirAfterHeaders("index");
}

$url = getRedditURL($_REQUEST['subreddit'], $_REQUEST['sort']);
$content = file_get_contents($url);
$posts = array();
if($content) {
   $json = json_decode($content,true);
   foreach($json['data']['children'] as $child) {
      $created = date("Y-m-d H:i:s",$child['data']['created']);
      $post = createPost($child['data']['id'], $child['data']['title'], $child['data']['url'], 0, $child['data']['ups'], $child['data']['downs'], $child['data']['permalink'], $child['data']['thumbnail'], $created, $child['data']['num_comments'], 0);
      $post['reddit_user'] = $child['data']['author'];
      $posts[] = $post;
   }
}
?>

   <fieldset>
      <legend>Preview Scrape Details</legend>
      <div class="scrape_subreddit"><?=$_REQUEST['subreddit']?></div>
      <div class="scrape_sort">(<?=$_REQUEST['sort']?>)</div>
   </fieldset>

<?

?>
<div class="totalRows">Total Records: <?=sizeof($posts)?></div>
<div class="scrollDiv">
<?
$cols = array("title"=>"Title", "content"=>"Content", "reddit_user"=>"Username", "upvote"=>"Upvote", "downvote"=>"Downvote", "total_comments"=>"Comments", "permalink"=>"Permalink", "thumbnail"=>"Thumbnail", "created"=>"Posted On");
drawTable($posts, $cols, 'postListing', 'post_id');

?>
</div>
