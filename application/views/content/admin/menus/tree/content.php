<?php
function children_loop ($structs) {
  if ($structs) { ?>
    <ul>
<?php foreach ($structs as $struct) { ?>
        <li><span>
              <i><?php echo $struct['text'];?></i>
              <?php echo implode ('', array_map (function ($role) { return '<i class="role">' . $role['name'] . '</i>';}, $struct['roles']));?>
            </span>
      <?php children_loop ($struct['children']);?>
        </li>
<?php } ?>
    </ul>
<?php
  }
} ?>

<div class='root'>
<?php
  if (!$menu) { ?>
    <i>根目錄下</i>
  <?php
  } else {
    echo '<i>' . $menu->text . '</i>';
    echo implode ('', array_map (function ($role) { return '<i class="role">' . $role . '</i>';}, $menu->roles ()));
  }?>
  <a href='<?php echo base_url ('admin', 'menus', $menu ? $menu->id : 0);?>'>回列表</a>
</div>

<?php children_loop ($structs);?>
