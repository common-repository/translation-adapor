<?php

class Iol_Translation_Qtranslate_Helper {

    public static function getAllLanguages(){
        global $q_config;
        qtrans_loadConfig();
        
        return $q_config['language_name'];
    }
    
    public static function getEnabledLanguages() {
        global $q_config;
        qtrans_loadConfig();
        
        $enabled_langs = $q_config['enabled_languages'];
        $all_langs = self::getAllLanguages();
        $result = array();
        foreach ($enabled_langs as $enabled_lang_code){
            $new_key = $enabled_lang_code;
            $new_val = $all_langs[$enabled_lang_code];
            $result[$new_key] = $new_val;
        }
        natcasesort($result);
        return $result;
    }
 
    public static function textSplit($text) {
        return qtrans_split($text, $quicktags = true); 
    }
    
    
    public static function textJoin($texts){
        return qtrans_join($texts);
    }
    
    
    public static function getLanguageArrayAfterTheTransformation(){
        $tmp_lanuages = array();
        global $wpdb;
        $data = null;
        foreach (self::getEnabledLanguages() as $language_code => $language) {
           $vars = $wpdb->get_results($wpdb->prepare("select iol_language_code from $wpdb->iol_translation_language where wp_language_code = %s", $language_code)); 
           $data = $vars[0];
           if(isset($data)){
             $tmp_lanuages[$data->iol_language_code] = $language;
           }
        }
        return $tmp_lanuages;
    }
    
    
    
    
}