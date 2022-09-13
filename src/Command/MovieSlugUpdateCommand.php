<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Services\MySlugger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieSlugUpdateCommand extends Command
{
    protected static $defaultName = 'app:movie:slug-update';
    protected static $defaultDescription = 'Generate slug for all movie in database and update database';

    /**
    * Service MySlugger
    *
    * @var MySlugger
    */
    private $slugger;
    
    /**
    * Service Repository pour les Movies
    *
    * @var MovieRepository
    */
    private $movieRepository;
    
    /**
    * Service ManagerRegistry
    *
    * @var ManagerRegistry
    */
    private $managerRegistry;
    

    /**
    * Constructor
    */
    public function __construct(MySlugger $slugger, MovieRepository $movieRepository, ManagerRegistry $registry)
    {
        parent::__construct();

        $this->slugger = $slugger;
        $this->movieRepository = $movieRepository;
        $this->managerRegistry = $registry;
    }   
    
    protected function configure(): void
    {
        /*
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
        */
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // on utilise un modificateur de symfony pour rendre plus zoli notre affichage dans le terminal
        // on lui fournit le Input et Output pour qu'il s'en occupe
        // @link https://symfony.com/doc/current/console/style.html
        $io = new SymfonyStyle($input, $output);

        $io->title("Bienvenue dans la génération de slug pour notre BDD !!!");

        
        
        $allMovie = $this->movieRepository->findAll();
        
        $io->progressStart(count($allMovie));

        // $io->info("There is " . count($allMovie) . " movie in database.");
       
        foreach ($allMovie as $movie) 
        {
            
            $newSlug = $this->slugger->slug($movie->getTitle());
            
            $movie->setSlug($newSlug);
            
            

            $io->progressAdvance();

            //$io->info("slug pour le film (" . $movie->getTitle() . ") a été généré");
        }
        
        $manager = $this->managerRegistry->getManager();
        $manager->flush();
        
        $output->writeln(['']);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
