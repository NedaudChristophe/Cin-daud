Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > ⁡⁢⁢⁢𝗺𝗼𝘃𝗶𝗲⁡

 Field type (enter ? to see all types) [string]:
 > ⁡⁢⁢⁢𝗿𝗲𝗹𝗮𝘁𝗶𝗼𝗻⁡

 What class should this entity be related to?:
 > ⁡⁢⁢⁢𝗺𝗼𝘃𝗶𝗲⁡

What type of relationship is this?
 --------------------------------------------------------------------
  Type Description                                                        
------------------------------------------------------------------- 
  ManyToOne    Each Season relates to (has) one movie.                            
               Each movie can relate to (can have) many Season objects            
                                                                                  
  OneToMany    Each Season can relate to (can have) many movie objects.           
               Each movie relates to (has) one Season                             
                                                                                  
  ManyToMany   Each Season can relate to (can have) many movie objects.           
               Each movie can also relate to (can also have) many Season objects  
                                                                                  
  OneToOne     Each Season relates to (has) exactly one movie.                    
               Each movie also relates to (has) exactly one Season.               
 --------------------------------------------------------------------


 Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > ⁡⁢⁢⁢𝗠𝗮𝗻𝘆𝗧𝗼𝗢𝗻𝗲⁡

 Is the Season.movie property allowed to be null (nullable)? (yes/no) [yes]:
 > ⁡⁢⁢⁢𝗻𝗼⁡

 Do you want to add a new property to movie so that you can access/update Season objects from it - e.g. $movie->getSeasons()? (yes/no) [yes]:
 > ⁡⁢⁢⁢𝘆𝗲𝘀⁡

 A new property will also be added to the movie class so that you can access the related Season objects from it.

 New field name inside movie [seasons]:
 > ⁡⁢⁢⁢𝘀𝗲𝗮𝘀𝗼𝗻𝘀⁡

 Do you want to activate orphanRemoval on your relationship?
 A Season is "orphaned" when it is removed from its related movie.
 e.g. $movie->removeSeason($season)
 
 NOTE: If a Season may *change* from one movie to another, answer "no".

 Do you want to automatically delete orphaned App\Entity\Season objects (orphanRemoval)? (yes/no) [no]:
 > ⁡⁢⁢⁢𝘆𝗲𝘀⁡

 updated: src/Entity/Season.php
 updated: src/Entity/Movie.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 ---------------------------
 > ⁡⁣⁣⁢𝗦𝘂𝗰𝗰𝗲𝘀𝘀⁡
-----------------------------
Next: When you're ready, create a migration with php bin/console make:migration



- php bin/console ma:mi = make:migration
- php bin/console d:m:m = doctrine:make:migrate




Je veux créer une relation entre deux entités suivant mon MCD.

Je vais donc détailler à Doctrine ce que je veux.

Pour les relations, la seule chose qui nous intéresse dans le MCD, c'est la cardinalité MAX, le 0 ou 1 de la cardinalité MIN est là pour l'option de nullité.

Je pars de mon MCD et je note :

- N de mon coté, et 1 de l'autre
- ManyToOne
- 1 de mon coté, et N de l'autre
- OneToMany
- N de mon coté, et N de l'autre
- ManyToMany

Ce qui est important pour Doctrine c'est qui porte la relation : mappedBy OU inversedBy

## ManyToOne

Je suis le porteur de la relation, c'est moi qui dans la base contient la FK.
Dans le code, je doit avoir :

1
2
3
4
/* dans la classe Post
* @ORM\ManyToOne(targetEntity=Author::class, inversedBy="posts")
*/
 private $author;

J'ai donc une propriété dans ma classe porteuse avec un objet de la classe correspondante (dans l'exemple Author)
Je doit trouver un inversedBy

## OneToMany

Je NE suis PAS le porteur de la relation, c'est l'autre qui dans la base contient la FK.
Dans le code, je doit avoir :

1
2
3
4
/** dans la classe Author
 * @ORM\OneToMany(targetEntity=Post::class, mappedBy="author")
 */
private $posts;

J'ai donc une propriété dans ma classe avec un ArrayCollection qui contient toutes les instances des objets liés (dans l'exemple Post)
Je doit trouver un mappedBy

## ManyToMany

Aucune des deux tables ne porte de FK, il y a une table pivot.
Dans le code je doit avoir :

1
2
3
4
/** dans la classe Tag
 * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="tags")
 */
private $posts;

1
2
3
4
/** dans la classe Post
* @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="posts")
*/
private $tags;

Mais ?? pourquoi on a quand même mappedBy OU inversedBy ?

Il faut quand même donner à Doctrine qui des deux entités est l'entité porteuse, celle qui est la plus logique, à vous de décider suivant le cas.
L'idée est que l'on veux avoir la collection d'entité depuis l'une plutôt que depuis l'autre.

Dans notre exemple, un Post est notre objet porteur car on affichera les tags dans la page du post, et pas l'inverse.
Donc on doit avoir inversedBy dans notre classe Post