      </div>
      <div id="footer">
         &copy; CoxWebDev 2013-<?=date("Y")?>
      </div>
      <script type="text/javascript">
      function init() {
         <?
         if (!empty($_SESSION['calendars_on_page'])) {
            foreach ($_SESSION['calendars_on_page'] as $cal) {
               ?>
         calendar.set("<?=$cal?>");
               <?
            }
         }
         unset($_SESSION['calendars_on_page']);
         ?>
      }
      </script>
      <script src="assets/calendar.js" type="text/javascript"></script>
      <script type="text/javascript">
         init();
      </script>
   </body>
</html>
