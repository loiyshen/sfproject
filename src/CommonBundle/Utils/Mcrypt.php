<?php

namespace Bundles\FrontendBundle\Util;

/**
 * Mcrypt Class
 */
class Mcrypt
{

    /**
     * Decryption
     * 
     * @param string $encryptedText encrypted data
     * @param string $key Mcrypt Secret
     * @return string
     */
    public static function _decrypt($encryptedText, $key = null)
    {
        if ($encryptedText) {
            $key = $key === null ? self::readMcryptSecretKey() : $key;
            $encryptedText = base64_decode($encryptedText);
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            $decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $encryptedText, MCRYPT_MODE_ECB, $iv);
            return trim($decryptText);
        } else {
            return '';
        }
    }

    /**
     * Encryption
     *
     * @param string $plainText	data to be encrypted 
     * @param string $key Mcrypt Secret
     * @return string $encryptedData encrypted data
     */
    public static function _encrypt($plainText, $key = null)
    {
        $key = $key === null ? self::readMcryptSecretKey() : $key;
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $encryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plainText, MCRYPT_MODE_ECB, $iv);
        $encryptedData = trim(base64_encode($encryptText));
        return $encryptedData;
    }

    /**
     * get mcrypt_secret from parameters.ini
     *
     * @return string $key Mcrypt Secret
     */
    public static function readMcryptSecretKey()
    {
        $configPath = '../../app/config/parameters.ini';
        $parametersArray = parse_ini_file($configPath);
        $key = $parametersArray['mcrypt_secret'];
        return $key;
    }

}
