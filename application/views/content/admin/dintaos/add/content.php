<form action='<?php echo base_url (array ('admin', 'dintaos', 'create', $tab_index));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo $posts['title'] ? $posts['title'] : '';?>' placeholder='請輸入名稱..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' />
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <input type='file' name='cover' value='' accept='image/gif, image/jpeg, image/jpg, image/png' required title='請選擇圖片(gif、jpg、png)檔案!' />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keywords' value='<?php echo $posts['keywords'] ? $posts['keywords'] : '';?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search'></div>
        </td>
      </tr>

      <tr>
        <th>內 容：</th>
        <td>
          <textarea name='content' class='ckeditor' placeholder='請輸入內容..'><?php echo $posts['content'] ? $posts['content'] : '北管是以大鑼、大鼓和鈸等高亢明亮的打擊樂器為主，加上殼仔弦、二弦、中胡等弦樂器，配合吹奏樂器如嗩吶等，是迎神、酬神、廟會不可缺少的樂陣。一百多年前的笨港北管陣頭有「和樂軒」、「集雅軒」、「北港開路鼓」（現在的金聲順開路鼓）等，後來成立了「聖震聲開路鼓」、「振樂社」（以前有高蹺表演）等。民間曲藝和宗教活動是先民謀生餘暇的兩大重要活動，先民的休閒因之有了意義，也可紓解生命的困惑，更藉此擴大或處理部分人際關係。朝天宮自清道光（或嘉慶）以來，即為全臺媽祖信仰的中心。每屆香期，真是「萬芳整隊來朝聖，無處分靈不返宮」。所以古笨港地區的核心－笨北港的民間曲藝活動特別活絡。對現今年輕一輩的北港子弟而言，可能感受不到早年子弟戲團旺盛的生命力。但是這些子弟戲團的組織和活動，卻曾因濃厚的宗教氛圍和社會環境，不論創立年代、數量或熱絡盛況皆為鄰近鄉鎮所不及。此種義務性的團體和長輩生活融為一體，不但隨著媽祖生、各社團的祭典節日，也隨婚嫁、喪事出陣參與，成為特殊的文化面。';?></textarea>
        </td>
      </tr>

      <tr>
        <th>參 考：</th>
        <td class='s' data-ms='<?php echo $posts['sources'] ? json_encode (array_slice ($posts['sources'], 0)) : json_encode (array ());?>'>
          <div class='ma'>
            <button type='button' class='icon-plus'></button>
          </div>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'dintaos', $tab_index);?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
