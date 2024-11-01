<?php

class Iol_Translation_Bulk_Post {

    public function __construct() {

        if (is_admin()) {
            // admin actions/filters
            add_action('admin_footer-edit.php', array(&$this, 'admin_footer'));
        }
    }

    /**
     * Step 1: add the custom Bulk Action to the select menus
     * add new batch action to table select
     */
    function admin_footer() {
        global $post_type;
        if ($post_type == 'post' || $post_type == 'page') {
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('<option>').val('iol_bulk_post').text('<?php echo Iol_Translation_U::__('Translation') ?>').appendTo("select[name='action']");
                    jQuery('<option>').val('iol_bulk_post').text('<?php echo Iol_Translation_U::__('Translation') ?>').appendTo("select[name='action2']");
                                                                                
                    jQuery('#doaction, #doaction2').click(function(e){
                        var n = jQuery(this).attr('id').substr(2);
                        if ( 'iol_bulk_post' === jQuery( 'select[name="' + n + '"]' ).val() ) {
                            e.preventDefault();
                            // show 
                            jQuery('#iol_transaltion_bluk_edit_place_holder tbody tr').prependTo('#the-list');
                            jQuery('#posts-filter #the-list .check-column input').attr('disabled',true);
                            jQuery('.wp-list-table #cb input:checkbox,#cb-select-all-2').attr('disabled',true);
                        }
                    });
                                                                    
                });
                                                                
                function iol_transaltion_bluk_edit_panel_revert(){
                    jQuery('#the-list tr:first-child').prependTo('#iol_transaltion_bluk_edit_place_holder');
                    jQuery('.iol_translation_table_flied .iol_submit_success').hide();
                    jQuery('.iol_translation_table_flied .iol_submit_fails').hide();
                    jQuery('.iol_translation_table_flied .iol_submit_fails .below-h2 p').html('');
                    jQuery('.iol_translation_table_flied .iol_submit_success .below-h2 p').html('');
                    jQuery('#posts-filter #the-list .check-column input').attr('disabled',false);
                    jQuery('.wp-list-table #cb input:checkbox,#cb-select-all-2').attr('disabled',false);
                }       
            </script>

            <table id="iol_transaltion_bluk_edit_place_holder" style="display:none;">
                <tbody>
                    <tr>
                        <td class="colspanchange" colspan="9" id="iol_transaltion_bluk_colspanchange">
                            <!--transaltion setting-->
                            <div class="iol_translation_table_flied">
                                <div class="wrap iol_submit_success" style="display: none;">
                                    <div class="updated below-h2"><p>&nbsp;</p></div>
                                </div>
                                <div class="wrap iol_submit_fails" style="display: none;">
                                    <div class="error below-h2"><p>&nbsp;</p></div>
                                </div>
                                <div id="iol_translation_setting">
                                    <input type="hidden" id="iol_translation_obj_type" value="<?php echo $post_type; ?>">
                                    <p class="first_row">
                                        <input type="checkbox" class="translation_post_title" name="translation_post_title" value="1" style="margin-left: 0px;"><span style="margin-right: 10px;">Title</span>
                                        <input type="checkbox" class="translation_post_comment" name="translation_post_comment" value="2" ><span>Content</span>
                                    </p>
                                    <div class="second_row" style="clear: both">
                                        <?php $qtanslation_lanuages = Iol_Translation_Qtranslate_Helper::getLanguageArrayAfterTheTransformation() ?>
                                        <span><?php echo Iol_Translation_U::__('Translation from') ?>&nbsp;</span>
                                        <select name="from_language" class="from_language">
                                            <?php foreach ($qtanslation_lanuages as $key => $value) { ?>
                                            <option  <?php echo $key == Iol_Translation_M_Iol_Translation_Language_Peer::getDefaultMapLanguageCode() ?"selected='selected'":''; ?>  value="<?php echo $key; ?>"><?php echo $value ?></option>
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
                                    <div class="third_row" style="display: none">
                                        <div class="show_translation_info" style="margin-top: 10px;"></div>
                                        <div class="show_price" style="margin: 18px 0px;"></div>
                                        <div class="translation_requirement">
                                            <span style="display: block;float: left"><?php echo Iol_Translation_U::__('Translation requirements') ?>: <em style="color: red;">*</em></span>&nbsp;&nbsp;
                                            <textarea name="translation_requirement" class="translation_requirement_textarea" style="width: 300px;"></textarea>
                                        </div>
                                        <p class="submit action_area" style="float: none;padding-left: 0px;margin-top: 10px;">
                                            <input type="button" value="<?php echo Iol_Translation_U::__('Submit') ?>" class="button button-primary" id="iol_translation_submit_btn">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/transaltion setting-->
                            <p class="submit inline-edit-save">
                                <a class="button-secondary cancel alignleft iol_translation_cancell" href="#inline-edit" accesskey="c"><?php echo Iol_Translation_U::__('Cancel Translation') ?></a>
                                <br class="clear">
                            </p>                            
                            <input type="hidden" value="" class="hidden_select_ids">
                        </td>
                    </tr>
                </tbody>
            </table>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('#iol_translation_setting .second_row select').attr('disabled',false);
                    jQuery('#iol_translation_setting .first_row input').attr('disabled',false);
                    jQuery('#the-list input:checkbox').click(function(){
                        var this_value = jQuery(this).val();
                        var select_ids = jQuery('#iol_transaltion_bluk_edit_place_holder .hidden_select_ids').val();
                        ids = select_ids.split(",");
                        tmp_ids ='';
                        jQuery.each(ids,function(key,value){
                            if(this_value != value && value!=''){
                                tmp_ids += value+",";
                            }
                        });
                        if(jQuery(this).is(':checked')){
                            jQuery(this).attr('checked',true);
                            //add
                            tmp_ids+=this_value;
                        }else{
                            jQuery(this).attr('checked',false);
                        }
                        jQuery('#iol_transaltion_bluk_edit_place_holder .hidden_select_ids').val(tmp_ids); 
                    })
                                
                    jQuery('.wp-list-table #cb input:checkbox,#cb-select-all-2').click(function(){
                        jQuery('#iol_transaltion_bluk_edit_place_holder .hidden_select_ids').val(''); 
                        if(jQuery(this).is(':checked')){
                            tmp_ids ='';
                            jQuery('#the-list input:checkbox').each(function(){
                                jQuery(this).attr('checked',true);
                                var this_value = jQuery(this).val();
                                tmp_ids += this_value+",";
                            })
                            jQuery('#iol_transaltion_bluk_edit_place_holder .hidden_select_ids').val(tmp_ids); 
                        }else{
                            jQuery('#the-list input:checkbox').each(function(){
                                jQuery(this).attr('checked',false);
                            })
                        }
                    })
                    //inquiry
                    jQuery('#iol_transaltion_bluk_colspanchange .iol_translation_table_flied #iol_translation_go_quiry_btn').click(function(){
                        //if not select
                        var select_ids = jQuery('#the-list .hidden_select_ids').val(); 
                        if(select_ids == ''){
                            alert('<?php echo Iol_Translation_U::__('please select items') ?>');
                            iol_transaltion_bluk_edit_panel_revert();return false;
                        }
                                    
                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_requirement_textarea').val('');
                        if(!iolTranslationContentIsChecked()){
                            alert('<?php echo Iol_Translation_U::__('please select items') ?>');return false;
                        }
                        var from_language = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .second_row select.from_language').val();
                        var to_language = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .second_row select.to_language').val();
                        if(iolTranslationIsSameValueSelect(from_language,to_language)){
                            alert('<?php echo Iol_Translation_U::__('The source language and target language can not be same') ?>');return false;
                        }
                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .inquiry_loading').show();
                        if (jQuery("#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_post_title").is(":checked")){
                            var title = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_post_title').val();
                        }
                        if (jQuery("#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_post_comment").is(":checked")){
                            var comment = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_post_comment').val(); 
                        }
                                    
                        var translate_level = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translate_level').val();
                        var post_ids = select_ids;
                        var post_type = jQuery('#iol_translation_obj_type').val();
                                    
                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .second_row select').attr('disabled',true);
                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .first_row input').attr('disabled',true);
                        jQuery.ajax({
                            url: ajaxurl,
                            data: {
                                'action':'iol_translation_all_inquiry',
                                'title' : title,
                                'comment':comment,
                                'translate_level':translate_level,
                                'from_language':from_language,
                                'to_language':to_language,
                                'post_ids' : post_ids,
                                'post_type':post_type
                            },
                            dataType: 'JSON',
                            success:function(data) {
                                jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .show_price').removeClass('iol_translation_error').html('');
                                jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .show_translation_info').html('');  
                                if(data.status == 'fails'){
                                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .third_row').show();
                                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .show_price').addClass('iol_translation_error').html(data.msg);
                                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_requirement').hide();
                                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting #iol_translation_submit_btn').hide();
                                }else{
                                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .third_row').show();
                                    if(data.show_info){
                                        jQuery('#iol_translation_setting .show_translation_info').html(data.show_info);  
                                    }
                                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .show_price').removeClass('iol_translation_error').html(data.msg);
                                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_requirement').show();
                                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting #iol_translation_submit_btn').show();
                                }
                                jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .inquiry_loading').hide();
                            }
                        }); 
                    })
                    //cancel
                    jQuery('#iol_transaltion_bluk_colspanchange .iol_translation_cancell').click(function(){
                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .second_row select').attr('disabled',false);
                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .first_row input').attr('disabled',false);
                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .third_row').hide();
                        iol_transaltion_bluk_edit_panel_revert();
                    })
                    //submit
                    jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_submit_btn').click(function(){
                        var select_ids = jQuery('#the-list .hidden_select_ids').val(); 
                        if (jQuery("#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_post_title").is(":checked")){
                            var title = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_post_title').val();
                        }
                        if (jQuery("#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_post_comment").is(":checked")){
                            var comment = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_post_comment').val(); 
                        }
                        var from_language = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .second_row select.from_language').val();
                        var to_language = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .second_row select.to_language').val();
                        var translate_level = jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translate_level').val();
                        var post_ids = select_ids;
                        var post_type = jQuery('#iol_translation_obj_type').val();
                        var translation_requirement = jQuery.trim(jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_requirement_textarea').val());
                        if(translation_requirement){
                            jQuery.ajax({
                                url: ajaxurl,
                                data: {
                                    'action':'iol_translation_all_submit',
                                    'from_language':from_language,
                                    'to_language':to_language,
                                    'title':title,
                                    'comment':comment,
                                    'translate_level':translate_level,
                                    'post_ids':post_ids,
                                    'translation_requirement':translation_requirement,
                                    'post_type':post_type
                                },
                                dataType: 'JSON',
                                success:function(data) {
                                    
                                    jQuery('.iol_translation_table_flied .wrap').hide();
                                    if(data.status == 'ok'){
                                        jQuery('#iol_transaltion_bluk_colspanchange .iol_translation_table_flied .iol_submit_success').show();
                                        jQuery('#iol_transaltion_bluk_colspanchange .iol_translation_table_flied .iol_submit_success .below-h2 p').html(data.msg);
                                        if(data.pay_url){
                                            window.open(data.pay_url); 
                                        }
                                    }else{
                                        jQuery('#iol_transaltion_bluk_colspanchange .iol_translation_table_flied .iol_submit_fails').show();
                                        jQuery('#iol_transaltion_bluk_colspanchange .iol_translation_table_flied .iol_submit_fails .below-h2 p').html(data.msg);
                                    }
                                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .second_row select').attr('disabled',false);
                                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .first_row input').attr('disabled',false);
                                        jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .third_row').hide();
                                }
                            }); 
                        }else{
                            jQuery('#iol_transaltion_bluk_colspanchange #iol_translation_setting .translation_requirement_textarea').css('border-color','red');
                        }
                        
                    })
                                
                });
                                                                
            </script>


            <?php
        }
    }

}

new Iol_Translation_Bulk_Post();