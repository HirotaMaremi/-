<?php

declare(strict_types=1);
namespace App\Repository;

use App\Entity\CustomerMst;
use App\Entity\Estimation;
use App\Entity\EstimationStatus;
use App\Entity\Organization;
use App\Exception\DBLogicException;
use App\Repository\Common\LikeTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\TransactionRequiredException;

class CustomerMstRepository extends ServiceEntityRepository
{
    use LikeTrait;

    private const FUZZY_MODE = 1;
    private const STRICT_MODE = 2;

    /**
     * CustomerMstRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerMst::class);
    }

    /**
     * IDで検索する。
     * @param int $id
     * @param int|null $lockMode
     * @return CustomerMst|null
     */
    public function findById(int $id, ?int $lockMode = null): ?CustomerMst
    {
        try {
            $q = $this->createQueryBuilder('cm')
                ->andWhere('cm.id = :id')->setParameter('id', $id)
                ->getQuery();
            if ($lockMode) {
                $q->setLockMode($lockMode);
            }

            return $q->getOneOrNullResult();
        } catch (NonUniqueResultException|TransactionRequiredException  $e) {
            throw new DBLogicException('', 0, $e);
        }
    }

    /**
     * @param int $id
     * @param string $hash
     * @return CustomerMst|null
     */
    public function findByIdHash(int $id, string $hash): ?CustomerMst
    {
        try {
            return $this->createQueryBuilder('cm')
                ->andWhere('cm.id = :id')->setParameter('id', $id)
                ->andWhere('cm.hash = :hash')->setParameter('hash', $hash)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new DBLogicException('', 0, $e);
        }
    }

    /**
     * フィルタ検索用のQueryを生成する。
     * @param array $filter
     * @param Organization $org
     * @return Query
     */
    public function createFilterQuery(array $filter = [], ?Organization $org = null): Query
    {
        $qb2 = $this->createQueryBuilder('cm_sub')
            ->select('COUNT(e_sub.id)')
            ->leftJoin('App:Estimation', 'e_sub', 'WITH', 'e_sub.customerMst = cm_sub.id')
            ->leftJoin('e_sub.estimationStatus', 'es_sub')
            ->andWhere('es_sub.id IN (:afterEstimations)')
            ->andWhere('cm_sub.id = cm.id')
        ;

        $qb = $this->createQueryBuilder('cm')
            ->select('cm customer, COUNT(des.id) design_qty')
            ->addSelect(sprintf('(%s) AS order_qty', $qb2->getDQL()))
            ->addSelect("REPLACE(cm.tel, '-', '') AS tel_no, REPLACE(cm.mobilePhone, '-', '') AS mobile_no, REPLACE(cm.fax, '-', '') AS fax_no")
            ->addSelect("CONCAT(u.nameLast, COALESCE(u.nameFirst, '')) AS user_name")
            ->distinct()
            ->leftJoin('App:Estimation', 'e', 'WITH', 'e.customerMst = cm.id')
            ->leftJoin('e.workEstimations', 'we')
            ->leftJoin('App:DesignWorkEstimationRelation', 'rel', 'WITH', 'rel.workEstimation = we.id')
            ->leftJoin('rel.design', 'des')
            ->leftJoin('e.estimationStatus', 'es')
            ->join('cm.createdDepartment', 'd')
            ->join('App:User', 'u', 'WITH', 'cm.createdBy = u.id')
            ->setParameter('afterEstimations', [EstimationStatus::ID_ORDERED, EstimationStatus::ID_DELIVERED])
            ->groupBy('cm')
        ;

        $this->listFilters($filter, $qb);

        return $qb->getQuery();
    }

    /**
     * @param array $filter
     * @param QueryBuilder $qb
     * @param int|null $mode
     */
    private function listFilters(array $filter, QueryBuilder $qb, ?int $mode = self::FUZZY_MODE)
    {
        if ($v = @$filter['id']) {
            $qb->andWhere('cm.id IN (:id)')->setParameter('id', explode(',', $v));
        }
        if ($v = @$filter['nameLast']) {
            $qb->andWhere('cm.nameLast LIKE :nameLast')->setParameter('nameLast', $this->partialMatch(trim($v)));
        }
        if ($v = @$filter['nameFirst']) {
            $qb->andWhere('cm.nameFirst LIKE :nameFirst')->setParameter('nameFirst', $this->partialMatch(trim($v)));
        }
        if ($v = @$filter['nameLastKana']) {
            $qb->andWhere('cm.nameLastKana LIKE :nameLastKana')->setParameter('nameLastKana', $this->partialMatch(trim($v)));
        }
        if ($v = @$filter['nameFirstKana']) {
            $qb->andWhere('cm.nameFirstKana LIKE :nameFirstKana')->setParameter('nameFirstKana', $this->partialMatch(trim($v)));
        }
        if ($v = @$filter['corporateName']) {
            $qb->andWhere('cm.corporateName LIKE :corporateName')->setParameter('corporateName', $this->partialMatch($v));
        }
        if ($v = @$filter['orderTimesFrom']) {
            $qb->andHaving('order_qty >= :orderTimesFrom')
                ->setParameter('orderTimesFrom', $v);
        }
        if ($v = @$filter['orderTimesTo']) {
            $qb->andHaving('order_qty <= :orderTimesFrom')
                ->setParameter('orderTimesFrom', $v);
        }
        if ($v = @$filter['priceFrom']) {
            $qb->andWhere('e.totalPrice >= :priceFrom')->setParameter('priceFrom', $v);
        }
        if ($v = @$filter['priceTo']) {
            $qb->andWhere('e.totalPrice <= :priceTo')->setParameter('priceTo', $v);
        }
        if ($mode == self::FUZZY_MODE) {
            if ($v = @$filter['tel']) {
                $v = str_replace('-', '', $v);
                $qb->andHaving('tel_no = :tel OR mobile_no = :tel OR fax_no = :tel')
                    ->setParameter('tel', $v);
            }
            if ($v = @$filter['contactEmail']) {
                $qb->andWhere('cm.contactEmail = :contactEmail OR cm.contactEmail2 = :contactEmail OR cm.contactEmail3 = :contactEmail')->setParameter('contactEmail', $v);
            }
        }
    }
}
