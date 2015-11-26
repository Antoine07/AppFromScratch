<?php namespace Controllers;

use Models\History;

class ProductController
{

    public function __construct()
    {
        if (!auth_guest()) {
            header("HTTP/1.1 200 Ok");
            header("Content-Type: html/text charset=UTF-8");
            header('Location:' . url());
            exit;
        }
    }

    public function index()
    {

        $history = new History;
        $histories = $history->all();

        view('dashboard.index', compact('histories'));
    }
}