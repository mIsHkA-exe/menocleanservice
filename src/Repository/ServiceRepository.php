<?php

namespace App\Repository;

use App\Entity\Service;
use App\Enum\CategorieService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * Tous les services visibles sur le site, dans l'ordre d'affichage voulu.
     *
     * @return Service[]
     */
    public function findActifsOrdonnes(): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.estActif = :actif')
            ->setParameter('actif', true)
            ->orderBy('s.position', 'ASC')
            ->addOrderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Les catégories réellement utilisées par au moins un service actif.
     * Évite d'afficher un bouton de filtre qui ne renverrait aucun résultat.
     *
     * @return CategorieService[]
     */
     public function findCategoriesUtilisees(): array
    {
        // getSingleColumnResult() renvoie des chaînes brutes, pas des énumérations :
        // la conversion enumType ne s'applique qu'à l'hydratation d'entités.
        $valeursUtilisees = $this->createQueryBuilder('s')
            ->select('DISTINCT s.categorie')
            ->andWhere('s.estActif = :actif')
            ->setParameter('actif', true)
            ->getQuery()
            ->getSingleColumnResult();

        // On repart des cas de l'énumération et on ne garde que ceux utilisés.
        return array_values(array_filter(
            CategorieService::cases(),
            static fn (CategorieService $categorie): bool => in_array($categorie->value, $valeursUtilisees, true)
        ));
    }
}