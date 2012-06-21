<?php

namespace application;

use application\models\Config as Config;

class BasicApplication {
	
	protected $_sources = array();
	
	public function __construct(){		
		// set the class sources
		$this->_sources['APP_PATH'] = str_replace('/', '\\', str_replace('index.php', 'custom\\', $_SERVER['SCRIPT_FILENAME']));
		$this->_sources['SRC_PATH'] = str_replace('application\BasicApplication.php', '', __FILE__);
		
		// set the autoloader
		spl_autoload_register(array($this, 'autoload'));
		
		// load the config file
		Config::instance()->load($this->_sources['APP_PATH'] . 'config.php');
		Config::instance()->srcPath = $this->_sources['SRC_PATH'];
		Config::instance()->appPath = $this->_sources['APP_PATH'];
		Config::instance()->rootUrl = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
	}
	
	protected function autoload($class){
		$path = str_replace('\\', '/', $class);
		$path = str_replace('_', '/', $class);
		$found = false;
		foreach($this->_sources as $source){
			if(file_exists($source . $path . '.php')){
				include($source . $path . '.php');
				spl_autoload($class);
				$found = true;
				break;
			}
		}
		if(!$found){
			throw new \Exception('class ' . $class . ' not found');
		}
	}
	
}
