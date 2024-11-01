<?php

/*
  Plugin Name: IOL Translation
  Plugin URI: http://qcm.iol8.com
  Description: IOL services plugin
  Version: 2.1.0.0
  Author: qcm.iol8.com
  Author URI: http://qcm.iol8.com
  License:

  Copyright 2014 qcm.iol8.com
 */

// dev mode
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


// 定义constant
define('IOL_TRANSLATION_VERSION', '2.1.0.0');
define('IOL_TRANSLATION_SLUG', 'iol-translation');
define('IOL_TRANSLATION_URL', plugin_dir_url(__FILE__));
define('IOL_TRANSLATION_DIR', plugin_dir_path(__FILE__));
define('IOL_TRANSLATION_DIR_BASE_FILE', __FILE__);
define('IOL_TRANSLATION_WP_VERSION_REQUIREMENT', '3.8.1');
define('IOL_TRANSLATION_QTRANSLATE_VERSION_REQUIREMENT', '2.5.19');

// 多语言定义域
define('IOL_TRANSLATION_TEXTDOMAIN', 'iol-translation');

// 从插件入口函数，开始加载插件
add_action('plugins_loaded', 'iol_translation_plugin_loaded');

// 插件激活钩子，db sql
register_activation_hook(__FILE__, 'iol_translation_db_install');
register_activation_hook(__FILE__, 'iol_translation_db_install_data');
// 插件卸载钩子
//register_deactivation_hook(__FILE__, 'iol_translation_db_uninstall');
register_uninstall_hook(__FILE__, 'iol_translation_db_uninstall');

/**
 * 插件入口函数
 */
function iol_translation_plugin_loaded() {
    // 加载多语言
    load_plugin_textdomain(IOL_TRANSLATION_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/lang/');

    // 加载class文件
    $directorys = array(
        IOL_TRANSLATION_DIR . 'lib' . DIRECTORY_SEPARATOR . 'init' . DIRECTORY_SEPARATOR,
        IOL_TRANSLATION_DIR . 'lib' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR,
        IOL_TRANSLATION_DIR . 'lib' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR,
        IOL_TRANSLATION_DIR . 'lib' . DIRECTORY_SEPARATOR . 'my' . DIRECTORY_SEPARATOR,
        IOL_TRANSLATION_DIR . 'lib' . DIRECTORY_SEPARATOR . 'bulk' . DIRECTORY_SEPARATOR,        
        IOL_TRANSLATION_DIR . 'lib' . DIRECTORY_SEPARATOR,
    );

    foreach ($directorys as $directory) {
        foreach (glob($directory . "*.php") as $class) {
            include_once $class;
        }
    }
    
    // deactive qTranslate时候，自动deactive IOL插件
    add_action( 'admin_init', 'iol_translation_admin_init_check' );
    
    // callback回调钩子，挂载到init事件
    add_action('init', 'iol_translation_api_callback_handler');

    // 增加自定义的 css和js 
    add_action('admin_enqueue_scripts', 'iol_transaltion_plugin_scripts');

    // 加载多语言
    load_plugin_textdomain('iol-translation', false, dirname(plugin_basename(__FILE__)) . '/lang');

    // 初始化主菜单页面
    $iol_translation_init_admin_menu = new Iol_Translation_Init_Admin_Menu();

    // define tables, 挂载插件附加表名字到全局 $wpdb object
    global $wpdb;
    $wpdb->iol_translation_configuration = "{$wpdb->prefix}iol_translation_configuration";
    $wpdb->iol_translation_type = "{$wpdb->prefix}iol_translation_type";
    $wpdb->iol_translation_sub_type = "{$wpdb->prefix}iol_translation_sub_type";
    $wpdb->iol_translation_order = "{$wpdb->prefix}iol_translation_order";
    $wpdb->iol_translation_manuscript = "{$wpdb->prefix}iol_translation_manuscript";
    $wpdb->iol_translation_language = "{$wpdb->prefix}iol_translation_language";

    // 定义IOL语言
    define('IOL_TRANSLATION_LANG_ZH', Iol_Translation_U::__('Simple Chinese'));
    define('IOL_TRANSLATION_LANG_EN', Iol_Translation_U::__('English'));
    define('IOL_TRANSLATION_LANG_JA', Iol_Translation_U::__('Japanese'));
    define('IOL_TRANSLATION_LANG_FR', Iol_Translation_U::__('French'));
    define('IOL_TRANSLATION_LANG_DE', Iol_Translation_U::__('German'));
    define('IOL_TRANSLATION_LANG_RU', Iol_Translation_U::__('Russian'));
    define('IOL_TRANSLATION_LANG_KO', Iol_Translation_U::__('Korea'));
    define('IOL_TRANSLATION_LANG_NL', Iol_Translation_U::__('Dutch'));
    define('IOL_TRANSLATION_LANG_IT', Iol_Translation_U::__('Italian'));
    define('IOL_TRANSLATION_LANG_ES', Iol_Translation_U::__('Spanish'));
    define('IOL_TRANSLATION_LANG_PT', Iol_Translation_U::__('Portuguese'));
    define('IOL_TRANSLATION_LANG_AR', Iol_Translation_U::__('Arabic'));
    define('IOL_TRANSLATION_LANG_TR', Iol_Translation_U::__('Turkish'));
    define('IOL_TRANSLATION_LANG_TH', Iol_Translation_U::__('Thai'));
    define('IOL_TRANSLATION_LANG_UK', Iol_Translation_U::__('Ukrainian'));
    define('IOL_TRANSLATION_LANG_DA', Iol_Translation_U::__('Denish'));
    define('IOL_TRANSLATION_LANG_NO', Iol_Translation_U::__('Norwegian'));
    define('IOL_TRANSLATION_LANG_FI', Iol_Translation_U::__('Finnish'));
    define('IOL_TRANSLATION_LANG_EL', Iol_Translation_U::__('Greek'));
    define('IOL_TRANSLATION_LANG_PL', Iol_Translation_U::__('Polish'));
    define('IOL_TRANSLATION_LANG_RO', Iol_Translation_U::__('Romanian'));
    define('IOL_TRANSLATION_LANG_BG', Iol_Translation_U::__('Bulgarian'));
    define('IOL_TRANSLATION_LANG_CS', Iol_Translation_U::__('Czech'));
    define('IOL_TRANSLATION_LANG_SK', Iol_Translation_U::__('Slovak'));
    define('IOL_TRANSLATION_LANG_HU', Iol_Translation_U::__('Hungarian'));
    define('IOL_TRANSLATION_LANG_IW', Iol_Translation_U::__('Rabbinic'));
    define('IOL_TRANSLATION_LANG_SV', Iol_Translation_U::__('Swedish'));
    define('IOL_TRANSLATION_LANG_HR', Iol_Translation_U::__('Croatian'));
    define('IOL_TRANSLATION_LANG_SQ', Iol_Translation_U::__('Albanian'));
    define('IOL_TRANSLATION_LANG_ZH_TW', Iol_Translation_U::__('Traditional Chinese (Taiwan)'));
    define('IOL_TRANSLATION_LANG_ZH_HK', Iol_Translation_U::__('Traditional Chinese (HongKong)'));
    define('IOL_TRANSLATION_LANG_KO_N', Iol_Translation_U::__('Korean'));
    define('IOL_TRANSLATION_LANG_SR', Iol_Translation_U::__('Serbian'));
    define('IOL_TRANSLATION_LANG_PT_BR', Iol_Translation_U::__('Portuguese(Brazil)'));
}

/**
 * db insert table， 插件激活时db初始化表用
 * 
 * @global type $wpdb
 */
function iol_translation_db_install() {
    iol_translation_active_pre_check();
  
    global $wpdb;
	
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}iol_translation_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `val` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

    dbDelta($sql);
	
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}iol_translation_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wp_language_code` varchar(255) DEFAULT NULL,
  `iol_language_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

    dbDelta($sql);
	
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}iol_translation_manuscript` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iol_translation_order_id` int(11) NOT NULL COMMENT 'order_id',
  `object_local_orginal_language_code` varchar(255) DEFAULT NULL COMMENT 'object local wp language code',
  `iol_translation_type_id` int(11) DEFAULT NULL COMMENT 'type, object table',
  `iol_translation_sub_type_id` int(11) DEFAULT NULL COMMENT 'sub type, object table field',
  `object_id` int(11) DEFAULT NULL COMMENT 'object id in given table base on type',
  `manuscript_number` varchar(255) DEFAULT NULL COMMENT 'api manuscriptid',
  `source_language_code` varchar(255) DEFAULT NULL COMMENT 'api language code',
  `target_language_code` varchar(255) DEFAULT NULL COMMENT 'api language code',
  `word_count` int(11) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `orginal` text COMMENT 'text before translation',
  `translations` text COMMENT 'text after translation',
  `user_param` varchar(255) DEFAULT NULL COMMENT 'our manuscript token',
  `transed_at` datetime DEFAULT NULL COMMENT 'api finishTime',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

    dbDelta($sql);
	
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}iol_translation_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iol_translation_type_id` int(11) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL COMMENT '1=professional translator, 2=standard translator',
  `price` varchar(255) DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `word_count` int(11) DEFAULT NULL,
  `pay_url` varchar(255) DEFAULT NULL,
  `user_param` varchar(255) DEFAULT NULL COMMENT 'our order token',
  `payment_number` varchar(255) DEFAULT NULL COMMENT 'payment transaction number from api',
  `payment_status` tinyint(4) DEFAULT '1' COMMENT 'pending = 1, done = 2',
  `translation_status` tinyint(4) DEFAULT '1' COMMENT 'pending = 1, done = 2',
  `paid_at` int(11) DEFAULT NULL COMMENT 'pay api callback time',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

    dbDelta($sql);
	
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}iol_translation_sub_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iol_translation_type_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

    dbDelta($sql);
	
    $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}iol_translation_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `tb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";
          
    dbDelta($sql);

    add_option("iol_translation_version", IOL_TRANSLATION_VERSION);
}

/**
 * db insert data， 插件激活时db初始化插入数据
 * 
 * @global type $wpdb
 */
function iol_translation_db_install_data() {
    global $wpdb;
    $config_table = $wpdb->prefix . "iol_translation_configuration";
    $rows_affected = $wpdb->insert($config_table, array('code' => 'api_access_useremail', 'val' => ''));
    $rows_affected = $wpdb->insert($config_table, array('code' => 'api_access_password', 'val' => ''));
      
    $type_table = $wpdb->prefix . "iol_translation_type";
    $rows_affected = $wpdb->insert($type_table, array('name' => 'posts', 'tb' => ''));
    $rows_affected = $wpdb->insert($type_table, array('name' => 'pages', 'tb' => ''));
    $rows_affected = $wpdb->insert($type_table, array('name' => 'categories', 'tb' => ''));
    $rows_affected = $wpdb->insert($type_table, array('name' => 'tags', 'tb' => ''));
    
    $sub_type_table = $wpdb->prefix . "iol_translation_sub_type";
    $rows_affected = $wpdb->insert($sub_type_table, array('iol_translation_type_id' => '1', 'name' => 'post_title'));
    $rows_affected = $wpdb->insert($sub_type_table, array('iol_translation_type_id' => '1', 'name' => 'post_content'));
    $rows_affected = $wpdb->insert($sub_type_table, array('iol_translation_type_id' => '2', 'name' => 'page_title'));
    $rows_affected = $wpdb->insert($sub_type_table, array('iol_translation_type_id' => '2', 'name' => 'page_content'));
    $rows_affected = $wpdb->insert($sub_type_table, array('iol_translation_type_id' => '4', 'name' => 'tag_name'));
    $rows_affected = $wpdb->insert($sub_type_table, array('iol_translation_type_id' => '3', 'name' => 'categorie_name'));
    
}

/**
 * db drop table， 插件uninstall时db删除表用
 * 
 * @global type $wpdb
 */
function iol_translation_db_uninstall() {
    global $wpdb;
    $sqls[] = "DROP TABLE IF EXISTS `{$wpdb->prefix}iol_translation_configuration`;";
    $sqls[] = "DROP TABLE IF EXISTS `{$wpdb->prefix}iol_translation_language`;";
    $sqls[] = "DROP TABLE IF EXISTS `{$wpdb->prefix}iol_translation_manuscript`;";
    $sqls[] = "DROP TABLE IF EXISTS `{$wpdb->prefix}iol_translation_order`;";
    $sqls[] = "DROP TABLE IF EXISTS `{$wpdb->prefix}iol_translation_sub_type`;";
    $sqls[] = "DROP TABLE IF EXISTS `{$wpdb->prefix}iol_translation_type`;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    foreach ($sqls as $sql) {
        $wpdb->query($sql);
    }
    delete_option("iol_translation_version");
}

/**
 * 回调，返稿
 * 
 * 样例callback url

 * order
 * http://iol8wordpress.local/?iol_translation_api_callback_handler=1&type=order
 * network_site_url('?iol_translation_api_callback_handler=1&type=order')
 * 
 * pay
 * http://iol8wordpress.local/?iol_translation_api_callback_handler=1&type=pay
 * network_site_url('?iol_translation_api_callback_handler=1&type=pay')
 */
function iol_translation_api_callback_handler() {
    // 无需处理，则立即返回，继续wp后续流程
    if (!isset($_GET['iol_translation_api_callback_handler'])) {
        return;
    }

    // 处理
    $helper = new Iol_Translation_Api_Callback_Handler();
    if ($_GET['type'] == 'order') {	
        $helper->processReturnOrder();		
    } elseif ($_GET['type'] == 'pay') {
        $helper->processReturnPayment();
    }
    // 立刻结束退出wp后续流程
    exit;
}

/*
 * 增加json格式到ajax
 */
wp_localize_script('storm_json_config', 'storm_config', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
));

/**
 * css, js资源
 */
function iol_transaltion_plugin_scripts() {
    wp_register_script('plugin', plugins_url('web/js/admin-page.js', __FILE__), array('jquery'), '');
    wp_enqueue_script('plugin');
    wp_register_style('plugin', plugins_url('web/css/admin-page.css', __FILE__));
    wp_enqueue_style('plugin');
}

/**
 * 激活iol插件时检查:wp版本，qTranslate插件依赖
 */
function iol_translation_active_pre_check() {
    global $wp_version;     
    $wp_version_required = IOL_TRANSLATION_WP_VERSION_REQUIREMENT;
    
    $qt_plugin = 'qtranslate/qtranslate.php';
    $q_t_version_required = IOL_TRANSLATION_QTRANSLATE_VERSION_REQUIREMENT;    
    $plugin = plugin_basename( __FILE__ );
    $plugin_data = get_plugin_data( __FILE__, false );
    $q_t_version = 0;
    
   if(is_plugin_active( $qt_plugin) && file_exists(WP_PLUGIN_DIR .'/'. $qt_plugin)){
        $q_t_parts = get_plugin_data(WP_PLUGIN_DIR .'/'. $qt_plugin);
        $q_t_version = $q_t_parts['Version'];
    }
    
    if(!($wp_version >= $wp_version_required)){
        echo 'Wordpress version must >='.$wp_version_required;
        exit;        
    }

    // check if qTranslate is there & active
    if (!is_plugin_active( $qt_plugin) || !($q_t_version >= $q_t_version_required)) {
        echo $plugin_data['Name']." requires qTranslate $q_t_version_required or higher, Please enable it and try again.";
        exit;
    }    
}

/**
 * admin init检查:wp版本，qTranslate插件依赖
 */
function iol_translation_admin_init_check() {
    global $wp_version;     
    $wp_version_required = IOL_TRANSLATION_WP_VERSION_REQUIREMENT;
    
    $qt_plugin = 'qtranslate/qtranslate.php';
    $q_t_version_required = IOL_TRANSLATION_QTRANSLATE_VERSION_REQUIREMENT;    
    $plugin = plugin_basename( __FILE__ );
    $plugin_data = get_plugin_data( __FILE__, false );
    $q_t_version = 0;
    
    if(is_plugin_active( $qt_plugin) && file_exists(WP_PLUGIN_DIR .'/'. $qt_plugin)){
        $q_t_parts = get_plugin_data(WP_PLUGIN_DIR .'/'. $qt_plugin);
        $q_t_version = $q_t_parts['Version'];
    }
    
    if(!($wp_version >= $wp_version_required)){
        deactivate_plugins ( $plugin );
        wp_die( "<strong>".$plugin_data['Name']."</strong> requires <strong>wordpress $wp_version_required</strong> or higher, Please fix it and try again.<br /><br />Back to the WordPress <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
    }
    
    if ( !is_plugin_active( $qt_plugin) || !($q_t_version >= $q_t_version_required)) {
        deactivate_plugins ( $plugin );
        wp_die( "<strong>".$plugin_data['Name']."</strong> requires <strong>qTranslate $q_t_version_required</strong> or higher, and has been deactivated! Please enable it and try again.<br /><br />Back to the WordPress <a href='".get_admin_url(null, 'plugins.php')."'>Plugins page</a>." );
    }
}

?>