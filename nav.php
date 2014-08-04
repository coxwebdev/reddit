<?
require_once 'utils.php';

?>
<div class="nav">
   <a href="index.php">Scrapes</a>
   <? if (!empty($_SESSION['superadmin'])) { ?>
   <a href="index.php?p=users">Users</a>
   <? } ?>
   <a href="logout.php">Logout</a>
</div>
