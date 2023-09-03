<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
//use controllers\controllers;


require __DIR__ . '/vendor/autoload.php';
require_once 'connect.php';
//include 'views.php';


$loader = new FilesystemLoader('templates');
$view = new Environment($loader);

//$controllers = new controllers($view);

$app = AppFactory::create();


$app->get('/', function (Request $request, Response $response, $args) use($view, $connect){
    $authors_query = "SELECT * FROM `authors`";
    $books_query = "SELECT * FROM `books`";
    $bookinstances_query = "SELECT * FROM `bookinstance`";

    $authors = mysqli_query($connect, $authors_query);
    $books = mysqli_query($connect, $books_query);
    $bookinstances = mysqli_query($connect, $bookinstances_query);

    $authors = mysqli_fetch_all($authors);
    $books = mysqli_fetch_all($books);
    $bookinstances = mysqli_fetch_all($bookinstances);


    $bookinstance_col = 0;



    foreach ($bookinstances as $bookinstance) {
        $bookinstance_col++;
    }

    foreach ($books as $book) {
        $bookinstance_col = 0;
        foreach ($bookinstances as $bookinstance) {
            if ($bookinstance[1] == $book[0] && $bookinstance[2] != null) {
                $bookinstance_col++;
            }
        }
        $popular[] = [$book[0], $bookinstance_col];
    }

    function popular_func($a, $b) {
        if ($a[1] === $b[1]) {
            return  0;
        }
        return ($a[1] > $b[1]) ? -1 : 1;
    }

    uasort($popular, 'popular_func');
    $popular_return = [];
    $i = 0;

    foreach ($popular as $popular_var) {
        $popular[$i] = $popular_var;
        $i++;
    }

    for ($i = 0; $i < count($books)/2+1; $i++) {
        foreach($books as $book) {
            if ($book[0] == $popular[$i][0]) {
                $popular_return[] = $book;
            }
        }

    }


    $body = $view->render('index.twig', ['title' => 'Добро пожаловать','authors' =>  $authors, 'books' => $popular_return,  'bookinstances' => $bookinstance_col]);
    $response->getBody()->write($body);
    return $response;
});


$app->get('/books', function(Request $request, Response $response, $args) use($view, $connect) {
    $authors_query = "SELECT * FROM `authors`";
    $books_query = "SELECT * FROM `books`";
    $genre_query = "SELECT * FROM `genres`";
    $genre_book_query = "SELECT * FROM `genre_book`";
    $bookinstances_query = "SELECT * FROM `bookinstance`";

    $authors = mysqli_query($connect, $authors_query);
    $books = mysqli_query($connect, $books_query);
    $genres = mysqli_query($connect, $genre_query);
    $genre_book = mysqli_query($connect, $genre_book_query);
    $bookinstances = mysqli_query($connect, $bookinstances_query);

    $authors = mysqli_fetch_all($authors);
    $books = mysqli_fetch_all($books);
    $genres = mysqli_fetch_all($genres);
    $genre_book = mysqli_fetch_all($genre_book);
    $bookinstances = mysqli_fetch_all($bookinstances);



//    print_r($books);
    $body = $view->render('books_list.twig', ['title' => 'Список книг', 'books' => $books,
        'authors' => $authors, 'genres' => $genres, 'genre_book' => $genre_book, 'bookinstances' => $bookinstances]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/authors', function(Request $request, Response $response, $args) use($view, $connect) {
    $authors_query = "SELECT * FROM `authors`";
    $books_query = "SELECT * FROM `books`";

    $authors = mysqli_query($connect, $authors_query);
    $books = mysqli_query($connect, $books_query);

    $authors = mysqli_fetch_all($authors);
    $books = mysqli_fetch_all($books);

    $body = $view->render('author_list.twig', ['title' => 'Список авторов', 'books' => $books,
        'authors' => $authors]);
    $response->getBody()->write($body);
    return $response;
});


$app->post('/books', function(Request $request, Response $response, $args) use($view, $connect) {
//    print_r($request[0]);
    $id = $_POST['id'];
    $query = "DELETE FROM `books` WHERE `id` = $id";
    $result = mysqli_query($connect, $query);
    $authors_query = "SELECT * FROM `authors`";
    $books_query = "SELECT * FROM `books`";
    $genre_query = "SELECT * FROM `genres`";
    $genre_book_query = "SELECT * FROM `genre_book`";
    $bookinstances_query = "SELECT * FROM `bookinstance`";

    $authors = mysqli_query($connect, $authors_query);
    $books = mysqli_query($connect, $books_query);
    $genres = mysqli_query($connect, $genre_query);
    $genre_book = mysqli_query($connect, $genre_book_query);
    $bookinstances = mysqli_query($connect, $bookinstances_query);

    $authors = mysqli_fetch_all($authors);
    $books = mysqli_fetch_all($books);
    $genres = mysqli_fetch_all($genres);
    $genre_book = mysqli_fetch_all($genre_book);
    $bookinstances = mysqli_fetch_all($bookinstances);



//    print_r($books);
    $body = $view->render('books_list.twig', ['title' => 'Список книг', 'books' => $books,
        'authors' => $authors, 'genres' => $genres, 'genre_book' => $genre_book, 'bookinstances' => $bookinstances]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/books/create', function(Request $request, Response $response, $args) use($view, $connect) {
    $authors = mysqli_query($connect, "SELECT * FROM `authors`");
    $authors = mysqli_fetch_all($authors);
    $body = $view->render('create-book.twig', ['title' => 'Добавление книги', 'authors' => $authors]);
    $response->getBody()->write($body);
    return $response;
});

$app->post('/books/create', function(Request $request, Response $response, $args) use($view, $connect) {
    $genre_name = $_POST['genre'];
    $genre = mysqli_query($connect, "SELECT `id` FROM `genres` WHERE `name` = '$genre_name'");
    $genre = mysqli_fetch_all($genre);
    if ($genre == null) {
        $new_genre = mysqli_query($connect, "INSERT INTO `genres` (`id`, `name`) VALUES (NULL, '$genre_name')");
        $genre = mysqli_query($connect, "SELECT `id` FROM `genres` WHERE `name` = '$genre_name'");
        $genre = mysqli_fetch_all($genre);
    }

    $title = $_POST['title'];
    $author = $_POST['author'];
    $date = $_POST['date_pub'];

    $img_name = $_FILES['img']['name'];
    $upload_dir = "./src/$img_name";
    move_uploaded_file($_FILES['img']['tmp_name'], $upload_dir);

    mysqli_query($connect, "INSERT INTO `books` (`id`, `title`, `author`, `publication`, `url`) VALUES (NULL,
                                    '$title', '$author', '$date', '$upload_dir')");

    $id_new_book = mysqli_query($connect,"SELECT `id` FROM `books` WHERE `title` = '$title'");
    $id_new_book = mysqli_fetch_all($id_new_book);

    $genre = $genre[0][0];
    $id_new_book = $id_new_book[0][0];
    mysqli_query($connect, "INSERT INTO `genre_book` (`id_book`, `id_genre`) VALUES ('$id_new_book', '$genre')");


   header("Location: /books");
});

$app->run();





