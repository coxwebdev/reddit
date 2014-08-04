<?
function drawTable($data, $headers, $cell_formatting = '', $keyCol = '', $allowEdit = false, $allowDelete = false, $links = '') {
?>
<table class="table">
   <tr>
<?
   if (!empty($links)) {
      foreach ($links as $key => $val) {
?>
      <th class="rowHead"><?=$val?></th>
<?
      }
   }
   if ($allowEdit && !empty($keyCol)) {
?>
      <th class="rowHead" width="20">Edit</th>
<?
   }
   if ($allowDelete && !empty($keyCol)) {
?>
      <th class="rowHead" width="20">Delete</th>
<?
   }
   foreach ($headers as $key => $val) {
?>
      <th class="rowHead"><?=$val?></th>
<?
   }
?>
   </tr>
<?
   $odd = true;
   $matches = array();
   foreach ($data as $row_data) {
?>
   <tr>
<?
      if (!empty($links)) {
         foreach ($links as $key => $val) {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><a href="<?=$key?><?=(strpos($key, "?")===false)?"?":"&"?><?=$keyCol?>=<?=$row_data[$keyCol]?>"><?=$val?></a></td>
<?
         }
      }
      if ($allowEdit && !empty($keyCol)) {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><a href="index.php?action=edit&<?=$keyCol?>=<?=$row_data[$keyCol]?>"><img class="icnEdit" src="assets/spacer.gif" width="16" height="16" /></a></td>
<?
      }
      if ($allowDelete && !empty($keyCol)) {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><a href="index.php?action=delete&<?=$keyCol?>=<?=$row_data[$keyCol]?>"><img class="icnDelete" src="assets/spacer.gif" width="16" height="16" /></a></td>
<?
      }

      foreach ($headers as $key => $val) {
         $value = $row_data[$key];
         if ($value == '0000-00-00' || $value == '0000-00-00 00:00:00')
            $value = '-';
         if (preg_match('/(\d\d\d\d\-\d\d\-\d\d)/', $value, $matches))
            $value = str_replace($matches[1], date("m/d/Y", strtotime($matches[1])), $value);
         if (preg_match('/(\d\d\:\d\d\:\d\d)/', $value, $matches))
            $value = str_replace($matches[1], date("g:i a", strtotime($matches[1])), $value);
         if (strpos($value, '/') === 0)
            $value = 'http://www.reddit.com'.$value;
         if (strpos($value, 'http://') === 0 || strpos($value, 'https://') === 0)
            $value = '<a href="'.$value.'" target="_blank">'.$value.'</a>';
         if ($key == 'comment')
            $value = html_entity_decode($value);

         if ($cell_formatting == 'example') {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><?=$value?></td>
<?
         } else {
?>
      <td class="<?=($odd) ? 'odd' : 'even'?>"><?=$value?></td>
<?
         }
      }
      $odd = !$odd;
?>
   </tr>
<?
   }
?>
</table>
<?
}


function drawSelect($name, $options) {
?>
<select name="<?=$name?>" id="<?=$name?>">
<?
   foreach ($options as $key => $val) {
?>
   <option value="<?=$key?>"><?=$val?></option>
<?
   }
?>
</select>
<?
}


function drawCheckboxes($name, $options) {
   foreach ($options as $key => $val) {
      $id = 'chbx'.$name.'_'.$key;
?>
<div class="checkboxDiv" id="div_<?=$id?>"><input type="checkbox" name="<?=$name?>[]" id="<?=$id?>" value="<?=$key?>" /><label for="<?=$id?>"><?=$val?></label></div>
<?
   }
}


function drawCalendar($name) {
   $_SESSION['calendars_on_page'][] = $name;
?>
<input id="<?=$name?>" readonly="readonly" type="text" name="<?=$name?>" />
<?
}
?>
