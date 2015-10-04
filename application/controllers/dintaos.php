<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Dintaos extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function content ($id = 0) {
    if (!($id && ($dintao = Dintao::find_by_id ($id))))
      return redirect_message (array ('dintaos', 'official'), array (
          '_flash_message' => '無此文章！'
        ));

    $method = $dintao->type != Dintao::TYPE_OTHER ? $dintao->type != Dintao::TYPE_LOCAL ? 'official' : 'local' : 'other';
    
    if (!preg_match ('/^data:/', $og_img = $dintao->cover->url ('1200x630c')))
      $this->add_meta (array ('property' => 'og:image', 'content' => $og_img, 'alt' => $dintao->title . ' - ' . Cfg::setting ('site', 'main', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'content' => 'image/' . pathinfo ($og_img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'content' => '630'));

    return $this->set_title ($dintao->title . ' - ' . Cfg::setting ('site', 'main', 'title'))
                ->add_subtitle ($dintao->title)
                ->set_back_link (base_url ($this->get_class (), $method))
                ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $dintao->keywords ())))
                ->add_meta (array ('name' => 'description', 'content' => $dintao->mini_content ()))
                ->add_meta (array ('property' => 'og:title', 'content' => $dintao->title))
                ->add_meta (array ('property' => 'og:description', 'content' => $dintao->mini_content ()))

                ->load_view (array (
                    'method' => $method,
                    'dintao' => $dintao
                  ), false);
  }
  public function official ($offset = 0) {
    $dintaos = render_cell ('dintao_cell', 'dintaos', $this->get_class (), $this->get_method (), $offset);

    return $this->set_method ('list')
                ->add_subtitle ('朝天宮 駕前陣頭')
                ->load_view (array (
                    'method' => 'official',
                    'dintaos' => $dintaos
                  ));
  }
}
