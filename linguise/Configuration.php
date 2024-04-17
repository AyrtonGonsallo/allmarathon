<?php
namespace Linguise\Script;

use Linguise\Script\Core\Response;

if (!defined('LINGUISE_SCRIPT_TRANSLATION')) die();

class Configuration {
    /** Mandatory configuration **/
    public static $token = 'lNx5wHya2RpxBUB05rP2mBiJsMBlhDPf'; //Replace the token by the one found in your Linguise dashboard

    /** Basic configuration **/
    /*
     * Update the CMS value according to your CMS
     * Available CMS are: laravel, prestashop, magento
     */
    public static $cms = 'auto';

    public $cache_enabled = false;
    public $cache_max_size = 200; // In megabyte

    /** Advanced configuration **/
    public static $server_ip = null;
    public static $server_port = 443;
    public static $debug = false;
    public static $data_dir = null;
    public static $base_dir = null;
    public static $dl_certificates = false;

    /** Advanced database configuration **/
    /*
     *  In case you don't want to use Sqlite, you can use MySQL
     *  To do so, you need to fill the following variables
     *  Linguise will create the tables for you
     */
    public static $db_host = '';
    public static $db_user = '';
    public static $db_password = '';
    public static $db_name = '';
    public static $db_prefix = '';

    /** Development configuration */
    public static $port = 443;
    public static $host = 'translate.linguise.com';
    public static $update_url = 'https://www.linguise.com/files/php-script-update.json';

    public static function onAfterMakeRequest()
    {
        if (\Linguise\Script\Core\Configuration::getInstance()->get('cms') === 'prestashop' && !empty($_POST['action']) && !empty($_POST['id_product'])) {
            // This is a cart request, let's just return the response
            Response::getInstance()->end();
        }
    }
}
