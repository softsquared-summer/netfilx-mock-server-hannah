<?php
require './pdos/DatabasePdo.php';
//require './pdos/IndexPdo.php';
require './vendor/autoload.php';

require './pdos/UserPdo.php';//로그인, 가입 등
//require './pdos/BoardPdo.php';//게시판 관리
require './pdos/ReviewPdo.php';
require './pdos/MoviePdo.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

////에러출력하게 하는 코드
//error_reporting(E_ALL); ini_set("display_errors", 1);

//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    /* ******************   Test   ****************** */
    $r->addRoute('GET', '/movie/latest', ['IndexController', 'movieGenre']);
    $r->addRoute('GET', '/movie/list', ['IndexController', 'movieList']);
    $r->addRoute('GET', '/genre', ['IndexController', 'genreList']);

    $r->addRoute('GET', '/', ['IndexController', 'index']);
    $r->addRoute('GET', '/user', ['MainController', 'user']);//user/list 로 수정하면 사용가능 get url이 두개잖아.
    $r->addRoute('GET', '/user/all', ['MainController', 'userAll']);
//    $r->addRoute('GET', '/user', ['MainController', 'userDetail']);
    $r->addRoute('POST', '/user', ['MainController', 'signUp']);
    $r->addRoute('POST', '/user/token', ['MainController', 'login']);
//
    $r->addRoute('GET', '/user/article', ['MainController', 'myArticle']);
    $r->addRoute('GET', '/user/comment', ['IndexController', 'myComment']);
    $r->addRoute('GET', '/user/scrap', ['IndexController', 'myScrap']);

    $r->addRoute('POST', '/article/thumbs-up', ['IndexController', 'articleThumbsUp']);
    $r->addRoute('POST', '/article/scrap', ['IndexController', 'articleScrap']);
//
    $r->addRoute('GET', '/hot-article', ['IndexController', 'hotArticle']);
    $r->addRoute('GET', '/free-board', ['IndexController', 'lookupFreeBoard']);
    $r->addRoute('GET', '/board?type=free', ['IndexController', 'lookupFreeBoard']);
    $r->addRoute('GET', '/secret-board', ['IndexController', 'lookupSecretBoard']);

    $r->addRoute('GET', '/article/all', ['IndexController', 'showArticleComment']);
    $r->addRoute('GET', '/comment', ['IndexController', 'showComment']);
    $r->addRoute('GET', '/article', ['IndexController', 'showArticle']);

    $r->addRoute('GET', '/free-board/limit', ['IndexController', 'freeListAll']);
    $r->addRoute('GET', '/free-board/scroll', ['IndexController', 'infiniteScroll']);
    $r->addRoute('GET', '/secret-board/limit', ['IndexController', 'secretListAll']);
    $r->addRoute('GET', '/secret-board/scroll', ['IndexController', 'infiniteScrollSecret']);

    $r->addRoute('POST', '/free-board', ['IndexController', 'writeFreeBoard']);
    $r->addRoute('POST', '/secret-board', ['IndexController', 'writeSecretBoard']);

    $r->addRoute('POST', '/article/comment', ['IndexController', 'writeComment']);
    $r->addRoute('POST', '/article/re-comment', ['IndexController', 'writeReComment']);

    $r->addRoute('GET', '/lecture/review/search', ['MainController', 'searchReview']);
    $r->addRoute('GET', '/lecture', ['MainController', 'lectureDetail']);
    $r->addRoute('GET', '/lecture/review', ['MainController', 'reviewList']);
    $r->addRoute('GET', '/lecture/review/scroll', ['MainController', 'reviewScroll']);

    $r->addRoute('GET', '/list-up', ['MainController', 'listUp']);

//    $r->addRoute('POST', '/article/re-comment/{parent}', ['IndexController', 'writeReComment']);
//    $r->addRoute('PATCH', '/article/thumbsUp/{articleNo}', ['IndexController', 'articleThumbsUp']);
//    $r->addRoute('GET', '/article/all/{articleNo}', ['IndexController', 'showArticleComment']);
//    $r->addRoute('GET', '/free-board/article/{articleNo}', ['IndexController', 'showFreeArticle']);
//    $r->addRoute('GET', '/free-board/comment/{articleNo}', ['IndexController', 'showFreeComment']);
//    $r->addRoute('GET', '/free-board/comment', ['IndexController', 'lookupFreeComment']);

//    $r->addRoute('GET', '/scroll-down', ['IndexController', 'pageScroll']);
//    $r->addRoute('GET', '/my-info/{userNo}', ['MainController', 'myInfo']);
//    $r->addRoute('GET', '/user/{userNo}', ['IndexController', 'userDetail']);
//    $r->addRoute('POST', '/signup', ['IndexController', 'signup']);//sign up
//    $r->addRoute('POST', '/login', ['IndexController', 'login']);//로그인은 바디값에 유저랑 비번 보내서 대조 하는거니까 post아닌가.
//
//    $r->addRoute('GET', '/category', ['IndexController', 'category']);
//    $r->addRoute('GET', '/category/{catName}', ['IndexController', 'catDetail']);
//    $r->addRoute('GET', '/restaurant', ['IndexController', 'restaurant']);//모든 식당 조회
//    $r->addRoute('GET', '/restaurant/{resName}', ['IndexController', 'resDetail']);
//    $r->addRoute('GET', '/menu',['IndexController', 'menu']);
//
//    $r->addRoute('POST', '/add-res', ['IndexController', 'addRestaurant']);
//    $r->addRoute('POST', '/add-menu', ['IndexController', 'addMenu']);
//
//    $r->addRoute('PATCH', '/update-menu', ['IndexController', 'updateMenu']);
//    $r->addRoute('PUT', '/update-restaurant', ['IndexController', 'updateRestaurant']);
//
//    $r->addRoute('DELETE', '/delete-menu', ['IndexController', 'deleteMenu']);
//    $r->addRoute('DELETE', '/delete-restaurant', ['IndexController', 'deleteRestaurant']);
//
//
    $r->addRoute('GET', '/jwt', ['MainController', 'validateJwt']);
//    $r->addRoute('POST', '/jwt', ['MainController', 'createJwt']);
//


//    $r->addRoute('GET', '/users', 'get_all_users_handler');
//    // {id} must be a number (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    // The /{title} suffix is optional
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 로거 채널 생성
$accessLogs = new Logger('ACCESS_LOGS');
$errorLogs = new Logger('ERROR_LOGS');
// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$accessLogs->pushHandler(new StreamHandler('logs/access.log', Logger::INFO));
$errorLogs->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));
// add records to the log
//$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
//$log->addDebug('Debug log');
//$log->addError('Error log');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($routeInfo[1][0]) {
            case 'IndexController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/IndexController.php';
                break;
            case 'MainController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/MainController.php';
                break;
            case 'ReviewController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/ReviewController.php';
                break;
            /*case 'ProductController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ProductController.php';
                break;
            case 'SearchController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SearchController.php';
                break;
            case 'ReviewController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ReviewController.php';
                break;
            case 'ElementController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ElementController.php';
                break;
            case 'AskFAQController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/AskFAQController.php';
                break;*/
        }

        break;
}
