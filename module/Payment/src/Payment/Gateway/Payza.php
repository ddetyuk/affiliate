<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payment\Gateway;

/**
 * Description of Payza
 *
 * @author ddetyuk
 */
class Payza
{

    protected $APIServer;
    protected $IPNSecurityCode;
    protected $IPNV2Handler;
    protected $username;
    protected $password;

    public function __construct($options)
    {
        $this->username        = $options['Username'];
        $this->password        = $options['Password'];
        $this->APIServer       = $options['APIServer'];
        $this->IPNSecurityCode = $options['IPNSecurityCode'];
        $this->IPNV2Handler    = $options['IPNV2Handler'];
    }

    public function sendMoney($amountPaid, $receiverEmail)
    {
        $data   = $this->buildSendMoneytVariables($amountPaid, 'USD', $receiverEmail, '', 0, '', '0');
        $result = $this->send($this->APIServer . 'sendmoney', $data);
        return $result;
    }

    public function getBalance()
    {
        $data   = $this->buildGetBalanceVariables('USD');
        $result = $this->send($this->APIServer . 'GetBalance', $data);
        return $result;
    }

    public function getIPNV2Handler($token)
    {
        $data   = "token=" + urlencode($token);
        $result = $this->send($this->IPNV2Handler, $data);
        if (strlen($result) > 0) {
            if (urldecode($result) != "INVALID TOKEN") {
                $result = urldecode($result);
                file_put_contents('data.txt', $result);
            }
        }
    }

    protected function EPDDecryptor($cypherText)
    {
        //Decode the base64 encoded text		
        $cypherText = base64_decode($cypherText);

        //Complete the key
        $key_add = 24 - strlen($this->IPNSecurityCode);
        $this->IPNSecurityCode .= substr($this->IPNSecurityCode, 0, $key_add);

        // use mcrypt library for encryption
        $decryptedText = mcrypt_decrypt(MCRYPT_3DES, $this->IPNSecurityCode, $cypherText, MCRYPT_MODE_CBC, 'payza');
        parse_str(trim($decryptedText, "\x00..\x1F"), $result);

        return $result;
    }

    protected function buildSendMoneytVariables($amountPaid = 0.00, $currency = 'USD', $receiverEmail = '', $senderEmail = '', $purchaseType = 0, $note = '', $testMode = '1')
    {
        $result = sprintf("USER=%s&PASSWORD=%s&AMOUNT=%s&CURRENCY=%s&RECEIVEREMAIL=%s&SENDEREMAIL=%s&PURCHASETYPE=%s&NOTE=%s&TESTMODE=%s", urlencode($this->username), urlencode($this->password), urlencode((string) $amountPaid), urlencode($currency), urlencode($receiverEmail), urlencode($senderEmail), urlencode((string) $purchaseType), urlencode((string) $note), urlencode((string) $testMode));
        return $result;
    }

    protected function buildGetBalanceVariables($currency)
    {
        $result = sprintf("USER=%s&PASSWORD=%s&CURRENCY=%s", urlencode($this->username), urlencode($this->password), urlencode($currency));
        return $result;
    }

    protected function send($url, $data)
    {
        $response = '';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

}
