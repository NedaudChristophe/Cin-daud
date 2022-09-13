# Formulaire

## Installation

`composer require symfony/form`

`composer require symfony/validator`

`composer require symfony/security-csrf`

`composer require symfony/twig-bridge`


## Formulaire de base (pas relier à une entité)

Ajout des namespace :

```PHP

// Contexte de construction de formulaire
// Pour les constraintes de validation des champs
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
// pour les types de champs
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
```

Dans le controller (exemple dans la class Author)

```PHP
    /**
     * Liste des auteurs et de leurs articles
     * 
     * @Route("/author/list", name="app_author-list")
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        $authors = $authorRepository->findAll();
        //dump($authors);
        // Le findAll() permet de récupérer tous les auteurs, mais pas de trier

        // Nous sommes obligés d'utiliser le findBy()
        $authors2 = $authorRepository->findBy([], ['firstname'=> 'ASC']);
        

        // ! Formulaire de base, qui n'est relié à aucune entité
        //?https://kourou.oclock.io/ressources/fiche-recap/formulaires-avec-symfony/
        // https://symfony.com/doc/current/reference/constraints/NotBlank.html

        //Todo Phase de construction du formulaire
        // 1. création d'un form builder
        // 2. ajout de champ avec types et contraintes
        // 3. récupération de l'instance Form
        // 4. on le transforme en FormView pour que Twig puisse l'afficher
        $form = $this->createFormBuilder()
                        ->add('task', TextType::class, array(
                            'constraints' => new NotBlank(),
                        ))
                        ->add('dueDate', DateType::class, array(
                            'constraints' => array(
                                new NotBlank(),
                                new Type(\DateTime::class),
                            )
                        ))
                        ->getForm();

        return $this->render('author/index.html.twig', [
            'authors' => $authors2,
            // la méthode createView() est nécessaire pour mettre à TWIG d'afficher le formulaire avec sa propre méthode {{form()}} qui ne traite que des FormView
            'formtest' => $form->createView()
        ]);
    }
```

**Pour avoir es formulaire plus esthétique**

Il faut rajouter dans twig.yaml (dans le dossier config/packages)

[https://symfony.com/doc/5.4/form/bootstrap5.html]

```PHP
form_themes: ['bootstrap_5_layout.html.twig']
```

## Formulaire avec des relations

Ajout des namespace :

```PHP
// Pour récupérer les informations de la requête HTTP
use Symfony\Component\HttpFoundation\Request;

// Pour interagir avec notre Entité Post
use App\Entity\Post;
use DateTimeImmutable;

// Pour interagir avec notre Entité Comment
use App\Entity\Comment;

// Pour construire un formulaire
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
```

Dans le controller (exemple dans la class Post)

```PHP
    /**
     * @Route("/post/add", name="app_post-add", methods={"POST","GET"})
     * 
     * @todo faire les vérifications des données récupérées après soumission du formulaire
     *
     * @return Response
     */
    public function add (ManagerRegistry $doctrine, Request $request): Response
    {

        //!Objectif : On veut créer un nouvel article à partir des données saisies dans le formulaire

        // On crée une nouvelle instance de Post
        $newPost = new Post();

        $form = $this->createFormBuilder($newPost);
        $form = $form->add('title', TextType::class);
        $form = $form->add('body', TextareaType::class);
        $form = $form->add('publishedAt', DateTimeType::class, ['input' => 'datetime_immutable',]);
        //! On fait le lien à ce niveau là 
        // Form type guessing => si "null" en second argument, il tente de configurer le champ lui-même
        // error : Object of class App\Entity\Author could not be converted to string
        // le form builder a bien compris que dans author on attendait un type App\Entity\Author, et donc un widget pour sélectionner un auteur
        // Sauf qu'il nous dit qu'il n'arrive pas à convertir cette entité Author en chaine de caractère... [𝘝𝘰𝘪𝘳 𝘱𝘰𝘪𝘯𝘵 //!\\ 𝘴𝘪 𝘦𝘳𝘳𝘦𝘶𝘳 ]
        // il faut donc ajouter une méthode __toString à l'entité Author
        $form = $form->add('author', null, ['placeholder' => 'Choisissez un auteur...']);

        // Pour choisir un ou plusieurs tags...
        $form = $form->add('tags', null, ['placeholder' => 'Choississez un ou plusieurs tags']);
        

        //  Envoyez tout
        $form = $form->getForm();

        // Le Form inspecte la Requête
        $form->handleRequest($request);
        // ET remplit le l'instance de Post contenue dans.. $newPost
          
        // traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // On va faire appel au Manager de Doctrine
            $entityManager = $doctrine->getManager();
            $entityManager->persist($newPost);
            $entityManager->flush();

            // On redirige vers la liste
            return $this->redirectToRoute('app_post');
        }
           
        
        return $this->render('post/add.html.twig', [
            'form' => $form->createView()
                ]
            );
    }
```

Dans le fichier twig

```PHP
{{ form_start(form) }}

    {{ form_widget(form) }}

    <button type="submit" class="btn btn-success">Enregistrer</button>

{{ form_end(form) }}

```

```PHP
//!\\ 𝗦͟𝗶 𝗘͟𝗿͟𝗿͟𝗲͟𝘂͟𝗿 

//J'ai une erreur quand je créer un formulaire avec une entity :
`Object of class App\Entity\Author could not be converted to string`

// On tente d'écrire directement un objet, il faut lui donner une chaine
// Il ne sais pas faire une chaine avec un objet


/**
 * HACK sur une classe pour ne pas avoir de soucis avec could not be converted to string
 */
public function __toString(){return "une valeur texte";}
```

## Formulaire avec un make:form

Installation

`composer require symfony/translation`

Il faut avoir changer la langue dans config/packages/translation.yaml 

```yaml
framework:
    default_locale: en #=> a passer en fr
    translator:
        default_path: '%kernel.project_dir%/translations'
        fallbacks:
            - en
#        providers:
#            crowdin:
#                dsn: '%env(CROWDIN_DSN)%'
#            loco:
#                dsn: '%env(LOCO_DSN)%'
#            lokalise:
#                dsn: '%env(LOKALISE_DSN)%'

```

> **Pour générer les formulaires avec le teminale :**

`php bin/console make:form`
`php bin/console make:registration-form`

Dans le terminal :

```bash

php bin/console make:form

 The name of the form class (e.g. GrumpyGnomeType):
 > Post

 The name of Entity or fully qualified model class name that the new form will be bound to (empty for none):
 > Post

 created: src/Form/PostType.php

           
  Success! 
           

 Next: Add fields to your form and start using it.
 Find the documentation at https://symfony.com/doc/current/forms.html
 ```

=> Cela créer un dossier Form avec un fichier PostType

Symfony recommande de mettre le moins de logique possible dans les contrôleurs. C'est pourquoi il est préférable de déplacer les formulaires complexes vers des classes dédiées au lieu de les définir dans les actions du contrôleur. De plus, les formulaires définis dans les classes peuvent être réutilisés dans plusieurs actions et services.

Modifiez/compléter le fichier avec les formats de types voulu dans le formulaire.

Exemple avant :

```PHP
public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title')
        ->add('body')
        ->add('publishedAt')
        ->add('author')
        ->add('tags')
        ;
    }
```

Après modif :

```PHP
namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('body', TextareaType::class)
            ->add('publishedAt', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('author', null, [
                'placeholder' => 'Choisissez un auteur...',
            ])
            ->add('tags', null, ['placeholder' => 'Choississez un ou plusieurs tags'])
        ;
    }
```

Dans le controller il faut ajouter notre formulaire pour l'afficher

```PHP
/**
     * @Route("/post/add", name="main_addPost", methods={"POST","GET"})
     *Ajout d'article
     * @todo faire les vérifications des données récupérées après soumission du formulaire
     *
     * @param ManagerRegistry $addPost
     * @return Response
     */
    public function add (ManagerRegistry $doctrine, Request $request): Response
    {
        // On crée une nouvelle instance de Post
        $newPost = new Post();

        //On appel createForm qui est dans AbstractType
        $form = $this->createForm(PostType::class, $newPost);

        return $this->render('main/add.html.twig', ['form' => $form->createView()]);
    }
```

### Traitement des formulaires

```PHP

public function add (ManagerRegistry $doctrine, Request $request): Response
    {

        // On crée une nouvelle instance de Post
        $newPost = new Post();

        //On appel createForm qui est dans AbstractType
        $form = $this->createForm(PostType::class, $newPost);

        // Le Form inspecte la Requête
        $form->handleRequest($request);
        // ET remplit le l'instance de Post contenue dans.. $newPost

        // traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            // On va faire appel au Manager de Doctrine
            $entityManager = $doctrine->getManager();
            $entityManager->persist($newPost);
            $entityManager->flush();

            // On redirige vers la liste
            return $this->redirectToRoute('main_home');
        }
        
        return $this->render('main/add.html.twig', [
            'form' => $form->createView()
                ]
            );
    }


```


#### Contraintes de validation

[https://symfony.com/doc/current/reference/constraints/Length.html]

Je veux rajouter des validation sur les données que l'on me fournit

je rajoute le composant validator

`composer require symfony/validator`

Avec ce composant je peux rajouter des annotations sur les propriétés de mon entity afin de définir des règles de validation

[Contraintes de validation](http://symfony.com/doc/current/reference/constraints.html)

```PHP
use Symfony\Component\Validator\Constraints as Assert;
    /**
     * @ORM\Column(type="string", length=255)
     * @link https://symfony.com/doc/current/reference/constraints/Length.html
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $username;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $body;
```