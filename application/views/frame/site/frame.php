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

    <?php echo render_cell ('frame_cell', 'nav', 'site', isset ($subtitle) ? $subtitle : '');?>

    <div id='container'>
      <div>
        <?php echo render_cell ('frame_cell', 'wrapper_left', 'site');?>
        <div>
          <?php echo render_cell ('frame_cell', 'tabs', 'site', isset ($tabs) ? $tabs : array ());?>

          <div class='_c'>
            <?php echo isset ($content) ? $content : ''; ?>
          </div>

    <?php if ($_flash_message = Session::getData ('_flash_message', true)) { ?>
            <div class='_m'><?php echo $_flash_message;?></div>
    <?php }?>
        </div>
        <div></div>
      </div>
    </div>

    <?php echo render_cell ('frame_cell', 'footer', 'site');?>

    <div id='action' class='icon-plus'></div>
    <div id='loading'><svg class="svg" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg></div>

  </body>
</html>