<?php

class Iol_Translation_M_Iol_Translation_Order_Peer extends Iol_Translation_M_Peer_Base {

    const TRANSLATION_STATUS_PENDING = 1;
    const TRANSLATION_STATUS_DONE = 2;
    const PAYMENT_STATUS_PENDING = 1;
    const PAYMENT_STATUS_DONE = 2;

    
    public static function retrieveByPK($id) {
        global $wpdb;
        $data = null;
        $vars = $wpdb->get_results($wpdb->prepare("select * from $wpdb->iol_translation_order where id = %d", $id));
        if (isset($vars[0])) {
            $data = $vars[0];
        }
        return $data;
    }
    
    
    public static function create($level, $price, $order_number, $word_count, $pay_url, $user_param) {
        global $wpdb;
        $wpdb->insert(
                        $wpdb->iol_translation_order, // Table    Array of key(col) => val(value to insert)
                        array('level' => $level,
                            'price' => $price,
                            'order_number' => $order_number,
                            'word_count' => $word_count,
                            'pay_url' => $pay_url,
                            'user_param' => $user_param,
                            'created_at' => time(),
                            'updated_at' => time()
                            ) 
                );
        
        $vars = $wpdb->get_results($wpdb->prepare("select id from $wpdb->iol_translation_order where order_number = %s", $order_number));
        $tmp_data = $vars[0];
        $id = $tmp_data->id;
        return $id;
    }

    public static function updateByOrderReturn($order_number) {
        global $wpdb;
        $wpdb->update(
                $wpdb->iol_translation_order,
                array('translation_status' => self::TRANSLATION_STATUS_DONE, 'updated_at' => time()), 
                array(
                'order_number' => addslashes($order_number)
                )
        );
    }

    public static function updateByPayReturn($order_number, $payment_number, $pay_status) {
        if ($pay_status == 'success') {
            global $wpdb;
            $wpdb->update(
                    $wpdb->iol_translation_order, 
                    array('payment_status' => self::PAYMENT_STATUS_DONE,
                            'payment_number' => $payment_number,
                            'paid_at' => time(),
                            'updated_at' => time()
                    ), 
                    array(
                'order_number' => addslashes($order_number)
                    )
            );
        }
    }
    
     public static function checkOrderIsPay($order_id){
        $order = self::retrieveByPK($order_id);
        
        $pay_status = $order->payment_status;
        if($pay_status == 2){
            return true;
        }else{
            return false;
        }
    }
    
    public static function deleteOrder($order_id){
         global $wpdb;
            $wpdb->delete(
                    $wpdb->iol_translation_order, 
                    array('id' => $order_id
                    )
            );
            self::_deleteManuscript($order_id);
    }
    
    public static function _deleteManuscript($order_id){
        global $wpdb;
            $wpdb->delete(
                    $wpdb->iol_translation_manuscript, 
                    array('iol_translation_order_id' => $order_id
                    )
            );
    }
    
    
   

}