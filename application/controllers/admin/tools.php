<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Tools extends Admin_controller {

  public function index () {
    $this->load_view ();
  }

  public function ckeditors_upload_image () {
    $funcNum = $_GET['CKEditorFuncNum'];
    $upload = OAInput::file ('upload');

    if (!($upload && verifyCreateOrm ($img = CkeditorImage::create (array ('name' => ''))) && $img->name->put ($upload, true)))
      echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction ($funcNum, '', '上傳失敗！');</script>";
    else
      echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction ($funcNum, '" . $img->name->url ('900w') . "', '上傳成功！');</script>";
  }
  public function scws () {
  }
}
