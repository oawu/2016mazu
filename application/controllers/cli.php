<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Cli extends Site_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!$this->input->is_cli_request ()) {
      echo 'Request 錯誤！';
      exit ();
    }
  }

  private function color ($string, $foreground_color = null, $background_color = null, $is_print = false) {
    if (!strlen ($string)) return "";
    $colored_string = "";
    $keys = array ('n' => '30', 'w' => '37', 'b' => '34', 'g' => '32', 'c' => '36', 'r' => '31', 'p' => '35', 'y' => '33');
    if ($foreground_color && in_array (strtolower ($foreground_color), array_map ('strtolower', array_keys ($keys)))) {
      $foreground_color = !in_array (ord ($foreground_color[0]), array_map ('ord', array_keys ($keys))) ? in_array (ord ($foreground_color[0]) | 0x20, array_map ('ord', array_keys ($keys))) ? '1;' . $keys[strtolower ($foreground_color[0])] : null : $keys[$foreground_color[0]];
      $colored_string .= $foreground_color ? "\033[" . $foreground_color . "m" : "";
    }
    $colored_string .= $background_color && in_array (strtolower ($background_color), array_map ('strtolower', array_keys ($keys))) ? "\033[" . ($keys[strtolower ($background_color[0])] + 10) . "m" : "";

    if (substr ($string, -1) == "\n") { $string = substr ($string, 0, -1); $has_new_line = true; } else { $has_new_line = false; }
    $colored_string .=  $string . "\033[0m";
    $colored_string = $colored_string . ($has_new_line ? "\n" : "");
    if ($is_print) printf ($colored_string);
    return $colored_string;
  }

  public function migration () {
    echo "\n " . $this->color ("Migration Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    $this->load->library ('migration');
    $a = $this->config->load('migration', TRUE);;
    
    echo " " . $this->color ("➜", 'r') . " Migration Back   ";
    $m = new CI_Migration ($this->config->item('migration'));
    echo str_repeat ('.', 5);
    $m->version (2);
    echo str_repeat ('.', 5) . " " . $this->color ("OK!", 'G') . "\n";

    echo " " . $this->color ("➜", 'r') . " Migration Update ";
    $m = new CI_Migration ($this->config->item('migration'));
    echo str_repeat ('.', 5);
    $m->latest ();
    echo str_repeat ('.', 5) . " " . $this->color ("OK!", 'G') . "\n";
  }
  public function dintao () {
    echo "\n " . $this->color ("Dintao Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    $this->load->library ('CreateDemo');

    echo " Create Dintao Tags.. Tag Count: " . count ($tags = array_merge (array (array ('name' => '駕前陣頭', 'is_on_site' => 1), array ('name' => '地方陣頭', 'is_on_site' => 1), array ('name' => '其他介紹', 'is_on_site' => 1)), array_map(function () { return array ('name' => CreateDemo::text (3, 3), 'is_on_site' => 0); }, range(0, rand (2, 5))))) . "\n" . str_repeat ('-', 60) . "\n";
    foreach ($tags as $t) 
      DintaoTag::transaction (function () use ($t) {
        if (!verifyCreateOrm ($tag = DintaoTag::create (array (
            'name' => $t['name'],
            'is_on_site' => $t['is_on_site'],
          ))))
          return false;
        
        echo " " . $this->color ("➜", 'r') . " Tag ID: " . $tag->id . ' ' . str_repeat ('-', 10) . " " . $this->color ("OK!", 'G') . "\n";
        return true;
      });

    echo "\n";
    echo " Create Dintao.. Dintao Count: " . count ($dins = CreateDemo::pics (20, 40, array ('北港', '朝天宮', '陣頭'))) . "\n" . str_repeat ('-', 60) . "\n";

    if ($tag_total = DintaoTag::count ())
      foreach ($dins as $din)
        Dintao::transaction (function () use ($din, $tag_total) {
          if (!verifyCreateOrm ($dintao = Dintao::create (array (
              'user_id' => 1,
              'destroy_user_id' => NULL,
              'title' => $din['title'],
              'keywords' => CreateDemo::text (),
              'content' => implode ('', array_map (function () { return '<p>' . CreateDemo::text (200, 500) . '</p>'; }, range (0, rand (2, 6)))),
              'pv' => rand (0, 100),
              'cover' => '',
              'cover_color_r' => 0,
              'cover_color_g' => 0,
              'cover_color_b' => 0,
              'cover_width' => 0,
              'cover_height' => 0,
              'is_enabled' => 1
            ))))
            return false;

          echo " " . $this->color ("➜", 'r') . " Dintao ID: " . $dintao->id . ' .';

          if (!$dintao->cover->put_url ($din['url']))
            return false;
          echo ".";

          foreach (range (0, rand (0, 4)) as $sort => $value)
            if (!($source = DintaoSource::create (array (
                                    'dintao_id' => $dintao->id,
                                    'title' => CreateDemo::text (),
                                    'href' => CreateDemo::password (20),
                                    'sort' => $sort
                                  ))))
              return false;
          echo ".";

          if ($tags = DintaoTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
            foreach ($tags as $tag)
              if (!verifyCreateOrm ($mapping = DintaoTagMapping::create (array (
                  'dintao_id' => $dintao->id,
                  'dintao_tag_id' => $tag->id,
                ))));
          echo ".";

          delay_job ('dintaos', 'update_cover_color_and_dimension', array ('id' => $dintao->id));
          echo " " . $this->color ("OK!", 'G') . "\n";
          return true;
        });
  }
  public function picture () {
    echo "\n " . $this->color ("Picture Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    $this->load->library ('CreateDemo');

    echo " Create Picture Tags.. Tag Count: " . count ($tags = array_merge (array (array ('name' => '三月十九', 'is_on_site' => 1), array ('name' => '笨港舊照片', 'is_on_site' => 1)), array_map(function () { return array ('name' => CreateDemo::text (3, 3), 'is_on_site' => 0); }, range(0, rand (2, 5))))) . "\n" . str_repeat ('-', 60) . "\n";
    foreach ($tags as $t) 
      PictureTag::transaction (function () use ($t) {
        if (!verifyCreateOrm ($tag = PictureTag::create (array (
            'name' => $t['name'],
            'is_on_site' => $t['is_on_site'],
          ))))
          return false;
        
        echo " " . $this->color ("➜", 'r') . " Tag ID: " . $tag->id . ' ' . str_repeat ('-', 10) . " " . $this->color ("OK!", 'G') . "\n";
        return true;
      });

    echo "\n";
    echo " Create Picture.. Picture Count: " . count ($pics = CreateDemo::pics (20, 40, array ('北港', '朝天宮', '陣頭'))) . "\n" . str_repeat ('-', 60) . "\n";

    if ($tag_total = PictureTag::count ())
      foreach ($pics as $pic)
        Picture::transaction (function () use ($pic, $tag_total) {
          if (!verifyCreateOrm ($picture = Picture::create (array (
              'user_id' => 1,
              'destroy_user_id' => NULL,
              'title' => $pic['title'],
              'keywords' => CreateDemo::text (),
              'content' => implode ('', array_map (function () { return '<p>' . CreateDemo::text (200, 500) . '</p>'; }, range (0, rand (2, 6)))),
              'pv' => rand (0, 100),
              'name' => '',
              'name_color_r' => 0,
              'name_color_g' => 0,
              'name_color_b' => 0,
              'name_width' => 0,
              'name_height' => 0,
              'is_enabled' => 1
            ))))
            return false;

          echo " " . $this->color ("➜", 'r') . " Picture ID: " . $picture->id . ' .';

          if (!$picture->name->put_url ($pic['url']))
            return false;
          echo ".";

          foreach (range (0, rand (0, 4)) as $sort => $value)
            if (!($source = PictureSource::create (array (
                                    'picture_id' => $picture->id,
                                    'title' => CreateDemo::text (),
                                    'href' => CreateDemo::password (20),
                                    'sort' => $sort
                                  ))))
              return false;
          echo ".";

          if ($tags = PictureTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
            foreach ($tags as $tag)
              if (!verifyCreateOrm ($mapping = PictureTagMapping::create (array (
                  'picture_id' => $picture->id,
                  'picture_tag_id' => $tag->id,
                ))));
          echo ".";

          delay_job ('pictures', 'update_name_color_and_dimension', array ('id' => $picture->id));
          echo "OK\n";
          return true;
        });
  }
  public function youtube () {
    echo "\n " . $this->color ("Youtube Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    $this->load->library ('CreateDemo');

    echo " Create Youtube Tags.. Tag Count: " . count ($tags = array_merge (array (array ('name' => '記錄北港', 'is_on_site' => 1)), array_map(function () { return array ('name' => CreateDemo::text (3, 3), 'is_on_site' => 0); }, range(0, rand (2, 5))))) . "\n" . str_repeat ('-', 60) . "\n";
    foreach ($tags as $t) 
      YoutubeTag::transaction (function () use ($t) {
        if (!verifyCreateOrm ($tag = YoutubeTag::create (array (
            'name' => $t['name'],
            'is_on_site' => $t['is_on_site'],
          ))))
          return false;
        
        echo " " . $this->color ("➜", 'r') . " Tag ID: " . $tag->id . ' ' . str_repeat ('-', 10) . " " . $this->color ("OK!", 'G') . "\n";
        return true;
      });

    echo "\n";
    echo " Create Youtube.. Youtube Count: " . count ($yous = Youtube::search_youtube (array ('q' => '犁炮', 'maxResults' => rand (10, 30)))) . "\n" . str_repeat ('-', 60) . "\n";

    if ($tag_total = YoutubeTag::count ())
      foreach ($yous as $you)
        Youtube::transaction (function () use ($you, $tag_total) {
          if (!verifyCreateOrm ($youtube = Youtube::create (array (
              'user_id' => 1,
              'destroy_user_id' => NULL,
              'title' => $you['title'],
              'keywords' => CreateDemo::text (),
              'content' => implode ('', array_map (function () { return '<p>' . CreateDemo::text (200, 500) . '</p>'; }, range (0, rand (2, 6)))),
              'pv' => rand (0, 100),
              'cover' => '',
              'cover_color_r' => 0,
              'cover_color_g' => 0,
              'cover_color_b' => 0,
              'cover_width' => 0,
              'cover_height' => 0,
              'url' => 'https://www.youtube.com/watch?v=' . $you['id'],
              'vid' => $you['id'],
              'is_enabled' => 1
            ))))
            return false;

          echo " " . $this->color ("➜", 'r') . " Youtube ID: " . $youtube->id . ' .';

          if (!$youtube->cover->put_url ($youtube->bigger_youtube_image_urls ()))
            return false;
          echo ".";

          foreach (range (0, rand (0, 4)) as $sort => $value)
            if (!($source = YoutubeSource::create (array (
                                    'youtube_id' => $youtube->id,
                                    'title' => CreateDemo::text (),
                                    'href' => CreateDemo::password (20),
                                    'sort' => $sort
                                  ))))
              return false;
          echo ".";

          if ($tags = YoutubeTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
            foreach ($tags as $tag)
              if (!verifyCreateOrm ($mapping = YoutubeTagMapping::create (array (
                  'youtube_id' => $youtube->id,
                  'youtube_tag_id' => $tag->id,
                ))));
          echo ".";

          delay_job ('youtubes', 'update_cover_color_and_dimension', array ('id' => $youtube->id));
          echo "OK\n";
          return true;
        });
  }
  public function build () {
    $this->migration ();

    $this->dintao ();
    $this->picture ();
    $this->youtube ();

    // echo "\n Youtube Start!\n\n";
    // $this->youtube ();
    // echo "\n Dintao Start!\n\n";
    // $this->dintao ();
    // echo "\n Path Start!\n\n";
    // $this->path ();
  }
}
