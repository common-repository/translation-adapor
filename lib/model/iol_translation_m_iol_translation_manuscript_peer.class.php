<?php

class Iol_Translation_M_Iol_Translation_Manuscript_Peer extends Iol_Translation_M_Peer_Base {

    public static function create($iol_translation_order_id, $object_local_orginal_language_code, $iol_translation_type_id, $iol_translation_sub_type_id, $object_id, $manuscript_number, $source_language_code, $target_language_code, $manuscript_word_count, $manuscript_price, $orginal, $user_param) {
        global $wpdb;
        $wpdb->insert(
                        $wpdb->iol_translation_manuscript,
                        array('iol_translation_order_id' =>$iol_translation_order_id , 
                            'object_local_orginal_language_code' => $object_local_orginal_language_code,
                            'iol_translation_type_id' => $iol_translation_type_id,
                            'iol_translation_sub_type_id' => $iol_translation_sub_type_id,
                            'object_id' => $object_id,
                            'manuscript_number' => $manuscript_number,
                            'source_language_code' => $source_language_code,
                            'target_language_code' => $target_language_code,
                            'word_count' => $manuscript_word_count,
                            'price' => $manuscript_price,
                            'orginal' => addslashes($orginal),
                            'user_param' => addslashes($user_param),
                            'created_at' =>time() ,
                            'updated_at' => time()
                            ) 
                );

    }
    
    public static function retrieveTypeInfoByManuscriptNumber($manuscript_number) {
        global $wpdb;
        $data = null;
        $vars = $wpdb->get_results($wpdb->prepare("select ma.manuscript_number, ma.iol_translation_type_id, 
                                     ma.iol_translation_sub_type_id, ma.translations, 
                                     ma.object_id, ma.target_language_code
                              from " . $wpdb->iol_translation_manuscript . " ma   where  
                               ma.manuscript_number = %s", strval($manuscript_number)));
        
        if (isset($vars[0])) {
            $data = $vars[0];
        }
        return $data;
        //  { 
        //  ["manuscript_number"]=> string(18) "201402172109531891" 
        //  ["iol_translation_type_id"]=> string(1) "2" 
        //  ["iol_translation_sub_type_id"]=> string(1) "6" 
        //  ["translations"]=> NULL 
        //  ["object_id"]=> string(1) "1" 
        //  ["target_language_code"]=> string(2) "zh" 
        //  ["name"]=> string(11) "description" 
        //  ["tb"]=> string(19) "coupons_description" 
        //  ["zencart_language_id"]=> string(1) "2" 
        //  } 
    }

    public static function updateByOrderReturn($manuscript_number, $manuscript_translations, $manuscript_user_param, $manuscript_finishTime) {
        // 回稿后，更新原object对应语言字段内容
        global $wpdb;
        $wpdb->update(
                $wpdb->iol_translation_manuscript, 
                array(
                    'manuscript_number' => addslashes($manuscript_number),
                    'translations' => addslashes(urldecode($manuscript_translations)),
                    'transed_at' => $manuscript_finishTime,
                ), 
                array('manuscript_number' => addslashes($manuscript_number),
                    'user_param' => addslashes($manuscript_user_param)
                )
        );
        
        $manuscript = self::retrieveTypeInfoByManuscriptNumber($manuscript_number);
        
        // 发送到对应type的更新function
        switch($manuscript->iol_translation_type_id){
            case Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS:
                Iol_Translation_M_Posts_Peer::processPostReturn($manuscript);
                break;
            case Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES:
                Iol_Translation_M_Posts_Peer::processPageReturn($manuscript);
                break;
            case Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES:
                iol_translation_m_terms_peer::processCategoriesOrTagReturn($manuscript);
                break;
            case Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS:
                iol_translation_m_terms_peer::processCategoriesOrTagReturn($manuscript);
                break;
        }
        
    }
    
    public static function fliedIsTranslation($id,$type_id,$language_code,$sub_type_id,$target_language_code){
        global $wpdb;
        $vars = $wpdb->get_results(
                $wpdb->prepare("select count(*) as count from $wpdb->iol_translation_manuscript where 
                    target_language_code = %s and 
                    iol_translation_type_id = %d and 
                    object_id = %d and 
                    iol_translation_sub_type_id = %d 
                    and object_local_orginal_language_code = %s", $target_language_code,$type_id,$id,$sub_type_id,$language_code));
        if (isset($vars[0])) {
            $data = $vars[0];
            if ($data->count > 0) {
                return true;
            }
            return false;
        }
        return false;
    }
    
}