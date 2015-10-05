<form action='<?php echo base_url (array ('admin', 'pictures', 'create'));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo $posts['title'] ? $posts['title'] : '';?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' />
        </td>
      </tr>

      <tr>
        <th>照 片：</th>
        <td>
          <input type='file' name='name' value='' accept='image/gif, image/jpeg, image/jpg, image/png' required title='請選擇圖片(gif、jpg、png)檔案!' />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keywords' value='<?php echo $posts['keywords'] ? $posts['keywords'] : '';?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search'></div>
        </td>
      </tr>

<?php if ($tags = PictureTag::all ()) { ?>
        <tr>
          <th>標 籤：</th>
          <td>
      <?php foreach ($tags as $tag) { ?>
              <label><input type='checkbox' name='tag_ids[]' value='<?php echo $tag->id;?>' /><div><?php echo $tag->name;?></div></label>
      <?php } ?>
          </td>
        </tr>
<?php }?>

      <tr>
        <th>描 述：</th>
        <td>
          <textarea name='description' class='cke' placeholder='請輸入描述..'><?php echo $posts['description'] ? $posts['description'] : '';?></textarea>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'pictures');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
