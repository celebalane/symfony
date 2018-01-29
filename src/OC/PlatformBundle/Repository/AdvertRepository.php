namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AdvertRepository extends EntityRepository{
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