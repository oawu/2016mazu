<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
class Paths extends Delay_controller {

  public function update_image_and_length () {
    if (!(($id = OAInput::post ('id')) && ($path = Path::find_by_id ($id, array ('select' => 'id, image, length')))))
      return ;
    
    $file_path = FCPATH . 'temp/path_' . $path->id . '.json';
    $s3_path = 'api/path/' . $path->id . '.json';

    $points = array_map (function ($point) {
      return array (
          'a' => $point->lat,
          'n' => $point->lng,
        );
    }, $path->mini_points ('', false));

    if (write_file ($file_path, json_encode ($points)))
      if (put_s3 ($file_path, $s3_path))
        @unlink ($path);

    $path->update_image ();
    $path->update_length ();
  }
}
