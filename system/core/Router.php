<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Route {
	static $route = array ();
	static $methods = array ('get', 'post', 'put', 'delete');

	public static function root ($controller) {
		$controller = array_filter (explode ('@', $controller), function ($t) { return $t || $t === '0'; });
		self::get('/', $controller[0]);
	}

	public static function __callStatic ($name, $arguments) {
		if (in_array (strtolower ($name), self::$methods) && (count ($arguments) == 2)) {
			$path = array_filter (explode ('/', $arguments[0]));
			$controller = array_filter (preg_split ('/[@,\(\)\s]+/', $arguments[1]), function ($t) { return $t || $t === '0'; });

			if (count ($controller) < 2)
				array_push ($controller, 'index');

			self::$route[$name . ':' . implode ('/', $path) . '/'] = implode ('/', $controller);
		} else {
			show_error ("Route 使用方法錯誤!<br/>尚未定義: Route::" . $name . " 的方法!");
		}
	}

	public static function resource ($uris, $controller, $prefix = '') {
		$uris = is_string ($uris) ? array ($uris) : $uris;
		$c = count ($uris);
		$prefix = trim ($prefix, '/') . '/';

		self::get ($prefix . implode ('/(:id)/', $uris) . '/', $prefix . $controller . '@index');
		self::get ($prefix . implode ('/(:id)/', $uris) . '/(:id)', $prefix . $controller . '@show($1' . ($c > 1 ? ', ' . implode (', ', array_map (function ($a) { return '$' . $a; }, range (2, $c))) : '') . ')');
		self::get ($prefix . implode ('/(:id)/', $uris) . '/add', $prefix . $controller . '@add(' . ($c > 1 ? ', ' . implode (', ', array_map (function ($a) { return '$' . $a; }, range (1, $c - 1))) : '') . ')');
		self::post ($prefix . implode ('/(:id)/', $uris) . '/', $prefix . $controller . '@create(' . ($c > 1 ? ', ' . implode (', ', array_map (function ($a) { return '$' . $a; }, range (1, $c - 1))) : '') . ')');
		self::get ($prefix . implode ('/(:id)/', $uris) . '/(:id)' .  '/edit', $prefix . $controller . '@edit($1' . ($c > 1 ? ', ' . implode (', ', array_map (function ($a) { return '$' . $a; }, range (2, $c))) : '') . ')');
		self::put ($prefix . implode ('/(:id)/', $uris) . '/(:id)', $prefix . $controller . '@update($1' . ($c > 1 ? ', ' . implode (', ', array_map (function ($a) { return '$' . $a; }, range (2, $c))) : '') . ')');
		self::delete ($prefix . implode ('/(:id)/', $uris) . '/(:id)', $prefix . $controller . '@destroy($1' . ($c > 1 ? ', ' . implode (', ', array_map (function ($a) { return '$' . $a; }, range (2, $c))) : '') . ')');
	}
	public static function getRoute () {
		return self::$route;
	}
}

class CI_Router {
	var $config;
	var $routes       = array ();
	var $class        = '';
	var $method       = 'index';
	var $directory    = '';
	var $default_controller;
	
	public function __construct () {
		$this->config =& load_class ('Config', 'core');
		$this->uri =& load_class ('URI', 'core');
		log_message ('debug', "Router Class Initialized");

		$this->_set_routing ();
	}

	private function _set_routing () {
		$segments = array ();

		if (($this->config->item ('enable_query_strings') === true) && isset ($_GET[$this->config->item ('controller_trigger')])) {
			if (isset ($_GET[$this->config->item ('directory_trigger')])) {
				$this->set_directory (trim ($this->uri->_filter_uri ($_GET[$this->config->item ('directory_trigger')])));
				array_push ($segments, $this->fetch_directory ());
			}

			if (isset ($_GET[$this->config->item ('controller_trigger')])) {
				$this->set_class (trim ($this->uri->_filter_uri ($_GET[$this->config->item ('controller_trigger')])));
				array_push ($segments, $this->fetch_class ());
			}

			if (isset ($_GET[$this->config->item ('function_trigger')])) {

				$this->set_method (trim ($this->uri->_filter_uri ($_GET[$this->config->item ('function_trigger')])));
				array_push ($segments, $this->fetch_method ());
			}
		}

		if (defined ('ENVIRONMENT') && is_file ($path = APPPATH . implode (DIRECTORY_SEPARATOR, array ('config', ENVIRONMENT, 'routes.php'))))
			include $path;
		else if (is_file($path = APPPATH . implode (DIRECTORY_SEPARATOR, array ('config', 'routes.php'))))
			include $path;

		$this->routes = Route::getRoute ();

		$this->default_controller = isset ($this->routes['get:/']) ? $this->routes['get:/'] : false;

		if ($segments)
			return $this->_validate_request($segments);

		$this->uri->_fetch_uri_string ();

		if ($this->uri->uri_string == '')
			return $this->_set_default_controller ();

		$this->uri->_remove_url_suffix ();
		$this->uri->_explode_segments ();
		$this->_parse_routes ();
		$this->uri->_reindex_segments ();
	}

	private function _set_default_controller () {
		if ($this->default_controller === false)
			show_error ("找不到預設的頁面，請確認 application/config/routes.php 是否有設置預設頁面。");

		$this->_set_class_method ();
		$this->uri->_reindex_segments();
		log_message('debug', "No URI present. Default controller set.");
	}

	private function _set_request ($segments = array ()) {
		$segments = $this->_validate_request ($segments);

		if (!$segments)
			return $this->_set_default_controller ();

		$this->set_class ($segments[0]);

		if (isset ($segments[1]))
			$this->set_method ($segments[1]);
		else
			$segments[1] = 'index';

		$this->uri->rsegments = $segments;
	}

	private function _set_class_method () {
		if (strpos ($this->default_controller, '/') !== false) {
			$x = explode ('/', $this->default_controller);

			$this->set_class ($x[0]);
			$this->set_method ($x[1]);
			$this->_set_request ($x);
		} else {
			$this->set_class ($this->default_controller);
			$this->set_method ('index');
			$this->_set_request (array ($this->default_controller, 'index'));
		}
	}
	private function _validate_request ($segments) {
		if (!$segments)
			return $segments;

		if (file_exists (APPPATH . implode (DIRECTORY_SEPARATOR, array ('controllers', $segments[0] . EXT))))
			return $segments;

		if (is_dir (APPPATH . 'controllers' . DIRECTORY_SEPARATOR . $segments[0])) {
			$this->set_directory (array_shift ($segments));

			if ($segments) {
				if (!file_exists (APPPATH . 'controllers' . DIRECTORY_SEPARATOR . $this->fetch_directory () . $segments[0] . EXT))
					return show_404 ();
			} else {
				$this->_set_class_method ();

				if (!file_exists (APPPATH . 'controllers' . DIRECTORY_SEPARATOR . $this->fetch_directory () . $this->default_controller . EXT)) {
					$this->directory = '';
					return array ();
				}
			}
			return $segments;
		}

		return show_404 ();
	}

	private function _parse_routes() {
		if (isset ($_REQUEST['_method']) && in_array (strtolower ($_REQUEST['_method']), Route::$methods))
			$_SERVER['REQUEST_METHOD'] = $_REQUEST['_method'];

		$request_method = isset ($_SERVER['REQUEST_METHOD']) ? strtolower ($_SERVER['REQUEST_METHOD']) : 'get';
		$uri = implode ('/', $this->uri->segments) . '/';

		if (isset ($this->routes[$request_method . ':' . $uri]) && is_string ($this->routes[$request_method . ':' . $uri]))
			return $this->_set_request (explode ('/', $this->routes[$request_method . ':' . $uri]));

		foreach ($this->routes as $key => $val) {
			$key = str_replace (':any', '.+', str_replace (':num', '[0-9]+', str_replace (':id', '[0-9]+', $key)));

			if (preg_match ('#^'.$key.'$#', $request_method . ':' . $uri)) {
				if ((strpos ($val, '$') !== false) && (strpos ($key, '(') !== false))
					$val = preg_replace('#^'.$key.'$#', $val, $request_method . ':' . $uri);

				return $this->_set_request (explode ('/', $val));
			}
		}

		$this->_set_request ($this->uri->segments);
	}

	public function set_class ($class) {
		$this->class = str_replace (array (DIRECTORY_SEPARATOR, '.'), '', $class);
		return $this;
	}

	public function fetch_class () {
		return $this->class;
	}

	public function set_method ($method) {
		$this->method = $method;
		return $this;
	}

	public function fetch_method () {
		if ($this->method == $this->fetch_class ())
			return 'index';

		return $this->method;
	}

	public function set_directory ($dir) {
		$this->directory = str_replace (array (DIRECTORY_SEPARATOR, '.'), '', $dir) . DIRECTORY_SEPARATOR;
		return $this;
	}

	public function fetch_directory () {
		return $this->directory;
	}
}