<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Edit extends Api_controller {
  
  public function xox () {
    // $this->output->delete_cache ('articles');
    // $this->output->delete_cache ('articles/10');
    $this->output->delete_all_cache ();


    
    // $a = Path::find_by_id (1);
    // $a->update_image ();
    // $a = Article::create (array (
    //   'user_id' => 1,
    //   'title' => 'xxx'));
    // echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    // var_dump ($a->cover->put (FCPATH . 'temp/a.jpg'));
    // exit ();
    // $a->cover->compressor ();
  }
  public function aaa () {
    // 新增使用者
      // 資料庫新增一筆帳號 aaa 密碼 xxx 的資料
    User::create (array (
        'name' => 'oa',
        'email' => 'xxxx',
        'uid' => 111
      ));
    echo "ok";
    // echo json_encode(array ("天氣好", '大晴天'));
  }
  public function x () {
    $paths = BaishatunComPath::find ('all', array ('order' => 'id ASC', 'conditions' => array ('is_enabled = 1')));
    $this->load->library ('SphericalGeometry');
    echo $l = round (SphericalGeometry::computeLength (array_map (function ($path) {return new LatLng ($path['a'], $path['n']);}, array_map (function ($path) {
          return array (
            'a' => $path->lat2,
            'n' => $path->lng2
          );
        }, $paths))) / 1000, 2);


    // return $this->output_json (array_map (function ($path) {
    //   return array (
    //     'a' => $path->lat,
    //     'n' => $path->lng
    //   );
    // }, $paths));
  }
  public function www () {
    if (!(($id = OAInput::post ('id')) && ($path = BaishatunComPath::find ('one', array ('select' => 'id, lat2, lng2', 'conditions' => array ('id = ?', $id))))))
      return;

    $path->lat2 = OAInput::post ('lat');
    $path->lng2 = OAInput::post ('lng');

    return $this->output_json (array (
        's' => $path->save ()
      ));
  }
  public function ooo () {
    if (!(($id = OAInput::post ('id')) && ($path = BaishatunComPath::find ('one', array ('select' => 'id, is_enabled', 'conditions' => array ('id = ?', $id))))))
      return;

    $path->is_enabled = 0;

    return $this->output_json (array (
        's' => $path->save ()
      ));
  }
  public function xxx () {
    $paths = array_map (function ($path) {
      return array (
          'id' => $path->id,
          'lat' => $path->lat2,
          'lng' => $path->lng2,
        );
    }, BaishatunComPath::all (array ('conditions' => array ('is_enabled = 1'))));

    return $this->set_frame_path ('frame', 'pure')
                ->add_js (resource_url ('resource', 'javascript', 'jrit.js'))
                ->add_js (Cfg::setting ('google', 'client_js_url'), false)
                ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
                ->load_view (array (
                    'paths' => json_encode ($paths)
                  ));
  }
}
