<?php
defined('ABSPATH') OR exit('Nenhum acesso de script direto permitido');

/**
 * DEFINE PATHS
 */
define('SIUP_PATH', plugin_dir_path(dirname(__FILE__)));
define('SIUP_CONTROLLERS_PATH', SIUP_PATH . 'controllers/');
define('SIUP_CORE_PATH', SIUP_PATH . 'core/');
define('SIUP_HELPERS_PATH', SIUP_PATH . 'helpers/');
define('SIUP_LANGUAGE_PATH', SIUP_PATH . 'language/');
define('SIUP_LIBRARIES_PATH', SIUP_PATH . 'libraries/');
define('SIUP_MODELS_PATH', SIUP_PATH . 'models/');
define('SIUP_VIEWS_PATH', SIUP_PATH . 'views/');
define('SIUP_IMAGES_PATH', SIUP_PATH . 'assets/images/');
define('SIUP_UPLOADS_PATH', dirname(dirname(SIUP_PATH)) . '/uploads/');

/**
 * DEFINE URLS
 */
define('SIUP_URL', plugin_dir_url(dirname(__FILE__)));
define('SIUP_JS_URL', SIUP_URL . 'assets/js/');
define('SIUP_CSS_URL', SIUP_URL . 'assets/css/');
define('SIUP_IMAGES_URL', SIUP_URL . 'assets/images/');
define('SIUP_UPLOADS_URL', dirname(dirname(SIUP_URL)) . '/uploads/');

define('SIUP_LANG', 'siup');
define('SIUP_PLUGIN_NOME', 'siup');
define('PLUGIN_PREFIX', 'SIUP');

?>