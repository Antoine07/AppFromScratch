<?php namespace Controllers;

use Cart\Cart;
use Models\Tag;
use Models\User;
use Models\Image;
use Models\History;
use Models\Product;
use Models\Customer;
use Models\Category;
use Cart\SessionStorage;

class FrontController
{

    protected $cart;

    public function __construct()
    {
        $category = new Category;
        $categories = $category->all();

        $cart = new Cart(new SessionStorage('starwars'));

        $this->cart = $cart;

        composite('partials.menu', 'menu', compact('categories'));
        composite('partials.header_cart', 'cart', compact('cart'));
    }

    public function index()
    {
        $product = new Product;
        $image = new Image;
        $products = $product->all();

        $tag = new Tag;

        view('front.index', compact('products', 'image', 'tag'));
    }

    public function show($id)
    {
        $productModel = new Product;
        $image = new Image;
        $product = $productModel->find($id);

        $tag = new Tag;

        view('front.single', compact('product', 'image', 'tag'));
    }

    public function showCart()
    {
        $products = $this->storage();

        $image = new Image;

        view('front.cart', compact('products', 'image'));

    }

    public function showProductByCategory($id)
    {
        $category = new Category;

        $products = $category->products($id);

        $image = new Image;

        $tag = new Tag;

        view('front.category', compact('products', 'image', 'tag'));
    }

    public function command()
    {
        $rules = [
            'price'    => FILTER_VALIDATE_FLOAT,
            'quantity' => FILTER_VALIDATE_INT,
            'name'     => FILTER_SANITIZE_INT
        ];

        $sanitize = filter_input_array(INPUT_POST, $rules);

        $productCart = new \Cart\Product($sanitize['name'], $sanitize['price']);

        $this->cart->buy($productCart, $sanitize['quantity']);

        $_SESSION['flashMessage'] = 'add product success';

        $this->redirect(url());
    }

    public function store()
    {

        $this->validToken('_token');

        if (empty($_SESSION)) session_start();

        (empty($_SESSION['old'])) ?: $_SESSION['old'] = [];
        (empty($_SESSION['error'])) ?: $_SESSION['error'] = [];

        $rules = [
            'email'   => FILTER_VALIDATE_EMAIL,
            'number'  => [
                'filter'  => FILTER_CALLBACK,
                'options' => function ($nb) {
                    if (iconv_strlen($nb) == 16 && ((int)$nb != 0))
                        return (int)$nb;

                    return false;
                }
            ],
            'address' => FILTER_SANITIZE_STRING,
        ];

        $sanitize = filter_input_array(INPUT_POST, $rules);

        $error = false;

        $_SESSION['old']['email'] = $sanitize['email'];
        $_SESSION['old']['address'] = $sanitize['address'];

        if (!$sanitize['email']) {
            $_SESSION['error']['email'] = 'your email is invalid';
            $error = true;
        }

        if (!$sanitize['number']) {
            $_SESSION['error']['number'] = 'your number blue card is invalid';
            $error = true;
        }

        if (!$sanitize['address']) {
            $_SESSION['error']['address'] = 'you must given your address';
            $error = true;
        }

        if ($error) {

            $_SESSION['flashMessage'] = 'there was a problem';

            $this->redirect(url('cart'));
        }

        try {

            \Connect::$pdo->beginTransaction();

            $history = new History;

            $customer = new Customer;

            if ($c = $customer->where('number_card', '=', (string)$sanitize['number'])->get()->fetch()) {
                $customer->update($c->id, ['number_command' => 'number_command+1']);
                $customerId = $c->id;
            } else {
                $customer->create(['email' => $sanitize['email'], 'number_card' => $sanitize['number'], 'address' => $sanitize['address'], 'number_command' => 1]);
                $customerId = \Connect::$pdo->lastInsertId();
            }

            $products = $this->storage();

            foreach ($products as $name => $p) {
                $p['commanded_at'] = date('Y-m-d h:i:s');
                $p['customer_id'] = $customerId;
                $history->create($p);
            }

            \Connect::$pdo->commit();

            $_SESSION['flashMessage'] = 'thank you for your purchase, the team of Star Wars';

            $this->cart->reset();

            $this->redirect(url());

        } catch (\PDOException $e) {

            \Connect::$pdo->rollBack();

            $_SESSION['flashMessage'] = 'there has been a problem for your order, so sorry';

            $this->redirect(url('cart'));
        }

    }

    public function reset()
    {
        $this->cart->reset();

        $_SESSION['flashMessage'] = 'reset cart';

        $this->redirect(url());
    }

    public function restore($productId)
    {
        $product = new Product;

        $p = $product->find((int)$productId);

        $productCart = new \Cart\Product($p->title, $p->price);

        $this->cart->restore($productCart);

        $_SESSION['flashMessage'] = 'we have restored the product';

        $this->redirect(url('cart'));
    }

    public function login()
    {
        view('front.login', []);
    }

    public function logout()
    {

        if (empty($_SESSION)) session_start();

        unset($_SESSION['secu']);

        session_regenerate_id(true);

        $this->redirect(url());
    }

    public function checkLogin()
    {
        if (empty($_SESSION)) session_start();

        (empty($_SESSION['old'])) ?: $_SESSION['old'] = [];
        (empty($_SESSION['error'])) ?: $_SESSION['error'] = [];

        $rules = [
            'email'    => FILTER_VALIDATE_EMAIL,
            'password' => FILTER_SANITIZE_STRING,
        ];

        $sanitize = filter_input_array(INPUT_POST, $rules);

        $error = false;

        if (!$sanitize['email']) {
            $_SESSION['error']['email'] = 'your email is invalid';
            $error = true;
        }

        if (!$sanitize['password']) {
            $_SESSION['error']['password'] = 'you must given your password';
            $error = true;
        }

        if ($error) {

            $_SESSION['flashMessage'] = 'there was a problem';

            $this->redirect(url('login'));
        }

        $user = new User;

        if ($u = $user->where('email', '=', $sanitize['email'])->get()->fetch()) {
            if (password_verify($sanitize['password'], $u->password)) {

                session_regenerate_id(true);

                $_SESSION['secu'] = $sanitize['email'];

                $this->redirect(url('dashboard'));
            }
        }
    }

    /**
     * @return array
     * @description the name of product is a primary key of product command
     */
    private function storage()
    {
        $storage = $this->cart->all();
        $products = [];

        foreach ($storage as $name => $total) {
            $pr = new Product;
            $p = $pr->find($name); // $name is id

            $title = $p->title;
            $products[$title]['price'] = (int)$p->price;
            $products[$title]['total'] = (float)$total;
            $products[$title]['quantity'] = (int)($total / $p->price);
            $products[$title]['product_id'] = (int)$p->id;

        }

        return $products;
    }

    private function validToken($path, $tokenName = '_token')
    {
        if (!checked_token($_POST[$tokenName])) {
            $this->redirect($path);
        }
    }

    private function redirect($path, $status = '200 Ok')
    {
        header("HTTP/1.1 $status");
        header("Content-Type: html/text charset=UTF-8");
        header('Location:' . $path);
        exit;
    }

}