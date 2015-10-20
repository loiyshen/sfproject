<?php

namespace Bundles\FrontendBundle\Util;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

class DoctrinePager extends Pager implements \Serializable
{

    protected
            $query = null,
            $tableMethodName = null,
            $tableMethodCalled = false;

    /* Hydration mode constants */

    /**
     * Hydrates an object graph. This is the default behavior.
     */
    const HYDRATE_OBJECT = 1;

    /**
     * Hydrates an array graph.
     */
    const HYDRATE_ARRAY = 2;

    /**
     * Hydrates a flat, rectangular result set with scalar values.
     */
    const HYDRATE_SCALAR = 3;

    /**
     * Hydrates a single scalar value.
     */
    const HYDRATE_SINGLE_SCALAR = 4;

    /**
     * Very simple object hydrator (optimized for performance).
     */
    const HYDRATE_SIMPLEOBJECT = 5;

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
     * Returns a query for counting the total results.
     *
     * @return Doctrine_Query
     */
    private function getCountQuery()
    {
        $qb = clone $this->queryBuilder;
        $qb->select('COUNT(' . $qb->getRootAlias() . '.id)')->setFirstResult(null)->setMaxResults(null);
        return $qb->getQuery();
    }

    /**
     * @see Pager
     */
    public function init()
    {
        $this->resetIterator();
        $countQuery = $this->getCountQuery();
        try {
            $count = (int) $countQuery->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $result = $this->queryBuilder->getQuery()->getArrayResult();
            $count = count($result);
        }
        $this->setNbResults($count);

        $this->queryBuilder->setFirstResult(null)->setMaxResults(null);
        if (0 == $this->getPage() || 0 == $this->getMaxPerPage() || 0 == $this->getNbResults()) {
            $this->setLastPage(0);
        } else {
            $offset = ($this->getPage() - 1) * $this->getMaxPerPage();
            $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));
            $this->queryBuilder->setFirstResult($offset)->setMaxResults($this->getMaxPerPage());
        }
    }

    /**
     * @see Pager
     */
    public function initArrayResult()
    {
        $this->resetIterator();
        $countQuery = $this->getCountQuery();
        try {
            $count = count($countQuery->getArrayResult());
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new \Exception($e->getMessage());
        }
        $this->setNbResults($count);

        $this->queryBuilder->setFirstResult(null)->setMaxResults(null);
        if (0 == $this->getPage() || 0 == $this->getMaxPerPage() || 0 == $this->getNbResults()) {
            $this->setLastPage(0);
        } else {
            $offset = ($this->getPage() - 1) * $this->getMaxPerPage();
            $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));
            $this->queryBuilder->setFirstResult($offset)->setMaxResults($this->getMaxPerPage());
        }
    }

    /**
     * Get the query for the pager.
     *
     * @return Doctrine_Query
     */
    public function getQuery()
    {
        return $this->queryBuilder->getQuery();
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
        $queryForRetrieve = clone $this->getQuery();
        $query->setFirstResult($offset - 1)->setMaxResults(1);
        $results = $queryForRetrieve->execute();

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
        return $this->getQuery()->execute(array(), $hydrationMode);
    }

    /**
     * @see Pager
     */
    protected function initializeIterator()
    {
        parent::initializeIterator();

        /* if ($this->results instanceof Doctrine_Collection)
          {
          $this->results = $this->results->getData();
          } */
    }

}
