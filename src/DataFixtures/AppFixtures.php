<?php

namespace App\DataFixtures;

use App\Entity\Casting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Movie;
use App\Entity\Season;
use App\Entity\Genre;
use App\Entity\Person;

use App\DataFixtures\Provider\OflixProvider;

use Faker;

use Doctrine\DBAL\Connection;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    

    //! Si lors de la suppression des données dans la BDD, il y a des erreurs
    //! On peut créer une méthode truncate() pour permettre la suppression guidée des données 

    // On y stockera notre objet de connexion à la BDD
    private $connection;
    
    
    protected $slugger;


    public function __construct(Connection $connection, SluggerInterface $slugger)
    {
        // On récupère la connexion à la BDD (DBAL ~= PDO)
        // pour exécuter des requêtes manuelles en SQL pur
        $this->connection = $connection;
        $this->slugger = $slugger;
    }

    /**
     * Permet de TRUNCATE les tables et de remettre les Auto-incréments à 1
     */
    private function truncate()
    {
        // On passe en mode SQL ! On cause avec MySQL
        // Désactivation la vérification des contraintes FK
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // On tronque
        $this->connection->executeQuery('TRUNCATE TABLE casting');
        $this->connection->executeQuery('TRUNCATE TABLE genre');
        $this->connection->executeQuery('TRUNCATE TABLE movie');
        $this->connection->executeQuery('TRUNCATE TABLE movie_genre');
        $this->connection->executeQuery('TRUNCATE TABLE person');
        $this->connection->executeQuery('TRUNCATE TABLE season');
        // etc.
        // Réactivation la vérification des contraintes FK
        $this->connection->executeQuery('SET foreign_key_checks = 1');
    }

    /**
     * Méthode exécutée lorsqu'on tape la commande : 
     * php bin/console doctrine:fixtures:load
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager ): void
    {

        //! d'abord, on vide les tables de leurs données
        $this->truncate();

        // Notre data Provider
        $oflixProvider = new OflixProvider();

        // Instaciation de l'usine de Faker
        $faker = Faker\Factory::create('fr_FR');
        
        //# Créons des films factices pour notre BDD

        $moviesList = [];

        for ($i=1; $i<30; $i++) {

            // nouvelle instance de l'entité Movie
            $movie = new Movie();

            $movie->setTitle($oflixProvider->getRandomMovieTitle());

            // la variable $type aura comme valeur soit série soit film
            // de manière aléatoire à chaque boucle
            $type = ( mt_rand(1,2) === 1 ) ? "série" : "film";
            $movie->setType($type);

            $movie->setSummary($faker->paragraph(2));

            $movie->setSynopsis($faker->realText(300));

            // Faker va nous générer une date aléatoire entre aujourd'hui et il y a 80 ans
            //?https://fakerphp.github.io/formatters/date-and-time/#datetimebetween
            $movie->setReleaseDate($faker->dateTimeBetween('-80 years'));

            $duration = $faker->numberBetween(30,240);
            $movie->setDuration($duration);

            $movie->setPoster('https://picsum.photos/id/'.mt_rand(1, 100).'/450/300');

            // Pour avoir une note avec des nombres décimaux
            //?https://fakerphp.github.io/formatters/numbers-and-strings/#randomfloat
            $rating = $faker->randomFloat(1, 1, 5);
            $movie->setRating($rating);
            // utilisation de slug pour slugger le titre du film
           // $movie->setSlug(strtolower($this->slugger->slug($movie->getTitle())));
            // ou  
            //$movieSlug = $this->slugger->slug($movie->getTitle())->lower();
            //$movie->setSlug($movieSlug);

             $movie->setSlug(strtolower($this->slugger->slug($movie->getTitle())->lower()));
            
            
            $moviesList[] = $movie;
            //array_push($moviesList, $movie);

            $manager->persist($movie);
        }

        //#Season

        // Je parcours la liste des films/séries générées précédemment
        foreach($moviesList as $key => $movie)
        {
            if ($movie->getType() === 'série' )
            {
                // cette portion de code ne s'exécute que lorsque le $movie courant est de type "série"

                // je génère pour cette série un nombre de saison aléatoirement compris entre 1 et 10 saisons
                // je le stocke dans nbMaxSeasons
                $nbMaxSeasons = mt_rand(1,10);

                // maintenant que j'ai le nombre de saisons pour ma série, 
                // je vais générer un nombre aléatoire d'épisodes pour chaque saison
                for ($j=1; $j < $nbMaxSeasons; $j++)
                {
                    $season = new Season(); // création d'une saison
                    $season->setNumber($j); // je lui assigne son numéro de saison
                    $season->setEpisodeNumber(mt_rand(3,16)); // je génère et assigne son nombre d'épisodes

                    // Ici on associe les deux entités
                    $season->setMovie($movie);

                    $manager->persist($season);
                }
            }
        }

        //#Genre

        $genresList = [];

        // Je veux créer 20 genres 
        for ($k=1; $k<=20; $k++) {
            $genre = new Genre();
            $genre->setName($oflixProvider->getRandomMovieGenre());

            // On l'ajoute à la liste pour usage ultérieur
            // en effet, il va falloir lier les genres et les movies entre eux
            $genresList[] = $genre;

            // ça demande au manager repository de Doctrine de prendre en compte cet objet pour le prochain flush
            $manager->persist($genre);
        }

        //#Person

        $personsList = [];

        // Je veux créer 200 persons 
        for ($l=1; $l<=200; $l++) {
            $person = new Person();
            $person->setFirstname($faker->firstname());
            $person->setLastname($faker->lastname());

            // On l'ajoute à la liste pour usage ultérieur
            // en effet, il va falloir lier les persons à des castings
            $personsList[] = $person;

            // ça demande au manager repository de Doctrine de prendre en compte cet objet pour le prochain flush
            $manager->persist($person);
        }

        //#Casting

        // on a pas besoin de stocker les castings générés dans un tableau
        // Pour chaque casting,
        // On a besoin d'un movie et d'une person
        // MAIS
        // On veut être certain que CHAQUE movie ait bien 2 à 5 castings
         // Je parcours la liste des films/séries générées précédemment
         foreach($moviesList as $key => $movie)
         {
             
            // je génère pour ce Movie un nombre de castings aléatoire compris entre 2 et 5
            // je le stocke dans nbMaxCasting
            $nbMaxCasting = mt_rand(2,5);

            // maintenant que j'ai le nombre de casting pour le Movie courant, 
            for ($m=1; $m < $nbMaxCasting; $m++)
            {
                $casting = new Casting();
                $casting->setCreditOrder($m);
                //?https://fakerphp.github.io/#modifiers
                $casting->setRole($faker->unique()->name());

                // J'assigne à ce casting une person aléatoirement choisie dans le tableau personsList
                $casting->setPerson($personsList[mt_rand(0,199)]);

                // Ici on associe les deux entités
                $casting->setMovie($movie);

                $manager->persist($casting);
            }
         }

        //# Relation entre Genre et Movie

        // Pour chaque Movie, je dois déterminer un nombre aléatoire de genres à lui assigner
        foreach($moviesList as $key => $movie)
        {
            // nbMaxGenre contient ce nombre maximum de genre
            $nbMaxGenre = mt_rand(1,3);
            for ($n=1; $n<=$nbMaxGenre; $n++) {

                // On cherche à mettre relier un genre aléatoirement à movie
                $movie->addGenre( $genresList[ mt_rand(0, count($genresList) - 1) ] );
                $manager->persist($movie);
            }
        }

        // 
        $manager->flush();
    }
}
