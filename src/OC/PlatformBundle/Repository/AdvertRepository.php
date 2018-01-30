<?php
namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertRepository extends EntityRepository{

  public function getAdverts($page, $nbPerPage){  //arguments pour la pagination
    $query= $this->createQueryBuilder('a')
                 ->leftJoin('a.image', 'i')  
                 ->addSelect('i')
                 ->leftJoin('a.categories', 'c')
                 ->addSelect('c')
                 ->orderBy('a.date', 'DESC')
                 ->getQuery();

    $query->setFirstResult(($page-1)*$nbPerPage) //Commencement
          ->setMaxResults($nbPerPage); //Nb par page

    return new Paginator($query, true);
  }

	public function getAdvertWithCategories(array $categoryNames){
    $qb = $this->createQueryBuilder('a');

    // On fait une jointure avec l'entité Category avec pour alias « c »
    $qb->innerJoin('a.categories', 'c')
      ->addSelect('c');

    // Puis on filtre sur le nom des catégories à l'aide d'un IN
    $qb->where($qb->expr()->in('c.name', $categoryNames));

    return $qb->getQuery()
      ->getResult();
  }

   public function getApplicationsWithAdvert($limit){
    $qb = $this->createQueryBuilder('a');

    $qb->innerJoin('a.advert', 'adv')
      ->addSelect('adv');

    $qb->setMaxResults($limit);

    return $qb->getQuery()
      ->getResult();
  }
}