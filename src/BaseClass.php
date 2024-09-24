<?php

namespace Imlolman\CCAvenue;

use Imlolman\CCAvenue\CCAvenue;

class BaseClass
{
    protected $config;

    public function __construct()
    {
        $this->config = CCAvenue::getInstance()->getConfig();
    }

    /*
     * Encrypt given plain text using the working key provided by CCAvenue
     * 
     * @param1 : Plain String
     * @return : Encrypted String
     */
    function encrypt($plainText)
    {
        $key = $this->hextobin(md5($this->config['WORKING_KEY']));
        $initVector = pack('C*', 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    /*
     * Decrypt given encrypted text using the working key provided by CCAvenue
     * 
     * @param1 : Encrypted String
     * @return : Plain String
     */
    function decrypt($encryptedText)
    {
        $key = $this->hextobin(md5($this->config['WORKING_KEY']));
        $initVector = pack('C*', 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    /**
     * Convert hex string to binary string
     * 
     * @param1 : Hex String
     * @return : Binary String
     */
    function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = '';
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack('H*', $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }
}
