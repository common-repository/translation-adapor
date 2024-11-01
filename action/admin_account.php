<?php
$email_from_db = Iol_Translation_M_Iol_Translation_Configuration_Peer::getApiAccessUseremail();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['from_new_user'])) {
        // 第一次注册
        $user_input_name = IOL_TRANSLATION_SLUG . '_api_access_useremail';
        $user_input_password = IOL_TRANSLATION_SLUG . '_api_access_password';
        $useremail = $_POST[$user_input_name];
        $password = md5($_POST[$user_input_password]);
        $iol_api_helper = new Iol_Translation_Api_Helper($useremail, $password);
        $user_check = $iol_api_helper->doCheckUser();
        if ($user_check) {
            //验证成功 更新到数据库
            Iol_Translation_M_Iol_Translation_Configuration_Peer::updateEmailAndPassword($useremail, $password);
            $msg = Iol_Translation_U::__('Authentication success');
            Iol_Translation_U::setSessionMessages(array('msg' => $msg, 'type' => 'success'));
            $email_from_db = $useremail;
        } else {
            //不成功
            $msg = Iol_Translation_U::__('Email account and password do not match');
            Iol_Translation_U::setSessionMessages(array('msg' => $msg, 'type' => 'fails'));
            $tmp_password = $_POST[$user_input_password];
        }
        $tmp_email = $useremail;
    } else {
        // 注销
        Iol_Translation_M_Iol_Translation_Configuration_Peer::updateEmailAndPassword('', '');
        $email_from_db = '';
    }
}
$tmp_email = isset($tmp_email) ? $tmp_email : '';
$tmp_password = isset($tmp_password) ? $tmp_password : '';
?>
<div class="wrap">
    <div><?php echo Iol_Translation_U::getSessionMessages(); ?></div>
    <h2><?php echo Iol_Translation_U::__('User Account') ?></h2>
    <br/>
    <form method="post" action=""> 
        <?php settings_fields(IOL_TRANSLATION_SLUG); ?>
        <?php if (!$email_from_db) { ?>
        <div id="namediv" class="stuffbox" style="width: 863px;">
            <div class="inside">
            <table class="form-table user_account_table editcomment">
                <tr valign="top">
                    <th scope="row"><label><?php echo Iol_Translation_U::__('Email') ?></label></th>
                    <td><input type="text" id="<?php echo IOL_TRANSLATION_SLUG . '_api_access_useremail' ?>" name="<?php echo IOL_TRANSLATION_SLUG . '_api_access_useremail' ?>" value="<?php echo $tmp_email; ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label ><?php echo Iol_Translation_U::__('Password') ?></label></th>
                    <td><input type="password" id="<?php echo IOL_TRANSLATION_SLUG . '_api_access_password' ?>" name="<?php echo IOL_TRANSLATION_SLUG . '_api_access_password' ?>" value="<?php echo $tmp_password; ?>" />
                        <input type="hidden" value="1" name="from_new_user">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"></th>
                    <td><?php echo Iol_Translation_U::__('New to IOL') ?>? <a target="_blank" href="http://qcm.iol8.com/register"><?php echo Iol_Translation_U::__('Register now') ?> »</a></td>                    
                </tr>            
            </table>
        </div>
        </div>
        <?php submit_button(); ?>
        <?php } else { ?>
                <div id="namediv" class="stuffbox" style="width: 863px;">
                <div class="inside">
                    <table class="form-table user_account_table editcomment">
                        <tr valign="top">
                            <th scope="row"><label><?php echo Iol_Translation_U::__('Email') ?></label></th>
                            <td><input type="text" id="<?php echo IOL_TRANSLATION_SLUG . '_api_access_useremail' ?>" name="<?php echo IOL_TRANSLATION_SLUG . '_api_access_useremail' ?>" value="<?php echo $email_from_db; ?>" /></td>
                        </tr>
                    </table>
                </div>
            </div>
            <p class="submit">
                <input id="submit" class="button button-primary" type="submit" value="<?php echo Iol_Translation_U::__('Logout') ?>" name="submit">
            </p>
        <?php } ?>
    </form>
</div>











