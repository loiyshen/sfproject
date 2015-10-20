<?php

namespace Bundles\FrontendBundle\Util;

class EntityHelper
{

    const ENTITY_NAMESPACE = 'Bundles\FrontendBundle\Entity\\';
    const VALIDATE_ENTITY_NAMESPACE = 'Bundles\FrontendBundle\EntityValidate\\';

    /**
     * 指定された実体の実例を取得する
     * @param string|object         $entity     実体類類名の文字列または実体類の実例.
     * @param array|validateEntity  $initArray  データを初期化するまたは実体類の実例をチェックする.
     * @return object
     */
    public static function getEntityInstance($entity, $initData = array())
    {
        if (is_string($entity)) {
            $entityName = self::ENTITY_NAMESPACE . $entity;
            $instance = new $entityName();
        } else {
            $instance = $entity;
        }
        $reflectionClass = new \ReflectionClass($instance);

        if (is_array($initData)) {
            foreach ($initData as $name => $value) {
                $methodName = "set" . ucfirst($name);
                if ($reflectionClass->hasMethod($methodName) && $reflectionClass->hasProperty($name)) {
                    if (is_object($value)) {
                        $instance->$methodName($value);
                    } elseif ($value === NULL && !preg_match('#\DateTime#', $reflectionClass->getProperty($name)->getDocComment())) {
                        $instance->$methodName($value);
                    } elseif (!preg_match('#' . preg_quote(self::ENTITY_NAMESPACE) . '#', $reflectionClass->getProperty($name)->getDocComment()) && !preg_match('#\DateTime#', $reflectionClass->getProperty($name)->getDocComment())) {
                        $instance->$methodName($value);
                    }
                }
            }
        } elseif (is_object($initData)) {
            $InitDataReflectionClass = new \ReflectionClass($initData);
            $initProps = $InitDataReflectionClass->getProperties();
            foreach ($initProps as $initProp) {
                $name = $initProp->getName();
                $methodName = "set" . ucfirst($name);
                if ($reflectionClass->hasMethod($methodName) && $reflectionClass->hasProperty($name)) {
                    $value = $initProp->getValue($initData);
                    if (is_object($value)) {
                        $instance->$methodName($value);
                    } elseif ($value === NULL && !preg_match('#\DateTime#', $reflectionClass->getProperty($name)->getDocComment())) {
                        $instance->$methodName($value);
                    } elseif (!preg_match('#' . preg_quote(self::ENTITY_NAMESPACE) . '#', $reflectionClass->getProperty($name)->getDocComment()) && !preg_match('#\DateTime#', $reflectionClass->getProperty($name)->getDocComment())) {
                        $instance->$methodName($value);
                    }
                }
            }
        }

        return $instance;
    }

    /**
     * 指定された認証実体類の実例を取得する
     * @param string|validateEntity $entity     実体類の類名文字列と実例をチェックする.
     * @param array|object          $initArray  データまたは実体類を初期化する.
     * @return object
     */
    public static function getValidateEntityInstance($entity, $initData = array())
    {
        if (is_string($entity)) {
            $entityName = self::VALIDATE_ENTITY_NAMESPACE . $entity;
            $instance = new $entityName();
        } else {
            $instance = $entity;
        }
        $reflectionClass = new \ReflectionClass($instance);

        if (is_array($initData)) {
            foreach ($initData as $name => $value) {
                if ($reflectionClass->hasProperty($name)) {
                    if (is_scalar($value) || $value === NULL) {
                        $instance->$name = $value;
                    }
                }
            }
        } elseif (is_object($initData)) {
            $InitDataReflectionClass = new \ReflectionClass($initData);
            $initProps = $InitDataReflectionClass->getProperties();
            foreach ($initProps as $initProp) {
                $name = $initProp->getName();
                $methodName = "get" . ucfirst($name);
                if ($reflectionClass->hasProperty($name) && $InitDataReflectionClass->hasMethod($methodName)) {
                    $value = $initData->$methodName();
                    if (is_scalar($value) || $value === NULL) {
                        $instance->$name = $value;
                    }
                }
            }
        }

        return $instance;
    }

}
