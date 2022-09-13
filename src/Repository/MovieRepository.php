<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //* DQL : Doctrine Query Language
    // Movies triés par leur titre en ordre alphabétique
    public function findAllMoviesByTitleAscDQL()
    {
        $entityManager = $this->getEntityManager();

        
        $query = $entityManager->createQuery('
            SELECT m FROM App\Entity\Movie m ORDER BY m.title ASC
        ');

        return $query->getResult();
    }

    public function findAllMoviesVisible()
    {
        
        $results = $this->createQueryBuilder('m') 
            ->getQuery()
            ->getResult();
            

        return $results;
    }

    //* DQL : Doctrine Query Language
    //Movies triés par genre
    public function findMoviesByGenre($id)
    {
        $sql = "SELECT * FROM movie
        INNER JOIN movie_genre ON movie.id = movie_id
        INNER JOIN genre ON genre.id = genre_id
        WHERE genre.id = " . $id;

        $dbal = $this->getEntityManager()->getConnection();
        $statement = $dbal->prepare($sql);
        $result = $statement->executeQuery();

        

        return $result->fetchAllAssociative();
    }

    //* Query Builder
    // Movies triés par leur titre en ordre alphabétique
    public function findAllMoviesByTitleAscQb()
    {
        
        $results = $this->createQueryBuilder('m') 
            ->orderBy('m.title', 'ASC') 
            ->getQuery()
            ->getResult();

        return $results;
    }

    // 10 films/Séries les plus récents
    public function findLastestByReleaseDateV1()
    {   
        $results = $this->createQueryBuilder('m') 
            ->orderBy('m.releaseDate', 'desc')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $results;
    }

    // ou 

    
    public function findLastestByReleaseDate()
    {
        
        $queryBuilder = $this->createQueryBuilder('m') ;

        
        $queryBuilder = $queryBuilder->orderBy('m.releaseDate', 'DESC') ;

        
        $queryBuilder = $queryBuilder->setMaxResults(10);

        
        $query = $queryBuilder->getQuery();

        
        $results = $query->getResult();

        return $results;
    }

    
    public function findRandomMovie(): Movie
    {
        // TODO : random en SQL : https://sql.sh/fonctions/rand
        
        $allMovie = $this->findAll();
        
        $randomMovie = $allMovie[array_rand($allMovie)];
        return $randomMovie;
        
    }


        

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

