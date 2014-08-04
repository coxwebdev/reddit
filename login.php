<?
require_once "utils.php";

if (isset($_REQUEST['email'])) {
   $conn = db_connect();
   $user = loginUser($_REQUEST['email'], $_REQUEST['password'], $conn);
   $conn->Close();
   if ($user === false) {
      $_SESSION['errorMsg'] = 'Invalid Login';
      redirToLogin();
   } else {
      redir("index");
   }
}

?>

<? include_once 'header.php'; ?>
<? include_once 'error.php'; ?>

<div class="login">
   <form method="post" action="?">
      <label for="email">Email:</label><input id="email" type="text" name="email" />
      <label for="email">Password:</label><input id="password" type="password" name="password" />
      <input class="button" type="submit" value="Login" />
      <br />
      <a href="forgotPassword.php">Forgot Password?</a>
   </form>
</div>

<? include_once 'footer.php'; ?>
