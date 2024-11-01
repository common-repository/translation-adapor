<div class="wrap">
    <h2>Demo</h2>

    <div class="updated"><p>This is update notice.</p></div>
    <div class="error"><p>This is error notice.</p></div>

    <h2>DB 读取</h2>
    <p>
        <?php
//    Iol_Translation_M_Iol_Translation_Configuration_Peer::updateEmailAndPassword(time(), rand(1, 9999));
        var_dump(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail());
        var_dump(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword());
        ?>    
    </p>    

    <h2>qTranslate</h2>
    <h3>qTranslate enabled languages</h3>
    <p>
        <textarea style="width:100%;height:150px;"><?php print_r(Iol_Translation_Qtranslate_Helper::getEnabledLanguages()); ?></textarea>
    </p>

    <h3>qTranslate all languages</h3>
    <p>
        <textarea style="width:100%;height:150px;"><?php print_r(Iol_Translation_Qtranslate_Helper::getAllLanguages()); ?></textarea>
    </p>

    <h2>IOL</h2>
    <h3>IOL languages</h3>
    <p>
        <textarea style="width:100%;height:150px;"><?php print_r(Iol_Translation_Api_Helper::getLanguages()); ?></textarea>
    </p>

    <h2>qTranslate 解析</h2>
    <h4>解析前</h4>
    <p>
        <?php
        $text = '<!--:de-->Hello world! Deutsch<!--:--><!--:en-->Hello world! English<!--:--><!--:zh-->Hello world!中文<!--:-->';
        ?>
        <textarea style="width:100%;height:50px;"><?php echo $text ?></textarea>
    </p>
    <h4>解析后</h4>    
    <p>
        <textarea style="width:100%;height:150px;"><?php print_r(Iol_Translation_Qtranslate_Helper::textSplit($text)) ?></textarea>
    </p>

<h4>反推</h4>
<p>
    <textarea style="width:100%;height:150px;"><?php print_r(Iol_Translation_Qtranslate_Helper::textJoin(Iol_Translation_Qtranslate_Helper::textSplit($text))) ?></textarea>
</p>
    <?php
// 还没有账号的时候，新建一个用户， 实际APi需要激活用户才能使用， 以下用户已经激活
//$iol_api_helper = new Iol_Translation_Api_Helper(null, null, true);
//$iol_api_helper->doCreateUser("dev@justwebworks.com", "5f4dcc3b5aa765d61d8327deb882cf99");
// 已有账号了，用现有账号初始化，开始使用
//$iol_api_helper = new Iol_Translation_Api_Helper('dev@justwebworks.com', '5f4dcc3b5aa765d61d8327deb882cf99', true);
    $iol_api_helper = new Iol_Translation_Api_Helper(Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail(), Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessPassword(), true);
    $iol_api_helper->doCheckUser();

// get product
//$post = Iol_Translation_M_Posts_Peer::retrieveByPK(1,2);
//echo '<h3>Product name</h3>'.$product->products_name;
//echo '<h3>Product description</h3>'.$product->products_description;
//echo '<h3>Product price</h3>'.$product->products_price;
//$manuscripts = array();
//$str = 'A 10%';
//$manuscripts[] = $iol_api_helper->createManuscriptForInquiry($str, 'en', 'zh');
//$iol_api_helper->doInquiry(Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD, $manuscripts);
//$manuscripts = array();
//$str = 'Bs"10';
//$manuscripts[] = $iol_api_helper->createManuscriptForInquiry($str, 'en', 'zh');
//$iol_api_helper->doInquiry(Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD, $manuscripts);
//$manuscripts = array();
//$str = 'Bs "10 hello';
//$manuscripts[] = $iol_api_helper->createManuscriptForInquiry($str, 'en', 'zh');
//$iol_api_helper->doInquiry(Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD, $manuscripts);
//$manuscripts = array();
//$str = 'Bs10';
//$manuscripts[] = $iol_api_helper->createManuscriptForInquiry($str, 'en', 'zh');
//$iol_api_helper->doInquiry(Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD, $manuscripts);
//$manuscripts = array();
//$manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($product->products_name, 'zh', Iol_Translation_M_IolTranslationTypePeer::TYPE_PRODUCT, Iol_Translation_M_IolTranslationSubTypePeer::SUB_TYPE_PRODUCT_NAME, $product->products_id);
//$manuscripts[] = $iol_api_helper->createManuscriptForCreateOrder($product->products_description, 'zh', Iol_Translation_M_IolTranslationTypePeer::TYPE_PRODUCT, Iol_Translation_M_IolTranslationSubTypePeer::SUB_TYPE_PRODUCT_DESCRIPTION, $product->products_id);
//$iol_api_helper->doCreateorder(Iol_Translation_Api_Helper::SERVICE_LEVEL_STANDARD, 'remarks', $manuscripts);
// update product
//Iol_Translation_M_ProductsDescriptionPeer::update(1, 2, $product->products_name.'_'.time(), $product->products_description.'_'.time());
// 再次 get product
//$product = Iol_Translation_M_ProductsDescriptionPeer::retrieveByPK(1,2);
//echo '<h3>Product name</h3>'.$product->products_name;
//echo '<h3>Product description</h3>'.$product->products_description;
//echo '<h3>Product price</h3>'.$product->products_price;
//  echo IolTranslationU::__('FOUND_ATTRIBUTES_IN_ITEMS_ATTRIBUTES_NEED_TO_BE_TRANSLATED', array('%item_total%'=>'1','%attr_total%'=>'2','%attr_trans%'=>'3',));
    ?>

</div>

