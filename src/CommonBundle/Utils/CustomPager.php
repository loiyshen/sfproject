<?php

namespace Bundles\FrontendBundle\Util;

abstract class CustomPager extends Pager
{

    protected
            $sql = '',
            $countSql = '',
            $querySql = '',
            $em = null;

    /**
     * Constructor.
     *
     * @param string  $class      The model class
     * @param integer $maxPerPage Number of records to display per page
     */
    public function __construct($em, $sql, $countSql, $maxPerPage = 10/* ,$class=null */)
    {
        //$this->setClass($class);
        $this->em = $em;
        $this->sql = $sql;
        $this->countSql = $countSql;
        $this->setMaxPerPage($maxPerPage);
        //$this->parameterHolder = new sfParameterHolder();
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function setSql($sql = '')
    {
        $this->sql = $sql;
    }

    public function getCountSql()
    {
        return $this->countSql;
    }

    public function setCountSql($sql = '')
    {
        $this->countSql = $sql;
    }

    public function getQuerySql()
    {
        return $this->querySql;
    }

    public function setQuerySql($querySql = '')
    {
        $this->querySql = $querySql;
    }

}
