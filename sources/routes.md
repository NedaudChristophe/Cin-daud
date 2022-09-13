# Routes de l'application

| URL | Méthode HTTP | Contrôleur       | Méthode | Titre HTML           | Commentaire    |
| --- | ------------ | ---------------- | ------- | -------------------- | -------------- |
| `/` | `GET`        | `MainController` | `home`  | Bienvenue sur O'flix | Page d'accueil |
| `/movie/{id}`   | `GET`        | `MainController`      | `movieShow`      | Titre du film/série            | Page détail d'un film/série |
| `/list`         | `GET`        | `MainController`      | `list`           | Tous les films et séries       | Liste de tous les films ou résultats de recherche |
| `/favorites`    | `GET`        | `FavoritesController` | `favorites`      | Ma liste                       | Les favoris de l'utilisateur courant              |
| `/favorites/add`    | `POST`        | `FavoritesController` | `add`      | Ma liste                       | ajoute un nouveau favori dans la liste              |
| `/favorites/delete`    | `POST`        | `FavoritesController` | `delete`      | Ma liste                       | supprime un favori de la liste             |
| `/favorites/delete-all`    | `POST`        | `FavoritesController` | `deleteAll`      | Ma liste                       | supprime tous les favoris            |