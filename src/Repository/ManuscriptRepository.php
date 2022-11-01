<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Manuscript;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ManuscriptRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ManuscriptRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Manuscript::class);
    }

    public function indexQuery(){
        $qb = $this->createQueryBuilder('e');
        $qb->select('e');
        return $qb;
    }

    public function typeaheadQuery($q) {
        $qb = $this->createQueryBuilder('e');
        $qb->where('e.title LIKE :q');
        $qb->orWhere('e.callNumber like :q');
        $qb->orderBy('e.title');
        $qb->setParameter('q', "%{$q}%");

        return $qb->getQuery();
    }

    public function searchQuery($q = null, $digitized = null) {
        $qb = $this->createQueryBuilder('e');
        $matches = [];
        if ($q && preg_match('/^\s*"(.*?)"\s*$/u', $q, $matches)) {
            $qb->where('e.callNumber LIKE :q');
            $qb->orWhere('e.title like :q');
            $qb->setParameter('q', "%${matches[1]}%");
        } else if($q) {
            $qb->where('MATCH(e.callNumber, e.description, e.format) AGAINST (:q BOOLEAN) > 0.0');
            $qb->setParameter('q', $q);
        }
        if($digitized === 'yes') {
            $qb->andWhere('e.digitized = 1');
        }
        $qb->orderBy('e.callNumber', 'ASC');
        return $qb;
    }

    public function getSortedResult($qb, $sort){
        if (! $sort ){
            return $qb->getQuery();
        }
        switch ($sort) {
            case 'title_asc':
                $qb->addOrderBy('e.title', 'ASC');
                return $qb->getQuery();
            case 'title_desc':
                $qb->addOrderBy('e.title', 'DESC');
                return $qb->getQuery();
            case 'callNumber_desc':
                $qb->addOrderBy('e.callNumber', 'DESC');
                return $qb->getQuery();
            case 'periods_asc':
                $results = $qb->getQuery()->getResult();
                uasort($results, function($a, $b){
                    $ay = $a->getEarliestYear();
                    $by = $b->getEarliestYear();
                    if ($ay == 0) {
                        return 1;
                    }
                    if ($by == 0){
                        return -1;
                    }
                    if ($ay == $by){
                        if ($a->getLatestYear() > $b->getLatestYear()){
                            return 1;
                        }
                    }
                    return $ay <=> $by;
                });
                return $results;
            default:
                $qb->addOrderBy('e.callNumber', 'ASC');
                return $qb->getQuery();
        }
    }

    public function getActiveFilters($form){
        $active = [];
        if ($form->getData()){
            foreach ($form->getData() as $key => $value){
                if ($value == null){
                    continue;
                }
                if (is_array($value)){
                    $flat = self::array_flatten($value);
                    if (reset($flat) instanceof \Doctrine\Common\Collections\ArrayCollection){
                        $values = reset($flat);
                        if (count($values) > 0){
                            $active[$key] = $values;
                        }
                    }
                    continue;
                }
                if ($value instanceof \Traversable){
                    if (count($value) > 0){
                        $active[$key] = $value->toArray();
                    }
                    continue;
                };
                if (is_string($value)){
                    $active[$key] = $value == 'y' ? 'True' : 'False';
                    continue;
                };
                $active[$key] = array($value);
            };
        };
        return $active;
    }

    public function array_flatten($array) {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }
        return $result;
    }


}
