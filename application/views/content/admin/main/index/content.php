<div>Hi, <?php echo User::current ()->name;?></div>
<div>您目前登入<?php echo User::current ()->login_count;?>次囉！</div>
<div>上次登入時間是<?php echo User::current ()->logined_at->format ('Y-m-d H:i:s');?></div>
<div>您目前擁有的權限如下：</div>
<ul>
  <?php echo implode ('', array_map (function ($role) {
    return '<li>' . Cfg::setting ('role', 'role_names', $role->name) . '</li>';
  }, User::current ()->roles));?>
</ul>