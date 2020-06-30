<?php

/**
 * @package siup
 */
/*
Plugin Name: siup
Plugin URI: http://siup.com.br/siup
Description: Plugin para gestão de site politico. Configura wordpress a http://siup.com.br/siup
Version: 0.0.1
Author: Washington Cosme
Author URI: http://siup.com.br/siup
License: GPLv2 or later
Text Domain: siup
*/

require_once(plugin_dir_path(__FILE__).'config/config.php');
require_once(SIUP_LIBRARIES_PATH.'componente.php');
require_once(SIUP_CORE_PATH.'meumodelo.php');
if (!function_exists('wp_get_current_user')) {
    include_once(ABSPATH . 'wp-includes/pluggable.php');
}
$controler = Componente::GetControle("controller");
?>