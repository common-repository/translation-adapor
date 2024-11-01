<?php 
/*
 * 创建订单
 */

function iol_translation_all_submit(){
    // check user logout
    if (Iol_Translation_M_Iol_Translation_Configuration_Peer::checkUserIsLogout()) {
        $check_msg = Iol_Translation_U::__('Fail to inquiry') . ' ! ' . Iol_Translation_U::__('Please check your network') . ' , ' . Iol_Translation_U::__('or your account is valid') . ' !';
        echo json_encode(array('status' => 'fails', 'msg' => $check_msg));
        die;
    }
     if (isset($_REQUEST)) {
        // post , page
        if (isset($_REQUEST['post_type'])) {
            switch (strtolower($_REQUEST['post_type'])) {
                case 'post':
                    echo createAllOrder_post($_REQUEST);
                    break;
                case 'page':
                    echo createAllOrder_page($_REQUEST);
                    break;
                default:
                    break;
            }
        }
        // categories , tag
        if (isset($_REQUEST['taxonomy'])) {
            switch (strtolower($_REQUEST['taxonomy'])) {
                case 'post_tag':
                    echo createAllOrder_tag($_REQUEST);
                    break;
                case 'category':
                    echo createAllOrder_category($_REQUEST);
                    break;

                default:
                    break;
            }
        }
    }
    die();
    
    
    
    
}
add_action('wp_ajax_iol_translation_all_submit', 'iol_translation_all_submit');

//post
function createAllOrder_post(){
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $remarks = $_REQUEST['translation_requirement'];
    $obj_ids = clearEmptyArrayValue(explode(',', $_REQUEST['post_ids']));
    
    $ids = getAllTranslationedIds($obj_ids, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS);
    $translated_ids = $ids['translated_ids'];
    $not_translated_ids = $ids['not_translated_ids'];
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    //检查是否被翻译了 被翻译，查看翻译的字段,是否被翻译
    foreach ($translated_ids as $id) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        $content_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        $is_title_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_TITLE, $to_language); 
        $is_content_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_CONTENT, $to_language);
        if(isset($_REQUEST['title']) && !$is_title_translated && (trim($title_text) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($title_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_TITLE, $id);
        }
        if(isset($_REQUEST['comment']) && !$is_content_translated && (trim($content_text) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($content_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_CONTENT, $id);
        }
    }
    //没有翻译,继续添加
    foreach ($not_translated_ids as $id) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        $content_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        
        if (isset($_REQUEST['title']) && trim($title_text)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($title_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_TITLE, $id);
        }
        if (isset($_REQUEST['comment']) && trim($content_text)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($content_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_CONTENT, $id);
        }
    }
    $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
    $result = getAllCreateOrderReslut($res);
    return json_encode($result);
}

//page
function createAllOrder_page(){
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $remarks = $_REQUEST['translation_requirement'];
    $obj_ids = clearEmptyArrayValue(explode(',', $_REQUEST['post_ids']));
    
    $ids = getAllTranslationedIds($obj_ids, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES);
    $translated_ids = $ids['translated_ids'];
    $not_translated_ids = $ids['not_translated_ids'];
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    //检查是否被翻译了 被翻译，查看翻译的字段,是否被翻译
    foreach ($translated_ids as $id) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        $content_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        $is_title_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_TITLE, $to_language); 
        $is_content_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_CONTENT, $to_language);
        if(isset($_REQUEST['title']) && !$is_title_translated && (trim($title_text) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($title_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_TITLE, $id);
        }
        if(isset($_REQUEST['comment']) && !$is_content_translated && (trim($content_text) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($content_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_CONTENT, $id);
        }
    }
    //没有翻译,继续添加
    foreach ($not_translated_ids as $id) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        $content_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        
        if (isset($_REQUEST['title']) && trim($title_text)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($title_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_TITLE, $id);
        }
        if (isset($_REQUEST['comment']) && trim($content_text)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($content_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_CONTENT, $id);
        }
    }
    $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
    $result = getAllCreateOrderReslut($res);
    return json_encode($result);
}

//tag
function createAllOrder_tag(){
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $remarks = $_REQUEST['translation_requirement'];
    $obj_ids = clearEmptyArrayValue(explode(',', $_REQUEST['post_ids']));
    
    $ids = getAllTranslationedIds($obj_ids, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS);
    $translated_ids = $ids['translated_ids'];
    $not_translated_ids = $ids['not_translated_ids'];
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    //检查是否被翻译了 被翻译，查看翻译的字段,是否被翻译
    foreach ($translated_ids as $id) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        $is_name_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_TAG_NAME, $to_language);
        if(isset($_REQUEST['name']) && !$is_name_translated && (trim($name) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($name, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_TAG_NAME, $id);
        }
    }
    //没有翻译,继续添加
    foreach ($not_translated_ids as $id) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        if (isset($_REQUEST['name']) && trim($name)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($name, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_TAG_NAME, $id);
        }
    }
    $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
    $result = getAllCreateOrderReslut($res);
    return json_encode($result);
}

// category
function createAllOrder_category(){
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $remarks = $_REQUEST['translation_requirement'];
    $obj_ids = clearEmptyArrayValue(explode(',', $_REQUEST['post_ids']));
    
    $ids = getAllTranslationedIds($obj_ids, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES);
    $translated_ids = $ids['translated_ids'];
    $not_translated_ids = $ids['not_translated_ids'];
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    //检查是否被翻译了 被翻译，查看翻译的字段,是否被翻译
    foreach ($translated_ids as $id) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        $is_name_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $to_language);
        if(isset($_REQUEST['name']) && !$is_name_translated && (trim($name) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($name, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $id);
        }
    }
    //没有翻译,继续添加
    foreach ($not_translated_ids as $id) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        if (isset($_REQUEST['name']) && trim($name)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($name, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $id);
        }
    }
    $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
    $result = getAllCreateOrderReslut($res);
    return json_encode($result);
}


function getAllCreateOrderReslut($res) {
    if ($res['status']) {
        $msg = Iol_Translation_U::__('The order has been submitted');
        return array('status' => 'ok', 'msg' => $msg,'pay_url'=>$res['pay_url']);
    } else {
        $msg = Iol_Translation_U::__('Submit fails');
        return array('status' => 'false', 'msg' => $msg);
    }
}

/*
 * 查询
 */
function iol_translation_all_inquiry() {
    // check user logout
    if (Iol_Translation_M_Iol_Translation_Configuration_Peer::checkUserIsLogout()) {
        $check_msg = Iol_Translation_U::__('Fail to inquiry') . ' ! ' . Iol_Translation_U::__('Please check your network') . ' , ' . Iol_Translation_U::__('or your account is valid') . ' !';
        echo json_encode(array('status' => 'fails', 'msg' => $check_msg));
        die;
    }
    
    if (isset($_REQUEST)) {
        // post , page
        if (isset($_REQUEST['post_type'])) {
            switch (strtolower($_REQUEST['post_type'])) {
                case 'post':
                    echo doAllInquiry_Post($_REQUEST);
                    break;
                case 'page':
                    echo doAllInquiry_Page($_REQUEST);
                    break;
                default:
                    break;
            }
        }
        // categories , tag
        if (isset($_REQUEST['taxonomy'])) {
            switch (strtolower($_REQUEST['taxonomy'])) {
                case 'post_tag':
                    echo doAllInquiry_Tag($_REQUEST);
                    break;
                case 'category':
                    echo doAllInquiry_Category($_REQUEST);
                    break;
                default:
                    break;
            }
        }
    }
    die();
}
add_action('wp_ajax_iol_translation_all_inquiry', 'iol_translation_all_inquiry');

// post
function doAllInquiry_Post(){
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    
    $attr_total = 0;
    $obj_ids = clearEmptyArrayValue(explode(',', $_REQUEST['post_ids']));
    $item_total = count($obj_ids);
    if(isset($_REQUEST['title'])){
        $attr_total += $item_total;
    }
    if(isset($_REQUEST['comment'])){
        $attr_total += $item_total;
    }
    $attr_trans = 0;

    $ids = getAllTranslationedIds($obj_ids, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS);
    $translated_ids = $ids['translated_ids'];
    $not_translated_ids = $ids['not_translated_ids'];
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    //检查是否被翻译了 被翻译，查看翻译的字段,是否被翻译
    foreach ($translated_ids as $id) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        $comment_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        $is_title_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_TITLE, $to_language); 
        $is_content_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_CONTENT, $to_language);
        if(isset($_REQUEST['title']) && !$is_title_translated && (trim($title_text) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($title_text, $from_language, $to_language);
           $attr_trans++;
        }
        if(isset($_REQUEST['comment']) && !$is_content_translated && (trim($comment_text) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($comment_text, $from_language, $to_language);
           $attr_trans++;
        }
    }
    //没有翻译,继续添加
    foreach ($not_translated_ids as $id) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        $comment_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        
        if (isset($_REQUEST['title']) && trim($title_text)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($title_text, $from_language,$to_language);
            $attr_trans++;
        }
        if (isset($_REQUEST['comment']) && trim($comment_text)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($comment_text, $from_language,$to_language);
            $attr_trans++;
        }
    }
   
    if($attr_trans == 0){
        $fails_msg = Iol_Translation_U::__('It already exists in the orders').' !';
        return json_encode(array('status'=>'fails','msg'=>$fails_msg,'show_info'=>''));
    }else{
        $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
        $show_info = Iol_Translation_U::__('Found') .' '. $attr_total .' '.
                  Iol_Translation_U::__("attribute(s) in ") . ' '.$item_total .' '.
                Iol_Translation_U::__('item(s)'). " , " . $attr_trans .' '.
               Iol_Translation_U::__('attribute(s) need to be translated');
        $succ_msg = getAllInquiryReslut($res);
        return json_encode(array('status' => 'success', 'msg' => $succ_msg, 'show_info' => $show_info));
    }
}

//page
function doAllInquiry_Page(){
     $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    
    $attr_total = 0;
    $obj_ids = clearEmptyArrayValue(explode(',', $_REQUEST['post_ids']));
    $item_total = count($obj_ids);
    if(isset($_REQUEST['title'])){
        $attr_total += $item_total;
    }
    if(isset($_REQUEST['comment'])){
        $attr_total += $item_total;
    }
    $attr_trans = 0;
    
    $ids = getAllTranslationedIds($obj_ids, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES);
    $translated_ids = $ids['translated_ids'];
    $not_translated_ids = $ids['not_translated_ids'];
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    //检查是否被翻译了 被翻译，查看翻译的字段,是否被翻译
    foreach ($translated_ids as $id) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        $comment_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        $is_title_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_TITLE, $to_language); 
        $is_content_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_CONTENT, $to_language);
        if(isset($_REQUEST['title']) && !$is_title_translated && (trim($title_text) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($title_text, $from_language, $to_language);
           $attr_trans++;
        }
        if(isset($_REQUEST['comment']) && !$is_content_translated && (trim($comment_text) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($comment_text, $from_language, $to_language);
           $attr_trans++;
        }
    }
    //没有翻译,继续添加
    foreach ($not_translated_ids as $id) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        $comment_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        
        if (isset($_REQUEST['title']) && trim($title_text)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($title_text, $from_language,$to_language);
            $attr_trans++;
        }
        if (isset($_REQUEST['comment']) && trim($comment_text)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($comment_text, $from_language,$to_language);
            $attr_trans++;
        }
    }
    
    if($attr_trans == 0){
        $fails_msg = Iol_Translation_U::__('It already exists in the orders').' !';
        return json_encode(array('status'=>'fails','msg'=>$fails_msg,'show_info'=>''));
    }else{
        $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
        $show_info = Iol_Translation_U::__('Found') .' '. $attr_total .' '.
                  Iol_Translation_U::__("attribute(s) in ") . ' '.$item_total .' '.
                Iol_Translation_U::__('page(s)'). " , " . $attr_trans .' '.
               Iol_Translation_U::__('attribute(s) need to be translated');
        $succ_msg = getAllInquiryReslut($res);
        return json_encode(array('status' => 'success', 'msg' => $succ_msg, 'show_info' => $show_info));
    }
}

// tag
function doAllInquiry_Tag(){
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    
    $attr_total = 0;
    $obj_ids = clearEmptyArrayValue(explode(',', $_REQUEST['post_ids']));
    $item_total = count($obj_ids);
    if(isset($_REQUEST['name'])){
        $attr_total += $item_total;
    }
    $attr_trans = 0;
    
    $ids = getAllTranslationedIds($obj_ids, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS);
    $translated_ids = $ids['translated_ids'];
    $not_translated_ids = $ids['not_translated_ids'];
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    //检查是否被翻译了 被翻译，查看翻译的字段,是否被翻译
    foreach ($translated_ids as $id) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        $is_name_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_TAG_NAME, $to_language);
        if(isset($_REQUEST['name']) && !$is_name_translated && (trim($name) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($name, $from_language, $to_language);
           $attr_trans++;
        }
    }
    //没有翻译,继续添加
    foreach ($not_translated_ids as $id) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        if (isset($_REQUEST['name']) && trim($name)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($name, $from_language,$to_language);
            $attr_trans++;
        }
    }
    
    if($attr_trans == 0){
        $fails_msg = Iol_Translation_U::__('It already exists in the orders').' !';
        return json_encode(array('status'=>'fails','msg'=>$fails_msg,'show_info'=>''));
    }else{
        $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
        $show_info = Iol_Translation_U::__('Found') .' '. $attr_total .' '.
                  Iol_Translation_U::__("attribute(s) in ") . ' '.$item_total .' '.
                Iol_Translation_U::__('tag(s)'). " , " . $attr_trans .' '.
               Iol_Translation_U::__('attribute(s) need to be translated');
        $succ_msg = getAllInquiryReslut($res);
        return json_encode(array('status' => 'success', 'msg' => $succ_msg, 'show_info' => $show_info));
    }
}

// category
function doAllInquiry_Category(){
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    
    $attr_total = 0;
    $obj_ids = clearEmptyArrayValue(explode(',', $_REQUEST['post_ids']));
    $item_total = count($obj_ids);
    if(isset($_REQUEST['name'])){
        $attr_total += $item_total;
    }
    $attr_trans = 0;
    
    $ids = getAllTranslationedIds($obj_ids, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES);
    $translated_ids = $ids['translated_ids'];
    $not_translated_ids = $ids['not_translated_ids'];
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    //检查是否被翻译了 被翻译，查看翻译的字段,是否被翻译
    foreach ($translated_ids as $id) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        $is_name_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $to_language);
        if(isset($_REQUEST['name']) && !$is_name_translated && (trim($name) != '')){
           $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($name, $from_language, $to_language);
           $attr_trans++;
        }
    }
    //没有翻译,继续添加
    foreach ($not_translated_ids as $id) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        if (isset($_REQUEST['name']) && trim($name)!='') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($name, $from_language,$to_language);
            $attr_trans++;
        }
    }
    
    if($attr_trans == 0){
        $fails_msg = Iol_Translation_U::__('It already exists in the orders').' !';
        return json_encode(array('status'=>'fails','msg'=>$fails_msg,'show_info'=>''));
    }else{
        $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
        $show_info = Iol_Translation_U::__('Found') .' '. $attr_total .' '.
                  Iol_Translation_U::__("attribute(s) in ") . ' '.$item_total .' '.
                Iol_Translation_U::__('category(s)'). " , " . $attr_trans .' '.
               Iol_Translation_U::__('attribute(s) need to be translated');
        $succ_msg = getAllInquiryReslut($res);
        return json_encode(array('status' => 'success', 'msg' => $succ_msg, 'show_info' => $show_info));
    }
}


function getAllInquiryReslut($res) {
    if ($res['status']) {
        $result = $res['result'];
        if ($result['status'] == 'ok') {
            return Iol_Translation_U::__('Contain') . ' ' . $result['wordcount'] . ' ' . Iol_Translation_U::__('words to translate') . ' ' . Iol_Translation_U::__('Translation costs') . ' ' . $result['price'];
        } else {
            return Iol_Translation_U::__('Inquiry false');
        }
    } else {
        return Iol_Translation_U::__('Inquiry false');
    }
}


function getAllTranslationedIds($obj_ids,$type_id){
    $translated_ids = array();
    $not_translated_ids = array();
    foreach ($obj_ids as $id) {
        $is_translation = Iol_Translation_U::isTranslation($id, $type_id);
        if ($is_translation) {
            $translated_ids[] = $id;
        } else {
            $not_translated_ids[] = $id;
        }
    }
    return array('translated_ids' => $translated_ids, 'not_translated_ids' => $not_translated_ids);
}

function clearEmptyArrayValue($array){
    $tmp = array();
    foreach ($array as $_tmp) {
        if(trim($_tmp) != ''){
            $tmp[] = $_tmp;
        }
    }
    return $tmp;
}



?>
