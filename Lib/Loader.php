<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bernardo
 */

namespace Lib;

class Loader
{

    /**
     * Construct
     */
    public function __construct()
    {

    }

    /**
     * Load
     */
    public function load()
    {
        $this->registerAutoloader();
    }

    /**
     * __autoload() deprecated  It only allows for a single autoloader, spl_autoload_register allows several
     * autoloaders to be registered which will be run through in turn until a match is found
     * @return bool
     */
    protected function registerAutoloader()
    {
        return spl_autoload_register(array(__CLASS__, 'includeClass'));
    }

    /**
     * Example: $class = \Scraping\Curl (namespace\class)
     * @param $class
     */
    protected function includeClass($class)
    {
        require_once(__DIR__ . '/../' . strtr($class, '\\', '//') . '.php');
    }

}