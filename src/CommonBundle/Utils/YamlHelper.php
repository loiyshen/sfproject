<?php

namespace CommonBundle\Utils;

use Symfony\Component\Yaml\Parser;

/**
 * Yaml Helper: Process Yaml file
 */
class YamlHelper
{
    // The Frontend config yaml file path
    //public static $frontendYamlPath = "../../app/config/";
    
    // The Frontend config yaml filename
    //public static $frontendYamlName = "parameters.yml";
    
    // The Admin config yaml file path
    public static $adminYamlPath = "../../app/config/admin/";
    
    // The Admin config yaml filename
    public static $adminYamlName = "parameters_admin.yml";
    
    // The key of the [parameters]
    public static $parametersKey = "parameters";

    /**
     * get the value of [parameters] by the key
     *
     * @return string $key Mcrypt Secret
     */
    public static function getValueFromParameters($key)
    {
        $parametersArray = self::getYamlParameterArray();
        $value = self::getValueFromArray($key, $parametersArray);
        return $value;
    }

    /**
     * Get the array of [parameters] from yaml file.
     *
     * @return array $parametersArray
     */
    public static function getYamlParameterArray ()
    {
        $yamlArray = self::parseYamlToArray();
        $key = self::$parametersKey;
        if($yamlArray && is_array($yamlArray)){
            $parametersArray = self::getValueFromArray($key, $yamlArray);
            return $parametersArray;
        }
        return FALSE;
    }

    /**
     * Get the value from array by the key.
     * 
     * @param string $key The key you need
     * @return mixed $value
     */
    public static function getValueFromArray ($key, $array)
    {
        if(array_key_exists($key, $array)){
            $value = $array[$key];
            return $value;
        }else{
            return FALSE;
        }
    }

    /**
     * Parse yaml file to a php array
     *
     * @return array $yamlArray
     */
    public static function parseYamlToArray ()
    {
        $parser = new Parser();
        $yamlStream = self::getYamlStream();
        if($yamlStream){
            $yamlArray = $parser->parse($yamlStream);
            return $yamlArray;
        }
        return FALSE;
    }

    /**
     * Get yaml file stream
     *
     * @return string $stream
     */
    public static function getYamlStream ()
    {
        $yamlFile = self::$adminYamlPath . self::$adminYamlName;
        if(file_exists($yamlFile)){
            $stream = file_get_contents($yamlFile);
            return $stream;
        }
        return FALSE;
    }
}
