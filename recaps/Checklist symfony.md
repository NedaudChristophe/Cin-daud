# E02
## Création d'un projet skeleton (projet minimum)
bash
composer create-project symfony/skeleton oflix
On déplace les fichier du sous-dossier du projet, puis on supprime le sous-dossier
bash
mv oflix/* oflix/.* .
## Création d'une route/controller
Pour pouvoir utiliser les annotations et que symfony les lise
bash
composer require annotations
### cache:clear
Si j'ai une erreur Script cache:clear returned with error code 1
Bine lire les messages, il s'agit probablement de nom de fichier/nom de classe qui ne corresponde pas.
Case mismatch between loaded and declared class names:
Pour vérifier que tout est bon
bash
bin/console cache:clear
## Erreurs
text
Semantical Error] The annotation "@Route" in method App\Controller\MainController::home() was never imported. Did you maybe forget to add a "use" statement for this annotation?
J'ai oublié de mettre le use en haut de mon controller
php
use Symfony\Component\Routing\Annotation\Route;
text
[Syntax Error] Expected Doctrine\Common\Annotations\DocLexer::T_CLOSE_PARENTHESIS, got '/' at position 8 in method App\Controller\MainController::home()
php
* @Route("/) --> erreur
* @Route("/") --> OK
## debug des routes
Pour voir la liste de toutes les routes du projet
bash
 bin/console debug:router
on peut donc donner un nom à notre route
php
/* @Route("/", name="default_page")
## Installation twig
bash



# E03
## debug / profiler
bash
composer require --dev symfony/profiler-pack
Var_dumper + gestion des dump dans la toolbar
bash
composer require --dev symfony/var-dumper
composer require --dev symfony/debug-bundle
## Gestion des assets et de leur inclusion
bash
composer require symfony/asset
## maker
bash
composer require --dev symfony/maker-bundle
text
          
 [ERROR] Missing package: to use the make:controller command, run:                    
                                                                                      
         composer require doctrine/annotations                                        
## make:controller
bash
bin/console make:controller
bash
bin/console m:cont
bash
 Choose a name for your controller class (e.g. VictoriousPuppyController):
 > Api

 created: src/Controller/ApiController.php
 created: templates/api/index.html.twig

           
  Success! 
           
## chrome json viewer
JSon Viewer
tulios/json-viewer
It is a Chrome extension for printing JSON and JSONP.
Website
https://chrome.google.com/webstore/detail/json-viewer/gbmdgpbipfallnflgajpaliibnhdgobh
Stars
2750
Ajouté(e) par GitHub