<?php

/*
 * 创建订单
 */

function iol_translation_submit() {
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
                    echo createOrder_post($_REQUEST);
                    break;
                case 'page':
                    echo createOrder_page($_REQUEST);
                    break;
                default:
                    break;
            }
        }
        // categories , tag
        if (isset($_REQUEST['taxonomy'])) {
            switch (strtolower($_REQUEST['taxonomy'])) {
                case 'post_tag':
                    echo createOrder_tag($_REQUEST);
                    break;
                case 'category':
                    echo createOrder_category($_REQUEST);
                    break;

                default:
                    break;
            }
        }
    }
    die();
}

add_action('wp_ajax_iol_translation_submit', 'iol_translation_submit');

//post
function createOrder_post() {
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $id = $_REQUEST['post_id'];
    $remarks = $_REQUEST['translation_requirement'];
    //是否被翻译过
    $tmp = array();
//    $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
    if (isset($_REQUEST['title'])) {
        $is_title_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_TITLE, $to_language);
        if ($is_title_translated) {
            $tmp[] = 'Title';
        }
    }
    if (isset($_REQUEST['comment'])) {
        $is_content_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_CONTENT, $to_language);
        if ($is_content_translated) {
            $tmp[] = 'Content';
        }
    }
    if (count($tmp) > 0) {
        $msg = Iol_Translation_U::__('items') . ' id of("' . $id . '") ' . implode(',', $tmp) . ' ' . Iol_Translation_U::__('have been transalted');
        return json_encode(array('status' => 'false', 'msg' => $msg));
    }
    // 创建order
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    if (isset($_REQUEST['title'])) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        if (trim($title_text) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($title_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_TITLE, $id);
        }
    }

    if (isset($_REQUEST['comment'])) {
        $content_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        if (trim($content_text) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($content_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_CONTENT, $id);
        }
    }
    $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
    $result = getCreateOrderReslut($res);
    return json_encode($result);
}

//page
function createOrder_page() {
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $id = $_REQUEST['post_id'];
    $remarks = $_REQUEST['translation_requirement'];

    //是否被翻译过
    $tmp = array();
//    $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
    if (isset($_REQUEST['title'])) {
        $is_title_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_TITLE, $to_language);
        if ($is_title_translated) {
            $tmp[] = 'Title';
        }
    }
    if (isset($_REQUEST['comment'])) {
        $is_content_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_CONTENT, $to_language);
        if ($is_content_translated) {
            $tmp[] = 'Content';
        }
    }
    if (count($tmp) > 0) {
        $msg = Iol_Translation_U::__('items') . ' id of("' . $id . '") ' . implode(',', $tmp) . ' ' . Iol_Translation_U::__('have been transalted');
        return json_encode(array('status' => 'false', 'msg' => $msg));
    }
    // 创建order
    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());

    if (isset($_REQUEST['title'])) {
        $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
        if (trim($title_text) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($title_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_TITLE, $id);
        }
    }

    if (isset($_REQUEST['comment'])) {
        $content_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
        if (trim($content_text) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($content_text, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_CONTENT, $id);
        }
    }
    $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
    $result = getCreateOrderReslut($res);
    return json_encode($result);
}

// tag
function createOrder_tag() {
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $id = $_REQUEST['tag_id'];
    $remarks = $_REQUEST['translation_requirement'];

    //是否被翻译过
    $tmp = array();
//    $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
    if (isset($_REQUEST['name'])) {
        $is_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_TAG_NAME, $to_language);
        if ($is_translated) {
            $tmp[] = 'Name';
        }
    }
    if (count($tmp) > 0) {
        $msg = Iol_Translation_U::__('items') . ' id of("' . $id . '") ' . implode(',', $tmp) . ' ' . Iol_Translation_U::__('have been transalted');
        return json_encode(array('status' => 'false', 'msg' => $msg));
    }

    $manuscripts = array();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());

    if (isset($_REQUEST['name'])) {
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
        if (trim($name) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($name, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_TAG_NAME, $id);
        }
    }
    $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
    $result = getCreateOrderReslut($res);
    return json_encode($result);
}

//category
function createOrder_category() {

    if (isset($_REQUEST['check_all_category']) && $_REQUEST['check_all_category']!='') {
        return proessCreateCategoriesSub($_REQUEST);
    } else {
        $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
        $from_language = $_REQUEST['from_language'];
        $to_language = $_REQUEST['to_language'];
        $id = $_REQUEST['tag_id'];
        $remarks = $_REQUEST['translation_requirement'];

        //是否被翻译过
        $tmp = array();
//        $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
        if (isset($_REQUEST['name'])) {
            $is_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $to_language);
            if ($is_translated) {
                $tmp[] = 'Name';
            }
        }
        if (count($tmp) > 0) {
            $msg = Iol_Translation_U::__('items') . ' id of("' . $id . '") ' . implode(',', $tmp) . ' ' . Iol_Translation_U::__('have been transalted');
            return json_encode(array('status' => 'false', 'msg' => $msg));
        }

        $manuscripts = array();
        $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());

        if (isset($_REQUEST['name'])) {
            $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
            if (trim($name) != '') {
                $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($name, $from_language, $to_language, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $id);
            }
        }
        $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
        $result = getCreateOrderReslut($res);
        return json_encode($result);
    }
}

function proessCreateCategoriesSub(){
    $_SESSION['iol_wordpress_category_ids'] = array();
    Iol_Translation_U::getSubCategoriesIds($_REQUEST['tag_id']);
    $sub_categories_ids = $_SESSION['iol_wordpress_category_ids'];
    $sub_categories_ids[] = $_REQUEST['tag_id'];
    $to_language = $_REQUEST['to_language'];
    
    $manuscripts = _getCreateCategoriesSubManuscripts($sub_categories_ids, $_REQUEST);
    $remarks = $_REQUEST['translation_requirement'];
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    $res = $iol_api_helper->doCreateorder($translate_level, $remarks, $manuscripts,$to_language);
    $result = getCreateOrderReslut($res);
    return json_encode($result);
}

function _getCreateCategoriesSubManuscripts($parent_ids){
    $manuscripts = array();
    $to_language = $_REQUEST['to_language'];
    $from_language = $_REQUEST['from_language'];
//    $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    foreach ($parent_ids as $parent_id) {
        if (isset($_REQUEST['name'])) {
            $is_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($parent_id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $_REQUEST['to_language']);
        }
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($parent_id,$from_language);
        if (isset($_REQUEST['name']) && !$is_translated && (trim($name) != '')) {
            $manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($name, $_REQUEST['from_language'], $_REQUEST['to_language'], Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $parent_id);
        }
    }
    return $manuscripts;
}

/*
 * 查询
 */

function iol_translation_inquiry() {
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
                    echo doInquiry_Post($_REQUEST);
                    break;
                case 'page':
                    echo doInquiry_Page($_REQUEST);
                    break;
                default:
                    break;
            }
        }
        // categories , tag
        if (isset($_REQUEST['taxonomy'])) {
            switch (strtolower($_REQUEST['taxonomy'])) {
                case 'post_tag':
                    echo doInquiry_Tag($_REQUEST);
                    break;
                case 'category':
                    echo doInquiry_Category($_REQUEST);
                    break;
                default:
                    break;
            }
        }
    }
    die();
}

add_action('wp_ajax_iol_translation_inquiry', 'iol_translation_inquiry');

// post 
function doInquiry_Post() {
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $id = $_REQUEST['post_id'];
    $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
    $comment_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
//    $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
    //是否为空
    $is_empty = 0;
    $select_fileds = 0;
    if (isset($_REQUEST['title'])) {
        $select_fileds++;
        if ((strlen($title_text) <= 0) || is_null($title_text) || $title_text == '') {
            $is_empty++;
        }
    }
    if (isset($_REQUEST['comment'])) {
        $select_fileds++;
        if ((strlen($comment_text) <= 0) || is_null($comment_text) || $comment_text == '') {
            $is_empty++;
        }
    }
    if ($is_empty == $select_fileds) {
        $msg = Iol_Translation_U::__('Your selection is empty') . ' , ' . Iol_Translation_U::__('this order was invalid') . ' ! ' . Iol_Translation_U::__('Please select again') . ' !';
        return json_encode(array('status' => 'fails', 'msg' => $msg));
    }
    //是否被翻译过
    $tmp = array();
    if (isset($_REQUEST['title'])) {
        $is_title_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_TITLE, $to_language);
        if ($is_title_translated) {
            $tmp[] = 'Title';
        }
    }
    if (isset($_REQUEST['comment'])) {
        $is_content_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_POSTS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_POST_CONTENT, $to_language);
        if ($is_content_translated) {
            $tmp[] = 'Content';
        }
    }
    if (count($tmp) > 0) {
        $msg = implode(',', $tmp) . ' ' . Iol_Translation_U::__('It already exists in the orders') . ' !';
        return json_encode(array('status' => 'fails', 'msg' => $msg));
    }

    //查询
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    $manuscripts = array();
    if (isset($_REQUEST['title'])) {
        if (trim($title_text) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($title_text, $from_language, $to_language);
        }
    }
    if (isset($_REQUEST['comment'])) {
        if (trim($comment_text) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($comment_text, $from_language, $to_language);
        }
    }
    $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
    $succ_msg = getInquiryReslut($res);
    return json_encode(array('status' => 'success', 'msg' => $succ_msg));
}

// page
function doInquiry_Page() {
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $id = $_REQUEST['post_id'];
    $title_text = Iol_Translation_M_Posts_Peer::getPostTitleByCurrentLanguage($id,$from_language);
    $comment_text = Iol_Translation_M_Posts_Peer::getPostCommentByCurrentLanguage($id,$from_language);
//    $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
    //是否为空
    $is_empty = 0;
    $select_fileds = 0;
    if (isset($_REQUEST['title'])) {
        $select_fileds++;
        if ((strlen($title_text) <= 0) || is_null($title_text) || $title_text == '') {
            $is_empty++;
        }
    }
    if (isset($_REQUEST['comment'])) {
        $select_fileds++;
        if ((strlen($comment_text) <= 0) || is_null($comment_text) || $comment_text == '') {
            $is_empty++;
        }
    }
    if ($is_empty == $select_fileds) {
        $msg = Iol_Translation_U::__('Your selection is empty') . ' , ' . Iol_Translation_U::__('this order was invalid') . ' ! ' . Iol_Translation_U::__('Please select again') . ' !';
        return json_encode(array('status' => 'fails', 'msg' => $msg));
    }
    //是否被翻译过
    $tmp = array();
    if (isset($_REQUEST['title'])) {
        $is_title_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_TITLE, $to_language);
        if ($is_title_translated) {
            $tmp[] = 'Title';
        }
    }
    if (isset($_REQUEST['comment'])) {
        $is_content_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_PAGES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_PAGE_CONTENT, $to_language);
        if ($is_content_translated) {
            $tmp[] = 'Content';
        }
    }
    if (count($tmp) > 0) {
        $msg = implode(',', $tmp) . ' ' . Iol_Translation_U::__('It already exists in the orders') . ' !';
        return json_encode(array('status' => 'fails', 'msg' => $msg));
    }

    //查询
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    $manuscripts = array();
    if (isset($_REQUEST['title'])) {
        if (trim($title_text) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($title_text, $from_language, $to_language);
        }
    }
    if (isset($_REQUEST['comment'])) {
        if (trim($comment_text) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($comment_text, $from_language, $to_language);
        }
    }
    $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
    $succ_msg = getInquiryReslut($res);
    return json_encode(array('status' => 'success', 'msg' => $succ_msg));
}

// tag
function doInquiry_Tag() {
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    $from_language = $_REQUEST['from_language'];
    $to_language = $_REQUEST['to_language'];
    $id = $_REQUEST['tag_id'];

    $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
//    $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
    //是否为空
    $is_empty = 0;
    $select_fileds = 0;
    if (isset($_REQUEST['name'])) {
        $select_fileds++;
        if ((strlen($name) <= 0) || is_null($name) || $name == '') {
            $is_empty++;
        }
    }
    if ($is_empty == $select_fileds) {
        $msg = Iol_Translation_U::__('Your selection is empty') . ' , ' . Iol_Translation_U::__('this order was invalid') . ' ! ' . Iol_Translation_U::__('Please select again') . ' !';
        return json_encode(array('status' => 'fails', 'msg' => $msg));
    }
    //是否被翻译过
    $tmp = array();
    if (isset($_REQUEST['name'])) {
        $is_name_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_TAGS, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_TAG_NAME, $to_language);
        if ($is_name_translated) {
            $tmp[] = 'Name';
        }
    }
    if (count($tmp) > 0) {
        $msg = implode(',', $tmp) . ' ' . Iol_Translation_U::__('It already exists in the orders') . ' !';
        return json_encode(array('status' => 'fails', 'msg' => $msg));
    }

    //查询
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    $manuscripts = array();
    if (isset($_REQUEST['name'])) {
        if (trim($name) != '') {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($name, $from_language, $to_language);
        }
    }
    $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
    $succ_msg = getInquiryReslut($res);
    return json_encode(array('status' => 'success', 'msg' => $succ_msg));
}

//category
function doInquiry_Category() {
    if (isset($_REQUEST['check_all_category']) && $_REQUEST['check_all_category']!='') {
        return proessCategoriesSub($_REQUEST);
    } else {
        $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
        $from_language = $_REQUEST['from_language'];
        $to_language = $_REQUEST['to_language'];
        $id = $_REQUEST['tag_id'];

        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($id,$from_language);
//        $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
        //是否为空
        $is_empty = 0;
        $select_fileds = 0;
        if (isset($_REQUEST['name'])) {
            $select_fileds++;
            if ((strlen($name) <= 0) || is_null($name) || $name == '') {
                $is_empty++;
            }
        }
        if ($is_empty == $select_fileds) {
            $msg = Iol_Translation_U::__('Your selection is empty') . ' , ' . Iol_Translation_U::__('this order was invalid') . ' ! ' . Iol_Translation_U::__('Please select again') . ' !';
            return json_encode(array('status' => 'fails', 'msg' => $msg));
        }
        //是否被翻译过
        $tmp = array();
        if (isset($_REQUEST['name'])) {
            $is_name_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $to_language);
            if ($is_name_translated) {
                $tmp[] = 'Name';
            }
        }
        if (count($tmp) > 0) {
            $msg = implode(',', $tmp) . ' ' . Iol_Translation_U::__('It already exists in the orders') . ' !';
            return json_encode(array('status' => 'fails', 'msg' => $msg));
        }

        //查询
        $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
        $manuscripts = array();
        if (isset($_REQUEST['name'])) {
            if (trim($name) != '') {
                $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($name, $from_language, $to_language);
            }
        }
        $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
        $succ_msg = getInquiryReslut($res);
        return json_encode(array('status' => 'success', 'msg' => $succ_msg));
    }
}

function getInquiryReslut($res) {
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

function getCreateOrderReslut($res) {
    if ($res['status']) {
        $msg = Iol_Translation_U::__('The order has been submitted');
        return array('status' => 'ok', 'msg' => $msg,'pay_url'=>$res['pay_url']);
    } else {
        $msg = Iol_Translation_U::__('Submit fails');
        return array('status' => 'false', 'msg' => $msg);
    }
}

function proessCategoriesSub() {
    $_SESSION['iol_wordpress_category_ids'] = array();
    Iol_Translation_U::getSubCategoriesIds($_REQUEST['tag_id']);
    $sub_categories_ids = $_SESSION['iol_wordpress_category_ids'];
    $sub_categories_ids[] = $_REQUEST['tag_id'];

    $tmp = _getCategoriesSubManuscripts($sub_categories_ids, $_REQUEST);
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    $manuscripts = $tmp['manuscripts'];
    $translation_info = $tmp['translation_info'];
    $translate_level = $_REQUEST['translate_level'] == Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL ? Iol_Translation_Api_Helper::SERVICE_LEVEL_PROFESSIONAL : Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD;
    
    if($translation_info['attr_trans'] == 0){
        $fails_msg = Iol_Translation_U::__('It already exists in the orders').' !';
        return json_encode(array('status'=>'fails','msg'=>$fails_msg,'show_info'=>''));
    }else{
        $res = $iol_api_helper->doInquiry($translate_level, $manuscripts);
        $show_info = Iol_Translation_U::__('Found') .' '. $translation_info['attr_total'] .' '.
                  Iol_Translation_U::__("attribute(s) in ") . ' '.$translation_info['item_total'] .' '.
                Iol_Translation_U::__('category(s)'). " , " . $translation_info['attr_trans'] .' '.
               Iol_Translation_U::__('attribute(s) need to be translated');
        $succ_msg = getInquiryReslut($res);
        return json_encode(array('status' => 'success', 'msg' => $succ_msg, 'show_info' => $show_info));
    }
}

function _getCategoriesSubManuscripts($parent_ids) {
    $to_language = $_REQUEST['to_language'];
    $from_language = $_REQUEST['from_language'];
    $attr_total = 0;
    $item_total = count($parent_ids);
    if (isset($_REQUEST['name'])) {
        $attr_total += $item_total;
    }
    $attr_trans = 0;
    $manuscripts = array();
//    $default_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode();
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
    foreach ($parent_ids as $parent_id) {
        if (isset($_REQUEST['name'])) {
            $is_translated = Iol_Translation_M_Iol_Translation_Manuscript_Peer::fliedIsTranslation($parent_id, Iol_Translation_M_Iol_Translation_Type_Peer::TYPE_CATEGORIES, $to_language, Iol_Translation_M_Iol_Translation_Sub_Type_Peer::SUB_TYPE_CATEGORIE_NAME, $_REQUEST['to_language']);
        }
        $name = iol_translation_m_terms_peer::getNameByCurrentLanguage($parent_id,$from_language);
        if (isset($_REQUEST['name']) && !$is_translated && (trim($name) != '')) {
            $manuscripts[] = $iol_api_helper->createManuscriptForInquiry($name, $_REQUEST['from_language'], $_REQUEST['to_language']);
            $attr_trans++;
        }
    }
    $translation_info = array('item_total' => $item_total, 'attr_trans' => $attr_trans, 'attr_total' => $attr_total);
    return array('manuscripts' => $manuscripts, 'translation_info' => $translation_info);
}

?>
