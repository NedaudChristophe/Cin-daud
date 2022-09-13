<?php

namespace App\Tests\Front;

use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class ReviewTest extends WebTestCase
{
    public function testAddReview(): void
    {
       // je crée mon client HTTP
        /** @var KernelBrowser $client */
        $client = static::createClient();

        // TODO : il me faut un ID de film qui existe en BDD
        /** @var MovieRepository $MovieRepository */
        $MovieRepository = static::getContainer()->get(MovieRepository::class);
        // je fais un randomMovie car je ne connais pas la BDD, 
        // donc je ne suis pas sûr des ID qu'il y a dedans
        // le random me rend service en allant chercher un film
        $randomMovie = $MovieRepository->findRandomMovie();
        
        // TODO : se connecter avec cet utilisateur
        /** @var UserRepository $UserRepository */
        $UserRepository = static::getContainer()->get(UserRepository::class);
        // je vais chercher un utilisateur par son email
        $user = $UserRepository->findOneBy(["email" => "user@user.com"]);
        //dump($user);
        $client->loginUser($user);
        
        // TODO : on navigue vers la page pour ajouter un review : /movie/{id}/review/add
        $crawler = $client->request('GET', '/movie/' . $randomMovie->getId() . '/review/add');
        // j'en profite pour vérifier que je suis bien arrivé quelque part
        $this->assertResponseIsSuccessful();

        // TODO : remplir le formulaire HTML
        // @link https://symfony.com/doc/current/testing.html#submitting-forms
        // select the button
        //! si le bouton n'a pas de name, car dans le template
        //? on doit avoir un name pour le cibler
        $buttonCrawlerNode = $crawler->selectButton('le_bouton_ajouter');
        //dd($buttonCrawlerNode);
        // avec le bouton je récupère le formulaire
        $formulaire = $buttonCrawlerNode->form();

        // je remplis le formulaire champs à champs
        $formulaire['review[username]'] = 'Fabien';
        $formulaire['review[email]'] = 'Fabien@symfony.com';
        $formulaire['review[content]'] = 'Que du bien sur ce film aléatoire';
        $formulaire['review[rating]'] = 5;
        //? il faut que les valeurs concordent avec mon ReviewType
        //! attention à l'ordre ???
        $formulaire['review[reactions]'] = ["smile", "cry"];
        $formulaire['review[watchedAt]'] = '2022-04-25';
        
        // TODO : on submit le formulaire
        $client->submit($formulaire);
        
        // TODO : on vérifie la réponse
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        
        /*********************************************************************** */
        /* Avec Validations                                                      */
        /*********************************************************************** */

        // TODO : faire des tests sur la contrainte de validation

        $crawler = $client->request('GET', '/movie/' .$randomMovie->getId(). '/review/add');
        // debug on vérifie que l'URL est bien généré
        $this->assertResponseIsSuccessful();

        // @link https://symfony.com/doc/5.4/testing.html#submitting-forms
        // select the button
        $buttonCrawlerNode = $crawler->selectButton('le_bouton_ajouter');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['review[username]'] = 'a';
        $formulaire['review[watchedAt]'] = '2022-04-25';

        //! je fais exprès de ne rien remplir
        // TODO JB : error sur watchedAt : datetime_immutable

        // submit the Form object
        $client->submit($form);

        // si je n'ai pas réussi à valider les contraintes, je dois recevoir un 422
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
