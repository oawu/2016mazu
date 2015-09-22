<!DOCTYPE html>
<html lang="zh">
  <head>
    <?php echo isset ($meta_list) ? $meta_list : ''; ?>

    <title><?php echo isset ($title) ? $title : ''; ?></title>

<?php echo isset ($css_list) ? $css_list : ''; ?>

<?php echo isset ($js_list) ? $js_list : ''; ?>

  </head>
  <body lang="zh-tw">
    <?php echo isset ($hidden_list) ? $hidden_list : ''; ?>

    <?php echo render_cell ('admin_cell', 'nav', isset ($subtitle) ? $subtitle : '');?>

    <div id='container'>
      <div>
        <?php echo render_cell ('site_cell', 'wrapper_left');?>
        <div>
          <?php echo isset ($content) ? $content : ''; ?>
        </div>
        <div></div>
      </div>
    </div>

    <?php echo render_cell ('site_cell', 'footer');?>

    <div id='action' class='icon-plus'></div>
    <div id='loading'><svg class="svg" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg></div>

  </body>
</html>