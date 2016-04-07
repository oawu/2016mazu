<div class='ps'>

<?php
  if ($pictures) {
    foreach ($pictures as $picture) { ?>
      <div class='p'>
        <div class='i _ic'><img src='<?php echo $picture->name->url ('500w');?>' /></div>
        <div class='t' data-id='<?php echo $picture->id;?>'><?php echo $picture->title;?></div>
        <div class='c' data-id='<?php echo $picture->id;?>'><?php echo $picture->content;?></div>
      </div>
<?php
    }
  }
?>

</div>