<?php

define('PATH_FILE_CONFIG','config');
define('FILE_CONFIG','config.json');

# This optional set
# define('TYPE_FILE_CONFIG','json');

class AdminDB {
    private $_location;
    public function run($location) {
        $this->_location = $location;
        $file = file_get_contents($this->_location . "/config/config.json");
        $data = json_decode($file, 1);

        $files_load = $data['loader_class'];
        
        foreach ($files_load AS $dir => $files) {
            foreach ($files AS $file) {
                $this->load($file, $dir);
            }
        }
        
        Connections::register_connections();
    }
    
    public function load($file, $dir) {
        $file = $this->_location . '/' . $dir . '/' . strtolower($file) . '.php';
        if (@file_exists($file)) {
            require_once $file;
        } else {
            throw new Exception('Not found ' . $file, 500);
        }
    }
}