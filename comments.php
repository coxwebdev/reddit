<?
require_once "utils.php";

if (!isset($conn))
   die('Invalid Request');
if (!isset($_REQUEST['post_id'])) {
   $_SESSION['errorMsg'] = "No post specified";
   redirAfterHeaders("index");
}

$post = getPostWithUserFromDB($_REQUEST['post_id'], $conn);
if (empty($post)) {
   $_SESSION['errorMsg'] = "Bad post specified";
   redirAfterHeaders("index");
}
?>

   <fieldset>
      <legend>Post Details</legend>
      <div class="excel_download"><a href="index.php?action=download&post_id=<?=$post['post_id']?>">Download to Excel</a></div>
      <img class="post_thumbnail" src="<?=$post['thumbnail']?>" />
      <div class="post_title"><a href="http://www.reddit.com<?=$post['permalink']?>" target="_blank"><?=$post['title']?></a></div>
      <div class="upvote"><?=$post['upvote']?></div>
      <div class="downvote"><?=$post['downvote']?></div>
      <br /><br />
      <div class="post_content"><?=$post['content']?></div>
      <br /><br />
      <div class="post_author"><?=$post['reddit_user']?></div>
      <div class="post_created"><?=date('d M Y g:i a', strtotime($post['created']))?></div>
   </fieldset>

<!--<div class="searchForm">
   <form method="post" action="index.php?p=comments">
      <fieldset>
         <legend>Filter Comments</legend>
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

<?

$comments = getCommentsForPost($post['post_id'], $conn);
?>
<div class="totalRows">Total Records: <?=sizeof($comments)?></div>
<div class="scrollDiv">
<?
//$sorted_comments = array();
//foreach($comments as $comment) {
//   if ($comment['parent_id'])
//}
$cols = array("comment"=>"Comment", "reddit_user"=>"Username", "karma"=>"User Karma", "user_created"=>"User Created", "ups"=>"Upvote", "downs"=>"Downvote", "replies"=>"Replies");
drawTable($comments, $cols, 'commentListing', 'comment_id');

?>
</div>
