<div class='container'>
<!-- 呼叫 cell 導入 main menu -->
{<{<{ echo render_cell ('demo_cell', 'main_menu', array ()); ?>
  
  <!-- 回列表按鈕 -->
  <a class='list' href='{<{<{ echo base_url (array ('tags', 'index'));?>'>列表</a>

  <!-- 資料詳細內容 -->
  <table class='table-form'>
    <tbody>
      <tr>
        <th>編號</th>
        <td>
          {<{<{ echo $tag->id;?>
        </td>
      </tr>
      <tr>
        <th>名稱</th>
        <td>
          {<{<{ echo $tag->name;?>
        </td>
      </tr>
      <tr>
        <th>活動</th>
        <td>
    {<{<{ if ($tag->events) { ?>
            <div class='units'>
        {<{<{ foreach ($tag->events as $event) { ?>
                <a class='unit' href='{<{<{ echo base_url (array ('events', 'show', $event->id));?>'>
                  <div class='id'>{<{<{ echo $event->id;?></div>
                  {<{<{ echo $event->title;?>
                </a>
        {<{<{ } ?>
            </div>
    {<{<{ } else { ?>
            沒任何活動。
    {<{<{ } ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>
