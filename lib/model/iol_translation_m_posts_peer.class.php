<?php

class Iol_Translation_M_Posts_Peer extends Iol_Translation_M_Peer_Base {

    public static function retrieveByPK($id) {
        global $wpdb;
        $data = null;
        $vars = $wpdb->get_results($wpdb->prepare("select * from $wpdb->posts where ID = %d", $id));
        if (isset($vars[0])) {
            $data = $vars[0];
        }
        return $data;
    }
    
    public static function getPostTitleByCurrentLanguage($id,$iol_language_code){
        $obj = self::retrieveByPK($id);
        $title = $obj->post_title;
        $title_array = Iol_Translation_Qtranslate_Helper::textSplit($title);
        $current_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getQtranslationLanguageCode($iol_language_code);
        return $title_array[$current_language_code];
    }
    
    public static function getPostCommentByCurrentLanguage($id,$iol_language_code){
        $obj = self::retrieveByPK($id);
        $comment = $obj->post_content;
        $comment_array = Iol_Translation_Qtranslate_Helper::textSplit($comment);
        $current_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getQtranslationLanguageCode($iol_language_code);
        return $comment_array[$current_language_code];
    }
    
     public static function isNew($id){
        $obj = self::retrieveByPK($id);
        if($obj->post_status == 'auto-draft'){
            return true;
        }else{
            return false;
        }
    }
    
    
    public static function processPostReturn($manuscript) {
        $translation = $manuscript->translations;
        $language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getQtranslationLanguageCode($manuscript->target_language_code);
        $object_id = $manuscript->object_id;
        global $wpdb;
        $obj = self::retrieveByPK($object_id);
        switch ($manuscript->iol_translation_sub_type_id) {
            case Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_TITLE :		
                $title = $obj->post_title;
                $title_array = Iol_Translation_Qtranslate_Helper::textSplit($title);
                $title_array[$language_code] = $translation;			
                $text = Iol_Translation_Qtranslate_Helper::textJoin($title_array);
                $wpdb->update($wpdb->posts, array('post_title' => addslashes($text)), array('ID' => $object_id));
                break;
            case Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_CONTENT :
                $comment = $obj->post_content;
                $comment_array = Iol_Translation_Qtranslate_Helper::textSplit($comment);
                $comment_array[$language_code] = $translation;
                $text = Iol_Translation_Qtranslate_Helper::textJoin($comment_array);
                $wpdb->update($wpdb->posts, array('post_content' => addslashes($text)), array('ID' => $object_id));
                break;
            default:
                break;
        }
    }
    public static function processPageReturn($manuscript) {
        $translation = $manuscript->translations;
        $language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getQtranslationLanguageCode($manuscript->target_language_code);
        $object_id = $manuscript->object_id;
        global $wpdb;
        $obj = self::retrieveByPK($object_id);
        switch ($manuscript->iol_translation_sub_type_id) {
            case Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_TITLE :
                $title = $obj->post_title;
                $title_array = Iol_Translation_Qtranslate_Helper::textSplit($title);
                $title_array[$language_code] = $translation;
                $text = Iol_Translation_Qtranslate_Helper::textJoin($title_array);
                $wpdb->update($wpdb->posts, array('post_title' => addslashes($text)), array('ID' => $object_id));
                break;
            case Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_CONTENT :
                $comment = $obj->post_content;
                $comment_array = Iol_Translation_Qtranslate_Helper::textSplit($comment);
                $comment_array[$language_code] = $translation;
                $text = Iol_Translation_Qtranslate_Helper::textJoin($comment_array);
                $wpdb->update($wpdb->posts, array('post_content' => addslashes($text)), array('ID' => $object_id));
                break;
            default:
                break;
        }
    }
    
}