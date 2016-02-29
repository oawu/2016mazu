<?php
require FCPATH . 'vendor/autoload.php';
use Facebook\FacebookApp;
use Facebook\SignedRequest;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Fb {
  private static $fb = null;
  private static $accessToken = null;

  public function __construct ($config = array ()) {
  }

  public static function faceBook () {
    if (self::$fb !== null)
      return self::$fb;

    return self::$fb = new Facebook\Facebook ([
      'app_id' => Cfg::setting ('facebook', 'appId'),
      'app_secret' => Cfg::setting ('facebook', 'secret'),
      'default_graph_version' => Cfg::setting ('facebook', 'version')
      ]);
  }

  public static function loginUrl () {
    if (session_status () == PHP_SESSION_NONE)
      session_start ();

    $helper = self::faceBook ()->getRedirectLoginHelper ();
    $permissions = Cfg::setting ('facebook', 'scope');
    return $helper->getLoginUrl (base_url (func_get_args ()), $permissions);
  }
  public static function logoutUrl () {
    return base_url (func_get_args ());
  }
  public static function login () {
    if (session_status() == PHP_SESSION_NONE)
      session_start();

    $helper = self::faceBook ()->getRedirectLoginHelper ();

    try {
      self::$accessToken = $helper->getAccessToken ();
      return true;
    } catch(Exception $e) {
      return false;
    }
    return false;
  }

  public static function me () {
    if (!(self::faceBook () && self::$accessToken))
      return null;
    $get_fields = implode (',', Cfg::setting ('facebook', 'get_fields'));
    self::faceBook ()->setDefaultAccessToken (self::$accessToken);
    return self::faceBook ()->get ('/me' . ($get_fields ? '?fields=' . $get_fields : ''))->getGraphUser ();
  }
}