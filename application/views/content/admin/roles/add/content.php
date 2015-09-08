    <form action='<?php echo base_url (array ('admin', 'towns', 'create'));?>' method='post' enctype='multipart/form-data'>
      <table class='table-form'>
        <tbody>
          <tr>
            <th>縣市</th>
            <td>
              <input readonly type='text' id='name' name='name' value='' placeholder='請輸入名稱..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
            </td>
          </tr>
          <tr>
            <th>縣市</th>
            <td>
              <input type='text' id='name' name='name' value='' placeholder='請輸入名稱..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
            </td>
          </tr>
          <tr>
            <th>縣市</th>
            <td>
              <select>
                <option>s</option>
                <option>sd</option>
              </select>
            </td>
          </tr>
          <tr>
            <th>縣市</th>
            <td>
              <label><input type='checkbox'><div>saddsa</div></label>
              <label><input type='checkbox' disabled><div>saddsa</div></label>
            </td>
          </tr>
          <tr>
            <th>縣市</th>
            <td>
              <label><input type='radio' name='x'><div>saddsa</div></label>
              <label><input type='radio' name='x' disabled><div>saddsa</div></label>
            </td>
          </tr>

          <tr>
            <td colspan='2'>
              <a href='<?php echo base_url ('admin', 'towns');?>'>回列表</a>
              <button type='reset' class='button'>重填</button>
              <button type='submit' class='button'>確定</button>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
