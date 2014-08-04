<?
require_once 'utils.php';

function getPostFromDB($post_id, $conn = '') {
   $rows = db_select($conn, "posts", "*", "post_id = ?", array($post_id));
   foreach ($rows as $row) {
      return $row;
   }
   return '';
}

function getPostWithUserFromDB($post_id, $conn = '') {
   $rows = db_select($conn, "posts p, reddit_users u", "p.*, u.name as reddit_user, u.karma, u.created as user_created", "p.reddit_user_id = u.reddit_user_id and p.post_id = ?", array($post_id));
   foreach ($rows as $row) {
      return $row;
   }
   return '';
}

function getPostByRedditID($reddit_post_id, $conn = '') {
   $rows = db_select($conn, "posts", "*", "reddit_post_id = ?", array($reddit_post_id));
   foreach ($rows as $row) {
      return $row;
   }
   return '';
}

function getPostsByScrapeID($scrape_id, $conn = '') {
   return db_select($conn, 'posts p left join (select post_id, count(comment_id) as top_level_comments from comments group by post_id) c on (p.post_id = c.post_id), reddit_users u', "p.*, c.top_level_comments, u.name as reddit_user, u.karma, u.created as user_created", "p.reddit_user_id = u.reddit_user_id and p.scrape_id = ?", array($scrape_id));
}

function createPost($reddit_post_id, $title, $content, $reddit_user_id, $upvote, $downvote, $permalink, $thumbnail, $created, $num_comments, $scrape_id) {
   return array("reddit_post_id"=>$reddit_post_id, "title"=>$title, "content"=>$content, "reddit_user_id"=>$reddit_user_id, "upvote"=>$upvote, "downvote"=>$downvote, "permalink"=>$permalink, "thumbnail"=>$thumbnail, "created"=>$created, "total_comments"=>$num_comments, "scrape_id"=>$scrape_id);
}

function putPostToDB($post, $conn = '') {
   if (empty($post['post_id'])) {
      $id = insertObjIntoTable('posts', $post, $conn);
      $post['post_id'] = $id;
   } else {
      updateObjInTable('posts', $post, 'post_id', $conn);
   }
   return $post;
}

?>
