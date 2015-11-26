<?php

require_once __DIR__ . '/vendor/autoload.php';  // class d'autoload de composer

define('SALT', '1fJxj0yZigmMNCAq');
define('URL_SITE', "http://localhost:8000/");
define('VALID_TIME_TOKEN', 2);


/* ------------------------------------------------- *\
    Helpers
\* ------------------------------------------------- */

$composite = [];

function view($path, array $data, $status = '200 Ok')
{

    global $composite;

    $fileName = __DIR__ . '/resources/views/' . str_replace('.', '/', $path) . ".php";

    if (!file_exists($fileName)) die(sprintf('this view doesn\t exists, %s', $fileName));

    if (!empty($status)) {
        header("HTTP/1.1 $status");
        header('Content-type: text/html; charset=UTF-8');
    }

    extract($data);
    extract($composite);
    include $fileName;
}

function composite($path, $name, array $data)
{
    global $composite;

    $fileName = __DIR__ . '/resources/views/' . str_replace('.', '/', $path) . ".php";

    if (!file_exists($fileName)) throw new RuntimeException(sprintf('this view doesn\t exists, %s', $fileName));

    ob_start();
    extract($data);
    include $fileName;

    $composite[$name] = ob_get_clean();

}

function url($path = '', $params = '')
{
    if (!empty($params)) $params = "/$params";

    return URL_SITE . $path . $params;
}

function token()
{
    $token = md5(date('Y-m-d h:i:00') . SALT);

    return '<input type="hidden" name="_token" value="' . $token . '">';
}

function checked_token($token)
{
    if (!empty($token)) {
        foreach (range(0, VALID_TIME_TOKEN) as $v) {
            if (($token == md5(date('Y-m-d h:i:00', time() - $v * 60) . SALT))) {
                return true;
            }
        }

        return false;
    }

    throw new RuntimeException('no _token checked');
}

function auth_guest()
{
    if (empty($_SESSION)) session_start();

    if (!empty($_SESSION['secu'])) {
        return true;
    }

    return false;
}

/* ------------------------------------------------- *\
    Connect
\* ------------------------------------------------- */

\Connect::set(['dsn' => 'mysql:host=localhost;dbname=db_starwars', 'password' => 'tony', 'username' => 'tony']);

/* ------------------------------------------------- *\
    Request
\* ------------------------------------------------- */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = strtolower($_SERVER["REQUEST_METHOD"]);

/* ------------------------------------------------- *\
    Router AND controller
\* ------------------------------------------------- */

if ($method == 'get') {
    switch ($uri) {

        case "/":

            $frontController = new Controllers\FrontController;
            $frontController->index();

            break;

        case preg_match('/\/product\/([1-9][0-9]*)/', $uri, $m) == 1:
            $frontController = new Controllers\FrontController;
            $frontController->show($m[1]);

            break;

        case preg_match('/\/category\/([1-9][0-9]*)/', $uri, $m) == 1:
            $frontController = new Controllers\FrontController;
            $frontController->showProductByCategory($m[1]);

            break;

        case "/cart":
            $frontController = new Controllers\FrontController;
            $frontController->showCart();
            break;

        case "/reset":

            $frontController = new Controllers\FrontController;
            $frontController->reset();

            break;

        case preg_match('/\/restore\/([1-9][0-9]*)/', $uri, $m) == 1:

            $frontController = new Controllers\FrontController;
            $frontController->restore($m[1]);

            break;

        case "/login":

            $frontController = new Controllers\FrontController;
            $frontController->login();

            break;

        case "/logout":

            $frontController = new Controllers\FrontController;
            $frontController->logout();

            break;

        case "/dashboard":

            $productController = new Controllers\ProductController;
            $productController->index();

            break;

        default:
            $message = "page not found";
            view('front.page404', compact('message'), $status = '404');
            break;

    }
}

if ($method == 'post') {

    switch ($uri) {
        case "/command":
            $frontController = new Controllers\FrontController;
            $frontController->command();
            break;

        case "/store":
            $frontController = new Controllers\FrontController;
            $frontController->store();
            break;

        case "/login":
            $frontController = new Controllers\FrontController;
            $frontController->checkLogin();
            break;

    }
}