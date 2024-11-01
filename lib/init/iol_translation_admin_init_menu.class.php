<?php

class Iol_Translation_Init_Admin_Menu {

    /**
     * 构造函数
     */
    public function __construct() {
        $this->title = Iol_Translation_U::__('IOL Translation');
        $this->slug = IOL_TRANSLATION_SLUG;

        add_action('admin_menu', array(&$this, 'add_menus'));
        add_action( 'admin_init', array( &$this, 'add_meta_box' ));
        // 多语言翻译
        add_filter('locale', array(&$this,'iol_transaltion_get_qtranslation_default_language'), 90);
    }
    
 /**
 * 获取qtranslation插件的当前language
 */
 public function iol_transaltion_get_qtranslation_default_language() {
        $t_config['de'] = "de_DE";
        $t_config['en'] = "en_US";
        $t_config['zh'] = "zh_CN";
        $t_config['zh_HK'] = "zh_HK";
        $t_config['fi'] = "fi";
        $t_config['fr'] = "fr_FR";
        $t_config['nl'] = "nl_NL";
        $t_config['it'] = "it_IT";
        $t_config['ru'] = "ru_RU";
        $t_config['sv'] = "sv_SE";
        $t_config['ro'] = "ro_RO";
        $t_config['hu'] = "hu_HU";
        $t_config['ja'] = "ja";
        $t_config['ko'] = "ko_KR";
        $t_config['es'] = "es_ES";
        $t_config['vi'] = "vi";
        $t_config['ar'] = "ar";
        $t_config['tr'] = "tr";
        $t_config['th'] = "th";
        $t_config['uk'] = "uk";
        $t_config['da'] = "da_DK";
        $t_config['no'] = "norsk";
        $t_config['pt'] = "pt_BR";
        $t_config['el'] = "el_GR";
        $t_config['pl'] = "pl_PL";
        $t_config['bg'] = "bg_BG";
        $t_config['cs'] = "cs_CZ";
        $t_config['sk'] = "sk_SK";
        $t_config['hr'] = "hr";
        $t_config['zh_TW'] = "zh_TW";
        $t_config['zh_HK'] = "zh_HK";
        $t_config['sq'] = "sq_AL";
        $t_config['pt_BR'] = "pt_BR";
        $t_config['gl'] = "gl_ES";

        $language_code = 'en';
        if (isset($_COOKIE['qtrans_admin_language'])) {
            $language_code = $_COOKIE['qtrans_admin_language'];
        }
        return $t_config[$language_code] ? $t_config[$language_code] : 'en_US';
     
    }
    
    
    
    public function add_meta_box(){
        add_meta_box( 'iol-translation', __( 'Translation', 'iol-translation' ), array( &$this, 'iol_translation_meta_box_in_post_and_page' ), 'post', 'normal', 'high' );
        add_meta_box( 'iol-translation', __( 'Translation', 'iol-translation' ), array( &$this, 'iol_translation_meta_box_in_post_and_page' ), 'page', 'normal', 'high' );
        add_action('edit_category_form_fields', array( &$this, 'iol_translation_meta_box_in_tags_and_categories' ));
        add_action('edit_tag_form_fields', array( &$this, 'iol_translation_meta_box_in_tags_and_categories' ));
    }
    
   public function iol_translation_meta_box_in_post_and_page() {
        Iol_Translation_U::loadAction('official_meta_box');
    }

    public function iol_translation_meta_box_in_tags_and_categories() {
        Iol_Translation_U::loadAction('unofficial_meta_box');
    }
    
    /**
     * 初始化页面，注册到钩子
     */
    public function add_menus() {
        if (current_user_can('manage_options')) {
            $page_main = add_menu_page($this->title . ' ' . Iol_Translation_U::__('Settings'), $this->title, 'manage_options', $this->slug, array(&$this, 'display_page'), plugins_url('web/img/menu.png', IOL_TRANSLATION_DIR_BASE_FILE), 100);
            $page_order = add_submenu_page($this->slug, $this->title . ' ' . Iol_Translation_U::__('Orders'), Iol_Translation_U::__('Orders'), 'manage_options', $this->slug, array(&$this, 'page_order'));
            $page_account = add_submenu_page($this->slug, $this->title . ' ' . Iol_Translation_U::__('Account'), Iol_Translation_U::__('Account'), 'manage_options', $this->slug . '-account', array(&$this, 'page_account'));
            $page_language = add_submenu_page($this->slug, $this->title . ' ' . Iol_Translation_U::__('Languages'), Iol_Translation_U::__('Languages'), 'manage_options', $this->slug . '-languages', array(&$this, 'page_languages'));
//            $page_demo = add_submenu_page($this->slug, $this->title . ' ' . Iol_Translation_U::__('Demo'), Iol_Translation_U::__('Demo'), 'manage_options', $this->slug . '-demo', array(&$this, 'page_demo'));
        }
        add_action('admin_print_styles-' . $page_main, array(&$this, 'add_styles'));
        add_action('admin_print_styles-' . $page_order, array(&$this, 'add_styles'));
        add_action('admin_print_styles-' . $page_account, array(&$this, 'add_styles'));
        add_action('admin_print_styles-' . $page_language, array(&$this, 'add_styles'));

        add_action('admin_print_scripts-' . $page_main, array(&$this, 'add_scripts'));
        add_action('admin_print_scripts-' . $page_order, array(&$this, 'add_scripts'));
        add_action('admin_print_scripts-' . $page_account, array(&$this, 'add_scripts'));
        add_action('admin_print_scripts-' . $page_language, array(&$this, 'add_scripts'));
    }

    /**
     * 定义css加载
     */
    public function add_styles() {
        wp_enqueue_style('farbtastic');
        wp_enqueue_style('thickbox');
        wp_enqueue_style("{$this->slug}-settings", IOL_TRANSLATION_URL . 'web/css/admin-page.css');
    }

    /**
     * 定义js加载
     */
    public function add_scripts() {
        wp_print_scripts('jquery');
        wp_enqueue_script('jquery');
        wp_enqueue_script('admin-widgets');
        wp_print_scripts('jquery-ui-sortable');
        wp_enqueue_script('farbtastic');
        wp_enqueue_script('thickbox');
        wp_enqueue_script($this->slug . '-settings', IOL_TRANSLATION_URL . 'web/js/admin-page.js');
    }

    /**
     * 主setting页面
     */
    public function display_page() {
        
    }

    public function page_order() {
        Iol_Translation_U::loadAction('admin_order'); 
    }

    public function page_account() {
        Iol_Translation_U::loadAction('admin_account');
    }

    public function page_languages() {
        Iol_Translation_U::loadAction('admin_languages'); 
    }

    public function page_demo() {
        Iol_Translation_U::loadAction('admin_demo'); 
    }
    
    public function page_call_back(){
        Iol_Translation_U::loadAction('page_call_back'); 
    }
    
    }
?>