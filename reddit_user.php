<?
require_once 'utils.php';

function getRedditUserFromDB($name, $conn = '') {
   $rows = db_select($conn, "reddit_users", "*", "name = ?", array($name));
   foreach ($rows as $row) {
      return $row;
   }
   return '';
}

function getAllRedditUsers($conn = '') {
   return db_select($conn, "reddit_users", "reddit_user_id, name, karma, created");
}

function createRedditUser($name, $created, $karma) {
   return array("name"=>$name, "created"=>$created, "karma"=>$karma);
}

function putRedditUserToDB($reddituser, $conn = '') {
   if (empty($reddituser['reddit_user_id'])) {
      $id = insertObjIntoTable('reddit_users', $reddituser, $conn);
      $reddituser['reddit_user_id'] = $id;
   } else {
      updateObjInTable('reddit_users', $reddituser, 'reddit_user_id', $conn);
   }
   return $reddituser;
}

?>
