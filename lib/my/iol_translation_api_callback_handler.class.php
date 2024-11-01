<?php

class Iol_Translation_Api_Callback_Handler {

    public static function processReturnOrder() {

        // debug
        //$_POST['parameter'] = '{"orderid":"201402201626314268","manuscript":[{"manuscriptid":"201402201626313375","translations":"Sale+Deduction+%E7%93%A6%E5%B0%94%E7%93%A6","userparam":"b0991641-39f1-49e2-ba7c-cfbafc351218","finishTime":"2014-02-20 16:26:31"}]}';

		//file_put_contents('c:\iol.debug.txt', 'json '.$_POST['parameter']."\n", FILE_APPEND);
	
        if (isset($_POST['parameter'])) {
            $parameter = $_POST['parameter'];

            $data = json_decode(stripslashes($parameter), true);
            $order_number = $data['orderid'];
            $manuscripts = $data['manuscript'];

            // 更新稿件
            foreach ($manuscripts as $manuscript) {
			
                $manuscript_number = isset($manuscript['manuscriptid']) ? $manuscript['manuscriptid'] : null;
				
                $manuscript_translations = isset($manuscript['translations']) ? $manuscript['translations'] : null;
				
                $manuscript_user_param = isset($manuscript['userparam']) ? $manuscript['userparam'] : null;
				
                $manuscript_finishTime = isset($manuscript['finishTime']) ? $manuscript['finishTime'] : null;
                // debug
                 //file_put_contents('c:\iol.debug.txt', 'json '.$manuscript_number .' '. $manuscript_translations .' '. $manuscript_user_param."\n", FILE_APPEND);

                if ($manuscript_number && $manuscript_translations && $manuscript_user_param && $manuscript_finishTime) {
                    Iol_Translation_M_Iol_Translation_Manuscript_Peer::updateByOrderReturn($manuscript_number, $manuscript_translations, $manuscript_user_param, $manuscript_finishTime);
                }
            }

            // 更新订单
            Iol_Translation_M_Iol_Translation_Order_Peer::updateByOrderReturn($order_number);

            // 回复API成功
            echo "{status:'success'}";
            exit;
        }
    }

    public static function processReturnPayment() {
        
        // debug
        //$_POST['parameter'] = '{"orderid":"201402170036282863","payid":"wdwa","payStatus":"success"}';
          
        if (isset($_POST['parameter'])) {	
            $parameter = $_POST['parameter'];
            $data = json_decode(stripslashes($parameter), true);
            $order_number = isset($data['orderid']) ? $data['orderid'] : null;
            $payment_number = isset($data['payid']) ? $data['payid'] : null;
            $pay_status = isset($data['payStatus']) ? $data['payStatus'] : null;		
        }
			
        if ($order_number && $payment_number && $pay_status) {
            // 更新订单
            Iol_Translation_M_Iol_Translation_Order_Peer::updateByPayReturn($order_number, $payment_number, $pay_status);
        }
        // 回复API成功
        echo "{status:'success'}";
        exit;
    }

}