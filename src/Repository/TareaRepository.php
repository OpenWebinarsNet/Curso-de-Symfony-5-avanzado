<?php

namespace App\Repository;

use App\Entity\Tarea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Tarea|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tarea|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tarea[]    findAll()
 * @method Tarea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TareaRepository extends ServiceEntityRepository
{
    private $usuario;

    public function __construct(Security $security, ManagerRegistry $registry)
    {
        parent::__construct($registry, Tarea::class);
        $this->usuario = $security->getUser();
    }

    /*public function buscarTodas($pagina = 1, $elementos_por_pagina = 5) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT t
            FROM App\Entity\Tarea t
            WHERE t.usuario = :usuario
            ORDER BY t.creadoEn DESC'
        )->setParameter('usuario', $this->usuario);

        return $this->paginacion($query, $pagina, $elementos_por_pagina);
    }*/

    public function buscarTodas($pagina = 1, $elementos_por_pagina = 5)
    {
        $query = $this->createQueryBuilder('t')
            ->addOrderBy('t.creadoEn', 'DESC')
            ->andWhere('t.usuario = :usuario')
            ->setParameter('usuario', $this->usuario)
            ->getQuery();

        return $this->paginacion($query, $pagina, $elementos_por_pagina);
    }

    public function paginacion($dql, $pagina = 1, $elementos_por_pagina = 5)
    {
        $paginador = new Paginator($dql);
        $paginador->getQuery()
            ->setFirstResult($elementos_por_pagina * ($pagina - 1))
            ->setMaxResults($elementos_por_pagina);
        return  $paginador;
    }

    // /**
    //  * @return Tarea[] Returns an array of Tarea objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tarea
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
