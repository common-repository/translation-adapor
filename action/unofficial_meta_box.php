
<tr>
    <td colspan="2" style="padding: 0px;padding-right: 70px;">
        <?php global $tag_ID;global $taxonomy; ?>
        <div class="postbox iol_translation_table_flied" style="display: block;margin-top: 20px;">
            <h3 style="font-size: 14px;padding: 8px 12px;line-height: 1.4;"><span><?php echo Iol_Translation_U::__('Translation') ?></span></h3>
            <div class="wrap iol_submit_success" style="display: none;">
                <div class="updated below-h2"><p>&nbsp;</p></div>
            </div>
            <div class="wrap iol_submit_fails" style="display: none;">
                <div class="error below-h2"><p>&nbsp;</p></div>
            </div>

            <div class="inside">
                <div id="iol_translation_setting">
                    <p class="first_row" style="margin-bottom: 10px;width: 80px;float: left">
                        <input type="checkbox" class="translation_name" name="translation_name" value="1" style="margin-left: 0px;"><span style="margin-right: 10px;">Name</span>
                    </p>
                    <?php if(strtolower($taxonomy) == 'category'){ ?>
                    <p style="float: left;">
                        <input type="checkbox" class="translation_same_properties_for_all_sub" name="translation_same_properties_for_all_sub" value="1" style="margin-left: 10px;"><span><?php echo Iol_Translation_U::__('Translation same properties for all subcategories') ?></span>
                    </p>
                    <?php } ?>
                    <div class="second_row" style="clear: both">
                        <?php $qtanslation_lanuages = Iol_Translation_Qtranslate_Helper::getLanguageArrayAfterTheTransformation() ?>
                        <span><?php echo Iol_Translation_U::__('Translation from') ?>&nbsp;</span>
                        <select name="from_language" class="from_language">
                            <?php foreach ($qtanslation_lanuages as $key => $value) { ?>
                                <option <?php echo $key == Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode() ?"selected='selected'":''; ?> value="<?php echo $key; ?>"><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                        <span>&nbsp;<?php echo Iol_Translation_U::__('into') ?>&nbsp;</span> 
                        <select name="to_language" class="to_language">
                            <?php foreach ($qtanslation_lanuages as $key => $value) { ?>
                                <option value="<?php echo $key; ?>"><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                        <span>&nbsp;<?php echo Iol_Translation_U::__('using') ?>&nbsp;</span>
                        <select name="translate_level" class="translate_level">
                            <option value="1"><?php echo Iol_Translation_U::__('Professional translation') ?></option>
                            <option value="2"><?php echo Iol_Translation_U::__('Standard translation') ?></option>
                        </select>
                        <span>
                            <input type="button" value="<?php echo Iol_Translation_U::__('Inquiry') ?>" class="button button-primary" id="iol_translation_go_quiry_btn">
                        </span><span style="margin-left: 10px;" class="inquiry_loading">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <div class="third_row" style="display: none;">
                        <div class="show_translation_info" style="margin-top: 10px;"></div>
                        <div class="show_price" style="margin: 15px 0px;"></div>
                        <div class="translation_requirement">
                            <span style="display: block;float: left"><?php echo Iol_Translation_U::__('Translation requirements') ?>: <em style="color: red;">*</em></span>&nbsp;&nbsp;
                            <textarea name="translation_requirement" class="translation_requirement_textarea" style="width: 300px;"></textarea>
                        </div>
                        <p class="submit action_area" style="float: none;padding-left: 0px;margin-top: 10px;">
                            <input type="button" value="<?php echo Iol_Translation_U::__('Submit') ?>" class="button button-primary" id="iol_translation_submit_btn">
                            <input type="button" value="<?php echo Iol_Translation_U::__('Cancel') ?>" class="button" id="iol_translation_cancel_btn">
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <script>
            jQuery(document).ready( function($){
                jQuery('#iol_translation_setting .second_row select').attr('disabled',false);
                jQuery('#iol_translation_setting .first_row input').attr('disabled',false);
            
                // 查询价格
                jQuery('#iol_translation_go_quiry_btn').click(function(){
                    jQuery('#iol_translation_setting .translation_requirement_textarea').val('');
                    if(!iolTranslationContentIsChecked()){
                        alert('<?php echo Iol_Translation_U::__('please select items') ?>');return false;
                    }
                    var from_language = jQuery('#iol_translation_setting .second_row select.from_language').val();
                    var to_language = jQuery('#iol_translation_setting .second_row select.to_language').val();
                    if(iolTranslationIsSameValueSelect(from_language,to_language)){
                        alert('<?php echo Iol_Translation_U::__('The source language and target language can not be same') ?>');return false;
                    }
                    jQuery('#iol_translation_setting .inquiry_loading').show();
                    if (jQuery("#iol_translation_setting .translation_name").is(":checked")){
                        var name = jQuery('#iol_translation_setting .translation_name').val();
                    }
            
                    var translate_level = jQuery('#iol_translation_setting .translate_level').val();
                    var tag_id = jQuery("input[name='tag_ID']").val();
                    var taxonomy = jQuery("input[name='taxonomy']").val();
                    var check_all_category = '';
                    <?php if(strtolower($taxonomy) == 'category'){ ?>
                    if (jQuery("#iol_translation_setting .translation_same_properties_for_all_sub").is(":checked")){
                        var check_all_category = jQuery('#iol_translation_setting .translation_same_properties_for_all_sub').val();
                    }
                    <?php } ?>
                    jQuery('#iol_translation_setting .second_row select').attr('disabled',true);
                    jQuery('#iol_translation_setting .first_row input').attr('disabled',true);
                    jQuery('#iol_translation_setting .translation_same_properties_for_all_sub').attr('disabled',true);
                    
                    jQuery.ajax({
                        url: ajaxurl,
                        data: {
                            'action':'iol_translation_inquiry',
                            'name' : name,
                            'translate_level':translate_level,
                            'from_language':from_language,
                            'to_language':to_language,
                            'tag_id' : tag_id,
                            'taxonomy':taxonomy,
                            'check_all_category':check_all_category
                        },
                        dataType: 'JSON',
                        success:function(data) {
                            jQuery('#iol_translation_setting .show_price').removeClass('iol_translation_error').html('');
                            jQuery('#iol_translation_setting .show_translation_info').html('');  
                            if(data.status == 'fails'){
                                jQuery('#iol_translation_setting .third_row').show();
                                jQuery('#iol_translation_setting .show_price').addClass('iol_translation_error').html(data.msg);
                                jQuery('#iol_translation_setting .translation_requirement').hide();
                                jQuery('#iol_translation_setting #iol_translation_submit_btn').hide();
                            }else{
                                jQuery('#iol_translation_setting .third_row').show();
                                if(data.show_info){
                                    jQuery('#iol_translation_setting .show_translation_info').html(data.show_info);  
                                }
                                jQuery('#iol_translation_setting .show_price').removeClass('iol_translation_error').html(data.msg);
                                jQuery('#iol_translation_setting .translation_requirement').show();
                                jQuery('#iol_translation_setting #iol_translation_submit_btn').show();
                            }
                            jQuery('#iol_translation_setting .inquiry_loading').hide();
                        }
                    });
                })
                //取消
                jQuery('#iol_translation_cancel_btn').click(function(){
                    jQuery('#iol_translation_setting .second_row select').attr('disabled',false);
                    jQuery('#iol_translation_setting .first_row input').attr('disabled',false);
                    jQuery('#iol_translation_setting .translation_same_properties_for_all_sub').attr('disabled',false);
                    jQuery('#iol_translation_setting .third_row').hide();
                })
        
        
                //提交
                jQuery('#iol_translation_submit_btn').click(function(){
                    if (jQuery("#iol_translation_setting .translation_name").is(":checked")){
                        var name = jQuery('#iol_translation_setting .translation_name').val();
                    }
                    var from_language = jQuery('#iol_translation_setting .second_row select.from_language').val();
                    var to_language = jQuery('#iol_translation_setting .second_row select.to_language').val();
                    var translate_level = jQuery('#iol_translation_setting .translate_level').val();
                    var tag_id = jQuery("input[name='tag_ID']").val();
                    var taxonomy = jQuery("input[name='taxonomy']").val();
                    var translation_requirement = jQuery.trim(jQuery('#iol_translation_setting .translation_requirement_textarea').val());
                    var check_all_category = '';
                    <?php if(strtolower($taxonomy) == 'category'){ ?>
                    if (jQuery("#iol_translation_setting .translation_same_properties_for_all_sub").is(":checked")){
                        var check_all_category = jQuery('#iol_translation_setting .translation_same_properties_for_all_sub').val();
                    }
                    <?php } ?>
                    if(translation_requirement){
                        jQuery.ajax({
                            url: ajaxurl,
                            data: {
                                'action':'iol_translation_submit',
                                'from_language':from_language,
                                'to_language':to_language,
                                'name':name,
                                'translate_level':translate_level,
                                'tag_id':tag_id,
                                'translation_requirement':translation_requirement,
                                'taxonomy':taxonomy,
                                'check_all_category':check_all_category
                            },
                            dataType: 'JSON',
                            success:function(data) {
                                jQuery('.iol_translation_table_flied .wrap').hide();
                                if(data.status == 'ok'){
                                    jQuery('.iol_translation_table_flied .iol_submit_success').show();
                                    jQuery('.iol_translation_table_flied .iol_submit_success .below-h2 p').html(data.msg);
                                    if(data.pay_url){
                                            window.open(data.pay_url); 
                                        }
                                }else{
                                    jQuery('.iol_translation_table_flied .iol_submit_fails').show();
                                    jQuery('.iol_translation_table_flied .iol_submit_fails .below-h2 p').html(data.msg);
                                }
                                jQuery('#iol_translation_cancel_btn').trigger('click');
                            }
                        }); 
                    }else{
                        jQuery('#iol_translation_setting .translation_requirement_textarea').css('border-color','red');
                    }
                })
            });
    
        </script>
    </td>
</tr>

