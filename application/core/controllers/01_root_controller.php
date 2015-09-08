<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Root_controller extends CI_Controller {
  private $class  = '';
  private $method = '';

  private $controllers_path = array ();
  private $views_path       = array ();
  private $libraries_path   = array ();

  public function __construct () {
    parent::__construct ();

    error_reporting (-1);
    ini_set('display_errors', 1);

    $this->load->driver ('cache', array ('adapter' => 'apc', 'backup' => 'file'));

    $this->load->helper ('url');
    $this->load->helper ('html');
    $this->load->helper ('oa');
    $this->load->helper ('upload_file');
    $this->load->helper ('cell');
    $this->load->library ('cfg');
    $this->load->library ('session');
    $this->load->library ('fb');
    $this->load->library ('OAInput');

    $this->set_controllers_path ('application', 'controllers')
         ->set_libraries_path ('application', 'libraries')
         ->set_views_path ('application', 'views')
         ->set_class ($this->router->fetch_class ())
         ->set_method ($this->router->fetch_method ());
  }

  protected function set_class ($class) {
    $this->class = strtolower (trim ($class));
    return $this;
  }

  protected function set_method ($method) {
    $this->method = strtolower (trim ($method));
    return $this;
  }

  protected function set_controllers_path () {
    $this->controllers_path = array_filter (func_get_args ());
    return $this;
  }

  protected function set_libraries_path () {
    $this->libraries_path = array_filter (func_get_args ());
    return $this;
  }

  protected function set_views_path () {
    $this->views_path = array_filter (func_get_args ());
    return $this;
  }

  public function get_class () {
    return $this->class;
  }

  public function get_method () {
    return $this->method;
  }

  public function get_controllers_path () {
    return $this->controllers_path;
  }

  public function get_libraries_path () {
    return $this->libraries_path;
  }

  public function get_views_path () {
    return $this->views_path;
  }

  protected function load_content ($data = '', $return = false) {
    if (!is_readable ($abs_path = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->get_views_path (), $this->get_content_path (), array ($this->get_class (), $this->get_method (), 'content.php')))))
      return show_error ('Can not find content file. path: ' . $abs_path);
    else
      $path = implode (DIRECTORY_SEPARATOR, array_merge ($this->get_content_path (), array ($this->get_class (), $this->get_method (), 'content.php')));

    if ($return) return $this->load->view ($path, $data, $return);
    else $this->load->view ($path, $data, $return);
  }
}
