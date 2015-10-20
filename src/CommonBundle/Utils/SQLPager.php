<?php

namespace Bundles\FrontendBundle\Util;

class SQLPager extends CustomPager implements \Serializable
{

    protected
            $query = null,
            $tableMethodName = null,
            $tableMethodCalled = false;

    /**
     * Get the name of the table method used to retrieve the query object for the pager
     *
     * @return string $tableMethodName
     */
    public function getTableMethod()
    {
        return $this->tableMethodName;
    }

    /**
     * Set the name of the table method used to retrieve the query object for the pager
     *
     * @param string $tableMethodName
     * @return void
     */
    public function setTableMethod($tableMethodName)
    {
        $this->tableMethodName = $tableMethodName;
    }

    /**
     * Serialize the pager object
     *
     * @return string $serialized
     */
    public function serialize()
    {
        $vars = get_object_vars($this);
        unset($vars['query']);
        return serialize($vars);
    }

    /**
     * Unserialize a pager object
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $array = unserialize($serialized);

        foreach ($array as $name => $values) {
            $this->$name = $values;
        }

        $this->tableMethodCalled = false;
    }

    /**
     * @see Pager
     */
    public function init()
    {
        $this->resetIterator();
        $countSql = $this->getCountSql();
        $result = $this->execute($countSql, 'one');
        $count = (int) ($result['count']);
        $this->setNbResults($count);
        if (0 == $this->getPage() || 0 == $this->getMaxPerPage() || 0 == $this->getNbResults()) {
            $this->setLastPage(0);
            $this->setQuerySql($this->getSql());
        } else {
            $offset = ($this->getPage() - 1) * $this->getMaxPerPage();
            $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));
            $this->setQuerySql($this->getSql() . 'LIMIT ' . $offset . ',' . $this->getMaxPerPage());
        }
    }

    /**
     * Retrieve the object for a certain offset
     *
     * @param integer $offset
     *
     * @return Doctrine_Record
     */
    protected function retrieveObject($offset)
    {
        $this->setQuerySql($this->getSql() . 'LIMIT ' . ($offset - 1) . ',1');
        $results = $this->execute($this->getQuerySql());
        return $results[0];
    }

    /**
     * Get all the results for the pager instance
     *
     * @param mixed $hydrationMode A hydration mode identifier
     *
     * @return Doctrine_Collection|array
     */
    public function getResults($hydrationMode = null)
    {
        return $this->execute($this->getQuerySql(), 'all');
    }

    public function execute($sql, $mode = 'all')
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $query = $connection->prepare($sql);
        $query->execute();
        $results = $mode == 'one' ? $query->fetch(\PDO::FETCH_ASSOC) : $query->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * @see Pager
     */
    protected function initializeIterator()
    {
        parent::initializeIterator();
    }

}
