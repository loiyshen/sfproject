<?php

namespace CommonBundle\EntityRepository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use CommonBundle\Utils\DoctrinePager;
use CommonBundle\Utils\SQLPager;

class AbstractRepository extends EntityRepository
{

    const ENTITY_BUNDLE = "CommonBundle";

    protected $nowDate;
    
    /**
     * @var Query
     */
    protected $query;

    /**
     * Gets the EntityRepository for an entity.
     * @param string $entityName The name of the entity.
     * @return EntityRepository
     */
    public function getRepository($entityName)
    {
        $entityName = self::ENTITY_BUNDLE . ":{$entityName}";
        return $this->getEntityManager()->getRepository($entityName);
    }

    /**
     * Get the result in array format
     * @param QueryBuilder $queryBuilder
     * @return array
     */
    public function getArrayResult(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->getQuery()->getArrayResult();
    }

    /**
     * Get exactly one result or null.
     * @param int $hydrationMode
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function getOneOrNullResult(QueryBuilder $queryBuilder, $hydrationMode = Query::HYDRATE_ARRAY)
    {
        return $queryBuilder->getQuery()->getOneOrNullResult($hydrationMode);
    }

    /**
     * Get the Object of the primary key given.
     * @param int $id primary key
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function getObjectById($id)
    {
        $primaryKeyName = $this->getClassMetadata()->getIdentifierFieldNames();
        if (!$primaryKeyName || !$id) {
            return NULL;
        }

        $query = $this->createQueryBuilder('ab')
                ->select('ab')
                ->where("ab.deletedAt IS NULL or ab.deletedAt = '0000-00-00 00:00:00'");
        if (is_int($id)) {
            $query->andWhere('ab.' . $primaryKeyName[0] . '=' . $id);
        } else {
            $query->andWhere("ab." . $primaryKeyName[0] . "='" . $id . "'");
        }
        $object = $query->getQuery()->getOneOrNullResult(Query::HYDRATE_OBJECT);
        return $object;
    }

    /**
     * Get DoctrinePager Data List
     *
     * @return DoctrinePager $pagerDataList
     */
    public function getPagerData($query, $where)
    {
        $pagerDataList = new DoctrinePager($query);
        $nbLinks = isset($where['nbLinks']) ? $where['nbLinks'] : $this->container->getParameter('twig.parking_list_nblinks');
        $pagesSize = isset($where['pageSize']) ? $where['pageSize'] : $this->container->getParameter('twig.select_empty_pageSize');
        $curPage = isset($where['cur_page']) ? $where['cur_page'] : 1;
        $pagerDataList->setNbLinks($nbLinks);
        $pagerDataList->setMaxPerPage($pagesSize);
        $pagerDataList->setPage($curPage);
        return $pagerDataList;
    }

    /**
     * Get DoctrinePager Data List
     *
     * @return DoctrinePager $pagerDataList
     */
    public function getPagerDataList($query, $where)
    {
        $pagerDataList = $this->getPagerData($query, $where);
        $pagerDataList->init();
        return $pagerDataList;
    }

    /**
     * Get DoctrinePager Data Array List
     *
     * @return DoctrinePager $pagerDataList
     */
    public function getPagerDataArrayList($query, $where)
    {
        $pagerDataList = $this->getPagerData($query, $where);
        $pagerDataList->initArrayResult();
        return $pagerDataList;
    }
    
    /**
     * Get Pager Data via SQL
     *
     * @return DoctrinePager $sqlPagerDataList
     */
    public function getSQLPagerData($sql,$countSql,$where)
    {
        $sqlPagerDataList = new SQLPager($this->getEntityManager(),$sql,$countSql);
        $nbLinks = isset($where['nbLinks']) ? $where['nbLinks'] : $this->container->getParameter('twig.parking_list_nblinks');
        $pagesSize = isset($where['pageSize']) ? $where['pageSize'] : $this->container->getParameter('twig.select_empty_pageSize');
        $curPage = isset($where['cur_page']) ? $where['cur_page'] : 1;
        $sqlPagerDataList->setNbLinks($nbLinks);
        $sqlPagerDataList->setMaxPerPage($pagesSize);
        $sqlPagerDataList->setPage($curPage);
        return $sqlPagerDataList;
    }
    
    /**
     * Get Pager Data in array format via SQL
     *
     * @return DoctrinePager $sqlPagerDataList
     */
    public function getSQLPagerDataArrayList($sql,$countSql,$where)
    {
        $sqlPagerDataList = $this->getSQLPagerData($sql,$countSql,$where);
        $sqlPagerDataList->init();
        return $sqlPagerDataList;
    }
}
