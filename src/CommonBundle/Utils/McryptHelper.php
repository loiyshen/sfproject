<?php

namespace CommonBundle\Utils;

use CommonBundle\Utils\YamlHelper;

/**
 * Mcrypt Helper: Encryption & Decryption use mcrypt
 */
class McryptHelper
{
    /**
     * Encrypt data use mcrypt
     *
     * @param string $data The data to be encrypted 
     * @return string $encryptedData The encrypted data
     */
    public static function mcryptEncrypt($data)
    {
        $data = trim($data);
        if(! $data){
            return FALSE;
        }
        $secretKey = self::getSecretKey();
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $encryptedData = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secretKey, $data, MCRYPT_MODE_ECB, $iv);
        $encryptedData = trim(base64_encode($encryptedData));
        return $encryptedData;
    }

    /**
     * Decrypt data use mcrypt
     * 
     * @param string $encryptedData encrypted data
     * @return string $data The decrypted data
     */
    public static function mcryptDecrypt($encryptedData)
    {
        if(! $encryptedData){
            return FALSE;
        }
        $secretKey = self::getSecretKey();
        $encryptedData = base64_decode($encryptedData);
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $decryptedData = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secretKey, $encryptedData, MCRYPT_MODE_ECB, $iv);
        $data = trim($decryptedData);
        return $data;
    }

    /**
     * Get mcrypt_secret from parameters.yml
     *
     * @return string $secretKey
     */
    public static function getSecretKey()
    {
        $secretKey = YamlHelper::getValueFromParameters('mcrypt_secret');
        return $secretKey;
    }
}