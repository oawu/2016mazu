<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Demo extends Site_controller {

  public function __construct () {
    parent::__construct ();

    if (!(($psw = $this->uri->segment(3)) && (md5 ($psw) == '23a6a54bf45b8ea5551f958e4ed82990'))) {
      echo '密碼錯誤！';
      exit ();
    }
    if (!$this->input->is_cli_request ()) {
      echo 'Request 錯誤！';
      exit ();
    }
  }

  public function all () {
    $this->load->library ('migration');
    $a = $this->config->load('migration', TRUE);;
    $m = new CI_Migration ($this->config->item('migration'));
    $m->version (2);
    $m = new CI_Migration ($this->config->item('migration'));
    $m->latest ();

    echo "\nPicture Start!\n\n";
    $this->picture ();
    echo "\nYoutube Start!\n\n";
    $this->youtube ();
    echo "\nDintao Start!\n\n";
    $this->dintao ();
  }
  public function picture () {
    $this->load->library ('CreateDemo');

    foreach (array_merge (array (array ('title' => '2015三月十九', 'url' => ''), array ('title' => '北港舊照片', 'url' => '')), CreateDemo::pics (0, 10)) as $pic) 
      PictureTag::transaction (function () use ($pic) {
        if (!verifyCreateOrm ($tag = PictureTag::create (array (
            'name' => $pic['title'],
            'cover' => '',
            'keywords' => CreateDemo::text (2, 5),
            'sort' => PictureTag::count (),
            'cover_color_r' => 0,
            'cover_color_g' => 0,
            'cover_color_b' => 0
          ))))
          return false;
        
        echo "Tag ID: " . $tag->id;

        if ($pic['url'] && !$tag->cover->put_url ($pic['url']))
          return false;
        
        if ($pic['url'])
          delay_job ('picture_tags', 'update_cover_color', array ('id' => $tag->id));

        echo " OK\n";
        return true;
      });

    echo " ---------------\n";

    if ($tag_total = PictureTag::count ())
      foreach (CreateDemo::pics (20, 40) as $pic)
        Picture::transaction (function () use ($pic, $tag_total) {
          if (!verifyCreateOrm ($picture = Picture::create (array (
              'user_id' => 1,
              'title' => $pic['title'],
              'keywords' => CreateDemo::text (),
              'description' => CreateDemo::text (200, 500),
              'name' => '',
              'pv' => rand (0, 100),
              'color_r' => 0,
              'color_g' => 0,
              'color_b' => 0,
              'width' => 0,
              'height' => 0,
            ))))
            return false;

          echo "ID: " . $picture->id;

          if (!$picture->name->put_url ($pic['url']))
            return false;

          foreach (range (0, rand (0, 4)) as $key => $value)
            if (!($source = PictureSource::create (array (
                                    'picture_id' => $picture->id,
                                    'title' => CreateDemo::text (),
                                    'href' => CreateDemo::password (20),
                                  ))))
              return false;

          if ($tags = PictureTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
            foreach ($tags as $tag)
              if (!verifyCreateOrm ($mapping = PictureTagMapping::create (array (
                  'picture_id' => $picture->id,
                  'picture_tag_id' => $tag->id,
                  'sort' => PictureTagMapping::count (array ('conditions' => array ('picture_tag_id = ?', $tag->id)))
                ))));

          delay_job ('pictures', 'update_color_dimension', array ('id' => $picture->id));
          echo " OK\n";
          return true;
        });
  }

  public function youtube () {
    $this->load->library ('CreateDemo');

    foreach (array_merge (array (array ('title' => '紀錄片', 'url' => '')), CreateDemo::pics (0, 10)) as $pic) 
      YoutubeTag::transaction (function () use ($pic) {
        if (!verifyCreateOrm ($tag = YoutubeTag::create (array (
            'name' => $pic['title'],
            'cover' => '',
            'keywords' => CreateDemo::text (2, 5),
            'sort' => YoutubeTag::count (),
            'cover_color_r' => 0,
            'cover_color_g' => 0,
            'cover_color_b' => 0
          ))))
          return false;
        
        echo "Tag ID: " . $tag->id;

        if ($pic['url'] && !$tag->cover->put_url ($pic['url']))
          return false;
        
        if ($pic['url'])
          delay_job ('youtube_tags', 'update_cover_color', array ('id' => $tag->id));
        
        echo " OK\n";
        return true;
      });

    echo " ---------------\n";

    if ($tag_total = YoutubeTag::count ())
      foreach (Youtube::search_youtube (array (
          'q' => '北港',
          'maxResults' => rand (10, 30)
        )) as $result)
        Youtube::transaction (function () use ($result, $tag_total) {
          if (!verifyCreateOrm ($youtube = Youtube::create (array (
              'user_id' => 1,
              'title' => $result['title'],
              'keywords' => implode (' ', array_merge ($result['tags'], array (CreateDemo::text ()))),
              'description' => $result['description'] . CreateDemo::text (200, 500),
              'url' => 'https://www.youtube.com/watch?v=' . $result['id'],
              'vid' => $result['id'],
              'cover' => '',
              'pv' => rand (0, 100),
              'cover_color_r' => 0,
              'cover_color_g' => 0,
              'cover_color_b' => 0,
            ))))
            return false;

          echo "ID: " . $youtube->id;

          if (!$youtube->cover->put_url ($youtube->bigger_youtube_image_urls ()))
            return false;

          foreach (range (0, rand (0, 4)) as $key => $value)
            if (!($source = YoutubeSource::create (array (
                                    'youtube_id' => $youtube->id,
                                    'title' => CreateDemo::text (),
                                    'href' => CreateDemo::password (20),
                                  ))))
              return false;

          if ($tags = YoutubeTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
            foreach ($tags as $tag)
              if (!verifyCreateOrm ($mapping = YoutubeTagMapping::create (array (
                  'youtube_id' => $youtube->id,
                  'youtube_tag_id' => $tag->id,
                  'sort' => YoutubeTagMapping::count (array ('conditions' => array ('youtube_tag_id = ?', $tag->id)))
                ))));

          delay_job ('youtubes', 'update_cover_color', array ('id' => $youtube->id));
          echo " OK\n";
          return true;
        });
  }

  public function dintao () {
    $this->load->library ('CreateDemo');

    foreach (array_merge (array (array ('title' => '駕前陣頭', 'url' => ''), array ('title' => '地方陣頭', 'url' => ''), array ('title' => '其他介紹', 'url' => '')), CreateDemo::pics (0, 10)) as $pic) 
      DintaoTag::transaction (function () use ($pic) {
        if (!verifyCreateOrm ($tag = DintaoTag::create (array (
            'name' => $pic['title'],
            'cover' => '',
            'keywords' => CreateDemo::text (2, 5),
            'sort' => DintaoTag::count (),
            'cover_color_r' => 0,
            'cover_color_g' => 0,
            'cover_color_b' => 0
          ))))
          return false;
        
        echo "Tag ID: " . $tag->id;

        if ($pic['url'] && !$tag->cover->put_url ($pic['url']))
          return false;
        
        if ($pic['url'])
          delay_job ('dintao_tags', 'update_cover_color', array ('id' => $tag->id));

        echo " OK\n";
        return true;
      });

    echo " ---------------\n";

    if ($tag_total = DintaoTag::count ())
      foreach (CreateDemo::pics (20, 40) as $pic)
        Dintao::transaction (function () use ($pic, $tag_total) {
          if (!verifyCreateOrm ($dintao = Dintao::create (array (
              'user_id' => 1,
              'title' => $pic['title'],
              'keywords' => CreateDemo::text (),
              'description' => CreateDemo::text (200, 500),
              'cover' => '',
              'pv' => rand (0, 100),
              'cover_color_r' => 0,
              'cover_color_g' => 0,
              'cover_color_b' => 0
            ))))
            return false;

          echo "ID: " . $dintao->id;

          if (!$dintao->cover->put_url ($pic['url']))
            return false;

          foreach (range (0, rand (0, 4)) as $key => $value)
            if (!($source = DintaoSource::create (array (
                                    'dintao_id' => $dintao->id,
                                    'title' => CreateDemo::text (),
                                    'href' => CreateDemo::password (20),
                                  ))))
              return false;

          if ($tags = DintaoTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
            foreach ($tags as $tag)
              if (!verifyCreateOrm ($mapping = DintaoTagMapping::create (array (
                  'dintao_id' => $dintao->id,
                  'dintao_tag_id' => $tag->id,
                  'sort' => DintaoTagMapping::count (array ('conditions' => array ('dintao_tag_id = ?', $tag->id)))
                ))));

          delay_job ('dintaos', 'update_cover_color', array ('id' => $dintao->id));
          echo " OK\n";
          return true;
        });
  }
}
