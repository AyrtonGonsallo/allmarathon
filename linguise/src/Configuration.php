<?php
namespace Linguise\Script\Core;

defined('LINGUISE_SCRIPT_TRANSLATION') or die();

class Configuration {
    /**
     * @var null|Configuration
     */
    private static $_instance = null;

    /** Mandatory configuration **/
    private $token = '';

    /** Basic configuration **/
    private $cache_enabled = true;
    private $cache_max_size = 200; // In megabyte
    private $cache_time_check = 600; // In seconds

    /** Advanced configuration **/
    private $cms = '';
    private $server_ip = null;
    private $server_port = null;
    private $debug = false;
    private $debug_ip = '';
    private $data_dir = null;
    private $base_dir = null;
    private $dl_certificates = false;

    /** Advanced database configuration **/
    private $db_host = '';
    private $db_user = '';
    private $db_password = '';
    private $db_name = '';
    private $db_prefix = '';
    private $db_port = '3306';

    /** Development configuration */
    private $port = 443;
    private $host = 'translate.linguise.com';
    private $update_url = 'https://www.linguise.com/php_script_update.json';

    private function __construct()
    {
    }

    /**
     * Retrieve singleton instance
     *
     * @return Configuration|null
     */
    public static function getInstance() {

        if(is_null(self::$_instance)) {
            self::$_instance = new Configuration();
        }

        return self::$_instance;
    }

    public function loadFile($configurationFile, $localConfiguration = false) {
        if (!file_exists($configurationFile)) {
            return false;
        }

        require_once($configurationFile);
        if ($localConfiguration) {
            // We override the WordPress Configuration with a local configuration
            $class = \Linguise\Script\ConfigurationLocal::class;
        } else {
            // We use the default configuration
            $class = \Linguise\Script\Configuration::class;
        }

        foreach (get_class_vars($class) as $attribute_name => $attribute_value) {
            Configuration::getInstance()->set($attribute_name, $attribute_value);
        }
        foreach (get_class_methods($class) as $hook) {
            if (strpos($hook, 'on') !== 0) {
                continue;
            }
            Hook::add($hook, $class);
        }

        return true;
    }

    public function load($basePath) {
        $configurationLocalLoaded = $this->loadFile($basePath . DIRECTORY_SEPARATOR . 'ConfigurationLocal.php', true);
        if (!$configurationLocalLoaded) {
            $this->load($basePath . DIRECTORY_SEPARATOR . 'Configuration.php');
        }
    }

    public function get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public function set($property, $value) {
        if ($property === '_instance') {
            return $this;
        }
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }
}