<?php

require 'vendor/autoload.php';
use Mailgun\Mailgun;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class OaMailGun {
  private $domain = '';
  private $key = '';

  public function __construct ($configs = array ()) {

    $this->setKey (Cfg::setting ('mail_gun', 'key'))
         ->setDomain (Cfg::setting ('mail_gun', 'domain'));
  }
  public function setKey ($key) {
    $this->key = $key;
    return $this;
  }
  public function setDomain ($domain) {
    $this->domain = $domain;
    return $this;
  }
  public function getKey () {
    return $this->key;
  }
  public function getDomain () {
    return $this->domain;
  }
  public function sendMessage ($mail) {
    // 'from'    => 'Admin <admin@flea.ioa.tw>',
    // 'to'      => 'comdan66 <comdan66@gmail.com>',
    // 'subject' => 'Testing Hello',
    // 'text'    => 'Testing!'

    $mail_gun = new Mailgun ($this->getKey ());
    return $this->getDomain () && $mail ? $mail_gun->sendMessage ($this->getDomain (), $mail) : null;
  }
}