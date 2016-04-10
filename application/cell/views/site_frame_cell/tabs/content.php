<?php
  if (!$tabs)
    return; ?>

<div class='_t'>
  <a class='icon-chevron-left'></a>
  <div>
    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<?php foreach ($tabs as $text => $tab) { 
        $a = ($index !== null) && isset ($tab['index']) && ($tab['index'] == $index);?>
        <a<?php echo isset ($tab['href']) ? " href='" . $tab['href'] . "'" : '';?><?php echo $a ? " class='a' itemprop='url'": '';?>><?php echo $a ? '<span itemprop="title">' . $text . '</span>' : $text;?></a>
<?php } ?>
    </div>
  </div>
  <a class='icon-chevron-right'></a>
</div>