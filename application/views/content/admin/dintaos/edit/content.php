<form action='<?php echo base_url (array ('admin', 'dintaos', $dintao->id));?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='_method' value='put' />
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo $posts['title'] ? $posts['title'] : $dintao->title;?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' />
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <?php echo img ($dintao->cover->url (), false, 'class="cover"');?>
          <input type='file' name='cover' value='' />
          <input type='text' name='url' value='<?php echo $posts['url'] ? $posts['url'] : '';?>' placeholder='請輸入封面網址..' title='輸入封面網址!' />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keywords' value='<?php echo $posts['keywords'] ? $posts['keywords'] : $dintao->keywords;?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search'></div>
        </td>
      </tr>
      
<?php if ($tags = DintaoTag::all ()) { ?>
        <tr>
          <th>標 籤：</th>
          <td>
      <?php $tag_ids = column_array ($dintao->mappings, 'dintao_tag_id');
            foreach ($tags as $tag) { ?>
              <label><input type='checkbox' name='tag_ids[]' value='<?php echo $tag->id;?>'<?php echo $tag_ids && in_array ($tag->id, $tag_ids) ? ' checked' : '';?>/><div><?php echo $tag->name;?></div></label>
      <?php } ?>
          </td>
        </tr>
<?php }?>

      <tr>
        <th>描 述：</th>
        <td>
          <textarea name='description' class='cke' placeholder='請輸入描述..'><?php echo $posts['description'] ? $posts['description'] : $dintao->description;?></textarea>
        </td>
      </tr>

      <tr>
        <th>參 考：</th>
        <td class='s' data-ms='<?php echo $posts['sources'] ? json_encode (array_slice ($posts['sources'], 0)) : ($dintao->sources ? json_encode (array_map (function ($source) {return array ('title' => $source->title, 'href' => $source->href);}, $dintao->sources)): json_encode (array ()));?>'>
          <div class='ma'>
            <button type='button' class='icon-plus'></button>
          </div>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'dintaos');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
