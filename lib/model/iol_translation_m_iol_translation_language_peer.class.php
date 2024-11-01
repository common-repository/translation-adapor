<?php

class Iol_Translation_M_Iol_Translation_Language_Peer extends Iol_Translation_M_Peer_Base {

    public static function insertWordpressIolLanguageXref($post_datas) {
        global $wpdb;
        foreach ($post_datas as $post_data) {
            if (self::dataIsExist($post_data['qtranslation_language_code'])) {
                // 有就更新
                $wpdb->update(
                        $wpdb->iol_translation_language, // Table
                        array('wp_language_code' => $post_data['qtranslation_language_code'], 'iol_language_code' => $post_data['iol_language_code']), // Array of key(col) => val(value to update to)
                        array(
                    'wp_language_code' => $post_data['qtranslation_language_code'],
                        ) // Where
                );
            } else {
                //没有就添加
                $wpdb->insert(
                        $wpdb->iol_translation_language, // Table
                        array('wp_language_code' => $post_data['qtranslation_language_code'], 'iol_language_code' => $post_data['iol_language_code']) // Array of key(col) => val(value to insert)
                );
            }
        }
        $msg =  Iol_Translation_U::__('Updated successful');
        Iol_Translation_U::setSessionMessages(array('msg' => $msg, 'type' => 'success'));
    }

    public static function dataIsExist($qtranslation_code) {
        global $wpdb;
        $vars = $wpdb->get_results($wpdb->prepare("select count(*) as count from $wpdb->iol_translation_language where wp_language_code = %s", $qtranslation_code));
        if (isset($vars[0])) {
            $data = $vars[0];
            if ($data->count > 0) {
                return true;
            }
            return false;
        }
        return false;
    }

    public static function getSelectedLanguage($wp_language_code) {
        $data = '';
        global $wpdb;
        $vars = $wpdb->get_results($wpdb->prepare("select iol_language_code from $wpdb->iol_translation_language where wp_language_code = %s", $wp_language_code));
        if (isset($vars[0])) {
            $tmp_data = $vars[0];
            $data = $tmp_data->iol_language_code;
        }
        return $data;
    }
    
    public static function getQtranslationDefaultLanguageCode(){
        //left click language
        $language_code = 'en';
        if (isset($_COOKIE['qtrans_admin_language'])) {
            $language_code = $_COOKIE['qtrans_admin_language'];
        }
        return $language_code;
    }
    
    public static function getDefaultMapLanguageCode($qt_language_code = null){
        if(is_null($qt_language_code)){
            return self::getSelectedLanguage(self::getQtranslationDefaultLanguageCode());
        }else{
            return self::getSelectedLanguage($qt_language_code);
        }
    }
    
    public static function getQtranslationLanguageCode($iol_language_code){
        $data = '';
        global $wpdb;
        $vars = $wpdb->get_results($wpdb->prepare("select wp_language_code from $wpdb->iol_translation_language where iol_language_code = %s", $iol_language_code));
        if (isset($vars[0])) {
            $tmp_data = $vars[0];
            $data = $tmp_data->wp_language_code;
        }
        return $data;
    }

}