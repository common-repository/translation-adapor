<?php

class iol_translation_m_terms_peer extends Iol_Translation_M_Peer_Base {
    
   public static function retrieveByPK($id) {
        global $wpdb;
        $data = null;
        $vars = $wpdb->get_results($wpdb->prepare("select * from $wpdb->terms where term_id = %d", $id));
        if (isset($vars[0])) {
            $data = $vars[0];
        }
        return $data;
    }
    
    
    public static function getNameByCurrentLanguage($id,$iol_language_code){
        $current_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getQtranslationLanguageCode($iol_language_code);
        $language_terms = self::getTranslationFieldsByDefaultLanguage($id);
        return $language_terms[$current_language_code];
    }
    
    
    public static function getTranslationFieldsByDefaultLanguage($id){
        $trem = self::retrieveByPK($id);
        $obj_name = $trem->name;
        $terms = self::getTermNames();
        
        $this_term = $terms[$obj_name];
        return $this_term;
    } 
    
    public static function getTermNames(){
        global $wpdb;
        $data = null;
        $vars = $wpdb->get_results($wpdb->prepare("select * from $wpdb->options where option_name = %s", 'qtranslate_term_name'));
        if (isset($vars[0])) {
            $data = $vars[0];
        }
        return unserialize($data->option_value);
    }
    
    public static function processCategoriesOrTagReturn($manuscript){
        global $wpdb;
        $translation = $manuscript->translations;
        $language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getQtranslationLanguageCode($manuscript->target_language_code);
        $object_id = $manuscript->object_id;
        switch ($manuscript->iol_translation_sub_type_id) {
            case Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME :
                    $trem = self::retrieveByPK($object_id);
                    $obj_name = $trem->name;
                    $terms = self::getTermNames();
                    $terms[$obj_name][$language_code] = $translation;
                    $wpdb->update($wpdb->options,array('option_value' => serialize($terms)),array('option_name' => 'qtranslate_term_name'));
                break;
            case Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_TAG_NAME :
                $trem = self::retrieveByPK($object_id);
                $obj_name = $trem->name;
                $terms = self::getTermNames();
                $terms[$obj_name][$language_code] = $translation;
                $wpdb->update($wpdb->options,array('option_value' => serialize($terms)),array('option_name' => 'qtranslate_term_name'));
                break;
            default:
                break;
        }
    }
    
}

?>
