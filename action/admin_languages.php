<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $post_datas = proessPostData();
    Iol_Translation_M_Iol_Translation_Language_Peer::insertWordpressIolLanguageXref($post_datas);
}

function proessPostData() {
    $param = $_POST;
    $qtranslation_language_codes = $param['qtranslation_language_code'];
    $iol_language_code = $param['iol_language_code'];
    $tmp_array = array();
    for ($i = 0; $i < count($qtranslation_language_codes); $i++) {
        $tmp_array[$i]['qtranslation_language_code'] = $qtranslation_language_codes[$i];
        $tmp_array[$i]['iol_language_code'] = $iol_language_code[$i];
    }

    return $tmp_array;
}
?>
<div class="wrap">
    <div><?php echo Iol_Translation_U::getSessionMessages(); ?></div>
    <h2><?php echo Iol_Translation_U::__('Languages') ?></h2>
    <br/>
    <form action="" method="post" id="iol_translation_languages_form">
        <?php $qtanslation_lanuage = Iol_Translation_Qtranslate_Helper::getEnabledLanguages(); ?>
        <div id="namediv" class="stuffbox" style="width: 863px;">
            <div class="inside">
                <table width="100%" id="iol_translation_change_languages">
                    <tr class="language_title">
                        <td width="30%">qTranslate <?php echo Iol_Translation_U::__('Languages') ?></td>
                        <td width="30%">IOL <?php echo Iol_Translation_U::__('Languages') ?></td>
                        <td width="40%">&nbsp;</td>
                    </tr>
                    <?php foreach ($qtanslation_lanuage as $q_key => $q_value) { ?>
                        <tr>
                            <td>
                                <?php echo $q_value; ?>
                                <input type="hidden" name="qtranslation_language_code[]" value="<?php echo $q_key ?>">
                            </td>
                            <td>
                                <?php $selected_language_code = Iol_Translation_M_Iol_Translation_Language_Peer::getSelectedLanguage($q_key); ?>
                                <select name="iol_language_code[]" class="need_select_language" style="width: 250px;">
                                    <option value=""> </option>  
                                    <?php foreach (Iol_Translation_Api_Helper::getLanguages() as $key => $value) { ?>
                                        <option <?php echo $selected_language_code == $key ? "selected='selected'" : ''; ?>  value="<?php echo $key ?>"><?php echo $value; ?></option>
                                    <?php } ?> 
                                </select>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        <p class="submit">
            <input id="pseudo_submit_language_form_btn" class="button button-primary" type="button" value="<?php echo Iol_Translation_U::__('Save Changes') ?>" name="submit">
            <input type="submit" id="submit_language_form_btn" style="display: none;">
        </p>
    </form>
</div>
<script>
    
    jQuery(function($){
        jQuery('#pseudo_submit_language_form_btn').click(function(){
            if(isAllSelect()){
                alert('<?php echo Iol_Translation_U::__('Please select language') ?>');return false;
            }else{
                jQuery('#submit_language_form_btn').trigger('click');
            } 
        })
    })
    
    function isAllSelect(){
        var i = 0;
        jQuery('#iol_translation_change_languages select.need_select_language').each(function(){
            var is_check = jQuery(this).val();
            if (is_check == '') {
                i++;
            }
        })
        if(i > 0){
            return true;
        }else{
            return false;
        }
    }
    
    
</script>

