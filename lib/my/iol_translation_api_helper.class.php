<?php

class Iol_Translation_Api_Helper {

    const SERVICE_LEVEL_PROFESSIONAL = 1;
    const SERVICE_LEVEL_STANDARD = 2;

    // API地址，正式环境请换成正式地址
    private $api_url_base = 'http://apiqcm.iol8.com/gateway.do/';
    // 注册地址
    public static $api_url_reg = 'http://qcm.iol8.com/register';
    // 账户名
    private $useremail;
    // 密码
    private $password;
    // 是否debug
    private $is_debug = false;

    /**
     * 返回Array结构的service level给前端用
     * 
     * @return array
     */
    public static function buildServiceLevels() {
        return array(
            self::SERVICE_LEVEL_PROFESSIONAL => IOL_TRANSLATION_SERVICE_LEVEL_PROFESSIONAL,
            self::SERVICE_LEVEL_STANDARD => IOL_TRANSLATION_SERVICE_LEVEL_STANDARD,
        );
    }

    public static function getLanguages() {
        $language_array = array(
            'zh' => IOL_TRANSLATION_LANG_ZH,
            'en' => IOL_TRANSLATION_LANG_EN,
            'ja' => IOL_TRANSLATION_LANG_JA,
            'fr' => IOL_TRANSLATION_LANG_FR,
            'de' => IOL_TRANSLATION_LANG_DE,
            'ru' => IOL_TRANSLATION_LANG_RU,
            'ko' => IOL_TRANSLATION_LANG_KO,
            'zh_HK' => IOL_TRANSLATION_LANG_ZH_HK,
            'nl' => IOL_TRANSLATION_LANG_NL,
            'it' => IOL_TRANSLATION_LANG_IT,
            'es' => IOL_TRANSLATION_LANG_ES,
            'pt' => IOL_TRANSLATION_LANG_PT,
            'ar' => IOL_TRANSLATION_LANG_AR,
            'tr' => IOL_TRANSLATION_LANG_TR,
            'th' => IOL_TRANSLATION_LANG_TH,
            'uk' => IOL_TRANSLATION_LANG_UK,
            'da' => IOL_TRANSLATION_LANG_DA,
            'no' => IOL_TRANSLATION_LANG_NO,
            'fi' => IOL_TRANSLATION_LANG_FI,
            'el' => IOL_TRANSLATION_LANG_EL,
            'pl' => IOL_TRANSLATION_LANG_PL,
            'ro' => IOL_TRANSLATION_LANG_RO,
            'bg' => IOL_TRANSLATION_LANG_BG,
            'cs' => IOL_TRANSLATION_LANG_CS,
            'sk' => IOL_TRANSLATION_LANG_SK,
            'hu' => IOL_TRANSLATION_LANG_HU,
            'iw' => IOL_TRANSLATION_LANG_IW,
            'sv' => IOL_TRANSLATION_LANG_SV,
            'hr' => IOL_TRANSLATION_LANG_HR,
            'sq' => IOL_TRANSLATION_LANG_SQ,
            'zh_TW' => IOL_TRANSLATION_LANG_ZH_TW,
            'zh_HK' => IOL_TRANSLATION_LANG_ZH_HK,
            'ko-n' => IOL_TRANSLATION_LANG_KO_N,
            'pt_BR' => IOL_TRANSLATION_LANG_PT_BR,
        );
        natsort($language_array);
        return $language_array;
    }

    /**
     * iol平台语言列表
     *
     */
    private $languages = array(
        'zh' => IOL_TRANSLATION_LANG_ZH,
        'en' => IOL_TRANSLATION_LANG_EN,
        'ja' => IOL_TRANSLATION_LANG_JA,
        'fr' => IOL_TRANSLATION_LANG_FR,
        'de' => IOL_TRANSLATION_LANG_DE,
        'ru' => IOL_TRANSLATION_LANG_RU,
        'ko' => IOL_TRANSLATION_LANG_KO,
        'zh_HK' => IOL_TRANSLATION_LANG_ZH_HK,
        'nl' => IOL_TRANSLATION_LANG_NL,
        'it' => IOL_TRANSLATION_LANG_IT,
        'es' => IOL_TRANSLATION_LANG_ES,
        'pt' => IOL_TRANSLATION_LANG_PT,
        'ar' => IOL_TRANSLATION_LANG_AR,
        'tr' => IOL_TRANSLATION_LANG_TR,
        'th' => IOL_TRANSLATION_LANG_TH,
        'uk' => IOL_TRANSLATION_LANG_UK,
        'da' => IOL_TRANSLATION_LANG_DA,
        'no' => IOL_TRANSLATION_LANG_NO,
        'fi' => IOL_TRANSLATION_LANG_FI,
        'el' => IOL_TRANSLATION_LANG_EL,
        'pl' => IOL_TRANSLATION_LANG_PL,
        'ro' => IOL_TRANSLATION_LANG_RO,
        'bg' => IOL_TRANSLATION_LANG_BG,
        'cs' => IOL_TRANSLATION_LANG_CS,
        'sk' => IOL_TRANSLATION_LANG_SK,
        'hu' => IOL_TRANSLATION_LANG_HU,
        'iw' => IOL_TRANSLATION_LANG_IW,
        'sv' => IOL_TRANSLATION_LANG_SV,
        'hr' => IOL_TRANSLATION_LANG_HR,
        'sq' => IOL_TRANSLATION_LANG_SQ,
        'zh_TW' => IOL_TRANSLATION_LANG_ZH_TW,
        'zh_HK' => IOL_TRANSLATION_LANG_ZH_HK,
        'ko-n' => IOL_TRANSLATION_LANG_KO_N,
        'pt_BR' => IOL_TRANSLATION_LANG_PT_BR,
    );

    /**
     * API error code, 备用
     */
    private $error_codes = array(
        '1001' => '注册用户失败',
        '1002' => '验证用户失败',
        '1101' => '创建订单失败',
        '1102' => '参数中没有验证信息',
        '1103' => '用户不是验证用户',
        '2000' => '调用接口系统出错',
        '2001' => '调用接口不存在',
        '2002' => '调用接口json格式不正确',
        '2003' => '接口返回json出错',
        '2404' => '不是有效参数',
        '2401' => '此邮箱已被注册过',
        '2402' => '邮箱账户和密码不匹配',
        '2004' => '返回已完成稿件失败',
        '2005' => '稿件未完成或不存在',
        '2006' => '调用询价接口失败',
        '3001' => '无此项价格配置',
    );

    /**
     * 构造函数
     * 
     * @param type $useremail  账号名
     * @param type $password 密码
     * @param type $is_debug 是否显示debug输出
     */
    public function __construct($useremail, $password, $is_debug = false) {
        $this->useremail = $useremail;
        $this->password = $password;
        $this->is_debug = $is_debug;
    }

    /**
     * 底层基础post函数
     * 
     * @param string $api_type 调用的哪个API子类型
     * @param string $data 打包的json数据
     * @return string 返回json格式数据
     */
    private function _requestPost($api_type, $data) {
        $url = $this->api_url_base . $api_type;
        // 这里用urlecode是为了post本身的标准
        $post_string = 'parameter=' . urlencode(json_encode($data));
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        $response = curl_exec($ch);
        curl_close($ch);

//        var_dump($response);
        $result = json_decode($response, true);
        if ($this->is_debug) {
            echo '<h2>API - ' . $api_type . '</h2>';

            echo '<h3>URL</h3>';
            echo $url . '<br />';

            echo '<h3>Request</h3>';
            print_r(urldecode($post_string));

            echo '<h3>Response</h3>';
            print_r($result);

            echo '<h3>Raw Response</h3>';
            echo '<textarea style="width:100%;height:50px;">' . $response . '</textarea>';
        }

        return $result;
    }

    /**
     * 新建用户
     * 
     * @param string $useremail
     * @param string $password (md5以后password)
     * @return boolean
     */
    public function doCreateUser($useremail, $password) {
        $data = array('useremail' => $useremail, 'password' => $password);
        $result = $this->_requestPost('createuser', $data);
        if (is_array($result) && isset($result['status']) && $result['status'] == 'ok') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证用户
     * 
     * @return boolean
     */
    public function doCheckUser() {
        $data = array('useremail' => $this->useremail, 'password' => $this->password);
        $result = $this->_requestPost('checkuser', $data);
        if (is_array($result) && isset($result['status']) && $result['status'] == 'ok') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 询价
     * 
     * @param type $level 1-专业；2-标准
     * @param type $manuscripts
     * @return boolean
     */
    public function doInquiry($level, $manuscripts) {
        $data = array('useremail' => $this->useremail, 'password' => $this->password, 'level' => $level, 'manuscript' => $manuscripts);
        $result = $this->_requestPost('inquiry', $data);
        if (is_array($result) && isset($result['status']) && $result['status'] == 'ok') {
            return array('result' => $result, 'status' => true);
        } else {
            return array('status' => false);
        }
    }

    /**
     * 下单
     * 
     * @param type $level
     * @param type $remarks
     * @param type $manuscripts
     * @return boolean
     */
    public function doCreateorder($level, $remarks, $manuscripts,$to_language) {
        $url_base = get_admin_url();
        $requesturl = get_site_url();
        $returnparam = network_site_url('?iol_translation_api_callback_handler=1&type=order');
        $payreturnurl = network_site_url('?iol_translation_api_callback_handler=1&type=pay');

        $data = array('useremail' => $this->useremail,
            'password' => $this->password,
            'ordertype' => '3', //0-magento下单；1-网页下单；2-zencart下单, 3-wordpress下单 WP写死3就行
            'requesturl' => $requesturl, // 发出请求的域名，API纯统计用，不影响翻译流程
            'level' => $level, // 翻译等级
            'returnparam' => $returnparam, // 完成稿件，返稿地址
            'paytype' => '0', // 0-支付宝
            'payreturnurl' => $payreturnurl, // 支付完成后返回客户端入口
            'remarks' => urlencode($remarks), // 翻译要求, 用urlencode为了遵循api对文本的要求
            'userparam' => Iol_Translation_U::UUIDv4(), // 系统不处理该参数, 这个字段是有些数据可能需要对应你那边的id什么的
            'returntype' => '1', // 0-返稿到邮箱；1-返稿json；2-本地文件返稿；3-异地返稿这个参数你可以写死1
            'manuscript' => $manuscripts,
        );
        $result = $this->_requestPost('createorder', $data);
        if (is_array($result) && isset($result['status']) && $result['status'] == 'ok') {
            $this->doCreateorder_doReturn($result, $level, $manuscripts, $data['userparam'],$to_language);
            return array('status'=>true,'pay_url'=>$result['payurl']);
        } else {
            return array('status'=>false,'pay_url'=>'');
        }
    }

    private function doCreateorder_doReturn($result, $level, $sent_manuscripts, $order_user_param,$to_language) {
        $price = $result['price'];
        $pay_url = $result['payurl'];
        $word_count = $result['wordcount'];
        $return_manuscripts = $result['manuscript'];
        $order_number = $result['orderid'];

        $iol_translation_order_id = Iol_Translation_M_Iol_Translation_Order_Peer::create($level, $price, $order_number, $word_count, $pay_url, $order_user_param);
        $object_local_orginal_language_code = $to_language;

        foreach ($return_manuscripts as $return_manuscript) {
            $manuscript_number = $return_manuscript['manuscriptid'];
            $manuscript_price = $return_manuscript['manuscriptprice'];
            $manuscript_word_count = $return_manuscript['wordcount'];
            $manuscript_user_param = $return_manuscript['userparam'];

            /**
             * 因为返回的稿件没有把我们请求时候稿件的源语言，目标语言发回来，所以这里利用userparam进行匹配，取原来的提交数据
             */
            foreach ($sent_manuscripts as $sent_manuscript) {
                if ($sent_manuscript['userparam'] == $manuscript_user_param) {
                    $source_language_code = $sent_manuscript['srclang'];
                    $target_language_code = $sent_manuscript['goallang'];
                    $iol_translation_type_id = $sent_manuscript['iol_translation_type_id'];
                    $iol_translation_sub_type_id = $sent_manuscript['iol_translation_sub_type_id'];
                    $object_id = $sent_manuscript['object_id'];
                    $orginal = $sent_manuscript['original']; // 原文
                    $user_param = $sent_manuscript['userparam'];
                }
            }
           
            Iol_Translation_M_Iol_Translation_Manuscript_Peer::create($iol_translation_order_id, $object_local_orginal_language_code, $iol_translation_type_id, $iol_translation_sub_type_id, $object_id, $manuscript_number, $source_language_code, $target_language_code, $manuscript_word_count, $manuscript_price, $orginal, $user_param
            );
        }
    }

    /**
     * 构建一个简单的稿件，array格式，给 Inquiry 用
     * 
     * @param string $original 要翻译的源稿
     * @param string $srclang  源语言
     * @param string $goallang 目标语言
     * @return array
     */
    public function createManuscriptForInquiry($original,$srclang, $goallang) {
        // 添加urlencode为了遵循api对原文的标准要求
        return array('original' => urlencode($original), 'srclang' => $srclang, 'goallang' => $goallang);
    }

    /**
     * 构建一个简单的稿件，array格式，给 CreateOrder 用
     * 
     * @param type $manuscripttype
     * @param type $original 要翻译的源稿
     * @param type $srclang  源语言
     * @param type $goallang 目标语言
     * @return type
     */
    public function createManuscriptForCreateOrder($original,$srclang, $goallang, $iol_translation_type_id, $iol_translation_sub_type_id, $object_id) {
        // 添加urlencode为了遵循api对原文的标准要求
        return array(
            'manuscripttype' => '0', // 稿件是文件还是文本, 0=文本
            'original' => urlencode($original),
            'srclang' => $srclang,
            'goallang' => $goallang,
            'userparam' => Iol_Translation_U::UUIDv4(), //自选标记参数
            'iol_translation_type_id' => $iol_translation_type_id,
            'iol_translation_sub_type_id' => $iol_translation_sub_type_id,
            'object_id' => $object_id,
        );
    }

}