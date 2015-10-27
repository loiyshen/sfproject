<?php

namespace CommonBundle\Utils;

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
    public static function _decrypt($encryptedText)
    {
        if ($encryptedText) {
            $secretKey = self::getSecretKey();
            $encryptedText = base64_decode($encryptedText);
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            $decryptText = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secretKey, $encryptedText, MCRYPT_MODE_ECB, $iv);
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
    public static function _encrypt($plainText)
    {
        $secretKey = self::getSecretKey();
        $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $encryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secretKey, $plainText, MCRYPT_MODE_ECB, $iv);
        $encryptedData = trim(base64_encode($encryptText));
        return $encryptedData;
    }

    /**
     * get mcrypt_secret from parameters.ini
     *
     * @return string $key Mcrypt Secret
     */
    public static function getSecretKey()
    {
        $parametersPath = '../../app/config/admin/parameters_admin.yml';
        $parametersArray = yaml_parse_file($parametersPath);
        $parameters = $parametersArray['parameters'];
        $secretKey = $parameters['mcrypt_secret'];
        return $secretKey;
    }

}
