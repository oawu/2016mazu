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

  public function migration ($version = 2) {
    echo "\n " . $this->color ("Migration Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    $this->load->library ('migration');
    $a = $this->config->load('migration', TRUE);;
    
    echo " " . $this->color ("➜", 'r') . " Migration Back   ";
    $m = new CI_Migration ($this->config->item('migration'));
    echo str_repeat ('.', 5);
    $m->version ($version);
    echo str_repeat ('.', 5) . " " . $this->color ("OK!", 'G') . "\n";

    echo " " . $this->color ("➜", 'r') . " Migration Update ";
    $m = new CI_Migration ($this->config->item('migration'));
    echo str_repeat ('.', 5);
    $m->latest ();
    echo str_repeat ('.', 5) . " " . $this->color ("OK!", 'G') . "\n";
  }
  public function article () {
    echo "\n " . $this->color ("Article Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    $this->load->library ('CreateDemo');

    echo " Create Article Tags.. Tag Count: " . count ($tags = array_merge (array (array ('name' => '駕前陣頭', 'is_on_site' => 1), array ('name' => '地方陣頭', 'is_on_site' => 1), array ('name' => '其他介紹', 'is_on_site' => 1)), array_map(function () { return array ('name' => CreateDemo::text (3, 3), 'is_on_site' => 0); }, range(0, rand (2, 5))))) . "\n" . str_repeat ('-', 60) . "\n";
    foreach ($tags as $t) 
      ArticleTag::transaction (function () use ($t) {
        if (!verifyCreateOrm ($tag = ArticleTag::create (array (
            'name' => $t['name'],
            'is_on_site' => $t['is_on_site'],
          ))))
          return false;
        
        echo " " . $this->color ("➜", 'r') . " Tag ID: " . $tag->id . ' ' . str_repeat ('-', 10) . " " . $this->color ("OK!", 'G') . "\n";
        return true;
      });

    echo "\n";
    echo " Create Article.. Article Count: " . count ($arts = CreateDemo::pics (20, 40, array ('北港', '朝天宮', '陣頭'))) . "\n" . str_repeat ('-', 60) . "\n";

    if ($tag_total = ArticleTag::count ())
      foreach ($arts as $art)
        if (Article::transaction (function () use ($art, $tag_total) {
                  if (!verifyCreateOrm ($article = Article::create (array (
                      'user_id' => 1,
                      'destroy_user_id' => NULL,
                      'title' => $art['title'],
                      'keywords' => CreateDemo::text (),
                      'content' => implode ('', array_map (function () { $pic = rand (0, 3) && ($pics = CreateDemo::pics (1, 5, array ('北港', '朝天宮', '陣頭'))) && ($pic = $pics[0]) && verifyCreateOrm ($cke = CkeditorPicture::create (array ('name' => ''))) && $cke->name->put_url ($pic['url']) ? '<p>' . '<img alt="" src="' . $cke->name->url ('400w') . '" style="width: 400px; height: 534px;" />' . '</p>' : ''; return $pic . '<p>' . CreateDemo::text (200, 500) . '</p>'; }, range (0, rand (2, 6)))),
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
        
                  echo " " . $this->color ("➜", 'r') . " Article ID: " . $article->id . ' .';
        
                  if (!$article->cover->put_url ($art['url']))
                    return false;
                  echo ".";
        
                  foreach (range (0, rand (0, 4)) as $sort => $value)
                    if (!($source = ArticleSource::create (array (
                                            'article_id' => $article->id,
                                            'title' => CreateDemo::text (),
                                            'href' => CreateDemo::password (20),
                                            'sort' => $sort
                                          ))))
                      return false;
                  echo ".";
        
                  if ($tags = ArticleTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
                    foreach ($tags as $tag)
                      if (!verifyCreateOrm ($mapping = ArticleTagMapping::create (array (
                          'article_id' => $article->id,
                          'article_tag_id' => $tag->id,
                        ))));
                  echo ".";
        
                  delay_job ('articles', 'update_cover_color_and_dimension', array ('id' => $article->id));
                  return true;
                }))
          echo " " . $this->color ("OK!", 'G') . "\n";
        else
          echo " " . $this->color ("ERROR!", 'R') . "\n";
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
        if (Dintao::transaction (function () use ($din, $tag_total) {
                  if (!verifyCreateOrm ($dintao = Dintao::create (array (
                      'user_id' => 1,
                      'destroy_user_id' => NULL,
                      'title' => $din['title'],
                      'keywords' => CreateDemo::text (),
                      'content' => implode ('', array_map (function () { $pic = rand (0, 3) && ($pics = CreateDemo::pics (1, 5, array ('北港', '朝天宮', '陣頭'))) && ($pic = $pics[0]) && verifyCreateOrm ($cke = CkeditorPicture::create (array ('name' => ''))) && $cke->name->put_url ($pic['url']) ? '<p>' . '<img alt="" src="' . $cke->name->url ('400w') . '" style="width: 400px; height: 534px;" />' . '</p>' : ''; return $pic . '<p>' . CreateDemo::text (200, 500) . '</p>'; }, range (0, rand (2, 6)))),
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
                  return true;
                }))
          echo " " . $this->color ("OK!", 'G') . "\n";
        else
          echo " " . $this->color ("ERROR!", 'R') . "\n";
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
        if (Picture::transaction (function () use ($pic, $tag_total) {
                  if (!verifyCreateOrm ($picture = Picture::create (array (
                      'user_id' => 1,
                      'destroy_user_id' => NULL,
                      'title' => $pic['title'],
                      'keywords' => CreateDemo::text (),
                      'content' => implode ('', array_map (function () { $pic = rand (0, 3) && ($pics = CreateDemo::pics (1, 5, array ('北港', '朝天宮', '陣頭'))) && ($pic = $pics[0]) && verifyCreateOrm ($cke = CkeditorPicture::create (array ('name' => ''))) && $cke->name->put_url ($pic['url']) ? '<p>' . '<img alt="" src="' . $cke->name->url ('400w') . '" style="width: 400px; height: 534px;" />' . '</p>' : ''; return $pic . '<p>' . CreateDemo::text (200, 500) . '</p>'; }, range (0, rand (2, 6)))),
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
                  return true;
                }))
          echo " " . $this->color ("OK!", 'G') . "\n";
        else
          echo " " . $this->color ("ERROR!", 'R') . "\n";
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
        if (Youtube::transaction (function () use ($you, $tag_total) {
                  if (!verifyCreateOrm ($youtube = Youtube::create (array (
                      'user_id' => 1,
                      'destroy_user_id' => NULL,
                      'title' => $you['title'],
                      'keywords' => CreateDemo::text (),
                      'content' => implode ('', array_map (function () { $pic = rand (0, 3) && ($pics = CreateDemo::pics (1, 5, array ('北港', '朝天宮', '陣頭'))) && ($pic = $pics[0]) && verifyCreateOrm ($cke = CkeditorPicture::create (array ('name' => ''))) && $cke->name->put_url ($pic['url']) ? '<p>' . '<img alt="" src="' . $cke->name->url ('400w') . '" style="width: 400px; height: 534px;" />' . '</p>' : ''; return $pic . '<p>' . CreateDemo::text (200, 500) . '</p>'; }, range (0, rand (2, 6)))),
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
                  return true;
                }))
          echo " " . $this->color ("OK!", 'G') . "\n";
        else
          echo " " . $this->color ("ERROR!", 'R') . "\n";
  }
  public function path () {
    $ps = array (array ('title' => '2016年 3月19下午',
                        'paths' => array (array ('latitude' => '23.567594387628766', 'longitude' => '120.30456505715847'), array ('latitude' => '23.567177242656555', 'longitude' => '120.3044938909054'), array ('latitude' => '23.567129485622374', 'longitude' => '120.30449447374349'), array ('latitude' => '23.566459113795833', 'longitude' => '120.30437770993717'), array ('latitude' => '23.566440859124423', 'longitude' => '120.30436957559596'), array ('latitude' => '23.566197651036756', 'longitude' => '120.30433193695558'), array ('latitude' => '23.566176323375803', 'longitude' => '120.30433453145042'), array ('latitude' => '23.565282221686637', 'longitude' => '120.30415339462775'), array ('latitude' => '23.56455320856034', 'longitude' => '120.30401855707169'), array ('latitude' => '23.564647433244243', 'longitude' => '120.30358328094485'), array ('latitude' => '23.5646494626261', 'longitude' => '120.30353625457292'), array ('latitude' => '23.56500988161549', 'longitude' => '120.30183792114258'), array ('latitude' => '23.566543996431655', 'longitude' => '120.30211552977562'), array ('latitude' => '23.566431703906833', 'longitude' => '120.30283293297293'), array ('latitude' => '23.566372515737058', 'longitude' => '120.3032735735178'), array ('latitude' => '23.566944301346144', 'longitude' => '120.30336736311915'), array ('latitude' => '23.56765584969732', 'longitude' => '120.3035156428814'), array ('latitude' => '23.567680617703015', 'longitude' => '120.30351220240595'), array ('latitude' => '23.56778325924684', 'longitude' => '120.30353164842131'), array ('latitude' => '23.567812944090047', 'longitude' => '120.30354363064771'), array ('latitude' => '23.568106980400117', 'longitude' => '120.30358672142029'), array ('latitude' => '23.568837942522357', 'longitude' => '120.30384814908507'), array ('latitude' => '23.56931777312747', 'longitude' => '120.30407890677452'), array ('latitude' => '23.56954105928993', 'longitude' => '120.3041230755091'), array ('latitude' => '23.569740626028807', 'longitude' => '120.3042110055685'), array ('latitude' => '23.570464202526566', 'longitude' => '120.3045187013388'), array ('latitude' => '23.570860804744644', 'longitude' => '120.3046668056727'), array ('latitude' => '23.571481192239975', 'longitude' => '120.30489698052406'), array ('latitude' => '23.571655739273744', 'longitude' => '120.30443631112576'), array ('latitude' => '23.571908340380133', 'longitude' => '120.30450470745564'), array ('latitude' => '23.57194908395867', 'longitude' => '120.30450327863696'), array ('latitude' => '23.571991671294317', 'longitude' => '120.30449045042997'), array ('latitude' => '23.572016435181002', 'longitude' => '120.30447158725269'), array ('latitude' => '23.57203259464362', 'longitude' => '120.30444400689612'), array ('latitude' => '23.57212590882681', 'longitude' => '120.30408963561058'), array ('latitude' => '23.57246350336232', 'longitude' => '120.30416599085333'), array ('latitude' => '23.57278081508801', 'longitude' => '120.30426916818624'), array ('latitude' => '23.572982043310432', 'longitude' => '120.30431494116783'), array ('latitude' => '23.57308793283711', 'longitude' => '120.30440001358988'), array ('latitude' => '23.573208572467934', 'longitude' => '120.30440931360727'), array ('latitude' => '23.573317534609686', 'longitude' => '120.30442934246071'), array ('latitude' => '23.573419121455412', 'longitude' => '120.30447351119528'), array ('latitude' => '23.573396560287012', 'longitude' => '120.30439899218095'), array ('latitude' => '23.573596637487917', 'longitude' => '120.30435249209404'), array ('latitude' => '23.57372034922479', 'longitude' => '120.3043041246176'), array ('latitude' => '23.573861883905543', 'longitude' => '120.304226252842'), array ('latitude' => '23.573975761735444', 'longitude' => '120.3041497221709'), array ('latitude' => '23.574033711638656', 'longitude' => '120.30410336635123'), array ('latitude' => '23.57409719278585', 'longitude' => '120.3040288473369'), array ('latitude' => '23.57413827083919', 'longitude' => '120.30396750738623'), array ('latitude' => '23.574468483604896', 'longitude' => '120.30416187982564'), array ('latitude' => '23.574920998067178', 'longitude' => '120.30438709766872'), array ('latitude' => '23.575243218663136', 'longitude' => '120.30455934188376'), array ('latitude' => '23.575287985813038', 'longitude' => '120.30459925532341'), array ('latitude' => '23.57531643407597', 'longitude' => '120.30466018786433'), array ('latitude' => '23.575327673942365', 'longitude' => '120.30474526028638'), array ('latitude' => '23.57531740333702', 'longitude' => '120.30481558055885'), array ('latitude' => '23.575304674399753', 'longitude' => '120.30493283948908'), array ('latitude' => '23.57529809131883', 'longitude' => '120.30504875731481'), array ('latitude' => '23.57531240412434', 'longitude' => '120.30513382973686'), array ('latitude' => '23.575331019009802', 'longitude' => '120.30518202178496'), array ('latitude' => '23.575371144335378', 'longitude' => '120.3052483187439'), array ('latitude' => '23.575413361057002', 'longitude' => '120.30530333518982'), array ('latitude' => '23.575474382150666', 'longitude' => '120.30538304319384'), array ('latitude' => '23.575497298976785', 'longitude' => '120.30541916530137'), array ('latitude' => '23.575512226192636', 'longitude' => '120.30545864017017'), array ('latitude' => '23.575520392974518', 'longitude' => '120.30551353774081'), array ('latitude' => '23.576015923716476', 'longitude' => '120.30582659792913'), array ('latitude' => '23.576141475073936', 'longitude' => '120.30590429427639'), array ('latitude' => '23.576218474367764', 'longitude' => '120.30593505196589'), array ('latitude' => '23.57637045230941', 'longitude' => '120.30597184462567'), array ('latitude' => '23.577020488997363', 'longitude' => '120.30609726905823'), array ('latitude' => '23.577093185071618', 'longitude' => '120.30610992183688'), array ('latitude' => '23.57764279118506', 'longitude' => '120.30609307031636'), array ('latitude' => '23.57828335108909', 'longitude' => '120.30603732676514'), array ('latitude' => '23.57958079061717', 'longitude' => '120.30590817332268'), array ('latitude' => '23.579758399703174', 'longitude' => '120.30480913817883'), array ('latitude' => '23.57977393775272', 'longitude' => '120.30463269522193'), array ('latitude' => '23.57976120585305', 'longitude' => '120.30450050871377'), array ('latitude' => '23.579342339286473', 'longitude' => '120.30314683914185'), array ('latitude' => '23.579166747608365', 'longitude' => '120.30251710286143'), array ('latitude' => '23.57869765813369', 'longitude' => '120.30100509524345'), array ('latitude' => '23.57859950183793', 'longitude' => '120.30099092593196'), array ('latitude' => '23.577204861881754', 'longitude' => '120.301508679986'), array ('latitude' => '23.577186600519703', 'longitude' => '120.30152468552592'), array ('latitude' => '23.576854114772377', 'longitude' => '120.30165141990187'), array ('latitude' => '23.576656835249338', 'longitude' => '120.30172115733626'), array ('latitude' => '23.576241380028073', 'longitude' => '120.30188410153391'), array ('latitude' => '23.576600732431476', 'longitude' => '120.30299730598927'), array ('latitude' => '23.576061922188046', 'longitude' => '120.3027725832701'), array ('latitude' => '23.575605111198538', 'longitude' => '120.30257754027843'), array ('latitude' => '23.57541599651639', 'longitude' => '120.30248022248747'), array ('latitude' => '23.575086756226334', 'longitude' => '120.30282479863172'), array ('latitude' => '23.57473547289503', 'longitude' => '120.30323803424835'), array ('latitude' => '23.57453204430513', 'longitude' => '120.30255742371082'), array ('latitude' => '23.574509482474753', 'longitude' => '120.30254057219031'), array ('latitude' => '23.574270585302056', 'longitude' => '120.30262028019433'), array ('latitude' => '23.57376021687857', 'longitude' => '120.30279914221774'), array ('latitude' => '23.5730109293012', 'longitude' => '120.30307039618492'), array ('latitude' => '23.57272821507578', 'longitude' => '120.30212759971619'), array ('latitude' => '23.57309100600595', 'longitude' => '120.30199071934226'), array ('latitude' => '23.573476791849085', 'longitude' => '120.30185736715794'), array ('latitude' => '23.573098816425492', 'longitude' => '120.30059538781643'), array ('latitude' => '23.573865214240254', 'longitude' => '120.30032314360142'), array ('latitude' => '23.574135198073023', 'longitude' => '120.30124908854964'), array ('latitude' => '23.57452159630259', 'longitude' => '120.30251584947109'), array ('latitude' => '23.57454451402065', 'longitude' => '120.30253118445876'), array ('latitude' => '23.575310110864798', 'longitude' => '120.30221812427044'), array ('latitude' => '23.57501405804375', 'longitude' => '120.30130541493895'), array ('latitude' => '23.574630118740078', 'longitude' => '120.3000473711968'), array ('latitude' => '23.57441465787572', 'longitude' => '120.29932133853436'), array ('latitude' => '23.575345142189114', 'longitude' => '120.29898539185524'), array ('latitude' => '23.575586851018265', 'longitude' => '120.29971285333636'), array ('latitude' => '23.575812840202527', 'longitude' => '120.30041836202145'), array ('latitude' => '23.575975881391965', 'longitude' => '120.30097483267787'), array ('latitude' => '23.576237255152254', 'longitude' => '120.30187060277467'), array ('latitude' => '23.576653502053233', 'longitude' => '120.30170622975834'), array ('latitude' => '23.576853938602547', 'longitude' => '120.30163541436195'), array ('latitude' => '23.577183527642802', 'longitude' => '120.30151127448084'), array ('latitude' => '23.57690960240137', 'longitude' => '120.30062069337373'), array ('latitude' => '23.57682496684297', 'longitude' => '120.30033629150398'), array ('latitude' => '23.576523385072658', 'longitude' => '120.29936926743994'), array ('latitude' => '23.57631741135536', 'longitude' => '120.29865145683289'), array ('latitude' => '23.577099154793043', 'longitude' => '120.2984120696783'), array ('latitude' => '23.576837520991813', 'longitude' => '120.29758384993079'), array ('latitude' => '23.57669230462253', 'longitude' => '120.29709778726101'), array ('latitude' => '23.576642085489187', 'longitude' => '120.29694280197623'), array ('latitude' => '23.576594939268432', 'longitude' => '120.29682000319963'), array ('latitude' => '23.57646666862345', 'longitude' => '120.29653224856861'), array ('latitude' => '23.576149721784187', 'longitude' => '120.29596219143878'), array ('latitude' => '23.5760982736945', 'longitude' => '120.29587560248387'), array ('latitude' => '23.576034533997866', 'longitude' => '120.29578968408123'), array ('latitude' => '23.575405986045965', 'longitude' => '120.29510021209717'), array ('latitude' => '23.57493047423387', 'longitude' => '120.29454289546015'), array ('latitude' => '23.574660493209734', 'longitude' => '120.29423788189888'), array ('latitude' => '23.574633014600586', 'longitude' => '120.29423712363246'), array ('latitude' => '23.57457910871922', 'longitude' => '120.294275927949'), array ('latitude' => '23.574521515326392', 'longitude' => '120.29429528625019'), array ('latitude' => '23.574433192514565', 'longitude' => '120.29430458626757'), array ('latitude' => '23.574211843378645', 'longitude' => '120.29433846473694'), array ('latitude' => '23.57411553092056', 'longitude' => '120.29433100094798'), array ('latitude' => '23.573479250222228', 'longitude' => '120.29424659907818'), array ('latitude' => '23.573390312909385', 'longitude' => '120.29422170093062'), array ('latitude' => '23.573498917206003', 'longitude' => '120.29379598796368'), array ('latitude' => '23.573386010738417', 'longitude' => '120.29377846589091'), array ('latitude' => '23.572886166359353', 'longitude' => '120.29366724193096'), array ('latitude' => '23.573083451547852', 'longitude' => '120.29250785708427'), array ('latitude' => '23.573416740737464', 'longitude' => '120.29258957674506'), array ('latitude' => '23.57377952924102', 'longitude' => '120.29270013015275'), array ('latitude' => '23.57447980428406', 'longitude' => '120.29288806021214'), array ('latitude' => '23.57418295765207', 'longitude' => '120.29379531741142'), array ('latitude' => '23.574635295109182', 'longitude' => '120.29421441257'), array ('latitude' => '23.574663129421936', 'longitude' => '120.29421365430358'), array ('latitude' => '23.5748845586652', 'longitude' => '120.29393528740411'), array ('latitude' => '23.575214850198794', 'longitude' => '120.29358007013798'), array ('latitude' => '23.575556121941787', 'longitude' => '120.29395280947688'), array ('latitude' => '23.576020568877848', 'longitude' => '120.29446855187416'), array ('latitude' => '23.576223557385998', 'longitude' => '120.29467767646315'), array ('latitude' => '23.576409951778363', 'longitude' => '120.29487204890256'), array ('latitude' => '23.576633220480836', 'longitude' => '120.29512006552227'), array ('latitude' => '23.576708375377226', 'longitude' => '120.29520513794432'), array ('latitude' => '23.576827779769093', 'longitude' => '120.2953679944278'), array ('latitude' => '23.576901090674124', 'longitude' => '120.29549128832832'), array ('latitude' => '23.576994062196057', 'longitude' => '120.29566548764706'), array ('latitude' => '23.576248139859963', 'longitude' => '120.29613478651049'), array ('latitude' => '23.575527059091378', 'longitude' => '120.29656134545803'), array ('latitude' => '23.575728819572888', 'longitude' => '120.29693207314017'), array ('latitude' => '23.57584638185506', 'longitude' => '120.2971753958941'), array ('latitude' => '23.575938131510252', 'longitude' => '120.29742810637958'), array ('latitude' => '23.576060610095336', 'longitude' => '120.29785516045104'), array ('latitude' => '23.57572372572924', 'longitude' => '120.29799096286297'), array ('latitude' => '23.575376048445452', 'longitude' => '120.29810218682292'), array ('latitude' => '23.57510975609353', 'longitude' => '120.29820285737514'), array ('latitude' => '23.575340225512576', 'longitude' => '120.29897063970566'), array ('latitude' => '23.574409303900996', 'longitude' => '120.29930716922286'), array ('latitude' => '23.574394731800293', 'longitude' => '120.29932116310601'), array ('latitude' => '23.57364641949034', 'longitude' => '120.29958754777908'), array ('latitude' => '23.573186703490972', 'longitude' => '120.29801979660988'), array ('latitude' => '23.573878120660765', 'longitude' => '120.29761210083961'), array ('latitude' => '23.573828517186072', 'longitude' => '120.29751478304865'), array ('latitude' => '23.573731590146657', 'longitude' => '120.29738594930177'), array ('latitude' => '23.573209443491344', 'longitude' => '120.29681481420994'), array ('latitude' => '23.57300188931888', 'longitude' => '120.29740883579257'), array ('latitude' => '23.572741121608047', 'longitude' => '120.29827125370502'), array ('latitude' => '23.572554463372665', 'longitude' => '120.29889008572104'), array ('latitude' => '23.5724095977048', 'longitude' => '120.29930708150869'), array ('latitude' => '23.572157253403812', 'longitude' => '120.30009984970093'), array ('latitude' => '23.571966292580584', 'longitude' => '120.30079713633063'), array ('latitude' => '23.571704037859188', 'longitude' => '120.30146894197469'), array ('latitude' => '23.571592360419444', 'longitude' => '120.30171092362411'), array ('latitude' => '23.571433973160616', 'longitude' => '120.30206756970892'), array ('latitude' => '23.57120462076069', 'longitude' => '120.30256815254688'), array ('latitude' => '23.570823565768485', 'longitude' => '120.30356056988239'), array ('latitude' => '23.57003643425521', 'longitude' => '120.30322185328009'), array ('latitude' => '23.56926860409709', 'longitude' => '120.30284978449345'), array ('latitude' => '23.569653965879418', 'longitude' => '120.3018157929182'), array ('latitude' => '23.569113106917374', 'longitude' => '120.30158177018166'), array ('latitude' => '23.56899159541896', 'longitude' => '120.30153004994395'), array ('latitude' => '23.568819070857387', 'longitude' => '120.30144010822778'), array ('latitude' => '23.568839535453147', 'longitude' => '120.30136693031795'), array ('latitude' => '23.568835233148064', 'longitude' => '120.30128713459976'), array ('latitude' => '23.568796694682767', 'longitude' => '120.30122066221247'), array ('latitude' => '23.56873020201016', 'longitude' => '120.30117876827717'), array ('latitude' => '23.5686726105061', 'longitude' => '120.30116594007018'), array ('latitude' => '23.56860070043971', 'longitude' => '120.30119410326483'), array ('latitude' => '23.56854741129212', 'longitude' => '120.30124028365617'), array ('latitude' => '23.568519388719587', 'longitude' => '120.30130751430988'), array ('latitude' => '23.567955352088486', 'longitude' => '120.30121958425048'), array ('latitude' => '23.567659288287157', 'longitude' => '120.30117121677404'), array ('latitude' => '23.567233538756685', 'longitude' => '120.30113558979042'), array ('latitude' => '23.567183937831853', 'longitude' => '120.30113885483752'), array ('latitude' => '23.566723467026367', 'longitude' => '120.30108958482742'), array ('latitude' => '23.56655075731467', 'longitude' => '120.30207060277462'), array ('latitude' => '23.566925862493022', 'longitude' => '120.30214159359934'), array ('latitude' => '23.567512643033037', 'longitude' => '120.3022375702858'), array ('latitude' => '23.567559537532343', 'longitude' => '120.30240847339633'), array ('latitude' => '23.567595368804835', 'longitude' => '120.30251366238599'), array ('latitude' => '23.567612761419262', 'longitude' => '120.30262086303242'), array ('latitude' => '23.56762831015615', 'longitude' => '120.30273208699236'), array ('latitude' => '23.56763126487337', 'longitude' => '120.30298791825771'), array ('latitude' => '23.567721182663103', 'longitude' => '120.30299520661833'), array ('latitude' => '23.567767710588253', 'longitude' => '120.3035156428814'), array ('latitude' => '23.567765435211072', 'longitude' => '120.30354438891413'), array ('latitude' => '23.567679205275816', 'longitude' => '120.30352838337421'), array ('latitude' => '23.567657876723747', 'longitude' => '120.30358529260161'), array ('latitude' => '23.567640850532563', 'longitude' => '120.30360666255956'), array ('latitude' => '23.56760600035086', 'longitude' => '120.30361864478596'), array ('latitude' => '23.567514605048', 'longitude' => '120.30362794480334'), array ('latitude' => '23.567460087043745', 'longitude' => '120.30362852764142'), array ('latitude' => '23.567409871410273', 'longitude' => '120.30363581600204'), array ('latitude' => '23.567285416441422', 'longitude' => '120.30368185212615'), array ('latitude' => '23.567298754677207', 'longitude' => '120.30362896621227'), array ('latitude' => '23.56710656124277', 'longitude' => '120.30363290181162'), array ('latitude' => '23.56697644468391', 'longitude' => '120.3036133680821'), array ('latitude' => '23.566849401216825', 'longitude' => '120.30357774109848'), array ('latitude' => '23.56679734194117', 'longitude' => '120.30356290123473'), array ('latitude' => '23.566750814307614', 'longitude' => '120.3035259331466'), array ('latitude' => '23.566694452692726', 'longitude' => '120.3034842711927'), array ('latitude' => '23.566654685978712', 'longitude' => '120.30346607856768'), array ('latitude' => '23.566610375995253', 'longitude' => '120.30345931649208'), array ('latitude' => '23.566584561724518', 'longitude' => '120.30348546802998'), array ('latitude' => '23.566568765462595', 'longitude' => '120.303565176034'), array ('latitude' => '23.566420026097532', 'longitude' => '120.30437922647002'), array ('latitude' => '23.566196117799723', 'longitude' => '120.3043457865715'), array ('latitude' => '23.566079338547123', 'longitude' => '120.30502505600452'), array ('latitude' => '23.565970733749438', 'longitude' => '120.30498875846865'), array ('latitude' => '23.565847377849387', 'longitude' => '120.30494910817151'), array ('latitude' => '23.56579962134409', 'longitude' => '120.304932256651'), array ('latitude' => '23.56539845244821', 'longitude' => '120.3048543848754'), array ('latitude' => '23.565180749456804', 'longitude' => '120.30482657253742'), array ('latitude' => '23.565276817106984', 'longitude' => '120.30417470803263'), array ('latitude' => '23.56535653412638', 'longitude' => '120.3037691116333'), array ('latitude' => '23.564635569632436', 'longitude' => '120.30355721712112'), array ('latitude' => '23.564538642808117', 'longitude' => '120.30401712825301'), array ('latitude' => '23.564541901547525', 'longitude' => '120.3040378276587'), array ('latitude' => '23.56436820327727', 'longitude' => '120.30492581427097'), array ('latitude' => '23.56514159868775', 'longitude' => '120.30504776706698'), array ('latitude' => '23.565517938223994', 'longitude' => '120.3051449094296'), array ('latitude' => '23.5660399433367', 'longitude' => '120.30523601682194'), array ('latitude' => '23.566472825226633', 'longitude' => '120.30533651194582'), array ('latitude' => '23.56656397394314', 'longitude' => '120.30534715306771'), array ('latitude' => '23.56670183403574', 'longitude' => '120.30538059296623'), array ('latitude' => '23.566988984716772', 'longitude' => '120.30543275177479'), array ('latitude' => '23.56715818968353', 'longitude' => '120.30450461974146'), array ('latitude' => '23.567591929145415', 'longitude' => '120.3045804798603')),
                        'infos' => array (
                            array ('type' => 1, 'title' => '歲次乙未年 二十下午繞境起馬', 'description' => '農曆三月二十下午繞境起馬', 'latitude' => '23.567600533836917', 'longitude' => '120.30456438660622', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/din-tao/an-start.jpg'),
                            array ('type' => 1, 'title' => '歲次乙未年 二十下午繞境落馬', 'description' => '農曆三月二十下午繞境落馬', 'latitude' => '23.567596231491233', 'longitude' => '120.30458383262157', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/din-tao/an-end.jpg'),
                            )),
                 array ('title' => '2016年 3月19晚間',
                        'paths' => array (array ('latitude' => '23.567596231491233', 'longitude' => '120.30456505715847'), array ('latitude' => '23.567153886987377', 'longitude' => '120.30448852648738'), array ('latitude' => '23.566195255347438', 'longitude' => '120.30432884733682'), array ('latitude' => '23.56528394408744', 'longitude' => '120.30414569885738'), array ('latitude' => '23.564566730530967', 'longitude' => '120.30401319265366'), array ('latitude' => '23.564537413575767', 'longitude' => '120.30404730310443'), array ('latitude' => '23.564275393219845', 'longitude' => '120.30540592968464'), array ('latitude' => '23.56542352892103', 'longitude' => '120.30562587082386'), array ('latitude' => '23.56551695279785', 'longitude' => '120.30514508485794'), array ('latitude' => '23.566043260311886', 'longitude' => '120.30523619225028'), array ('latitude' => '23.566469995927587', 'longitude' => '120.30533601682191'), array ('latitude' => '23.566551925262907', 'longitude' => '120.3053453168393'), array ('latitude' => '23.566699619377935', 'longitude' => '120.30538009784232'), array ('latitude' => '23.566989905980467', 'longitude' => '120.30543633651746'), array ('latitude' => '23.567302318121385', 'longitude' => '120.30550263347641'), array ('latitude' => '23.567629421011357', 'longitude' => '120.30554741621017'), array ('latitude' => '23.568335003639945', 'longitude' => '120.30601479113102'), array ('latitude' => '23.568719321596376', 'longitude' => '120.30542729964259'), array ('latitude' => '23.56889860655309', 'longitude' => '120.30511021614075'), array ('latitude' => '23.569569946356715', 'longitude' => '120.30557280948165'), array ('latitude' => '23.569964958774044', 'longitude' => '120.30587196350098'), array ('latitude' => '23.57023845982174', 'longitude' => '120.30586190521717'), array ('latitude' => '23.570426711090796', 'longitude' => '120.30546216835978'), array ('latitude' => '23.570494498979365', 'longitude' => '120.30534540455346'), array ('latitude' => '23.570549994641933', 'longitude' => '120.3052480867625'), array ('latitude' => '23.570592583496644', 'longitude' => '120.30515211007605'), array ('latitude' => '23.570627506398164', 'longitude' => '120.30504919588566'), array ('latitude' => '23.570552705286985', 'longitude' => '120.30501960387232'), array ('latitude' => '23.570450861464643', 'longitude' => '120.3049638603211'), array ('latitude' => '23.57030422291008', 'longitude' => '120.30493251979351'), array ('latitude' => '23.570167346387933', 'longitude' => '120.30487476458552'), array ('latitude' => '23.56992475696296', 'longitude' => '120.30477208237653'), array ('latitude' => '23.569692615662195', 'longitude' => '120.30463050813682'), array ('latitude' => '23.569538529840962', 'longitude' => '120.30451977930079'), array ('latitude' => '23.569367556751963', 'longitude' => '120.30436925590038'), array ('latitude' => '23.56953964814807', 'longitude' => '120.30412316322327'), array ('latitude' => '23.569321460804026', 'longitude' => '120.3040836006403'), array ('latitude' => '23.56875048432057', 'longitude' => '120.30499689280987'), array ('latitude' => '23.56845239550393', 'longitude' => '120.30481651425362'), array ('latitude' => '23.56841631568573', 'longitude' => '120.30486738851073'), array ('latitude' => '23.568351963493054', 'longitude' => '120.30491222779756'), array ('latitude' => '23.56827654820085', 'longitude' => '120.3049409738303'), array ('latitude' => '23.56821576781727', 'longitude' => '120.30494593083858'), array ('latitude' => '23.56811515319888', 'longitude' => '120.3049431609154'), array ('latitude' => '23.567972744490685', 'longitude' => '120.30492563884263'), array ('latitude' => '23.567836548012245', 'longitude' => '120.30489765107632'), array ('latitude' => '23.567759903576402', 'longitude' => '120.30487476458552'), array ('latitude' => '23.567713990171264', 'longitude' => '120.30484047870641'), array ('latitude' => '23.567682213055413', 'longitude' => '120.30480082840927'), array ('latitude' => '23.567655352920518', 'longitude' => '120.30474508485804'), array ('latitude' => '23.567627878179916', 'longitude' => '120.30468062412751'), array ('latitude' => '23.567609008145407', 'longitude' => '120.30461549284473'), array ('latitude' => '23.56761282625235', 'longitude' => '120.30457846820354'), array ('latitude' => '23.567632062713155', 'longitude' => '120.30452071299555'), array ('latitude' => '23.56766973777209', 'longitude' => '120.3044683222056'), array ('latitude' => '23.567736914562634', 'longitude' => '120.3044105669976'), array ('latitude' => '23.567795486589517', 'longitude' => '120.30437292835722'), array ('latitude' => '23.567879257947457', 'longitude' => '120.30435540628446'), array ('latitude' => '23.567989588227416', 'longitude' => '120.30435986816883'), array ('latitude' => '23.568238508678412', 'longitude' => '120.30440613627434'), array ('latitude' => '23.568350551814913', 'longitude' => '120.30444628169539'), array ('latitude' => '23.568410966952', 'longitude' => '120.30448374490743'), array ('latitude' => '23.568457927055142', 'longitude' => '120.3045342117548'), array ('latitude' => '23.56884101533019', 'longitude' => '120.30384814908507'), array ('latitude' => '23.569115315614198', 'longitude' => '120.30319628458028'), array ('latitude' => '23.569270812791295', 'longitude' => '120.30284893851285'), array ('latitude' => '23.56965273665707', 'longitude' => '120.30182383954525'), array ('latitude' => '23.57025892356163', 'longitude' => '120.30212885310652'), array ('latitude' => '23.570535315370506', 'longitude' => '120.30226238071918'), array ('latitude' => '23.570034409655854', 'longitude' => '120.3032199293375'), array ('latitude' => '23.570818648922682', 'longitude' => '120.30355922877789'), array ('latitude' => '23.57046832318315', 'longitude' => '120.30452348291874'), array ('latitude' => '23.57148119223995', 'longitude' => '120.30489765107632'), array ('latitude' => '23.571826164136343', 'longitude' => '120.30397354235652'), array ('latitude' => '23.572146190612802', 'longitude' => '120.30300535261631'), array ('latitude' => '23.57120725894207', 'longitude' => '120.3025566654444'), array ('latitude' => '23.570563332937905', 'longitude' => '120.30226555805211'), array ('latitude' => '23.570538929643654', 'longitude' => '120.30224803597935'), array ('latitude' => '23.570261922559233', 'longitude' => '120.30211316726218'), array ('latitude' => '23.569658990387584', 'longitude' => '120.301811418748'), array ('latitude' => '23.569637660653417', 'longitude' => '120.30180864882482'), array ('latitude' => '23.568991884298196', 'longitude' => '120.3015296113731'), array ('latitude' => '23.568821165074763', 'longitude' => '120.30143894255161'), array ('latitude' => '23.568840400438607', 'longitude' => '120.30137045850756'), array ('latitude' => '23.568837327363582', 'longitude' => '120.30129334499838'), array ('latitude' => '23.5687975596669', 'longitude' => '120.3012194965363'), array ('latitude' => '23.568728972779144', 'longitude' => '120.30117809772491'), array ('latitude' => '23.568670766659682', 'longitude' => '120.30116728117468'), array ('latitude' => '23.568599471208415', 'longitude' => '120.30119075050357'), array ('latitude' => '23.568547411294002', 'longitude' => '120.30123760144716'), array ('latitude' => '23.568515086404034', 'longitude' => '120.30130751430988'), array ('latitude' => '23.567663839764123', 'longitude' => '120.30117407441139'), array ('latitude' => '23.56761915563998', 'longitude' => '120.30101104249957'), array ('latitude' => '23.567588607815107', 'longitude' => '120.30085538666253'), array ('latitude' => '23.567551978784707', 'longitude' => '120.30072949826717'), array ('latitude' => '23.5675171286471', 'longitude' => '120.30072203447821'), array ('latitude' => '23.567428191836495', 'longitude' => '120.30075547437673'), array ('latitude' => '23.567359537558573', 'longitude' => '120.30080232532032'), array ('latitude' => '23.5673136243183', 'longitude' => '120.30085454068194'), array ('latitude' => '23.567273242698043', 'longitude' => '120.30088663947595'), array ('latitude' => '23.567213322161876', 'longitude' => '120.30090652406216'), array ('latitude' => '23.567128687737995', 'longitude' => '120.30066637864115'), array ('latitude' => '23.56705554757963', 'longitude' => '120.30039681663516'), array ('latitude' => '23.567039751015795', 'longitude' => '120.30028541724687'), array ('latitude' => '23.567041163918894', 'longitude' => '120.30017401785858'), array ('latitude' => '23.567043071587907', 'longitude' => '120.30009515583515'), array ('latitude' => '23.567038338257554', 'longitude' => '120.3000615405083'), array ('latitude' => '23.567023156328265', 'longitude' => '120.30003463070398'), array ('latitude' => '23.567006130538033', 'longitude' => '120.30001643807896'), array ('latitude' => '23.56687282079327', 'longitude' => '120.2999945729971'), array ('latitude' => '23.566723467026367', 'longitude' => '120.30109092593193'), array ('latitude' => '23.56717275706318', 'longitude' => '120.30113719403744'), array ('latitude' => '23.56723255901619', 'longitude' => '120.3011350946665'), array ('latitude' => '23.56723458639532', 'longitude' => '120.30127582292562'), array ('latitude' => '23.56723845763801', 'longitude' => '120.30135351927288'), array ('latitude' => '23.567255116492834', 'longitude' => '120.30142955482006'), array ('latitude' => '23.56729156270237', 'longitude' => '120.30149585177901'), array ('latitude' => '23.5673538229987', 'longitude' => '120.30156684260373'), array ('latitude' => '23.567419156326395', 'longitude' => '120.30165593833931'), array ('latitude' => '23.56743962220612', 'longitude' => '120.30171821198473'), array ('latitude' => '23.567458858824146', 'longitude' => '120.30183010649694'), array ('latitude' => '23.56747748080335', 'longitude' => '120.3019440126659'), array ('latitude' => '23.56748442496073', 'longitude' => '120.3020921169998'), array ('latitude' => '23.567507111442005', 'longitude' => '120.3022375702858'), array ('latitude' => '23.567803541843848', 'longitude' => '120.3022871034384'), array ('latitude' => '23.568077845084474', 'longitude' => '120.3023453537703'), array ('latitude' => '23.56823967253239', 'longitude' => '120.30238080532558'), array ('latitude' => '23.56839031919102', 'longitude' => '120.30243337154388'), array ('latitude' => '23.56822578433004', 'longitude' => '120.30310517718794'), array ('latitude' => '23.56810698040014', 'longitude' => '120.30358538031578'), array ('latitude' => '23.56776420571177', 'longitude' => '120.30353701283934'), array ('latitude' => '23.56735013425947', 'longitude' => '120.30344975333219'), array ('latitude' => '23.56694220805604', 'longitude' => '120.30336785824306'), array ('latitude' => '23.566585909452517', 'longitude' => '120.30331077358733'), array ('latitude' => '23.566373130363626', 'longitude' => '120.3032749146223'), array ('latitude' => '23.56624897574308', 'longitude' => '120.30394949018955'), array ('latitude' => '23.56537005601427', 'longitude' => '120.30371814966202'), array ('latitude' => '23.565490093978088', 'longitude' => '120.30308640172484'), array ('latitude' => '23.565493351969607', 'longitude' => '120.30299780111318'), array ('latitude' => '23.56553533168351', 'longitude' => '120.30281129987247'), array ('latitude' => '23.565622793983938', 'longitude' => '120.30227879366885'), array ('latitude' => '23.56566692256642', 'longitude' => '120.30195660889149'), array ('latitude' => '23.565009266982575', 'longitude' => '120.30183993279934'), array ('latitude' => '23.56455505246552', 'longitude' => '120.30398368835449'), array ('latitude' => '23.564562613640962', 'longitude' => '120.30402986874583'), array ('latitude' => '23.565280690912655', 'longitude' => '120.3041638914824'), array ('latitude' => '23.566200976282413', 'longitude' => '120.30434619398125'), array ('latitude' => '23.567147068670263', 'longitude' => '120.30450502715121'), array ('latitude' => '23.567593773007935', 'longitude' => '120.30458249151707')),
                        'infos' => array (
                            array ('type' => 1, 'title' => '歲次乙未年 十九晚間繞境起馬', 'description' => '農曆三月十九晚間繞境起馬', 'latitude' => '23.56759807535368', 'longitude' => '120.30456371605396', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/din-tao/ni-start.jpg'),
                            array ('type' => 1, 'title' => '歲次乙未年 十九晚間繞境落馬', 'description' => '農曆三月十九晚間繞境落馬', 'latitude' => '23.567596231491247', 'longitude' => '120.30458651483059', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/din-tao/ni-end.jpg'),
                            )),
                 array ('title' => '2016年 3月20下午',
                        'paths' => array (array ('latitude' => '23.567600533836917', 'longitude' => '120.30456438660622'), array ('latitude' => '23.567177674045556', 'longitude' => '120.30449330806732'), array ('latitude' => '23.56713790713946', 'longitude' => '120.30449590256217'), array ('latitude' => '23.566212896304233', 'longitude' => '120.30433966388705'), array ('latitude' => '23.566175588286335', 'longitude' => '120.30432415347104'), array ('latitude' => '23.565304227961857', 'longitude' => '120.30415106327541'), array ('latitude' => '23.565260159465005', 'longitude' => '120.30415231666575'), array ('latitude' => '23.564545403870195', 'longitude' => '120.30401981046202'), array ('latitude' => '23.564368817913188', 'longitude' => '120.30492581427097'), array ('latitude' => '23.565146944685694', 'longitude' => '120.3050485253334'), array ('latitude' => '23.5652243883303', 'longitude' => '120.3045603632927'), array ('latitude' => '23.566128508771392', 'longitude' => '120.30474007129669'), array ('latitude' => '23.56604184623875', 'longitude' => '120.30523493885994'), array ('latitude' => '23.565515108906183', 'longitude' => '120.30514441430569'), array ('latitude' => '23.56540017294145', 'longitude' => '120.30576467514038'), array ('latitude' => '23.56527128535443', 'longitude' => '120.30650621821883'), array ('latitude' => '23.56574866831077', 'longitude' => '120.30656397342682'), array ('latitude' => '23.56614405910312', 'longitude' => '120.30661149492266'), array ('latitude' => '23.56621043852074', 'longitude' => '120.30661015381816'), array ('latitude' => '23.566138342814067', 'longitude' => '120.30631519854069'), array ('latitude' => '23.56607644975139', 'longitude' => '120.30606231262686'), array ('latitude' => '23.56600681243274', 'longitude' => '120.30593432486057'), array ('latitude' => '23.565995933580393', 'longitude' => '120.30583231320384'), array ('latitude' => '23.566153708504242', 'longitude' => '120.30586525797844'), array ('latitude' => '23.566315539607952', 'longitude' => '120.30588662793639'), array ('latitude' => '23.56647491206455', 'longitude' => '120.30589056353574'), array ('latitude' => '23.566546392756013', 'longitude' => '120.30588242919453'), array ('latitude' => '23.56661221987159', 'longitude' => '120.30583441257477'), array ('latitude' => '23.566631887883705', 'longitude' => '120.30574858188629'), array ('latitude' => '23.566736374148725', 'longitude' => '120.30574522912502'), array ('latitude' => '23.56683797109638', 'longitude' => '120.30575855245593'), array ('latitude' => '23.566994516329636', 'longitude' => '120.30579686164856'), array ('latitude' => '23.567177059422754', 'longitude' => '120.30585519969463'), array ('latitude' => '23.567307359387932', 'longitude' => '120.30550517141819'), array ('latitude' => '23.567629421011357', 'longitude' => '120.30554808676243'), array ('latitude' => '23.567762179007982', 'longitude' => '120.30487820506096'), array ('latitude' => '23.56797668122822', 'longitude' => '120.30492514371872'), array ('latitude' => '23.568112080288966', 'longitude' => '120.30494047870638'), array ('latitude' => '23.56824465485644', 'longitude' => '120.30494123697281'), array ('latitude' => '23.568375136499608', 'longitude' => '120.30490158667567'), array ('latitude' => '23.568459770905513', 'longitude' => '120.30481986701488'), array ('latitude' => '23.568755401243866', 'longitude' => '120.30500292778015'), array ('latitude' => '23.569046910965874', 'longitude' => '120.3052127229214'), array ('latitude' => '23.56947732220845', 'longitude' => '120.30550901930337'), array ('latitude' => '23.569769512732588', 'longitude' => '120.30572444200516'), array ('latitude' => '23.569968646432418', 'longitude' => '120.30587464570999'), array ('latitude' => '23.570237845213327', 'longitude' => '120.30585989356041'), array ('latitude' => '23.570377975867174', 'longitude' => '120.30555881559849'), array ('latitude' => '23.570545330192974', 'longitude' => '120.30525295605662'), array ('latitude' => '23.570592221300434', 'longitude' => '120.3051536266089'), array ('latitude' => '23.57061944494338', 'longitude' => '120.30505563826569'), array ('latitude' => '23.570609068197836', 'longitude' => '120.30503779649734'), array ('latitude' => '23.570529350233524', 'longitude' => '120.30501289834979'), array ('latitude' => '23.570485279509835', 'longitude' => '120.30497928302293'), array ('latitude' => '23.570421541363213', 'longitude' => '120.30495773763664'), array ('latitude' => '23.57030422291008', 'longitude' => '120.304931178689'), array ('latitude' => '23.570469552397928', 'longitude' => '120.30452214181423'), array ('latitude' => '23.569542106594973', 'longitude' => '120.30412450432777'), array ('latitude' => '23.56959127552295', 'longitude' => '120.30403465032578'), array ('latitude' => '23.569742651571033', 'longitude' => '120.30402316322329'), array ('latitude' => '23.570478156900876', 'longitude' => '120.30386567115784'), array ('latitude' => '23.570627687431536', 'longitude' => '120.30379919877055'), array ('latitude' => '23.570761490576327', 'longitude' => '120.30371814966202'), array ('latitude' => '23.570822951162768', 'longitude' => '120.30356325209141'), array ('latitude' => '23.57105852571304', 'longitude' => '120.30366978218558'), array ('latitude' => '23.571428697762318', 'longitude' => '120.30384001474386'), array ('latitude' => '23.57180938963965', 'longitude' => '120.30402056872845'), array ('latitude' => '23.5721468052123', 'longitude' => '120.30300334095955'), array ('latitude' => '23.572394053749196', 'longitude' => '120.30309310724738'), array ('latitude' => '23.572684323429982', 'longitude' => '120.30321170728212'), array ('latitude' => '23.573106806161203', 'longitude' => '120.30343249440193'), array ('latitude' => '23.5741251861799', 'longitude' => '120.3039575368166'), array ('latitude' => '23.574125800770158', 'longitude' => '120.30392400920391'), array ('latitude' => '23.573769337934113', 'longitude' => '120.30281960964203'), array ('latitude' => '23.573743446018955', 'longitude' => '120.30280459434994'), array ('latitude' => '23.57301215849219', 'longitude' => '120.30307106673717'), array ('latitude' => '23.57272655038627', 'longitude' => '120.30212550034526'), array ('latitude' => '23.572337330926867', 'longitude' => '120.30086494982243'), array ('latitude' => '23.573082836952793', 'longitude' => '120.30060343444347'), array ('latitude' => '23.57309530790163', 'longitude' => '120.3005758540869'), array ('latitude' => '23.57287203064915', 'longitude' => '120.29985576868057'), array ('latitude' => '23.57308916211114', 'longitude' => '120.29977923800948'), array ('latitude' => '23.573109622803166', 'longitude' => '120.29978116195207'), array ('latitude' => '23.57362982549168', 'longitude' => '120.29960297048092'), array ('latitude' => '23.57364291056456', 'longitude' => '120.29961025884154'), array ('latitude' => '23.573857839142338', 'longitude' => '120.30032448470592'), array ('latitude' => '23.573119098061223', 'longitude' => '120.30058734118938'), array ('latitude' => '23.57310575599001', 'longitude' => '120.30061608722212'), array ('latitude' => '23.573369852571293', 'longitude' => '120.30150063335896'), array ('latitude' => '23.574136248804148', 'longitude' => '120.30124917626381'), array ('latitude' => '23.57424687499598', 'longitude' => '120.30161395668983'), array ('latitude' => '23.57347679184906', 'longitude' => '120.30185669660568'), array ('latitude' => '23.57375599545476', 'longitude' => '120.30277794768813'), array ('latitude' => '23.57378418767364', 'longitude' => '120.30279176614295'), array ('latitude' => '23.574191225366462', 'longitude' => '120.30265019190324'), array ('latitude' => '23.574506846179922', 'longitude' => '120.30254401266575'), array ('latitude' => '23.575289214982693', 'longitude' => '120.30222617089748'), array ('latitude' => '23.576216620195996', 'longitude' => '120.30188351869583'), array ('latitude' => '23.57663883630399', 'longitude' => '120.30172057449818'), array ('latitude' => '23.576945686950097', 'longitude' => '120.3016118573189'), array ('latitude' => '23.57718458087715', 'longitude' => '120.30151806771755'), array ('latitude' => '23.57690986514245', 'longitude' => '120.30062288045883'), array ('latitude' => '23.57650749256646', 'longitude' => '120.30077500810626'), array ('latitude' => '23.57597447526524', 'longitude' => '120.30097626149654'), array ('latitude' => '23.575016339040445', 'longitude' => '120.30130550265312'), array ('latitude' => '23.5746346805213', 'longitude' => '120.300068333745'), array ('latitude' => '23.574646535553104', 'longitude' => '120.30004075338843'), array ('latitude' => '23.575587288280516', 'longitude' => '120.29971562325954'), array ('latitude' => '23.575981235662773', 'longitude' => '120.29956206679344'), array ('latitude' => '23.576520837179064', 'longitude' => '120.29936894774437'), array ('latitude' => '23.577278610963013', 'longitude' => '120.29910139739513'), array ('latitude' => '23.577302755255012', 'longitude' => '120.29911337962153'), array ('latitude' => '23.577681157301342', 'longitude' => '120.30033454298973'), array ('latitude' => '23.57770451109683', 'longitude' => '120.30034996569157'), array ('latitude' => '23.577780103616885', 'longitude' => '120.30033454298973'), array ('latitude' => '23.577884756474525', 'longitude' => '120.30030360987189'), array ('latitude' => '23.578410039591425', 'longitude' => '120.3001045435667'), array ('latitude' => '23.578038224089045', 'longitude' => '120.29885463416576'), array ('latitude' => '23.57731302718675', 'longitude' => '120.29908798635006'), array ('latitude' => '23.577289234565285', 'longitude' => '120.29907582869532'), array ('latitude' => '23.57710099852207', 'longitude' => '120.29841408133507'), array ('latitude' => '23.57683611585405', 'longitude' => '120.2975832670927'), array ('latitude' => '23.57669230462253', 'longitude' => '120.297095105052'), array ('latitude' => '23.576646387538506', 'longitude' => '120.29695151915553'), array ('latitude' => '23.576591251792877', 'longitude' => '120.29680860381131'), array ('latitude' => '23.576467983369998', 'longitude' => '120.29653452336788'), array ('latitude' => '23.576251651278447', 'longitude' => '120.29667735099792'), array ('latitude' => '23.576239359671636', 'longitude' => '120.29672227799892'), array ('latitude' => '23.576240150890285', 'longitude' => '120.29676845839026'), array ('latitude' => '23.57626701578534', 'longitude' => '120.2968791872263'), array ('latitude' => '23.576316358812427', 'longitude' => '120.29704271426203'), array ('latitude' => '23.576503014385356', 'longitude' => '120.29770463705063'), array ('latitude' => '23.576062974987153', 'longitude' => '120.29786020517349'), array ('latitude' => '23.575722673336976', 'longitude' => '120.29799154570105'), array ('latitude' => '23.575130037418628', 'longitude' => '120.2981948107481'), array ('latitude' => '23.575090703936784', 'longitude' => '120.29820419847965'), array ('latitude' => '23.5750232770021', 'longitude' => '120.29822020401957'), array ('latitude' => '23.574933724972695', 'longitude' => '120.29825163226133'), array ('latitude' => '23.57418295765207', 'longitude' => '120.29856160283089'), array ('latitude' => '23.574411763138087', 'longitude' => '120.2993138747454'), array ('latitude' => '23.574623617938983', 'longitude' => '120.30002608895302'), array ('latitude' => '23.574612118638814', 'longitude' => '120.30005349388125'), array ('latitude' => '23.573871974746385', 'longitude' => '120.30031710863113'), array ('latitude' => '23.573659940524763', 'longitude' => '120.29960431158543'), array ('latitude' => '23.573625701949773', 'longitude' => '120.2995861189604'), array ('latitude' => '23.573106191566108', 'longitude' => '120.29976323246956'), array ('latitude' => '23.573084859767825', 'longitude' => '120.29975778033736'), array ('latitude' => '23.57303551311811', 'longitude' => '120.29945947229862'), array ('latitude' => '23.57300680621546', 'longitude' => '120.29928839375975'), array ('latitude' => '23.572990391240502', 'longitude' => '120.29913944344526'), array ('latitude' => '23.572970903297705', 'longitude' => '120.29897775263794'), array ('latitude' => '23.572960634302913', 'longitude' => '120.29893072626601'), array ('latitude' => '23.57292701067402', 'longitude' => '120.29889845204366'), array ('latitude' => '23.57287433458378', 'longitude' => '120.29887422444835'), array ('latitude' => '23.572591159917017', 'longitude' => '120.29878221452236'), array ('latitude' => '23.572416793548477', 'longitude' => '120.29929040541651'), array ('latitude' => '23.572223374508386', 'longitude' => '120.2998864386559'), array ('latitude' => '23.57196672742804', 'longitude' => '120.30079655349255'), array ('latitude' => '23.57170675121516', 'longitude' => '120.30146710574627'), array ('latitude' => '23.571607185720733', 'longitude' => '120.30168168246746'), array ('latitude' => '23.571534228228774', 'longitude' => '120.30185124447348'), array ('latitude' => '23.571209537592065', 'longitude' => '120.30255541205406'), array ('latitude' => '23.57054207604807', 'longitude' => '120.30225701630116'), array ('latitude' => '23.57003502426525', 'longitude' => '120.30322059988976'), array ('latitude' => '23.56926983332308', 'longitude' => '120.30284777283669'), array ('latitude' => '23.568841447371128', 'longitude' => '120.30384488403797'), array ('latitude' => '23.568459770905488', 'longitude' => '120.30453689396381'), array ('latitude' => '23.568405070000715', 'longitude' => '120.3044805675745'), array ('latitude' => '23.568328857466156', 'longitude' => '120.30443631112576'), array ('latitude' => '23.568246681382895', 'longitude' => '120.30441007187369'), array ('latitude' => '23.568099604978762', 'longitude' => '120.30437998473644'), array ('latitude' => '23.56798897360844', 'longitude' => '120.30435852706432'), array ('latitude' => '23.568108209636982', 'longitude' => '120.30358605086803'), array ('latitude' => '23.568389704573928', 'longitude' => '120.30243404209614'), array ('latitude' => '23.56864661427363', 'longitude' => '120.3015435487032'), array ('latitude' => '23.568669355060155', 'longitude' => '120.30152477324009'), array ('latitude' => '23.568717477496968', 'longitude' => '120.30151865055564'), array ('latitude' => '23.568766214494868', 'longitude' => '120.30149643461709'), array ('latitude' => '23.56879958605873', 'longitude' => '120.30146885426052'), array ('latitude' => '23.568820550459666', 'longitude' => '120.30144095420837'), array ('latitude' => '23.568979121057538', 'longitude' => '120.30152074992657'), array ('latitude' => '23.56965458049063', 'longitude' => '120.3018144518137'), array ('latitude' => '23.56975002685195', 'longitude' => '120.30155083706381'), array ('latitude' => '23.56985715066634', 'longitude' => '120.30127515237336'), array ('latitude' => '23.569995004795135', 'longitude' => '120.3009169897557'), array ('latitude' => '23.57019728104876', 'longitude' => '120.30037142336369'), array ('latitude' => '23.569796122236276', 'longitude' => '120.30011049082282'), array ('latitude' => '23.56912724303222', 'longitude' => '120.29967539012432'), array ('latitude' => '23.56897850644321', 'longitude' => '120.30024468898773'), array ('latitude' => '23.568870516450307', 'longitude' => '120.30062547495368'), array ('latitude' => '23.568806164134365', 'longitude' => '120.30086477439409'), array ('latitude' => '23.568736962780644', 'longitude' => '120.30115932226181'), array ('latitude' => '23.568712378159038', 'longitude' => '120.3011754155159'), array ('latitude' => '23.568670766672305', 'longitude' => '120.30117063393595'), array ('latitude' => '23.568631613668003', 'longitude' => '120.30117524008756'), array ('latitude' => '23.56858877298482', 'longitude' => '120.30120063335903'), array ('latitude' => '23.5685551515656', 'longitude' => '120.30123407325755'), array ('latitude' => '23.568531978643307', 'longitude' => '120.30127019536508'), array ('latitude' => '23.568518774103094', 'longitude' => '120.30130885541439'), array ('latitude' => '23.567667527487064', 'longitude' => '120.30117273330688'), array ('latitude' => '23.567627577149345', 'longitude' => '120.30105136334896'), array ('latitude' => '23.567605019390395', 'longitude' => '120.30093795230391'), array ('latitude' => '23.567582461644754', 'longitude' => '120.30082856457238'), array ('latitude' => '23.56754822611307', 'longitude' => '120.30073057622917'), array ('latitude' => '23.567524935456692', 'longitude' => '120.30072413384914'), array ('latitude' => '23.567490699953797', 'longitude' => '120.30073075165751'), array ('latitude' => '23.56744626392539', 'longitude' => '120.30074626207352'), array ('latitude' => '23.567394819053654', 'longitude' => '120.30077500810626'), array ('latitude' => '23.567361812859737', 'longitude' => '120.30079906027322'), array ('latitude' => '23.56731657872015', 'longitude' => '120.30084885656834'), array ('latitude' => '23.56728726029982', 'longitude' => '120.30087693204882'), array ('latitude' => '23.567264088119142', 'longitude' => '120.30089159648423'), array ('latitude' => '23.567212092916627', 'longitude' => '120.30090719461441'), array ('latitude' => '23.567173986308774', 'longitude' => '120.30080929398537'), array ('latitude' => '23.5670789032948', 'longitude' => '120.30050008168223'), array ('latitude' => '23.567051061691974', 'longitude' => '120.30036002397537'), array ('latitude' => '23.56704141136531', 'longitude' => '120.30020034482482'), array ('latitude' => '23.567044915458112', 'longitude' => '120.30011795461178'), array ('latitude' => '23.5670248165367', 'longitude' => '120.30003874173167'), array ('latitude' => '23.567008652672477', 'longitude' => '120.30002139508724'), array ('latitude' => '23.56695659333869', 'longitude' => '120.30000387301448'), array ('latitude' => '23.56687423372184', 'longitude' => '120.29999649693968'), array ('latitude' => '23.566722237776563', 'longitude' => '120.30109159648418'), array ('latitude' => '23.566558747448695', 'longitude' => '120.3020303696394'), array ('latitude' => '23.565682288311706', 'longitude' => '120.30187077820301'), array ('latitude' => '23.565620825319723', 'longitude' => '120.30229389667511'), array ('latitude' => '23.565534777082583', 'longitude' => '120.30280619859695'), array ('latitude' => '23.565875896548565', 'longitude' => '120.30294232070446'), array ('latitude' => '23.565963358363007', 'longitude' => '120.30298447778227'), array ('latitude' => '23.565983210949504', 'longitude' => '120.30301791768079'), array ('latitude' => '23.565987758955337', 'longitude' => '120.30305497348309'), array ('latitude' => '23.565941047192666', 'longitude' => '120.30321322381496'), array ('latitude' => '23.56549052368152', 'longitude' => '120.30308581888676'), array ('latitude' => '23.56537005601427', 'longitude' => '120.30371747910976'), array ('latitude' => '23.56528769540271', 'longitude' => '120.30413456261158'), array ('latitude' => '23.565300787672687', 'longitude' => '120.3041680025101'), array ('latitude' => '23.56617460583995', 'longitude' => '120.304344445467'), array ('latitude' => '23.56619814599793', 'longitude' => '120.30431686511042'), array ('latitude' => '23.56624897574308', 'longitude' => '120.30394949018955'), array ('latitude' => '23.56637638765634', 'longitude' => '120.30327080359461'), array ('latitude' => '23.566944731805634', 'longitude' => '120.30336812138557'), array ('latitude' => '23.567348539066977', 'longitude' => '120.30345059931278'), array ('latitude' => '23.56728830609916', 'longitude' => '120.30367657542229'), array ('latitude' => '23.567161079229415', 'longitude' => '120.30447520315647'), array ('latitude' => '23.56717416987249', 'longitude' => '120.30450730195048'), array ('latitude' => '23.567596231491233', 'longitude' => '120.30458383262157')),
                        'infos' => array (
                            array ('type' => 1, 'title' => '歲次乙未年 十九下午繞境起馬', 'description' => '農曆三月十九下午繞境起馬', 'latitude' => '23.567600533836917', 'longitude' => '120.30456438660622', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/din-tao/an-start.jpg'),
                            array ('type' => 1, 'title' => '歲次乙未年 十九下午繞境落馬', 'description' => '農曆三月十九下午繞境落馬', 'latitude' => '23.567596231491233', 'longitude' => '120.30458383262157', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/din-tao/an-end.jpg'),
                            )),
                 array ('title' => '2016年 3月20晚間',
                        'paths' => array (array ('latitude' => '23.56759807535368', 'longitude' => '120.30456371605396'), array ('latitude' => '23.567156776869346', 'longitude' => '120.30448794364929'), array ('latitude' => '23.566699496652923', 'longitude' => '120.3044081479311'), array ('latitude' => '23.566196117799723', 'longitude' => '120.3043270111084'), array ('latitude' => '23.565780014409786', 'longitude' => '120.30424453318119'), array ('latitude' => '23.565285236876154', 'longitude' => '120.30414529144764'), array ('latitude' => '23.564548291479774', 'longitude' => '120.30400983989239'), array ('latitude' => '23.56482118917279', 'longitude' => '120.3027431666851'), array ('latitude' => '23.5650584376074', 'longitude' => '120.30282497406006'), array ('latitude' => '23.56523053464935', 'longitude' => '120.30291616916656'), array ('latitude' => '23.5654886797895', 'longitude' => '120.30299931764603'), array ('latitude' => '23.565488679789524', 'longitude' => '120.30308581888676'), array ('latitude' => '23.5659385886784', 'longitude' => '120.30321054160595'), array ('latitude' => '23.56636206708534', 'longitude' => '120.30334733426571'), array ('latitude' => '23.566433363751084', 'longitude' => '120.30283100903034'), array ('latitude' => '23.566555059694586', 'longitude' => '120.30207060277462'), array ('latitude' => '23.566721008526745', 'longitude' => '120.30109696090221'), array ('latitude' => '23.567186278764133', 'longitude' => '120.30113786458969'), array ('latitude' => '23.567232375461483', 'longitude' => '120.30113652348518'), array ('latitude' => '23.56723667781906', 'longitude' => '120.3013376891613'), array ('latitude' => '23.567253887248004', 'longitude' => '120.30142553150654'), array ('latitude' => '23.5672993692994', 'longitude' => '120.30149795114994'), array ('latitude' => '23.56735591453052', 'longitude' => '120.3015636652708'), array ('latitude' => '23.567410615872276', 'longitude' => '120.301643460989'), array ('latitude' => '23.567442576196203', 'longitude' => '120.30173599720001'), array ('latitude' => '23.567466546434055', 'longitude' => '120.30186608433723'), array ('latitude' => '23.56748191196885', 'longitude' => '120.30200622975826'), array ('latitude' => '23.56749604825926', 'longitude' => '120.30214570462704'), array ('latitude' => '23.567508955305694', 'longitude' => '120.30223958194256'), array ('latitude' => '23.56755197878468', 'longitude' => '120.30238576233387'), array ('latitude' => '23.567596231491247', 'longitude' => '120.30251383781433'), array ('latitude' => '23.56762081632175', 'longitude' => '120.30267141759396'), array ('latitude' => '23.567628191770023', 'longitude' => '120.30280485749245'), array ('latitude' => '23.567630650252678', 'longitude' => '120.30292689800262'), array ('latitude' => '23.56763741107976', 'longitude' => '120.30299060046673'), array ('latitude' => '23.56779782696568', 'longitude' => '120.3030026704073'), array ('latitude' => '23.567942262557438', 'longitude' => '120.30302681028843'), array ('latitude' => '23.568016631459745', 'longitude' => '120.30304960906506'), array ('latitude' => '23.568222528614246', 'longitude' => '120.3031025826931'), array ('latitude' => '23.568374339145336', 'longitude' => '120.30318707227707'), array ('latitude' => '23.568501564839856', 'longitude' => '120.3032547980547'), array ('latitude' => '23.568645999657758', 'longitude' => '120.30335739254951'), array ('latitude' => '23.56869455430549', 'longitude' => '120.30336812138557'), array ('latitude' => '23.56873634816518', 'longitude' => '120.30336275696754'), array ('latitude' => '23.568762162013027', 'longitude' => '120.3033372759819'), array ('latitude' => '23.568982808743588', 'longitude' => '120.30270896852016'), array ('latitude' => '23.569270447936063', 'longitude' => '120.30284777283669'), array ('latitude' => '23.56965089282344', 'longitude' => '120.30182853341103'), array ('latitude' => '23.56963921521', 'longitude' => '120.3018057346344'), array ('latitude' => '23.56899448641541', 'longitude' => '120.3015274554491'), array ('latitude' => '23.568820550459666', 'longitude' => '120.30144162476063'), array ('latitude' => '23.568845135061057', 'longitude' => '120.30135177075863'), array ('latitude' => '23.568819935844587', 'longitude' => '120.30124314129353'), array ('latitude' => '23.56873081662567', 'longitude' => '120.30117809772491'), array ('latitude' => '23.56882300892003', 'longitude' => '120.30080057680607'), array ('latitude' => '23.569133389168655', 'longitude' => '120.29968209564686'), array ('latitude' => '23.569761522793886', 'longitude' => '120.30008777976036'), array ('latitude' => '23.57019789565739', 'longitude' => '120.30037343502045'), array ('latitude' => '23.569995689257244', 'longitude' => '120.30091926455498'), array ('latitude' => '23.57009156831861', 'longitude' => '120.30096016824245'), array ('latitude' => '23.570345401649565', 'longitude' => '120.3010681271553'), array ('latitude' => '23.57050950187117', 'longitude' => '120.30114524066448'), array ('latitude' => '23.570701259173703', 'longitude' => '120.30126057565212'), array ('latitude' => '23.570982748552947', 'longitude' => '120.30145436525345'), array ('latitude' => '23.571553100729165', 'longitude' => '120.30180640518665'), array ('latitude' => '23.571208308384243', 'longitude' => '120.30255943536758'), array ('latitude' => '23.571020854055707', 'longitude' => '120.30304290354252'), array ('latitude' => '23.57082479497996', 'longitude' => '120.30356124043465'), array ('latitude' => '23.57004854567153', 'longitude' => '120.30322529375553'), array ('latitude' => '23.570028878171012', 'longitude' => '120.30323266983032'), array ('latitude' => '23.569541491983248', 'longitude' => '120.3041211515665'), array ('latitude' => '23.56932084619126', 'longitude' => '120.30408225953579'), array ('latitude' => '23.568839603526126', 'longitude' => '120.303850248456'), array ('latitude' => '23.568121116623253', 'longitude' => '120.3035894036293'), array ('latitude' => '23.568104521926383', 'longitude' => '120.30360348522663'), array ('latitude' => '23.567987744370466', 'longitude' => '120.30434846878052'), array ('latitude' => '23.568012943746588', 'longitude' => '120.30436590313911'), array ('latitude' => '23.568226830939405', 'longitude' => '120.30440278351307'), array ('latitude' => '23.56836081756667', 'longitude' => '120.3044530749321'), array ('latitude' => '23.568456697821546', 'longitude' => '120.30453085899353'), array ('latitude' => '23.568465917073144', 'longitude' => '120.30457779765129'), array ('latitude' => '23.568475750940788', 'longitude' => '120.30468843877316'), array ('latitude' => '23.56846284398934', 'longitude' => '120.30481986701488'), array ('latitude' => '23.568606664233698', 'longitude' => '120.30490837991238'), array ('latitude' => '23.568751713551393', 'longitude' => '120.30500024557114'), array ('latitude' => '23.568902294241415', 'longitude' => '120.30510820448399'), array ('latitude' => '23.56914813989489', 'longitude' => '120.30472062528133'), array ('latitude' => '23.56937247365214', 'longitude' => '120.3043645620346'), array ('latitude' => '23.569500927603613', 'longitude' => '120.30448861420155'), array ('latitude' => '23.569679164935856', 'longitude' => '120.30462205410004'), array ('latitude' => '23.569911487716126', 'longitude' => '120.30476622283459'), array ('latitude' => '23.570306066734535', 'longitude' => '120.30493319034576'), array ('latitude' => '23.569971719480968', 'longitude' => '120.30586324632168'), array ('latitude' => '23.569929926014407', 'longitude' => '120.3058860450983'), array ('latitude' => '23.568706846618536', 'longitude' => '120.30621461570263'), array ('latitude' => '23.56861219577841', 'longitude' => '120.30621863901615'), array ('latitude' => '23.568545202610107', 'longitude' => '120.30619315803051'), array ('latitude' => '23.568397079978997', 'longitude' => '120.30607648193836'), array ('latitude' => '23.568323325909525', 'longitude' => '120.3060107678175'), array ('latitude' => '23.567680434516678', 'longitude' => '120.3055802732706'), array ('latitude' => '23.567619587080355', 'longitude' => '120.30554741621017'), array ('latitude' => '23.567309203254407', 'longitude' => '120.30550718307495'), array ('latitude' => '23.567184434895886', 'longitude' => '120.30583374202251'), array ('latitude' => '23.567096543813594', 'longitude' => '120.30606709420681'), array ('latitude' => '23.566992672458696', 'longitude' => '120.30620321631432'), array ('latitude' => '23.56654338180591', 'longitude' => '120.3065975010395'), array ('latitude' => '23.566448114780542', 'longitude' => '120.30663438141346'), array ('latitude' => '23.565813204388824', 'longitude' => '120.30656933784485'), array ('latitude' => '23.56589064764063', 'longitude' => '120.30599534511566'), array ('latitude' => '23.565932442392466', 'longitude' => '120.30595511198044'), array ('latitude' => '23.566009885573994', 'longitude' => '120.30594103038311'), array ('latitude' => '23.565995134495303', 'longitude' => '120.30586458742619'), array ('latitude' => '23.56586114545486', 'longitude' => '120.3058498352766'), array ('latitude' => '23.565410007038622', 'longitude' => '120.30570968985558'), array ('latitude' => '23.56542352892103', 'longitude' => '120.30562587082386'), array ('latitude' => '23.5648543793941', 'longitude' => '120.30551455914974'), array ('latitude' => '23.564272934674396', 'longitude' => '120.30540861189365'), array ('latitude' => '23.56454337439904', 'longitude' => '120.30402727425098'), array ('latitude' => '23.56528216371799', 'longitude' => '120.30416540801525'), array ('latitude' => '23.565777555892492', 'longitude' => '120.30426196753979'), array ('latitude' => '23.566194273917585', 'longitude' => '120.30434645712376'), array ('latitude' => '23.566699496652912', 'longitude' => '120.30442960560322'), array ('latitude' => '23.567157391492227', 'longitude' => '120.3045067191124'), array ('latitude' => '23.567596231491247', 'longitude' => '120.30458651483059')), 
                        'infos' => array (
                            array ('type' => 1, 'title' => '歲次乙未年 十九晚間繞境起馬', 'description' => '農曆三月十九晚間繞境起馬', 'latitude' => '23.56759807535368', 'longitude' => '120.30456371605396', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/din-tao/ni-start.jpg'),
                            array ('type' => 1, 'title' => '歲次乙未年 十九晚間繞境落馬', 'description' => '農曆三月十九晚間繞境落馬', 'latitude' => '23.567596231491247', 'longitude' => '120.30458651483059', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/din-tao/ni-end.jpg'),
                            )),
                 );
    
    echo "\n " . $this->color ("Path Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    $this->load->library ('CreateDemo');

    echo " Create Path Tags.. Tag Count: " . count ($tags = array_merge (array (array ('name' => '陣頭路線', 'is_on_site' => 1), array ('name' => '藝閣路線', 'is_on_site' => 1)), array_map(function () { return array ('name' => CreateDemo::text (3, 3), 'is_on_site' => 0); }, range(0, rand (2, 5))))) . "\n" . str_repeat ('-', 60) . "\n";
    foreach ($tags as $t) 
      PathTag::transaction (function () use ($t) {
        if (!verifyCreateOrm ($tag = PathTag::create (array (
            'name' => $t['name'],
            'is_on_site' => $t['is_on_site'],
          ))))
          return false;
        
        echo " " . $this->color ("➜", 'r') . " Tag ID: " . $tag->id . ' ' . str_repeat ('-', 10) . " " . $this->color ("OK!", 'G') . "\n";
        return true;
      });
    echo "\n";
    echo " Create Path.. Path Count: " . count ($ps) . "\n" . str_repeat ('-', 60) . "\n";

    if ($tag_total = PathTag::count ())
      foreach ($ps as $p) {
        $path = null;
        $create = Path::transaction (function () use ($p, $tag_total, &$path) {
          if (!verifyCreateOrm ($path = Path::create (array (
              'user_id' => 1,
              'destroy_user_id' => NULL,
              'title' => $p['title'],
              'keywords' => CreateDemo::text (),
              'pv' => rand (0, 100),
              'length' => 0,
              'image' => '',
              'is_enabled' => 1
            ))))
            return false;

          echo " " . $this->color ("➜", 'r') . " Path ID: " . $path->id . ' .';

          echo ".";

          echo ".";

          if ($tags = PathTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
            foreach ($tags as $tag)
              if (!verifyCreateOrm ($mapping = PathTagMapping::create (array (
                  'path_id' => $path->id,
                  'path_tag_id' => $tag->id,
                ))));
          echo ".";

          return true;
        });
        if (!($create && $path)) {
          echo " " . $this->color ("Error!", 'R') . "\n";
          continue;
        }

        if ($p['paths'])
          foreach ($p['paths'] as $pp)
            PathPoint::transaction (function () use ($path, $pp) {
              if (!verifyCreateOrm ($point = PathPoint::create (array (
                'path_id' => $path->id,
                'latitude' => $pp['latitude'],
                'longitude' => $pp['longitude'],
              ))))
                return false;
              return true;
            });
        echo ".";

        delay_job ('paths', 'update_image_and_length', array ('id' => $path->id));

        if ($p['infos'])
          foreach ($p['infos'] as $pi)
            PathInfo::transaction (function () use ($path, $pi) {
              if (!verifyCreateOrm ($info = PathInfo::create (array (
                'path_id' => $path->id,
                'user_id' => 1,
                'title' => $pi['title'],
                'content' => $pi['description'],
                'latitude' => $pi['latitude'],
                'longitude' => $pi['longitude'],
                'type' => $pi['type'],
                'cover' => '',
              ))))
                return false;

              if (!$info->cover->put_url ($pi['cover']))
                return false;
        
              delay_job ('path_infos', 'update_cover_color_and_dimension', array ('id' => $info->id));

              return true;
            });
        echo ".";

        echo " " . $this->color ("OK!", 'G') . "\n";
      }
  }

  public function other () {
    echo "\n " . $this->color ("Other Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    
    $this->load->library ('CreateDemo');
    $cover = FCPATH . 'resource' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . 'og' . DIRECTORY_SEPARATOR . 'larger.jpg';
    copy ($cover, $cover1 = FCPATH . 'temp' . DIRECTORY_SEPARATOR . 'larger1.jpg');
    copy ($cover, $cover2 = FCPATH . 'temp' . DIRECTORY_SEPARATOR . 'larger2.jpg');
    copy ($cover, $cover3 = FCPATH . 'temp' . DIRECTORY_SEPARATOR . 'larger3.jpg');

    $ots = array (
      array (
        'title' => '網站作者',
        'type' => 'author',
        'cover' => $cover1,
        'content' => "<p>烘爐引炮 驚奇火花 驚震全場，<br/>輪廓描繪 傳承力量 霓彩妝童 延續風華，<br/>三聲起馬炮後三鼓三哨聲的先鋒中壇開路啟程，<br/>兩聲哨鼓的北港黃袍勇士也在炮火花中吞雲吐霧聞炮起舞，<br/>四小將鏗鏘響，門一開 青紅將軍開路展威風！<br/></p><p>不變的，還是一樣的開場詞..<br/>是的，又一年了。<br/></p><p>「農曆三月十九」這個慶典對於北港人而言，就如候鳥一般，是一個返鄉的季節！<br/>每年十九前一晚，小鎮內車子就漸漸的多了，辦桌的廚棚也滿在街道上，<br/>這是一個屬於北港囝仔的春節、北港人的過年！<br/></p><p>其實這一天對於北港人來說，不只是熱情也不僅僅是信仰，<br/>更是一種習慣、參與感、責任感！<br/></p><p>還記得，從國中時，自己去圖書館翻閱北港鎮地圖，<br/>用紙筆 一筆一畫的將路關路線圖完成。<br/>高中時，拿著人生第一台的數位相機，<br/>記錄著每一年的活動，從起馬、唱班到落馬安座，<br/>如今，終於可以用我所學的技能，來為我的故鄉做點什麼！<br/></p><p>十幾年過去了，不曾改變的習慣還依然繼續！<br/>不曾冷卻的期待也依然澎湃！<br/>在外地的北港囝仔，還記得北港的鞭炮味嗎？<br/>這一天這是我們北港人最榮耀、最團結的過年，<br/>今年要記得回來，再忙都要回來幫媽祖婆逗熱鬧一下吧！<br/></p><p>如果你不是北港人，<br/>但對傳統文化有著興趣與熱誠，推薦你來北港參與一次吧！<br/>你會看到的不只是陣頭鞭炮，而是整個鎮上參與的感動！<br/></p>"
      ),
      array (
        'title' => '製作人員',
        'type' => 'developers',
        'cover' => $cover2,
        'content' => "<p>嗨 各位好：）</p><p>我們是這個網站的作者，製作北港三月十九遶境活動網站，今年已經邁入第三年了，<br/>近幾年因為智慧手機、Google Maps 普及，所以我便開始嘗試將路關圖製作成 Google Maps，並且發揮網頁設計的專長，將網頁與地圖結合！</p><p>最初製作的發想原因，其實只是單純想為北港媽祖三月十九繞境活動作宣傳，並且沒有任何營利與貪圖，唯一的目的就是讓更多的人可以瞭解北港文化與在地人對於在地的熱愛僅此而已，<br/>而對於鄉土北港的熱血直至今年也未曾間斷，當然的，未來的每一年也是！</p><p>就在 2014 年，我們完成了第一版的北港迎媽祖網站，<br/>如今看來雖是簡單畫面，但這一個網站成為讓我們每年繼續為北港製作網站的一個動力，<br/>當時就使用簡單的 Google Maps 規劃路線，並且使用 iframe 內嵌網頁呈現。</p><p>接著 2015 年，我們更利用 Google Maps 提供的 JavaScript API，<br/>製作的會移動的三月十九繞境路關地圖和提供多項北港文化的說明頁面，<br/>此時的網站奠定了今年網站的雛形！<br/>然而為了跟上手機時代，就將網頁技術上提升、導入 RWD 技術，讓更多使用者可以用手機瀏覽！</p><p>今年我們更加發揮自己的專長與技術，<br/>打算讓北港文化被記錄、被分享、被發現，<br/>當然不只是繞境地圖而已，目標是所有北港地區的文化、歷史、人文、美食.. 等，<br/>將這些通通都記錄在網站上，進而讓大家更進一步的認識北港！</p>"
      ),
      array (
        'title' => '網站聲明',
        'type' => 'license',
        'cover' => $cover3,
        'content' => "<p>這是一個熱愛北港廟會活動的非營利網站，<br/>主要希望能為地方古蹟、習俗活動帶來多一點的貢獻！<br/>更希望大家參與北港廟會活動的同時，能更加的融入北港當地的文化。<p>網站出發點，其實單純的只想為北港媽祖三月十九繞境活動作宣傳，<br/>若您個是熱愛在地文化的朋友們，那一定來參與這盛會！<br/>若您是個道道地地的北港囝仔，那更可以將這個網站分享出去，<br/>讓台灣所有人更可以看得到北港的美。</p><p>北港這個可愛以及迷人文化古鎮，<br/>它擁有的不只印象中的宗教信仰中心<br/>而是有著數多的百年藝陣、人文傳統、宗教信仰..等，<br/>讓我們期許這小鎮特色的是能被記錄與看見！<br/>無論文章或攝影，都一起來記錄吧！希望大家一起分享一起加油！</p><p>還是要說一下，<br/>網站上面資料多數是參考網路上資源以及前輩們對於地方研究敘述的資料！<br/>如要引用，請標明出處或告知原作者！<br/>另外，若是網站內文章、資訊有錯誤或有不妥，<br/>也歡迎各位來信指導、建議。</p>"
        ),
    );
    echo " Create Other.. Other Count: " . count ($ots) . "\n" . str_repeat ('-', 60) . "\n";

    foreach ($ots as $ot)
      if (Other::transaction (function () use ($ot) {
                if (!verifyCreateOrm ($other = Other::create (array (
                    'user_id' => 1,
                    'destroy_user_id' => NULL,
                    'title' => $ot['title'],
                    'keywords' => CreateDemo::text (),
                    'content' => $ot['content'],
                    'pv' => rand (0, 100),
                    'type' => $ot['type'],
                    'cover' => '',
                    'cover_color_r' => 0,
                    'cover_color_g' => 0,
                    'cover_color_b' => 0,
                    'cover_width' => 0,
                    'cover_height' => 0,
                    'is_enabled' => 1
                  ))))
                  return false;
      
                echo " " . $this->color ("➜", 'r') . " Other ID: " . $other->id . ' .';

                if (!$other->cover->put ($ot['cover']))
                  return false;
                echo ".";
      
                foreach (range (0, rand (0, 4)) as $sort => $value)
                  if (!($source = OtherSource::create (array (
                                          'other_id' => $other->id,
                                          'title' => CreateDemo::text (),
                                          'href' => CreateDemo::password (20),
                                          'sort' => $sort
                                        ))))
                    return false;
                echo ".";
      
                echo ".";
      
                delay_job ('others', 'update_cover_color_and_dimension', array ('id' => $other->id));
                return true;
              }))
        echo " " . $this->color ("OK!", 'G') . "\n";
      else
        echo " " . $this->color ("ERROR!", 'R') . "\n";
  }
  public function store () {
    echo "\n " . $this->color ("Store Start!", 'C') . "\n" . str_repeat ('=', 60) . "\n";
    $this->load->library ('CreateDemo');

    echo " Create Store Tags.. Tag Count: " . count ($tags = array_merge (array (array ('name' => '美食小吃', 'is_on_site' => 1), array ('name' => '民宿旅館', 'is_on_site' => 1), array ('name' => '名勝古蹟', 'is_on_site' => 1)), array_map(function () { return array ('name' => CreateDemo::text (3, 3), 'is_on_site' => 0); }, range(0, rand (2, 5))))) . "\n" . str_repeat ('-', 60) . "\n";
    foreach ($tags as $t) 
      StoreTag::transaction (function () use ($t) {
        if (!verifyCreateOrm ($tag = StoreTag::create (array (
            'name' => $t['name'],
            'is_on_site' => $t['is_on_site'],
          ))))
          return false;
        
        echo " " . $this->color ("➜", 'r') . " Tag ID: " . $tag->id . ' ' . str_repeat ('-', 10) . " " . $this->color ("OK!", 'G') . "\n";
        return true;
      });

    echo "\n";

    $stos = array (
      array ('title' => '[雲記] 發揚在地工藝‧北港工藝坊', 'tag' => 3, 'type' => 2, 'source' => 'http://piecece.pixnet.net/blog/post/109294934', 'content' => '走在充滿老屋的北港歷史街區，在甕牆後方，往門縫內看去，可以看到一個可愛的玩偶。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/01-01.jpg', 'geo' => array ('lat' => 23.566748052020262, 'lng' => 120.30353240668774)),
      array ('title' => '[雲記] 木工與藝術的遇見‧北港春生活博物館', 'tag' => 3, 'type' => 2, 'source' => 'http://piecece.pixnet.net/blog/post/108923950', 'content' => '這天，和朋友相約來北港走跳。從網路上得知，北港這兒有個「北港春生活博物館」，在好奇心驅使下，來到這兒一探究竟。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/01-02.jpg', 'geo' => array ('lat' => 23.59144749901999, 'lng' => 120.29296986758709)),
      array ('title' => '[雲記] 北港振興戲院．日興堂餅鋪看布袋戲', 'tag' => 3, 'type' => 2, 'source' => 'http://woxko.pixnet.net/blog/post/40952869', 'content' => '對於布袋戲完全陌生的我，跟著「親親小旅行，讓我們看雲去」的雲林北港行程，前往振興戲院欣賞「黃世志電視木偶劇團」的布袋戲。說是欣賞，但對布袋戲我來說是認識一個新的領域。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/01-02.jpg', 'geo' => array ('lat' => 23.565054749811168, 'lng' => 120.3041285276413)),
      array ('title' => '[食記] 北港 小籠湯包', 'tag' => 1, 'type' => 1, 'source' => 'https://www.ptt.cc/bbs/Yunlin/M.1296565841.A.4BC.html', 'content' => '說到心得 說真的這家的話 我個人會推薦 1.湯包 2.酸辣湯!! 但是 我每次去吃都是吃乾麵啦XD 在這裡 推薦給 住北港或者要來北港玩的各位做個參考XDDD', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-01.jpg', 'geo' => array ('lat' => 23.57351612581438, 'lng' => 120.29720105230808)),
      array ('title' => '[食記] 雲林北港 福安鴨肉飯', 'tag' => 1, 'type' => 1, 'source' => 'http://douglas82328.pixnet.net/blog/post/109564667', 'content' => '上次一月底去北港吃過老受鴨肉飯時，就一直很想再去嘗嘗北港另外家也是人氣超高的鴨肉飯-【福安鴨肉飯】趁著禮拜四這天沒課，自己一個人機車一騎，咻~~~一下就到北港了', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-02.jpg', 'geo' => array ('lat' => 23.565343626868415, 'lng' => 120.3041540086269)),
      array ('title' => '[食記] 雲林北港。老受鴨肉飯', 'tag' => 1, 'type' => 1, 'source' => 'http://windko0813.pixnet.net/blog/post/107795083', 'content' => '北港有名的食物，除了大餅、花生、麻油之外，應該就是鴨肉飯與鴨肉粳了吧！如果沒點名到的北港食物，就sorry啦，因為windko也太久沒去北港晃晃了。小時候其實每年都會來北港幾趟，小時候的北港燈會超有名的！還要來朝天宮拜拜！', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-03.jpg', 'geo' => array ('lat' => 23.566428446740897, 'lng' => 120.3044021129608)),
      array ('title' => '[食記] 雲林北港 北港圓仔湯', 'tag' => 1, 'type' => 1, 'source' => 'http://sam76227.pixnet.net/blog/post/106376858', 'content' => '還在北港讀書的時候~最常吃的剉冰就是三代豆花另一間就是北港圓仔湯~圓仔湯有冰的和熱的~我是都吃冰的啦~如果女生月事來~這裡也有熱的紅豆湯~聽說還不錯~最近回到北港~吃北港圓仔湯的次數還比較高~因為這麼多年才漲五元~算是很有良心的店家~', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-04.jpg', 'geo' => array ('lat' => 23.565172759238962, 'lng' => 120.3041164577007)),
      array ('title' => '[食記] 北港的阿等土豆油飯(便宜好吃)', 'tag' => 1, 'type' => 1, 'source' => 'http://alisa0415.pixnet.net/blog/post/42004303', 'content' => '↑↑↑猜猜這些總共多少錢???.土豆油飯20+爌肉飯25+蚵仔酥35+特製香腸20+燙青菜20+蘆筍沙拉35=155元.真的便宜到會嚇死人！！！', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-05.jpg', 'geo' => array ('lat' => 23.57071047826772, 'lng' => 120.30380666255951)),
      array ('title' => '[食記] 北港橋頭香菇肉羹', 'tag' => 1, 'type' => 1, 'source' => 'http://alisa0415.pixnet.net/blog/post/42004306', 'content' => '這間北港陸橋旁的肉羹麵很神奇.每次經過時幾乎都客滿.有一次還看到門口一長排的排隊人潮.而且都是本地人.我常吃對面的阿婆煎盤粿.一直想找機會吃這家看看.後來吃過兩次感覺都不錯.', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-06.jpg', 'geo' => array ('lat' => 23.56649236785899, 'lng' => 120.30239783227444)),
      array ('title' => '[食記] 北港的阿不倒(+2015北港燈會)', 'tag' => 1, 'type' => 1, 'source' => 'http://alisa0415.pixnet.net/blog/post/42097768', 'content' => '網路上蒐尋"阿不倒"，看到好幾個誇張的關鍵字，例如［好吃到讓人流淚］、［沒吃到會讓人流淚］，所以說有吃到和沒吃到都會流淚就是了，大家形容的太over了啦！', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-07.jpg', 'geo' => array ('lat' => 23.566267414555526, 'lng' => 120.3037523478269)),
      array ('title' => '[食記] 北港武德宮之樂咖啡-財神爺廟裡喝咖啡', 'tag' => 1, 'type' => 1, 'source' => 'http://alisa0415.pixnet.net/blog/post/42026104', 'content' => '早就聽說過北港財神爺廟裡有一家咖啡店.聽起來就很潮很有趣樣.但一直沒機會去.今天大年初四和某個朋友約好要碰面聊天一下.喜歡文青文創的她提議這家咖啡店.', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-08.jpg', 'geo' => array ('lat' => 23.581215522612414, 'lng' => 120.2986474335193)),
      array ('title' => '[食記] 北港的一郎', 'tag' => 1, 'type' => 1, 'source' => 'http://alisa0415.pixnet.net/blog/post/40724839', 'content' => '這間一郎也是我家挑嘴的小弟喜歡的店家之一.如果晚上經過北港.常要幫他外帶豬腳和蝦仁飯回去.之前有介紹過兩次了.這次是發現它換新店址了.拍給大家看一下~~', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-09.jpg', 'geo' => array ('lat' => 23.568582694203908, 'lng' => 120.3048668056726)),
      array ('title' => '[食記] 雲林北港．九久醇義麵坊', 'tag' => 1, 'type' => 1, 'source' => 'http://blog.yam.com/larle/article/87603574', 'content' => '如非電視節目介紹，大概不會想到往北港巷弄裡尋義大利麵。這家位在公民路與博愛路口的餐廳，其實同時是家民宿。招牌不大，若不仔細很容易錯過。原擔心在媒體播放後會人潮湧現，幸好假日正午仍未滿座。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-10.jpg', 'geo' => array ('lat' => 23.56949723993212, 'lng' => 120.30414260923862)),
      array ('title' => '[食記] 雲林北港．秋月生炒鱔魚', 'tag' => 1, 'type' => 1, 'source' => 'http://blog.yam.com/larle/article/85381339', 'content' => '前陣子老大很愛到雲林縣北港鎮小散。因皆為臨時起意，沒有特別準備什麼食物情報，便採隨機挑選的方式覓食。在途經中山路時剛巧看到這家熱炒小攤，於是坐下用餐。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-11.jpg', 'geo' => array ('lat' => 23.566081797058768, 'lng' => 120.3042948246002)),
      array ('title' => '[食記] 北港鎮 煎盤粿(美味銅板美食)', 'tag' => 1, 'type' => 1, 'source' => 'http://babbitwang.pixnet.net/blog/post/108226570', 'content' => '吃完假魚肚之後，接著就是回到老街上繼續走走尋找下一個目標。下一個目標就是位於老街上的煎盤粿！這次我跟白芷公主兩個人只有點一份綜合煎盤粿品味一下這特色小吃。無論口味或是價格都是非常吸引人的喔！', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-12.jpg', 'geo' => array ('lat' => 23.565915847419063, 'lng' => 120.3043042123317)),
      array ('title' => '[食記] 北港鎮 廟邊假魚肚(30元,超特別美食)', 'tag' => 1, 'type' => 1, 'source' => 'http://babbitwang.pixnet.net/blog/post/108226990', 'content' => '七月的三日遊來到第二天，清早我跟白芷公主就到北港朝天宮附近去找早餐吃。朝天宮周邊真的還蠻強的，可以從早吃到晚。前面的文章我們晚餐吃過了阿不倒，現在早上我們要來吃的也是蠻特別又便宜的美食，叫"假魚肚"。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-13.jpg', 'geo' => array ('lat' => 23.567859903558606, 'lng' => 120.304929837584)),
      array ('title' => '[食記] 北港 阿豐麵線糊油飯', 'tag' => 1, 'type' => 1, 'source' => 'http://woxko.pixnet.net/blog/post/40952866', 'content' => '阿豐麵線糊就在一郎土魠魚羹對面、北港朝天宮後方，是個歷史 40 多年的老店，寫此篇文章時，阿豐麵線糊已經是第二訪了，是非常有特色的北港美食。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-14.jpg', 'geo' => array ('lat' => 23.568581464971494, 'lng' => 120.3049378842115)),
      array ('title' => '[食記] 北港圓環邊紅燒青蛙', 'tag' => 1, 'type' => 1, 'source' => 'http://thudadai.pixnet.net/blog/post/52867036', 'content' => '沿朝天宮旁的民主路走到文化路的圓環，旁邊就是這間在我出發來雲林前就掙扎過是否要嘗試的店，畢竟在台灣其他地方比較少能看到這種店家，感覺都來了沒試一下真的會遺憾。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-15.jpg', 'geo' => array ('lat' => 23.568563026483893, 'lng' => 120.3015100210905)),
      array ('title' => '[食記] 北港圓環邊的香菇魯肉飯', 'tag' => 1, 'type' => 1, 'source' => 'https://www.ptt.cc/bbs/Yunlin/M.1236278986.A.440.html', 'content' => '我吃的那家我已經吃了好幾年了，就算現在到外地讀書每次回去一定必吃！他們的香菇肉飯當然是主打，裡面不是放那種絞肉(有肥有瘦的)而是小塊的肉片，他們家的醬汁也不會很死鹹。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/02-16.jpg', 'geo' => array ('lat' => 23.56848066787434, 'lng' => 120.30132226645947)),
      array ('title' => '[心得] 雲林北港 笨港客棧(1200元/雙人房)', 'tag' => 2, 'type' => 4, 'source' => 'http://babbitwang.pixnet.net/blog/post/108226279', 'content' => '三日遊的第一天落腳於北港朝天宮附近。這裡有間價格平民的旅社值得推薦一下！笨港客棧原名為國宮旅社，便宜的住宿價格但是缺點就是硬體比較舊，不過房間還算乾淨，住起來還蠻舒適的。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/03-01.jpg', 'geo' => array ('lat' => 23.568832842760933, 'lng' => 120.30503377318382)),
      array ('title' => '[雲記] 北港 金山商務旅館 住宿心得', 'tag' => 2, 'type' => 4, 'source' => 'http://thudadai.pixnet.net/blog/post/59263852', 'content' => '北港附近的住宿選擇不多，因為這次不想住民宿，當天傍晚才打電話到邦尼熊motel問問看，得知只剩下近3,000元的房間，覺得這間應該沒有必要住到此種價位，所以不考慮。後來打電話問問看歷史悠久的金山商旅(這間和邦尼熊都沒有網路訂房系統)，因為有空房所以就直接過去現場買單check in，住的是假日一晚1,800元的商務雙人套房。', 'cover' => 'http://comdan66.github.io/matsu/2015/img/site/food/03-02.jpg', 'geo' => array ('lat' => 23.570500897370263, 'lng' => 120.30230395495892)),
    );

    echo " Create Store.. Store Count: " . count ($stos) . "\n" . str_repeat ('-', 60) . "\n";

    if ($tag_total = StoreTag::count ())
      foreach ($stos as $sto)
        if (Store::transaction (function () use ($sto, $tag_total) {
                  if (!verifyCreateOrm ($store = Store::create (array (
                      'user_id' => 1,
                      'destroy_user_id' => NULL,
                      'title' => $sto['title'],
                      'keywords' => $sto['title'],
                      'content' => $sto['content'],
                      'pv' => rand (0, 100),
                      'latitude' => $sto['geo']['lat'],
                      'longitude' => $sto['geo']['lng'],
                      'type' => $sto['type'],
                      'cover' => '',
                      'cover_color_r' => 0,
                      'cover_color_g' => 0,
                      'cover_color_b' => 0,
                      'cover_width' => 0,
                      'cover_height' => 0,
                      'is_enabled' => 1
                    ))))
                    return false;
        
                  echo " " . $this->color ("➜", 'r') . " Store ID: " . $store->id . ' .';
        
                  if (!$store->cover->put_url ($sto['cover']))
                    return false;
                  echo ".";
        
                  foreach (range (0, rand (0, 4)) as $sort => $value)
                    if (!($source = StoreSource::create (array ('store_id' => $store->id, 'title' => CreateDemo::text (), 'href' => CreateDemo::password (20), 'sort' => $sort))))
                      return false;
                  echo ".";

                  if (!($source = StoreSource::create (array ('store_id' => $store->id, 'title' => $sto['title'], 'href' => $sto['source'], 'sort' => $sort + 1))))
                    return false;
                  echo ".";
        
                  if ($tags = StoreTag::find ('all', array ('order' => 'RAND()', 'offset' => 0, 'limit' => rand (1, $tag_total))))
                    foreach ($tags as $tag)
                      if (!verifyCreateOrm ($mapping = StoreTagMapping::create (array (
                          'store_id' => $store->id,
                          'store_tag_id' => $tag->id,
                        ))));
                  echo ".";

                  if (!StoreTagMapping::find ('one', array ('conditions' => array ('store_id = ? AND store_tag_id = ?', $store->id, $sto['tag']))))
                    if (!verifyCreateOrm ($mapping = StoreTagMapping::create (array (
                        'store_id' => $store->id,
                        'store_tag_id' => $sto['tag'],
                      ))));
                    echo ".";
        
                  delay_job ('stores', 'update_cover_color_and_dimension', array ('id' => $store->id));
                  return true;
                }))
          echo " " . $this->color ("OK!", 'G') . "\n";
        else
          echo " " . $this->color ("ERROR!", 'R') . "\n";

  }
  public function build () {
    $this->migration ();

    $this->article ();
    $this->dintao ();
    $this->picture ();
    $this->youtube ();
    $this->path ();
    $this->other ();
    $this->store ();
  }
  public function init () {
    // $this->migration ();

    // $this->article ();
    // $this->dintao ();
    // $this->picture ();
    // $this->youtube ();
    // $this->path ();
    $this->other ();
    $this->store ();
  }
  private function _directory_map ($files, &$a, $k = null) {
    foreach ($files as $key => $file)
      if (is_array ($file)) $key . $this->_directory_map ($file, $a, ($k ? $k . DIRECTORY_SEPARATOR : '') . $key);
      else array_push ($a, ($k ? $k . DIRECTORY_SEPARATOR : '') . $file);
  }
  public function put_resource () {
    $dir = FCPATH . 'resource' . DIRECTORY_SEPARATOR;
    $this->load->helper ('directory');

    $files = array ();
    $this->_directory_map (directory_map ($dir), $files);
    foreach ($files as $i => $file) {
      // echo $dir . $file . "\n";
      // echo 'resource' . DIRECTORY_SEPARATOR . $file . "\n";
      echo $i . ': ' . $file . "\n";;
      put_s3 ($dir . $file, 'resource' . DIRECTORY_SEPARATOR . $file);
    }
  }

  public function sitmap () {
    $this->load->library ('Sitemap');

    // 基礎設定
    $domain = rtrim (base_url (), '/');
    $sit_map = new Sitemap ($domain);
    $sit_map->setPath (FCPATH . 'sitemap' . DIRECTORY_SEPARATOR);
    $sit_map->setDomain ($domain);

    $list = array ();
    $menus_list = array_filter (array_map (function ($group) use ($domain, &$list) {
      return array_filter ($group, function ($item) use ($domain, &$list) {
        if (!(!(isset ($item['no_show']) && $item['no_show']) && (in_array ('all', $item['roles']) || (User::current () && User::current ()->in_roles ($item['roles'])))))
          return false;
        array_push ($list, str_replace ($domain, '', $item['href']));
        return true;
      });
    }, Cfg::setting ('site', 'menu')));

    // main pages
    foreach ($list as $link)
      $sit_map->addItem ($link, '0.5', 'weekly', date ('c'));

    $sit_map->createSitemapIndex ($domain . '/sitemap/', date ('c'));
  }

  public function compressor () {
    $pics = Picture::find ('all', array ('select' => 'id, name, is_compressor', 'order' => 'id DESC', 'limit' => 10, 'conditions' => array ('is_compressor = 0')));

    $keys = array (
        'bbh9hX2_P6O8ZJFbsFsBXE8T9NJLSLgG' => 152,
        'CEzB_7LBQLEuL1auQwmhGsAFixGv5LTP' => 0,
        '4fxgbklWFEJ6YdfiRxac4ZF6YZwHrxvQ' => 0,
        'BcumbKabN3NgmRYL8m-fn6fBb89tqC-C' => 0,
        'ITPuxzFFmEPnJHnE-O3PjwvyElbNz6ii' => 0,
      );
    require_once ('vendor/autoload.php');

    $ss = array ('500w', '');;
    foreach ($pics as $i => $pic) {
      echo str_repeat ('=', 60) . "\n";
      echo $i . ': ' . $pic->id . "\n";

      foreach ($ss as $s) {
        echo str_repeat ('-', 60) . "\n";
        echo "Size: " . ($s ? $s : 'ori') . "\n";
        download_web_file ($pic->name->url ($s), $path = FCPATH . 'temp' . DIRECTORY_SEPARATOR . $s . '_' . $pic->name);
        echo "Download！\n";

        if (!file_exists ($path)) {
          echo $this->color ("Error！", 'r') . "Download Error!\n";
          return;
        }
        if (!$tinypng = TinypngKey::find ('one', array ('conditions' => array ('quantity < 500')))) {
          echo $this->color ("Error！", 'r') . "No any key Error!\n";
          return; 
        }

        try {
          \Tinify\setKey ($tinypng->key);
          \Tinify\validate ();

          if (!(($source = \Tinify\fromFile ($path)) && ($source->toFile ($path)))) {
            echo $this->color ("Error！", 'r') . "Tinify toFile Error!\n";
            return; 
          }
        } catch (Exception $e) {
          echo $this->color ("Error！", 'r') . "Tinify try catch Error!\n";
          return; 
        }

        $tinypng->quantity += 1;
        $tinypng->save ();
        echo "Key + 1, Key:" . $tinypng->key . " quantity: " . $tinypng->quantity . "！\n";

        $s3_path = implode (DIRECTORY_SEPARATOR, array_merge ($pic->name->getBaseDirectory (), $pic->name->getSavePath ())) . DIRECTORY_SEPARATOR . $s . '_' . $pic->name;
        if (!put_s3 ($path, $s3_path)) {
          echo $this->color ("Error！", 'r') . "Put s3 Error!\n";
          return; 
        }

        @unlink ($path);

        $pic->is_compressor = 1;
        if (!$pic->save ())
          echo $this->color ("Error！", 'r') . "Save Error!\n";
        else
          echo $this->color ("Sessus！", 'g') . "\n";
      }
    }
  }
  public function set_pics () {
    $pics = Picture::find ('all', array ('select' => 'id, name, name_color_r, name_color_g, name_color_b, name_width, name_height, is_enabled', 'conditions' => array ('is_enabled = 0')));
    
    $j = 0;
    foreach ($pics as $i => $pic) {
      if ($j++ > 30) break;

      echo str_repeat ('-', 60) . "\n";
      echo $i . ': ' . $pic->id . "\n";
      if (!$pic->update_name_color_and_dimension ()) {
        echo $this->color ("Error！", 'r') . "\n";
        return;
      }
      $pic->is_enabled = 1;

      if (!$pic->save ())
        echo $this->color ("Error！", 'r') . "\n";
      else
        echo $this->color ("Sessus！", 'g') . "\n";
    }
  }
  public function put_pics ($year = 0) {
    $tag_ids = array (
      2006 => 2,
      2007 => 3,
      2010 => 4,
      2011 => 5,
      2012 => 6,
      2013 => 7,
      2014 => 8,
      2015 => 9,
      );
    $this->load->helper ('directory');
    
    $dir = FCPATH . 'pics' . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR;

    if (!is_dir ($dir)) return;

    $files = array ();
    $this->_directory_map (directory_map ($dir), $files);
    uasort ($files, function ($a, $b) {
      return pathinfo ($a, PATHINFO_FILENAME) > pathinfo ($b, PATHINFO_FILENAME);
    });

    foreach ($files as $i => $file) {
      $path = $dir . $file;
      echo str_repeat ('-', 60) . "\n";
      echo $i . ': ' . $path . "\n";
      $pic = null;
      $create = Picture::transaction (function () use ($year, $path, &$pic) {
        if (!verifyCreateOrm ($pic = Picture::create (array (
                  'user_id' => 1,
                  'title' => $year . ' 北港迎媽祖',
                  'keywords' => '北港迎媽祖 北港廟會 農曆三月十九 朝天宮 遶境',
                  'content' => $year . ' 北港迎媽祖 農曆三月十九 北港廟會',
                  'is_enabled' => 0
                )))) {
          echo $this->color ("Error！", 'r') . ' create pic error！' . "\n";
          return false;
        }
        echo "Put pic..\n";
        return $pic->name->put ($path);
      });
      if (!($create && $pic))
        continue;

      echo $this->color ("Sessus！", 'g') . ' create pic OK' . "\n";
      if (!(verifyCreateOrm (PictureTagMapping::create (array (
                            'picture_id' => $pic->id,
                            'picture_tag_id' => $tag_ids[$year],
                          ))) && verifyCreateOrm (PictureTagMapping::create (array (
                      'picture_id' => $pic->id,
                      'picture_tag_id' => 1,
                    )))))
        echo $this->color ("Error！", 'r') . ' create pic tag error！' . "\n";
      else
        echo $this->color ("Sessus！", 'g') . ' create pic tag OK' . "\n";
    }
  }
}
