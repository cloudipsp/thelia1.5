<?php

class fondycsl
{
    const RESPONCE_SUCCESS = 'success';
    const RESPONCE_FAIL = 'failure';
    const ORDER_SEPARATOR = '#';
    const SIGNATURE_SEPARATOR = '|';
    const ORDER_APPROVED = 'approved';
    const ORDER_DECLINED = 'declined';

    public static function do_pay($data)
    {
        if (is_callable('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.fondy.eu/api/checkout/url/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('request' => $data)));
            $result = json_decode(curl_exec($ch));
            curl_close($ch);
            return $result;

        } else {
            echo "Curl not found!";
            die;
        }
    }
    public static function getSignature($data, $password, $encoded = true)
    {
        $data = array_filter($data, function ($var) {
            return $var !== '' && $var !== null;
        });
        ksort($data);
        $str = $password;
        foreach ($data as $k => $v) {
            $str .= self::SIGNATURE_SEPARATOR . $v;
        }
        if ($encoded) {
            return sha1($str);
        } else {
            return $str;
        }
    }

    public static function isPaymentValid($oplataSettings, $response)
    {
        if ($oplataSettings['merchant'] != $response['merchant_id']) {
            return 'An error has occurred during payment. Merchant data is incorrect.';
        }

        $responseSignature = $response['signature'];
        if (isset($response['response_signature_string'])) {
            unset($response['response_signature_string']);
        }
        if (isset($response['signature'])) {
            unset($response['signature']);
        }
        if (fondycsl::getSignature($response, $oplataSettings['secretkey']) != $responseSignature) {
            return 'An error has occurred during payment. Signature is not valid.';
        }
        return true;
    }
}

?>