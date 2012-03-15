<?php

class F_Cache_File {

    private $cacheDir;
    private $cacheExpire;
    private $cacheFile;
    private $cacheEnabled;
    private $caching;


    /**
     * @var F_Cache_File
     */
    private static $_instance;

    /**
     * @return F_Cache_File
     */
    public static function getInstance()
    {
	if (self::$_instance === null) {
	    self::$_instance = new self;
	}
	return self::$_instance;
    }

    private function __construct() {

        $config = F_Configuration::getInstance();

        $this->cacheDir     = $config->cache_dir;
        $this->cacheFile    = md5($_SERVER['REQUEST_URI']) . '.cache';
        $this->cacheExpire  = $config->cache_expire;
        $this->cacheEnabled = $config->cache_enabled;
        $this->caching      = FALSE;
    }
    private function __clone() {}

    public function start()
    {
        if ($this->cacheEnabled) {
            if (file_exists($this->cacheDir . DS . $this->cacheFile) && (time() - filemtime($this->cacheDir . DS . $this->cacheFile)) < $this->cacheExpire) {
                $this->caching = FALSE;
                echo file_get_contents($this->cacheDir . DS . $this->cacheFile);
                return;
            } else {
                $this->caching = TRUE;
                ob_start();
            }
        }
    }

    public function end()
    {
        if ($this->caching) {
            file_put_contents($this->cacheDir . DS . $this->cacheFile, ob_get_contents());
            ob_end_clean();
        }
    }

    public function purge()
    {
        if (file_exists($this->cacheDir . DS . $this->cacheFile)) {
            unlink($this->cacheDir . DS . $this->cacheFile);
        }
    }

    public function purge_all()
    {
        if(!$dirhandle = @opendir($this->cacheDir)) {
            return;
        }
        while(false != ($filename = readdir($dirhandle))) {
            if(substr($filename,-4) == '.cache') {
                $filename = $this->cacheDir. "/". $filename;
                unlink($filename);
            }
        }
    }

    public function is_cache_enabled()
    {
        return $this->cacheEnabled;
    }

    public function enable_cache($true = TRUE)
    {
        $this->cacheEnabled = $true;
        return;
    }

    /**
     * Returns current caching state
     * @return boolean TRUE: Page needs to be cached. FALSE: Page already cached and is going to be shown
     **/
    public function get_caching_state()
    {
        return $this->caching;
    }
}