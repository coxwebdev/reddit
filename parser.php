<?
require_once 'utils.php';

function getSubreddit($scrape, $log, $conn = '') {
	$reddit_url = getRedditURL($scrape['subreddit'], $scrape['sort']);
   $limit = 50;

   $deletedUser = getRedditUserFromDB('[deleted]');
   if (empty($deletedUser)) {
      $deletedUser = putRedditUserToDB(createRedditUser('[deleted]', '0000-00-00', 0));
   }

	$content = file_get_contents($reddit_url);
	if($content) {
      $json = json_decode($content,true);
      foreach($json['data']['children'] as $child) {
         $reddit_user_id = handleUser($child['data']['author'], $deletedUser, $conn);
         $reddit_post_id = $child['data']['id'];
         $post = getPostByRedditID($reddit_post_id, $conn);
         if (empty($post)) {
            $created = date("Y-m-d H:i:s",$child['data']['created']);
            $post = createPost($reddit_post_id, $child['data']['title'], $child['data']['url'], $reddit_user_id, $child['data']['ups'], $child['data']['downs'], $child['data']['permalink'], $child['data']['thumbnail'], $created, $child['data']['num_comments'], $scrape['scrape_id']);
         } else {
            $post['upvote'] = $child['data']['ups'];
            $post['downvote'] = $child['data']['downs'];
         }
         $post = putPostToDB($post, $conn);
         handleComments($post, $scrape, $deletedUser, $conn);
//         flush();
         if ($limit == 0) return;
         $limit--;
      }
	}
}

function handleUser($username, $deletedUser, $conn = '') {
   if ($username == '[deleted]')
      return $deletedUser['reddit_user_id'];

   $reddituser = getRedditUserFromDB($username, $conn);

   $user_url = getRedditUserURL($username);
   $user_content = file_get_contents($user_url);
   $user_json = json_decode($user_content,true);

   if (!isset($user_json['data'])) {
      return $deletedUser['reddit_user_id'];
   }
   if (empty($reddituser)) {
      $created = date("Y-m-d H:i:s",$user_json['data']['created']);
      $reddituser = createRedditUser($username, $created, $user_json['data']['link_karma']);
   } else {
      $reddituser['karma'] = $user_json['data']['link_karma'];
   }
   $reddituser = putRedditUserToDB($reddituser, $conn);
   return $reddituser['reddit_user_id'];
}

function handleComments($post, $scrape, $deletedUser, $conn = '') {
   $comment_url = getRedditCommentURL($scrape['subreddit'], $post['reddit_post_id']); //.'?sort='.$scrape['sort'].'&limit=25';
   $comment_content = file_get_contents($comment_url);
   $comment_json = json_decode($comment_content,true);
   handleCommentOrReply($comment_json[1]['data']['children'], $post, $deletedUser, $conn);
}

function handleCommentOrReply($jsonData, $post, $deletedUser, $conn = '') {
   $limit = 500;
   foreach($jsonData as $child) {
      if ($child['kind'] != 'more') {
         $reddit_comment_id = $child['data']['id'];
         $comment = getCommentFromDB($reddit_comment_id, $conn);
         if (empty($comment)) {
            $reddit_user_id = handleUser($child['data']['author'], $deletedUser, $conn);
            $comment = createComment($post['post_id'], $reddit_comment_id, $child['data']['body_html'], $reddit_user_id, $child['data']['ups'], $child['data']['downs']);
         } else {
            $comment['ups'] = $child['data']['ups'];
            $comment['downs'] = $child['data']['downs'];
         }
         if (!empty($child['data']['replies'])) {
            $comment['replies'] = sizeof($child['data']['replies']['data']['children']);
         }
         $comment = putCommentToDB($comment, $conn);
         if ($limit == 0)
            return;
         $limit--;
      }
   }
//    $log .= '\nComment added: '.$child['data']['author'];
}

?>
