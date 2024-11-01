<?php

class Iol_Translation_U {

    /**
     * Get lanaguage string
     * 
     * @uses IolTranslationUtils::__()
     * @param type $string
     */
    public static function __($in, $vars = array()) {
        // wp原生指定定义域多语言
        $result = __($in, IOL_TRANSLATION_TEXTDOMAIN);

        // 替换变量，如果存在
        // $var= array('%name%=> 'a', '%number%' => 3);
        if (count($vars) > 0) {
            $var_keys = array_keys($vars);
            $var_values = array_values($vars);
            $result = str_replace($var_keys, $var_values, $result);
        }

        return $result;
    }

    public static function getSessionMessages($mode = true) {
        $html = ''; 
        if (isset($_SESSION['iol8_translation_session'])) {
            $tmp_session = $_SESSION['iol8_translation_session'];
            self::setSessionMessagesNull();
            if ($tmp_session) {
                if ($mode) {
                    if ($tmp_session['type'] == 'success') {
                        $html = "<div class='updated'><p>" . $tmp_session['msg'] . "</p></div>";
                    } else {
                        $html = "<div class='error'><p>" . $tmp_session['msg'] . "</p></div>";
                    }
                } else {
                    if ($tmp_session['type'] == 'success') {
                        $html = "<div class='updated'><p>" . $tmp_session['msg'] . "</p></div>";
                    } else {
                        $html = "<div class='error'><p>" . $tmp_session['msg'] . "</p></div>";
                    }
                }
            }
            return $html;
        }
    }

    public static function setSessionMessages($val) {
        $_SESSION['iol8_translation_session'] = $val;
    }

    public static function setSessionMessagesNull() {
        $_SESSION['iol8_translation_session'] = '';
    }

    /*
     * 产生一个唯一的字符串
     * 
     */

    public static function UUIDv4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                        // 32 bits for "time_low"
                        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                        // 16 bits for "time_mid"
                        mt_rand(0, 0xffff),
                        // 16 bits for "time_hi_and_version",
                        // four most significant bits holds version number 4
                        mt_rand(0, 0x0fff) | 0x4000,
                        // 16 bits, 8 bits for "clk_seq_hi_res",
                        // 8 bits for "clk_seq_low",
                        // two most significant bits holds zero and one for variant DCE1.1
                        mt_rand(0, 0x3fff) | 0x8000,
                        // 48 bits for "node"
                        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function loadAction($action) {
        include IOL_TRANSLATION_DIR . DIRECTORY_SEPARATOR . 'action' . DIRECTORY_SEPARATOR . $action . '.php';
    }

    public static function getWhichModelTranslated($type_id) {
        switch ($type_id) {
            case Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS:
                return 'post';
                break;
            case Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES:
                return 'page';
                break;
            case Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES:
                return 'categorie';
                break;
            case Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS:
                return 'tag';
                break;
            default:
                break;
        }
    }
    
    public static function hasSubCategories($parent_id) {
        global $wpdb;
        $data = null;
        $vars = $wpdb->get_results($wpdb->prepare("select count(*) as count from $wpdb->term_taxonomy where parent = %d", $parent_id));
        if (isset($vars[0])) {
            $data = $vars[0];
            if($data->count > 0){
                return true;
            }else{
               return false; 
            }
        }
        return false; 
    }
    
    
    public static function getSubCategoriesIds($parent_id){
         global $wpdb;
         $term_taxonomy = $wpdb->get_results($wpdb->prepare("select * from $wpdb->term_taxonomy where term_id = %d", $parent_id));
         $tmp = $term_taxonomy[0];
         $term_taxonomy_id = $tmp->term_taxonomy_id;
         
         if(self::hasSubCategories($term_taxonomy_id)){
             $categories = $wpdb->get_results($wpdb->prepare("select * from $wpdb->term_taxonomy where parent = %d", $term_taxonomy_id));
             foreach ($categories as $categorie) {
                 self::getSubCategoriesIds($categorie->term_taxonomy_id);
                 $_SESSION['iol_wordpress_category_ids'][] = $categorie->term_id;
             }
             
         }
         
    }
    
    
   public static function isTranslation($id, $type_id){
        global $wpdb;
        $data = null;
        $vars = $wpdb->get_results($wpdb->prepare("select count(*) as count from $wpdb->iol_translation_manuscript where  
            iol_translation_type_id = %d and object_id = %d", $type_id,$id));
        if (isset($vars[0])) {
            $data = $vars[0];
            if($data->count > 0){
                return true;
            }else{
               return false; 
            }
        }
        return false; 
    }
    

}