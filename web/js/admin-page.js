jQuery(document).ready(function($){

});

function iolTranslationContentIsChecked(){
    var boxes = jQuery('#iol_translation_setting .first_row input');
    var i = 0;
    boxes.each(function(){
        var is_check = jQuery(this).is(':checked');
        if (is_check) {
            i++;
        }
    })
    if(i > 0){
        return true;
    }else{
        return false;
    }
}

function iolTranslationIsSameValueSelect(select_1,select_2){
    
    if(select_1 == select_2){
        return true;
    }else{
        return false;
    }
    
}

function iolTranslationLoading(){
    var image_load = "<img src='web/image/040.gif' />"
    jQuery('#iol_translation_setting .inquiry_loading').html(image_load);
}