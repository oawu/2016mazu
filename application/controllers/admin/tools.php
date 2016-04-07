<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Tools extends Admin_controller {

  public function update () {
    if (!$this->has_post ())
      return $this->output_json (array ('status' => false));

    if (!(($id = OAInput::post ('id')) && ($picture = Picture::find ('one', array ('conditions' => array ('id = ?', $id))))))
      return $this->output_json (array ('status' => false));
    
    if (!(($val = OAInput::post ('val')) && ($val = trim ($val))))
      return $this->output_json (array ('status' => false));

    if (!(($column = OAInput::post ('column')) && ($column = trim ($column)) && isset ($picture->$column)))
      return $this->output_json (array ('status' => false));

    $picture->$column = $val;
    $update = Picture::transaction (function () use ($picture) {
      return $picture->save ();
    });

    return $this->output_json (array ('status' => $update));
  }
  public function pictures () {
    $pictures = Picture::all (array ('order' => 'id DESC'));
    return $this->set_frame_path ('frame', 'pure')
                ->add_hidden (array ('id' => 'update_url', 'value' => base_url ('admin', $this->get_class (), 'update')))
                ->load_view (array (
                    'pictures' => $pictures
                  ));
  }
  public function ckeditors_browser_image () {
    $ckes = CkeditorPicture::all (array ('order' => 'id DESC'));

    return $this->set_frame_path ('frame', 'pure')
                ->load_view (array (
                    'ckes' => $ckes
                  ));
  }
  public function ckeditors_upload_image () {
    $funcNum = $_GET['CKEditorFuncNum'];
    $upload = OAInput::file ('upload');
    
    $cke = null;
    $create = CkeditorPicture::transaction (function () use ($upload, &$cke) {
      if (!($upload && verifyCreateOrm ($cke = CkeditorPicture::create (array ('name' => '')))))
        return false;
      return $cke->name->put ($upload);
    });

    if (!($create && $cke))
      echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction ($funcNum, '', '上傳失敗！');</script>";
    else
      echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction ($funcNum, '" . $cke->name->url ('400w') . "', '上傳成功！');</script>";
  }
  
  public function scws () {
    if (!$this->has_post ())
      return $this->output_json (array ('status' => false, 'words' => array ()));
    
    if (!($str = OAInput::post ('str', false)))
      return $this->output_json (array ('status' => false, 'words' => array ()));
    
    $this->load->library ('Scws');

    return $this->output_json (array ('status' => true, 'words' => Scws::explode ($str)));
  }
}
