<?php

/**
 * Sitemap
 *
 * This class used for generating Google Sitemap files
 *
 * @package    Sitemap
 * @author     Osman Üngür <osmanungur@gmail.com>
 * @copyright  2009-2011 Osman Üngür
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version    Version @package_version@
 * @since      Class available since Version 1.0.0
 * @link       http://github.com/osmanungur/sitemap-php
 */
class Sitemap {

  /**
   *
   * @var XMLWriter
   */
  private $writer;
  private $domain;
  private $path;
  private $filename = 'sitemap';
  private $current_item = 0;
  private $current_sitemap = 0;

  const EXT = '.xml';
  const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';
  const DEFAULT_PRIORITY = 0.5;
  const ITEM_PER_SITEMAP = 200;
  const SEPERATOR = '_';
  const INDEX_SUFFIX = 'index';

  /**
   *
   * @param string $domain
   */
  public function __construct() {
  }

  /**
   * Sets root path of the website, starting with http:// or https://
   *
   * @param string $domain
   */
  public function setDomain($domain) {
    $this->domain = $domain;
    return $this;
  }

  /**
   * Returns root path of the website
   *
   * @return string
   */
  private function getDomain() {
    return $this->domain;
  }

  /**
   * Returns XMLWriter object instance
   *
   * @return XMLWriter
   */
  private function getWriter() {
    return $this->writer;
  }

  /**
   * Assigns XMLWriter object instance
   *
   * @param XMLWriter $writer 
   */
  private function setWriter(XMLWriter $writer) {
    $this->writer = $writer;
  }

  /**
   * Returns path of sitemaps
   * 
   * @return string
   */
  private function getPath() {
    return $this->path;
  }

  /**
   * Sets paths of sitemaps
   * 
   * @param string $path
   * @return Sitemap
   */
  public function setPath($path) {
    $this->path = $path;
    return $this;
  }

  /**
   * Returns filename of sitemap file
   * 
   * @return string
   */
  private function getFilename() {
    return $this->filename;
  }

  /**
   * Sets filename of sitemap file
   * 
   * @param string $filename
   * @return Sitemap
   */
  public function setFilename($filename) {
    $this->filename = $filename;
    return $this;
  }

  /**
   * Returns current item count
   *
   * @return int
   */
  private function getCurrentItem() {
    return $this->current_item;
  }

  /**
   * Increases item counter
   * 
   */
  private function incCurrentItem() {
    $this->current_item = $this->current_item + 1;
  }

  /**
   * Returns current sitemap file count
   *
   * @return int
   */
  private function getCurrentSitemap() {
    return $this->current_sitemap;
  }

  /**
   * Increases sitemap file count
   * 
   */
  private function incCurrentSitemap() {
    $this->current_sitemap = $this->current_sitemap + 1;
  }

  /**
   * Prepares sitemap XML document
   * 
   */
  private function startSitemap() {
    $this->setWriter(new XMLWriter());
    $this->getWriter()->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . $this->getCurrentSitemap() . self::EXT);
    $this->getWriter()->startDocument('1.0', 'UTF-8');
    $this->getWriter()->setIndent(true);
    $this->getWriter()->startElement('urlset');
    $this->getWriter()->writeAttribute('xmlns', self::SCHEMA);
  }

  /**
   * Adds an item to sitemap
   *
   * @param string $loc URL of the page. This value must be less than 2,048 characters. 
   * @param string $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0.
   * @param string $changefreq How frequently the page is likely to change. Valid values are always, hourly, daily, weekly, monthly, yearly and never.
   * @param string|int $lastmod The date of last modification of url. Unix timestamp or any English textual datetime description.
   * @return Sitemap
   */
  public function addItem($loc, $priority = self::DEFAULT_PRIORITY, $changefreq = NULL, $lastmod = NULL) {
    if (($this->getCurrentItem() % self::ITEM_PER_SITEMAP) == 0) {
      if ($this->getWriter() instanceof XMLWriter) {
        $this->endSitemap();
      }
      $this->startSitemap();
      $this->incCurrentSitemap();
    }
    $this->incCurrentItem();
    $this->getWriter()->startElement('url');
    $this->getWriter()->writeElement('loc', $this->getDomain() . $loc);
    $this->getWriter()->writeElement('priority', $priority);
    if ($changefreq)
      $this->getWriter()->writeElement('changefreq', $changefreq);
    if ($lastmod)
      $this->getWriter()->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
    $this->getWriter()->endElement();
    return $this;
  }

  //增加 image sitemap 
  //$loc 網頁網址  , $image_loc 圖片網址
  //$param['caption'] 圖片的說明。
  //$param['geo_location'] 圖片所顯示的地理位置。例如，<image:geo_location>Limerick, Ireland</image:geo_location>。
  //$param['title'] 圖片的標題。
  //$param['license'] 圖片授權的網址。
  //詳細參考  http://support.google.com/webmasters/bin/answer.py?hl=zh-Hant&answer=178636  
  // 2013/02/27 By Rich 
  public function addImage($loc , $image_loc , $param = array()){
    if (($this->getCurrentItem() % self::ITEM_PER_SITEMAP) == 0) {
      if ($this->getWriter() instanceof XMLWriter) {
        $this->endSitemap();
      }
      $this->startSitemap();
      $this->incCurrentSitemap();
    }
    $this->incCurrentItem();
    $this->getWriter()->startElement('url');
    $this->getWriter()->writeElement('loc', $this->getDomain() . $loc);
    $this->getWriter()->startElement('image:image');
      $this->getWriter()->writeElement('image:loc', $image_loc);
      $attrs = array("caption" , "geo_location" , "title" , "license");    
      if (is_array($param)) {
        foreach ($attrs as $attr) {
          if (isset($param[$attr]))
            $this->getWriter()->writeElement('image:'.$attr , $param[$attr]);
        }
      }
    $this->getWriter()->endElement(); 


    // if ($changefreq)
    //   $this->getWriter()->writeElement('changefreq', $changefreq);
    // if ($lastmod)
    //   $this->getWriter()->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
    $this->getWriter()->endElement();
    return $this;
  }
  /**
   * Prepares given date for sitemap
   *
   * @param string $date Unix timestamp or any English textual datetime description
   * @return string Year-Month-Day formatted date.
   */
  private function getLastModifiedDate($date) {
    return $date;
    if (ctype_digit($date)) {
      return date('Y-m-d', $date);
    } else {
      $date = strtotime($date);
      return date('Y-m-d', $date);
    }
  }

  /**
   * Finalizes tags of sitemap XML document.
   *
   */
  private function endSitemap() {
    $this->getWriter()->endElement();
    $this->getWriter()->endDocument();
  }

  /**
   * Writes Google sitemap index for generated sitemap files
   *
   * @param string $loc Accessible URL path of sitemaps
   * @param string|int $lastmod The date of last modification of sitemap. Unix timestamp or any English textual datetime description.
   */
  public function createSitemapIndex($loc, $lastmod = 'Today') {
    $this->endSitemap();
    $indexwriter = new XMLWriter();
    $indexwriter->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . self::INDEX_SUFFIX . self::EXT);
    $indexwriter->startDocument('1.0', 'UTF-8');
    $indexwriter->setIndent(true);
    $indexwriter->startElement('sitemapindex');
    $indexwriter->writeAttribute('xmlns', self::SCHEMA);
    for ($index = 0; $index < $this->getCurrentSitemap(); $index++) {
      $indexwriter->startElement('sitemap');
      $indexwriter->writeElement('loc', $loc . $this->getFilename() . self::SEPERATOR . $index . self::EXT);
      $indexwriter->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
      $indexwriter->endElement();
    }
    $indexwriter->endElement();
    $indexwriter->endDocument();
  }

}