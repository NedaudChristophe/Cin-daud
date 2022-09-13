La method dans le MovieRepository
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
    public function findAllMovieByTitleOrderAscQb(int $page){

        $pageSize = 3;
        $firstResult = ($page - 1) * $pageSize;

        $query = $this->createQueryBuilder('m')
            ->orderBy('m.title', 'ASC')
            ->setFirstResult($firstResult)
            ->setMaxResults($pageSize);

        $paginator = new Paginator($query, true);
        
        return $paginator;

    }
La method home
1
2
3
4
5
6
7
8
9
10
11
12
    public function home(int $page, MovieRepository $movieRepository, GenreRepository $genreRepository):Response{
        // $movies = new Movie();

        // $moviesList = $movies->getAllMovies();

        $moviesList = $movieRepository->findAllMovieByTitleOrderAscQb($page);
        $genreList = $genreRepository->findAll();
        $numberOfPage = intval(ceil(count($moviesList)/3));
        $currentPage = $page;

        return $this->render('main/home.html.twig', compact('moviesList', 'genreList', 'numberOfPage', 'currentPage'));
    }
Et la navigation de la pagination
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
<nav class="col-lg-9 offset-lg-3">
    <ul class="pagination">
        
        {% if currentPage != 0 %}
        <li class="page-item">
            <a class="page-link" href="{{path('home', {page: currentPage - 1})}}">&laquo;</a>
        {% else %}
        <li class="page-item disabled">
            <a class="page-link">&laquo;</a>
        {% endif %}
        </li>
        {% for pageNumber in 1.. numberOfPage %}
            {% if currentPage == pageNumber %}
                <li class="page-item active">
            {% else %}
                <li class="page-item">
            {% endif %}
                <a class="page-link" href="{{path('home', {page: pageNumber})}}">{{pageNumber}}</a>
            </li>
        {% endfor %}

        {% if currentPage != 0 %}
        <li class="page-item">
            <a class="page-link" href="{{path('home', {page: currentPage - 1})}}">&raquo;</a>
        {% else %}
        <li class="page-item disabled">
            <a class="page-link">&raquo;</a>
        {% endif %}

        </li>
    </ul>
</nav>
Il ne faut pas oublier de modifier votre path pour la home pour lui donner la première page en paramètre