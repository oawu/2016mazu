<nav>
  <div>
    <a href='<?php echo base_url ();?>' class='o'><div>北港</div><div><div>迎媽祖</div><div>Beigang Mazu</div></div></a>

    <div>
<?php if ($back_link) { ?>
        <a class='fi-a-l' href='<?php echo $back_link;?>'></a>
<?php } else { ?>
        <div class='l fi-m'></div>
<?php }?>
      <h1><?php echo $subtitle;?></h1>
    </div>

    <div>
      <div class='r fi-mr'>
        <div class='c'></div>
        <div class='menu i2'>
          <a>原始碼資源</a>
          <a class='tl'>網站作者</a>
        </div>
      </div>

      <a class='fi-sr b share'></a>

    </div>
  </div>
</nav>