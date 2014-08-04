<?
require_once 'utils.php';

function getCommentFromDB($reddit_comment_id, $conn = '') {
   $rows = db_select($conn, "comments", "*", "reddit_comment_id = ?", array($reddit_comment_id));
   foreach ($rows as $row) {
      return $row;
   }
   return '';
}

function getCommentsForPost($post_id, $conn = '') {
   return db_select($conn, "comments c, reddit_users u", "c.*, u.name as reddit_user, u.karma, u.created as user_created", "c.reddit_user_id = u.reddit_user_id and c.post_id = ?", array($post_id));
}

function createComment($post_id, $reddit_comment_id, $comment, $reddit_user_id, $ups, $downs) {
   $stripHTML = array('&lt;div class="md"&gt;&lt;p&gt;', '&lt;/p&gt;', '&lt;/div&gt;');
   return array("post_id"=>$post_id, "reddit_comment_id"=>$reddit_comment_id, "comment"=>str_replace($stripHTML, '', $comment), "reddit_user_id"=>$reddit_user_id, "ups"=>$ups, "downs"=>$downs);
}

function putCommentToDB($comment, $conn = '') {
   if (empty($comment['comment_id'])) {
      $id = insertObjIntoTable('comments', $comment, $conn);
      $comment['comment_id'] = $id;
   } else {
      updateObjInTable('comments', $comment, 'comment_id', $conn);
   }
   return $comment;
}

?>
