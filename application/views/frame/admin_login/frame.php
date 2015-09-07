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

    <nav>
        <a href='<?php echo base_url ();?>'>
          <div>北港</div>
          <div>
            <div>迎媽祖</div>
            <div>Beigang Mazu</div>
          </div>
        </a>
    </nav>

    <div id='container'>
      <div class='wrapper'>
        <?php echo isset ($content) ? $content : ''; ?>
      </div>
    </div>

    <?php echo render_cell ('admin_login_cell', 'loading');?>
    <?php echo render_cell ('admin_login_cell', 'footer');?>

  </body>
</html>