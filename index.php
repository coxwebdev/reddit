<?
require_once "utils.php";

if (empty($_SESSION['user']))
   redirToLogin();

$current_page = 'default';
$pages = array('default','scrapes','posts','comments','preview','download');
if ($_SESSION['superadmin'])
   $pages = array_merge($pages, array('users'));
if (!empty($_REQUEST['p']) && in_array($_REQUEST['p'], $pages))
   $current_page = $_REQUEST['p'];

$conn = db_connect();

// submitted forms --------------
if ($current_page == 'scrapes' && isset($_POST['subreddit'])) {
   if (!in_array($_REQUEST['sort'], getSorts())) {
      $_SESSION['errorMsg'] = 'Invalid Sort Selected';
      redir('index', 'default');
   }
   foreach ($_REQUEST['hours_run'] as $hour) {
      if (!in_array($hour, array_keys(getHoursToRun()))) {
         $_SESSION['errorMsg'] = 'Invalid Hours Selected';
         redir('index', 'default');
      }
   }

   $hours_run = implode(',', $_REQUEST['hours_run']);
   putScrapeToDB(createScrape($_SESSION['user']['user_id'], $_REQUEST['start_date'], $_REQUEST['end_date'], $_REQUEST['subreddit'], $_REQUEST['sort'], $hours_run), $conn);
   redir('index', 'default');

} else if ($current_page == 'users' && isset($_POST['email']) && $_SESSION['superadmin']) {
   $superadmin = !empty($_REQUEST['superadmin']);
   $user = createUser($_REQUEST['email'], '', $superadmin, date('Y-m-d H:i:s'));
   resetPassword($user);
   redir('index', 'users');
}

// actions ---------------
if (!empty($_REQUEST['action'])) {
   if ($_REQUEST['action'] == 'delete') {
      if (!empty($_REQUEST['scrape_id'])) {
         $scrape = getScrapeFromDB($_REQUEST['scrape_id'], $conn);
         if (empty($_SESSION['superadmin']) && $scrape['user_id'] != $_SESSION['user']['user_id']) {
            $_SESSION['errorMsg'] = 'You can only delete scrape requests that you submitted';
            redir('index', 'default');
         }
         deleteScrape($scrape);
         redir('index', 'default');
      }
   } else if ($_REQUEST['action'] == 'download') {
      if (!empty($_REQUEST['scrape_id'])) {
         excelDownloadScrape($_REQUEST['scrape_id'], $conn);
      } else if (!empty($_REQUEST['post_id'])) {
         excelDownloadPost($_REQUEST['post_id'], $conn);
      }
   }
}

?>

<? include_once 'header.php'; ?>
<? include_once 'nav.php'; ?>
<? include_once 'error.php'; ?>

<? include_once $current_page.'.php'; ?>

<?
$conn->Close();
?>

<? include_once 'footer.php'; ?>
