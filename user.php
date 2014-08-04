<?
require_once "utils.php";

function loginUser($email, $password, $conn = '') {
   session_destroy();
   session_start();
   $user = getUserFromDB($email, $conn);
   if (empty($user)) {
      return false;
   } else if (passwordMatches($password, $user['password'])) {
      $_SESSION['user'] = $user;
      $_SESSION['superadmin'] = $user['superadmin'];
      if ($user['change_password'])
         redirAfterHeaders("changePassword");
      redirAfterHeaders("index");
   }
   return false;
}

function logoutUser() {
   unset($_SESSION['user']);
   unset($_SESSION['superadmin']);
   session_destroy();
   redirToLogin();
}

function changePassword($user, $new_password, $conn = '') {
   $user['password'] = oneWayEncrypt($new_password);
   $user['modified'] = date('Y-m-d H:i:s');
   putUserToDB($user, $conn);
}

function resetPassword($user, $conn = '') {
   $password = generateRandomString(8);
   $user['change_password'] = 1;
   changePassword($user, $password, $conn);
   sendEmail($user['email'], 'New User Account - Reddit Scraper', 'You have been added as a user at <a href="http://reddit.coxcrew.org">http://reddit.coxcrew.org</a>. Your username is your email address and your temporary password is: '.$password);
}

function passwordMatches($password, $encrypted) {
   return oneWayEncrypt($password) == $encrypted;
}

function getAllUsers($conn = '') {
   return db_select($conn, "users", "user_id, email, IF(superadmin=1,'Yes','No') as superadmin, modified");
}

function getUserFromDB($email, $conn = '') {
   $rows = db_select($conn, "users", "*", "email = ?", array($email));
   foreach ($rows as $row) {
      return $row;
   }
   return '';
}

function createUser($email, $password, $superadmin) {
   return array("email"=>$email, "password"=>oneWayEncrypt($password), "superadmin"=>$superadmin, "change_password"=>1);
}

function putUserToDB($user, $conn = '') {
   if (empty($user['user_id'])) {
      $id = insertObjIntoTable('users', $user, $conn);
      $user['user_id'] = $id;
   } else {
      updateObjInTable('users', $user, 'user_id', $conn);
   }
   return $user;
}

?>
