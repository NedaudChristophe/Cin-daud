-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `casting`;
CREATE TABLE `casting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit_order` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D11BBA508F93B6FC` (`movie_id`),
  KEY `IDX_D11BBA50217BBB47` (`person_id`),
  CONSTRAINT `FK_D11BBA50217BBB47` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `FK_D11BBA508F93B6FC` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `casting` (`id`, `role`, `credit_order`, `movie_id`, `person_id`) VALUES
(1,	'Lucy',	1,	5,	1),
(2,	'Professor Norman ',	2,	5,	2),
(3,	'Leeloo',	2,	4,	4),
(4,	'James Bond',	1,	1,	5),
(5,	'Vesper Lynd',	2,	1,	6),
(6,	'Obi-Wan Kenobi',	1,	3,	7),
(7,	'Anakin Skywalker',	2,	3,	8),
(8,	'Qui-Gon Jinn',	1,	2,	9),
(9,	'Obi-Wan Kenobi',	2,	2,	7),
(10,	'Korben Dallas',	1,	4,	3),
(11,	'Blondin',	1,	6,	10),
(12,	'sergent Sentenza',	2,	6,	11),
(13,	'Tuco Benedicto Pacifico Juan Maria Ramirez',	3,	6,	12),
(14,	'James Bond',	1,	7,	13),
(15,	' Alec Trevelyan /Janus',	2,	7,	14),
(16,	'Natalya Fyodorovna Simonova',	3,	7,	15),
(17,	'Harry S. Stamper',	1,	8,	3),
(18,	'Grace Stamper',	2,	8,	16),
(19,	'Albert Jones « A. J. » Frost',	3,	8,	17),
(20,	' John Patrick Mason',	1,	9,	18),
(21,	' Dr Stanley Goodspeed',	2,	9,	19),
(22,	'Francis Xavier Humme',	3,	9,	20),
(23,	'Maximus Decimus Meridius',	1,	10,	21),
(24,	'Commode',	2,	10,	22),
(25,	'',	3,	10,	23),
(26,	'Robert Clayton Dean',	1,	11,	24),
(27,	'Edward « Brill » Lyle',	2,	11,	25),
(28,	'',	3,	11,	26);

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220610120300',	'2022-06-10 14:08:27',	46),
('DoctrineMigrations\\Version20220610123847',	'2022-06-10 14:42:14',	44),
('DoctrineMigrations\\Version20220613120845',	'2022-06-13 14:09:31',	57),
('DoctrineMigrations\\Version20220613123709',	'2022-06-13 14:37:27',	82),
('DoctrineMigrations\\Version20220615140143',	'2022-06-15 16:01:57',	59),
('DoctrineMigrations\\Version20220615140451',	'2022-06-15 16:04:58',	42),
('DoctrineMigrations\\Version20220615141644',	'2022-06-15 16:17:02',	88),
('DoctrineMigrations\\Version20220615142233',	'2022-06-15 16:23:02',	66),
('DoctrineMigrations\\Version20220615142526',	'2022-06-15 16:25:32',	64),
('DoctrineMigrations\\Version20220615150117',	'2022-06-15 17:01:23',	59);

DROP TABLE IF EXISTS `genre`;
CREATE TABLE `genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `genre` (`id`, `name`) VALUES
(1,	'Action'),
(2,	'Science-fiction'),
(3,	'Péplum'),
(4,	'Western'),
(5,	'Thriller'),
(6,	'Aventure'),
(7,	'Espionnage');

DROP TABLE IF EXISTS `movie`;
CREATE TABLE `movie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_date` date DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `summary` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `synopsis` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `poster` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `movie` (`id`, `title`, `type`, `release_date`, `duration`, `summary`, `synopsis`, `poster`, `rating`) VALUES
(1,	'Casino Royale',	'Film',	'2006-11-16',	144,	'Pour sa première mission, James Bond affronte le tout-puissant banquier privé du terrorisme international, Le Chiffre.',	'Pour sa première mission, James Bond affronte le tout-puissant banquier privé du terrorisme international, Le Chiffre. Pour achever de le ruiner et démanteler le plus grand réseau criminel qui soit, Bond doit le battre lors d\'une partie de poker à haut risque au Casino Royale. La très belle Vesper, attachée au Trésor, l\'accompagne afin de veiller à ce que l\'agent 007 prenne soin de l\'argent du gouvernement britannique qui lui sert de mise, mais rien ne va se passer comme prévu.',	'https://fr.web.img6.acsta.net/medias/nmedia/18/36/12/35/18674702.jpg',	4),
(2,	'Star Wars: Episode I - The Phantom Menace',	'Film',	'1999-10-13',	136,	'Avant de devenir un célèbre chevalier Jedi, et bien avant de se révéler l’âme la plus noire de la galaxie, Anakin Skywalker est un jeune esclave sur la planète Tatooine.',	'Avant de devenir un célèbre chevalier Jedi, et bien avant de se révéler l’âme la plus noire de la galaxie, Anakin Skywalker est un jeune esclave sur la planète Tatooine. La Force est déjà puissante en lui et il est un remarquable pilote de Podracer. Le maître Jedi Qui-Gon Jinn le découvre et entrevoit alors son immense potentiel.\r\nPendant ce temps, l’armée de droïdes de l’insatiable Fédération du Commerce a envahi Naboo, une planète pacifique, dans le cadre d’un plan secret des Sith visant à accroître leur pouvoir. Pour défendre la reine de Naboo, Amidala, les chevaliers Jedi vont devoir affronter le redoutable Seigneur Sith, Dark Maul.',	'https://m.media-amazon.com/images/I/81Faw0Fs5-L._AC_SL1200_.jpg',	5),
(3,	'Star Wars: Episode II - Attack of the Clones',	'Film',	'2002-05-17',	142,	'Depuis le blocus de la planète Naboo par la Fédération du commerce,',	'Depuis le blocus de la planète Naboo par la Fédération du commerce, la République, gouvernée par le Chancelier Palpatine, connaît une véritable crise. Un groupe de dissidents, mené par le sombre Jedi comte Dooku, manifeste son mécontentement envers le fonctionnement du régime. Le Sénat et la population intergalactique se montrent pour leur part inquiets face à l\'émergence d\'une telle menace.\r\nCertains sénateurs demandent à ce que la République soit dotée d\'une solide armée pour empêcher que la situation ne se détériore davantage. Parallèlement, Padmé Amidala, devenue sénatrice, est menacée par les séparatistes et échappe de justesse à un attentat. Le Padawan Anakin Skywalker est chargé de sa protection. Son maître, Obi-Wan Kenobi, part enquêter sur cette tentative de meurtre et découvre la constitution d\'une mystérieuse armée de clones...',	'https://fr.web.img5.acsta.net/medias/nmedia/00/02/34/81/affclones.jpg',	5),
(4,	'Le cinquième élément',	'Film',	'1997-05-07',	126,	'Egypte, 1914. Des extraterrestres récupèrent quatre pierres magiques',	'Egypte, 1914. Des extraterrestres récupèrent quatre pierres magiques, symboles des quatre éléments, jadis confiées à des prêtres. Avant de partir, les extraterrestres promettent que dans 300 ans, ils rapporteront les précieux cailloux. Au XXIIIe siècle, alors qu\'ils font route vers la Terre, ils sont anéantis par la planète du Mal. Les habitants de ce monde maléfique, les Mangalores, s\'emparent des pierres et foncent vers la Terre.',	'https://fr.web.img6.acsta.net/pictures/14/08/21/14/17/385506.jpg',	5),
(5,	'Lucy',	'Film',	'2014-08-06',	90,	'Lucy Miller est une jeune femme vivant à Taipei (Taiwan), dans un monde où les humains n\'utilisent que 10 pourcent des capacités de leur cerveau.',	'Lucy Miller est une jeune femme vivant à Taipei (Taiwan), dans un monde où les humains n\'utilisent que 10 pourcent des capacités de leur cerveau. Prise dans un guet-apens par la mafia coréenne, elle est contrainte de faire la mule pour des trafiquants de drogue qui insèrent un paquet de poudre bleue dans son ventre, le CPH4, produit de synthèse expérimental.',	'https://fr.web.img6.acsta.net/pictures/14/06/05/09/47/324245.jpg',	4),
(6,	'Le Bon, la Brute et le Truand',	'Film',	'1968-03-08',	178,	'Pendant la Guerre de Sécession, trois hommes, préférant s\'intéresser à leur profit personnel,',	'Pendant la Guerre de Sécession, trois hommes, préférant s\'intéresser à leur profit personnel, se lancent à la recherche d\'un coffre contenant 200 000 dollars en pièces d\'or volés à l\'armée sudiste.',	'https://fr.web.img2.acsta.net/pictures/14/09/23/10/28/237103.jpg',	5),
(7,	'GoldenEye',	'Film',	'1995-12-20',	130,	'James Bond est chargé par le MI6 de retrouver le Goldeneye',	'James Bond est chargé par le MI6 de retrouver le Goldeneye, un satellite russe volé par des mercenaires, dont la puissance de frappe pourrait rayer de la carte n\'importe quelle capitale. Sur les traces des responsables, l\'agent 007 se rend aux quatre coins du monde avant de retrouver sur son chemin une vieille connaissance. Entre sa mission et ses sentiments personnels, l\'agent secret se voit dans l\'obligation de faire un choix.',	'https://fr.web.img6.acsta.net/medias/nmedia/18/66/20/36/18994062.jpg',	5),
(8,	'Armageddon',	'Film',	'1998-08-05',	145,	'Alors qu\'elle se trouve en mission en orbite terrestre, la navette Atlantis est détruite par une pluie de météorites qui termine sa course sur New York.',	'Alors qu\'elle se trouve en mission en orbite terrestre, la navette Atlantis est détruite par une pluie de météorites qui termine sa course sur New York. Ceci est le prélude d\'une catastrophe majeure : un astéroïde de la taille du Texas va s\'écraser sur Terre dans dix-huit jours. Dan Truman, directeur des opérations de vol à la NASA, envisage la mission de la dernière chance : envoyer des astronautes sur l\'astéroïde pour qu\'ils y creusent un puits dans lequel sera insérée une charge nucléaire.',	'https://fr.web.img5.acsta.net/medias/nmedia/18/85/98/00/19816173.jpg',	4),
(9,	'Rock',	'Film',	'1996-06-07',	136,	'Excédé par l\'injustice de son gouvernement, le Général Hummel se rend maître de l\'île d\'Alcatraz ',	'Excédé par l\'injustice de son gouvernement, le Général Hummel se rend maître de l\'île d\'Alcatraz et menace de lancer un gaz mortel sur San Francisco. Deux hommes sont chargés de le contrer : un expert en armes chimiques, Stanley Goodspeed, et John Patrick Mason, l\'unique prisonnier à s\'être évadé d\'Alcatraz. Ils se rendent ensemble sur l\'île afin de stopper les projets destructeurs du Général.',	'https://fr.web.img5.acsta.net/medias/nmedia/00/02/51/08/rock.jpg',	4),
(10,	'Gladiator',	'Film',	'2000-06-20',	171,	'Le général romain Maximus est le plus fidèle soutien de l\'empereur Marc Aurèle, qu\'il a conduit de victoire en victoire.',	'Le général romain Maximus est le plus fidèle soutien de l\'empereur Marc Aurèle, qu\'il a conduit de victoire en victoire. Jaloux du prestige de Maximus, et plus encore de l\'amour que lui voue l\'empereur, le fils de Marc Aurèle, Commode, s\'arroge brutalement le pouvoir, puis ordonne l\'arrestation du général et son exécution. Maximus échappe à ses assassins, mais ne peut empêcher le massacre de sa famille. Capturé par un marchand d\'esclaves, il devient gladiateur et prépare sa vengeance.',	'https://fr.web.img6.acsta.net/medias/nmedia/18/68/64/41/19254510.jpg',	5),
(11,	'Ennemi d\'État',	'Film',	'1998-11-20',	132,	'Robert Clayton Dean, avocat engagé depuis ses débuts dans une lutte acharnée contre la mafia, rencontre fortuitement un ami d\'enfance, témoin malgré lui d\'un meurtre politique.',	'Robert Clayton Dean, avocat engagé depuis ses débuts dans une lutte acharnée contre la mafia, rencontre fortuitement un ami d\'enfance, témoin malgré lui d\'un meurtre politique. Il devient ainsi le dernier possesseur de la seule preuve existante du crime commis par Thomas Reynolds, le directeur de la NSA, l\'organisation gouvernementale la plus secrète et la plus puissante des Etats Unis, envers un député. Reynolds va déployer toutes ses ressources pour neutraliser et discréditer Dean.',	'https://fr.web.img6.acsta.net/pictures/210/151/21015100_20130625124415787.jpg',	5);

DROP TABLE IF EXISTS `movie_genre`;
CREATE TABLE `movie_genre` (
  `movie_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL,
  PRIMARY KEY (`movie_id`,`genre_id`),
  KEY `IDX_FD1229648F93B6FC` (`movie_id`),
  KEY `IDX_FD1229644296D31F` (`genre_id`),
  CONSTRAINT `FK_FD1229644296D31F` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_FD1229648F93B6FC` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `movie_genre` (`movie_id`, `genre_id`) VALUES
(1,	1),
(1,	7),
(2,	2),
(3,	2),
(4,	1),
(4,	2),
(5,	1),
(5,	2),
(6,	4),
(7,	1),
(7,	7),
(8,	1),
(9,	1),
(10,	3),
(10,	6),
(11,	1),
(11,	5);

DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `person` (`id`, `firstname`, `lastname`) VALUES
(1,	'Scarlett Johansson',	'Johansson'),
(2,	'Morgan ',	'Freeman'),
(3,	'Bruce ',	'Willis'),
(4,	'Milla ',	'Jovovich'),
(5,	'Daniel ',	'Craig'),
(6,	'Eva ',	'Green'),
(7,	'Ewan ',	'McGregor'),
(8,	'Hayden ',	'Christensen'),
(9,	'Liam ',	'Neeson'),
(10,	'Clint ',	'Eastwood'),
(11,	'Lee ',	'Van Cleef'),
(12,	'Eli ',	'Wallach'),
(13,	'Pierce  ',	'Brosnan'),
(14,	'Sean ',	'Bean'),
(15,	'Izabella ',	'Scorupco'),
(16,	'Liv  ',	'Tyler'),
(17,	'Ben ',	'Affleck'),
(18,	'Sean ',	'Connery'),
(19,	'Nicolas ',	'Cage'),
(20,	'Ed ',	'Harris'),
(21,	'Russell  ',	'Crowe'),
(22,	'Joaquin ',	'Phoenix'),
(23,	'Connie ',	'Nielsen'),
(24,	'Will ',	'Smith'),
(25,	'Gene ',	'Hackman'),
(26,	'Jon ',	'Voight');

DROP TABLE IF EXISTS `season`;
CREATE TABLE `season` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `episode_number` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F0E45BA98F93B6FC` (`movie_id`),
  CONSTRAINT `FK_F0E45BA98F93B6FC` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `season` (`id`, `number`, `episode_number`, `movie_id`) VALUES
(1,	'0',	25,	1),
(2,	'0',	9,	2),
(3,	'0',	9,	3),
(4,	'0',	1,	4),
(5,	'0',	1,	5),
(6,	'0',	3,	6),
(7,	'0',	1,	7),
(8,	'0',	1,	8),
(9,	'0',	1,	9),
(10,	'0',	1,	10),
(11,	'0',	1,	11);

-- 2022-06-16 08:20:45
