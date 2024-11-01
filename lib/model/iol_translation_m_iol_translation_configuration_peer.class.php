<?php

class Iol_Translation_M_Iol_Translation_Configuration_Peer extends Iol_Translation_M_Peer_Base {

    const TYPE_API_ACCESS_USEREMAIL = 1;
    const TYPE_API_ACCESS_PASSWORD = 2;

    public static function retrieveByPK($id) {
        global $wpdb;
        $data = null;
        $vars = $wpdb->get_results($wpdb->prepare("select id, code, val from $wpdb->iol_translation_configuration where id = %d", $id));
        if (isset($vars[0])) {
            $data = $vars[0];
        }
        return $data;
    }

    public static function getApiAccessUseremail() {
        $data = self::retrieveByPK(self::TYPE_API_ACCESS_USEREMAIL);
        return $data->val;
    }

    public static function getApiAccessPassword() {
        $data = self::retrieveByPK(self::TYPE_API_ACCESS_PASSWORD);
        return $data->val;
    }

    public static function updateEmailAndPassword($email, $password) {
        global $wpdb;
        $wpdb->update(
                $wpdb->iol_translation_configuration, // Table
                array('val' => $email), // Array of key(col) => val(value to update to)
                array(
            'id' => self::TYPE_API_ACCESS_USEREMAIL,
                ) // Where
        );

        $wpdb->update(
                $wpdb->iol_translation_configuration, // Table
                array('val' => $password), // Array of key(col) => val(value to update to)
                array(
            'id' => self::TYPE_API_ACCESS_PASSWORD,
                ) // Where
        );
    }

    public static function checkUserIsLogout() {
        $user_email = self::getApiAccessUseremail();
        if ($user_email == '') {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

}