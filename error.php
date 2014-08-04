<?
if (!empty($_SESSION['errorMsg'])) {
?>
<div class="errorMsg"><?=$_SESSION['errorMsg']?></div><br />
<?
}
$_SESSION['errorMsg'] = '';
?>